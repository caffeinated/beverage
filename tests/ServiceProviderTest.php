<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Tests\Beverage;

use Caffeinated\Dev\Testing\Traits\ServiceProviderTester;
use Caffeinated\Tests\Beverage\Fixture\ServiceProvider;

/**
 * This is the BeverageServiceProviderTest.
 *
 * @package        Caffeinated\Tests
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class ServiceProviderTest extends TestCase
{
    use ServiceProviderTester;

    protected function getServiceProviderClass()
    {
        return ServiceProvider::class;
    }
    protected function getPackageRootPath()
    {
        return realpath(__DIR__ . '/..');
    }
}
