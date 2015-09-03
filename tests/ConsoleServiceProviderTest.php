<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Tests\Beverage;

use Caffeinated\Dev\Testing\Traits\ServiceProviderTester;
use Caffeinated\Tests\Beverage\Fixture\ConsoleServiceProvider;

/**
 * This is the BeverageServiceProviderTest.
 *
 * @package        Caffeinated\Tests
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class ConsoleServiceProviderTest extends TestCase
{
    use ServiceProviderTester;
    protected function getServiceProviderClass()
    {
        return ConsoleServiceProvider::class;
    }
}
