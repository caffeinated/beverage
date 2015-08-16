<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Tests\Beverage\Fixture\Traits;

use Caffeinated\Beverage\Traits\NamespacedPackageTrait;

/**
 * This is the NamespacedPackage.
 *
 * @package        Caffeinated\Tests
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class NamespacedPackage
{
    use NamespacedPackageTrait;

    /** Instantiates the class */
    public function __construct()
    {
    }
}
