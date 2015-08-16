<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Tests\Beverage;

use Caffeinated\Beverage\Dotenv;

/**
 * This is the DotenvTest.
 *
 * @package        Caffeinated\Tests
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class DotenvTest extends TestCase
{
    public function testDotenvArray()
    {
        $arr = Dotenv::getEnvFile(__DIR__ . '/Fixture');
        $this->arrayTest($arr);
    }
    public function testDotenvAlternativeFilename()
    {
        $arr = Dotenv::getEnvFile(__DIR__ . '/Fixture', '.second.env');
        $this->arrayTest($arr);
    }

    protected function arrayTest($array)
    {
        $this->assertTrue(is_array($array));
        $this->assertArrayHasKey('FIRST', $array);
        $this->assertArrayHasKey('SECOND', $array);
        $this->assertEquals($array['FIRST'], 'one');
        $this->assertEquals($array['SECOND'], 'two');
    }
}
