<?php

/**
 * IRatingChainRule interface.
 *
 * PHP version 5
 *
 * @category   Interfaces
 *
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link       https://jviba.com/display/PhpDoc
 */
/**
 * IRatingChainRule interface which describe functionality
 * of any rating chain rule item.
 * 
 * PHP version 5
 *
 * @category   Packages
 *
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link       https://jviba.com/display/PhpDoc
 */
interface IRatingChainRule
{
    /**
     * Calculates rating part value and returns it.
     * For breaking rating calculation chain you must
     * return false.
     * 
     * @return mixed calculated rating value
     */
    public function calc();
}
