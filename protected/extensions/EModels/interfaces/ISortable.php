<?php

/**
 * ISortable interface.
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
 * ISortable is the interface which describe functionality
 * of all sortable models.
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
interface ISortable
{
    /**
     * Returns sort order.
     * 
     * @return mixed sort order (false if disabled)
     */
    public function getSortOrder();

    /**
     * Changes sort order.
     * 
     * @param mixed $order sort order (false for disabling)
     */
    public function setSortOrder($order);

    /**
     * Returns default sort order.
     * 
     * @return mixed sort order
     */
    public function getDefaultSortOrder();

    /**
     * Returns sort direction.
     * 
     * @return mixed sort direction (false if disabled)
     */
    public function getSortDirection();

    /**
     * Returns sort direction.
     * 
     * @param mixed $mode sort direction (false for disabling)
     */
    public function setSortDirection($mode);

    /**
     * Returns default sort direction.
     * 
     * @return mixed sort direction
     */
    public function getDefaultSortDirection();

    /**
     * Returns available sort orders.
     * 
     * @return array sort orders
     */
    public function getAvailableSortOrders();

    /**
     * Returns available sort direction.
     * 
     * @return array sort direction
     */
    public function getAvailableSortDirections();
}
