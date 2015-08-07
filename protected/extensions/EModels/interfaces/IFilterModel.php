<?php
/**
 * IFilterModel interface
 *
 * PHP version 5
 *
 * @category   Interfaces
 * @package    Ext.model
 * @subpackage Ext.model.interfaces
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/display/PhpDoc
 */
/**
 * IFilterModel is the interface which describe functionality
 * of all filter models
 * 
 * PHP version 5
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.interfaces
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/display/PhpDoc
 */
interface IFilterModel
{
    /**
     * Applies pre-filtering via changing search existing criteria
     * 
     * @param CDbCriteria $criteria database criteria
     * 
     * @return void
     */
    public function preFilter($criteria);
    
    /**
     * Applies post-filtering with given data
     * 
     * @param array &$data pointer to data collection to filter
     * 
     * @return void
     */
    public function postFilter(&$data);
}