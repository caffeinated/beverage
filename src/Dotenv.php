<?php

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
}
