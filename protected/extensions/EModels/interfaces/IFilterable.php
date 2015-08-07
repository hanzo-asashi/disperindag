<?php
/**
 * IFilterable interface
 *
 * PHP version 5
 *
 * @category   Interfaces
 * @package    Ext.model
 * @subpackage Ext.model.interfaces
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       http://jviba.com/display/PhpDoc
 */
/**
 * IFilterable is the interface which describe functionality
 * of all filterable models
 * 
 * PHP version 5
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.interfaces
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       http://jviba.com/display/PhpDoc
 */
interface IFilterable
{
    /**
     * Attachs search filter to current model
     * 
     * @param string       $id     filter identifier
     * @param IFilterModel $filter search filter instance
     * 
     * @return void
     */
    public function attachFilter($id, IFilterModel $filter);
    
    /**
     * Detachs search filter by identifier
     * 
     * @param string $id filter identifier
     * 
     * @return void
     */
    public function detachFilter($id);
    
    /**
     * Returns filters collection
     * 
     * @return array filters collection
     */
    public function getFilters();
    
    /**
     * Returns attached filter instance by it's identifier
     * 
     * @param string $id filter identifier
     * 
     * @return IFilterModel filter instance
     */
    public function getFilter($id);
}