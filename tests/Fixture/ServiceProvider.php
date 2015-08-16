<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Tests\Beverage\Fixture;

/**
 * This is the ServiceProvider.
 *
 * @package        Caffeinated\Tests
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class ServiceProvider extends \Caffeinated\Beverage\ServiceProvider
{

    protected $dir = __DIR__;

    protected $configFiles = ['a', 'b'];

    /**
     * A collection of directories in this package containing views.
     * ['dirName' => 'namespace']
     *
     * @var array
     */
    protected $viewDirs = [ /* 'dirName' => 'namespace' */ ];

    /**
     * A collection of directories in this package containing assets.
     * ['dirName' => 'namespace']
     *
     * @var array
     */
    protected $assetDirs = [ /* 'dirName' => 'namespace' */ ];

    /**
     * Array of directory names/paths relative to $databasePath containing seed files.
     *
     * @var array
     */
    protected $seedDirs = [ /* 'dirName', */ ];

    /**
     * Array of directory names/paths relative to $databasePath containing migration files.
     *
     * @var array
     */
    protected $migrationDirs = [ /* 'dirName', */ ];
}
