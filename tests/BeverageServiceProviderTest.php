<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Tests\Beverage;

use Caffeinated\Dev\Testing\Traits\ServiceProviderTester;

/**
 * This is the BeverageServiceProviderTest.
 *
 * @package        Caffeinated\Tests
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class BeverageServiceProviderTest extends TestCase
{
    use ServiceProviderTester;

    protected function getPackageRootPath()
    {
        return realpath(__DIR__ . '/..');
    }


    public function testFunctionsExists()
    {
        $this->app->register($this->getServiceProviderClass());

        $this->assertTrue(function_exists('path_join'));

        $this->assertTrue(function_exists('path_is_absolute'));

        $this->assertTrue(function_exists('path_is_relative'));

        $this->assertTrue(function_exists('path_get_directory'));

        $this->assertTrue(function_exists('path_get_extension'));

        $this->assertTrue(function_exists('path_get_filename'));
    }



    public function testConfigFiles()
    {
        # $this->registerBeverageServiceProvider();
        # $this->runServiceProviderPublishesConfigTest([ 'caffeinated.beverage']);
    }
}
