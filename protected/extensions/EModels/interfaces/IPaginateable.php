<?php

/**
 * IPaginateable interface.
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
 * IPaginateable is the interface which describe functionality
 * of all paginatable models.
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
interface IPaginateable
{
    /**
     * Returns default page size.
     * 
     * @return int default page size
     */
    public function getDefaultPageSize();

    /**
     * Returns page size.
     * 
     * @return int page size
     */
    public function getPageSize();

    /**
     * Returns page size.
     * 
     * @param int $count page size (0 for unlimited)
     */
    public function setPageSize($count);

    /**
     * Returns active page index.
     * 
     * @return int active page index
     */
    public function getPageIndex();

    /**
     * Returns active page index.
     * 
     * @param int $index active page index
     */
    public function setPageIndex($index);

    /**
     * Returns pagination configuration.
     * 
     * @return array pagination config
     */
    public function getPagination();
}
