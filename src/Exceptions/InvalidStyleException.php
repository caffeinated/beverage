<?php
namespace Caffeinated\Beverage\Exceptions;

/**
 * This is the InvalidStyleException used in Caffeinated\Beverage\Vendor\ConsoleColor and Caffeinated\Beverage\Command.
 *
 * @package        Caffeinated\Beverage
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class InvalidStyleException extends \Exception
{
    /**
     * @param string $styleName
     */
    public function __construct($styleName)
    {
        parent::__construct("Invalid style $styleName.");
    }
}
