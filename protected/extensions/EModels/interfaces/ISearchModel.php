<?php
/**
 * ISearchModel interface
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
 * ISearchModel is the interface which describe functionality
 * of all search models
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
interface ISearchModel
{
    /**
     * Returns initialized data provider used for fetching data
     * 
     * @return CDataProvider initialized data provider
     */
    public function search();
}