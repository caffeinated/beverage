<?php
namespace Caffeinated\Beverage\Vendor;

use Stringy\Stringy as BaseStringy;

/**
 * Stringy
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
class Stringy extends BaseStringy
{
    /**
     * Studly Namespace
     *
     * Transforms "vendor-name/package-name" into "VendorName/PackageName".
     *
     * @return Stringy
     */
    public function namespacedStudly()
    {
        $str = implode('\\', array_map('studly_case', explode('/', $this->str)));

        return static::create($str, $this->encoding);
    }

    /**
     * Explode a string into an array
     *
     * @param  string    $delimiter
     * @param  int|null  $limit
     * @return array
     */
    public function split($delimiter, $limit = null)
    {
        return explode($delimiter, $this->str, $limit);
    }
}
