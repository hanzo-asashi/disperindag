<?php
/**
 * IPaginateable interface
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
 * IPaginateable is the interface which describe functionality
 * of all paginatable models
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
interface IPaginateable
{
    /**
     * Returns default page size
     * 
     * @return integer default page size
     */
    public function getDefaultPageSize();
    
    /**
     * Returns page size
     * 
     * @return integer page size
     */
    public function getPageSize();
    
    /**
     * Returns page size
     * 
     * @param integer $count page size (0 for unlimited)
     * 
     * @return void
     */
    public function setPageSize($count);
    
    /**
     * Returns active page index
     * 
     * @return integer active page index
     */
    public function getPageIndex();
    
    /**
     * Returns active page index
     * 
     * @param integer $index active page index
     * 
     * @return void
     */
    public function setPageIndex($index);
    
    /**
     * Returns pagination configuration
     * 
     * @return array pagination config
     */
    public function getPagination();
}