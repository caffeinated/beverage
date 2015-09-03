<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Illuminate\Filesystem\Filesystem as IlluminateFilesystem;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

/**
 * Caffeinated Beverage Filesystem
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 * @mixin \Symfony\Component\Filesystem\Filesystem
 */
class Filesystem extends IlluminateFilesystem
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $SymfonyFilesystem;

    /**
     * Create a new filesystem instance.
     *
     */
    public function __construct()
    {
        $this->SymfonyFilesystem = new SymfonyFilesystem;
    }

    /**
     * Recursively find pathnames matching the given pattern.
     *
     * @param  string  $pattern
     * @param  int     $flags
     * @return array
     */
    public function rglob($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->rglob($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }

    /**
     * Search the given folder recursively for files using
     * a regular expression pattern.
     *
     * @param  string  $folder
     * @param  string  $pattern
     * @return array
     */
    public function rsearch($folder, $pattern)
    {
        $dir      = new RecursiveDirectoryIterator($folder);
        $iterator = new RecursiveIteratorIterator($dir);
        $files    = new RegexIterator($iterator, $pattern, RegexIterator::GET_MATCH);
        $fileList = [];

        foreach ($files as $file) {
            $fileList = array_merge($fileList, $file);
        }

        return $fileList;
    }

    /**
     * Magic call method.
     *
     * @param  string  $method
     * @param  mixed   $parameters
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->SymfonyFilesystem, $method)) {
            return call_user_func_array([$this->SymfonyFilesystem, $method], $parameters);
        }
    }
}
