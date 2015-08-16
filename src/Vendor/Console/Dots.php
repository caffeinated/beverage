<?php
/**
 * Part of the Robin Radic's PHP packages.
 *
 * MIT License and copyright information bundled with this package
 * in the LICENSE file or visit http://radic.mit-license.com
 */
namespace Caffeinated\Beverage\Vendor\Console;

/**
 * A dots class based on wp-cli/php-cli-tools.
 * Modifies a few properties/values to alter the displayed stuff.
 *
 * @package        Laradic\Console
 * @version        1.0.0
 * @author         Robin Radic
 * @license        MIT License
 * @copyright      2015, Robin Radic
 * @link           https://github.com/robinradic
 */
class Dots extends \cli\notify\Dots
{

    protected $_dots;

    protected $_format = '{:msg}{:dots}'; //({:elapsed}, {:speed}/s)

    protected $_iteration;

    protected $started;

    public function start(&$started)
    {
        $this->started = true;
        while ($started === true) {
            $this->tick();
        }
    }

    public function stop()
    {
        $this->started = false;
        $this->finish();
    }
}
