<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Tests\Beverage;

use Caffeinated\Beverage\BeverageServiceProvider;
use Caffeinated\Dev\Testing\AbstractTestCase;

/**
 * This is the TestCase.
 *
 * @package        Caffeinated\Tests
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
abstract class TestCase extends AbstractTestCase
{
    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass()
    {
        return BeverageServiceProvider::class;
    }

    protected function getPackageRootPath()
    {
        return realpath(__DIR__ . '/..');
    }
}
