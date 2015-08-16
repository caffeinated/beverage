<?php
/**
 * Part of the Robin Radic's PHP packages.
 *
 * MIT License and copyright information bundled with this package
 * in the LICENSE file or visit http://radic.mit-license.com
 */
namespace Caffeinated\Beverage\Vendor\Console;

/**
 * A spinner class based on wp-cli/php-cli-tools.
 * Modifies a few properties/values to alter the displayed stuff.
 *
 * @package        Laradic\Console
 * @version        1.0.0
 * @author         Robin Radic
 * @license        MIT License
 * @copyright      2015, Robin Radic
 * @link           https://github.com/robinradic
 */
class Spinner extends \cli\notify\Spinner
{

    protected $_chars = '-\|/';

    protected $_format = '{:msg} {:char}  ({:elapsed}, {:speed}/s)';

    protected $_iteration = 0;
}
