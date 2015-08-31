<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage;

use Illuminate\Container\Container;

/**
 * This is the Publisher.
 *
 * @package        Caffeinated\Beverage
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class Publisher
{
    /**
     * The package name (ex: caffeinated/themes)
     * @var string
     */
    protected $package;

    /**
     * The path to the source files which are to be published
     * @var string
     */
    protected $sourcePath;

    /** @var \Illuminate\Filesystem\Filesystem */
    protected $files;

    protected $destinationPath;

    /**
     * @param \Caffeinated\Beverage\Filesystem $files
     */
    protected function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * publish
     */
    public function publish()
    {
        $destination = config_path('packages/' . $this->package);
        if (! $this->files->exists($this->sourcePath)) {
            return;
        }
        if (! $this->files->exists($this->destinationPath)) {
            $this->files->makeDirectory($destination, 0755, true);
        }
        $this->files->copyDirectory($this->sourcePath, $destination);
    }

    /**
     * Create a new publisher instance
     *
     * @param \Caffeinated\Beverage\Filesystem $files
     * @return static
     */
    public static function create(Filesystem $files)
    {
        return Container::getInstance()->make(static::class);
    }

    /**
     * package
     *
     * @param $package
     * @return $this
     */
    public function to($destinationPath)
    {
        $this->destinationPath = $destinationPath;

        return $this;
    }

    /**
     * from
     *
     * @param $sourcePath
     * @return $this
     */
    public function from($sourcePath)
    {
        $this->sourcePath = $sourcePath;

        return $this;
    }
}
