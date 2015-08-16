<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage\Models;

use Config;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Base model class
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
class Model extends Eloquent
{
    public function __call($method, $parameters)
    {
        $className = class_basename($this);

        $config = implode('.', ['relationship', $className, $method]);

        if (Config::has($config)) {
            $function = Config::get($config);

            return $function($this);
        }

        return parent::__call($method, $parameters);
    }
}
