<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage\Contracts;

/**
 * Interface Dependable
 *
 * @package        Laradic\Support
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
interface Dependable
{
    /**
     * get dependencies
     *
     * @return array
     */
    public function getDependencies();

    /**
     * get item key/identifier
     *
     * @return string|mixed
     */
    public function getHandle();
}
