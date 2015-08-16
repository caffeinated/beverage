<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage;

use Webmozart\PathUtil\Path as BasePath;

/**
 * Path
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
class Path extends BasePath
{
    /**
     * Joins a split file system path.
     *
     * @param  array|string $path
     * @return string
     */
    public static function join()
    {
        $arguments = func_get_args();

        if (func_get_args() === 1 and is_array($arguments[ 0 ])) {
            $arguments = $arguments[ 0 ];
        }

        foreach ($arguments as $key => $argument) {
            $arguments[ $key ] = Str::removeRight($arguments[ $key ], '/');

            if ($key > 0) {
                $arguments[ $key ] = Str::removeLeft($arguments[ $key ], '/');
            }
        }

        return implode(DIRECTORY_SEPARATOR, $arguments);
    }

    public static function getDirectoryName($path)
    {
        $path = realpath($path);
        if (is_dir($path)) {
            return last(explode(DIRECTORY_SEPARATOR, $path));
        } else {
            return static::getDirectory($path);
        }
    }
}
