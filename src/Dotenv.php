<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage;

/**
 * Dotenv.
 *
 * Loads a `.env` file in the given directory and sets the environment vars.
 */
class Dotenv extends \Dotenv
{

    public static function getEnvFile($path, $file = '.env')
    {

        if (!is_string($file)) {
            $file = '.env';
        }

        $filePath = rtrim($path, '/').'/'.$file;
        if (!is_readable($filePath) || !is_file($filePath)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Dotenv: Environment file %s not found or not readable. '.
                    'Create file with your environment settings at %s',
                    $file,
                    $filePath
                )
            );
        }

        // Read file into an array of lines with auto-detected line endings
        $autodetect = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', '1');
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        ini_set('auto_detect_line_endings', $autodetect);

        $vars = [];
        foreach ($lines as $line) {
            // Disregard comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            // Only use non-empty lines that look like setters
            if (strpos($line, '=') !== false) {
                list($name, $value) = static::normaliseEnvironmentVariable($line, null);
                $vars[ $name ] = $value;
            }
        }
        return $vars;
    }

    /**
     * Takes value as passed in by developer and:
     * - ensures we're dealing with a separate name and value, breaking apart the name string if needed
     * - cleaning the value of quotes
     * - cleaning the name of quotes
     * - resolving nested variables
     *
     * @param $name
     * @param $value
     * @return array
     */
    private static function normaliseEnvironmentVariable($name, $value)
    {
        list($name, $value) = self::splitCompoundStringIntoParts($name, $value);
        $name  = self::sanitiseVariableName($name);
        $value = self::sanitiseVariableValue($value);
        $value = self::resolveNestedVariables($value);

        return array($name, $value);
    }

    /**
     * If the $name contains an = sign, then we split it into 2 parts, a name & value
     *
     * @param $name
     * @param $value
     * @return array
     */
    private static function splitCompoundStringIntoParts($name, $value)
    {
        if (strpos($name, '=') !== false) {
            list($name, $value) = array_map('trim', explode('=', $name, 2));
        }

        return array($name, $value);
    }

    /**
     * Strips quotes from the environment variable value.
     *
     * @param $value
     * @return string
     */
    private static function sanitiseVariableValue($value)
    {
        $value = trim($value);
        if (!$value) {
            return '';
        }
        if (strpbrk($value[0], '"\'') !== false) { // value starts with a quote
            $quote = $value[0];
            $regexPattern = sprintf('/^
                %1$s          # match a quote at the start of the value
                (             # capturing sub-pattern used
                 (?:          # we do not need to capture this
                  [^%1$s\\\\] # any character other than a quote or backslash
                  |\\\\\\\\   # or two backslashes together
                  |\\\\%1$s   # or an escaped quote e.g \"
                 )*           # as many characters that match the previous rules
                )             # end of the capturing sub-pattern
                %1$s          # and the closing quote
                .*$           # and discard any string after the closing quote
                /mx', $quote);
            $value = preg_replace($regexPattern, '$1', $value);
            $value = str_replace("\\$quote", $quote, $value);
            $value = str_replace('\\\\', '\\', $value);
        } else {
            $parts = explode(' #', $value, 2);
            $value = $parts[0];
        }
        return trim($value);
    }

    /**
     * Strips quotes and the optional leading "export " from the environment variable name.
     *
     * @param $name
     * @return string
     */
    private static function sanitiseVariableName($name)
    {
        return trim(str_replace(array('export ', '\'', '"'), '', $name));
    }

    /**
     * Look for {$varname} patterns in the variable value and replace with an existing
     * environment variable.
     *
     * @param $value
     * @return mixed
     */
    private static function resolveNestedVariables($value)
    {
        if (strpos($value, '$') !== false) {
            $value = preg_replace_callback(
                '/{\$([a-zA-Z0-9_]+)}/',
                function ($matchedPatterns) {
                    $nestedVariable = Dotenv::findEnvironmentVariable($matchedPatterns[1]);
                    if (is_null($nestedVariable)) {
                        return $matchedPatterns[0];
                    } else {
                        return  $nestedVariable;
                    }
                },
                $value
            );
        }

        return $value;
    }
}
