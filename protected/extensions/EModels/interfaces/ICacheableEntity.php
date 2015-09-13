<?php

/**
 * ICacheableEntity interface.
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
 * ICacheableEntity interface which describe functionality
 * for caching data loaded via entity.
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
interface ICacheableEntity
{
    /**
     * Loads internal entity's data
     * Do not call this method manually.
     * 
     * @return mixed loaded data
     */
    public function loadData();

    /**
     * Returns whether form data was changed.
     * 
     * @return bool whether data was changed
     */
    public function getIsDataChanged();

    /**
     * Return cache id used for caching loaded data.
     * 
     * @return string cache id
     */
    public function getDataCacheId();

    /**
     * Return cache duration used for caching loaded data.
     * 
     * @return int cache duration
     */
    public function getDataCacheDuration();

    /**
     * Return cache dependency used for recalculating cache of loaded data
     * If you don't want to use additional dependency for caching entity data,
     * please return null.
     * 
     * @return mixed cache dependency
     */
    public function getDataCacheDependency();
}
