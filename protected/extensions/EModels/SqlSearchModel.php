<?php

/**
 * SqlSearchModel class.
 *
 * PHP version 5
 *
 * @category  Packages
 *
 * @author    Evgeniy Marilev <marilev@jviba.com>
 * @copyright 2011 5-SOFT
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link      https://jviba.com/packages/php/docs
 */
/**
 * SqlSearchModel is the search model class used for
 * modeling sql select queries.
 * 
 * @category  Packages
 *
 * @author    Evgeniy Marilev <marilev@jviba.com>
 * @copyright 2011 5-SOFT
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link      https://jviba.com/packages/php/docs
 */
abstract class SqlSearchModel extends SearchModel
{
    /**
     * Applies sorting conditions for criteria.
     * 
     * @param CDbCriteria $criteria search criteria
     */
    public function applySort($criteria)
    {
        $order = $this->getSortOrder();
        if (is_array($order)) {
            $parts = array();
            foreach ($order as $item) {
                if (is_string($item)) {
                    $parts[] = $item;
                } elseif (is_array($item)) {
                    $parts[] = $item[0].' '.$item[1];
                } else {
                    throw new Exception('Invalid sort order item format.');
                }
            }
            $criteria->order = implode(',', $parts);

            return;
        } elseif (is_string($order)) {
            if ($order == self::SORT_ORDER_RANDOM) {
                $criteria->order = 'RAND()';

                return;
            }
            $direction = $this->getSortDirection();
            if (!empty($criteria->order) && $direction !== false) {
                $criteria->order .= ' '.$direction;
            }
        }
    }

    /**
     * Applies additional dependencies special for sql search criteria.
     * 
     * @return CDataProvider data provider
     *
     * @see SearchModel::fetch()
     */
    public function fetch()
    {
        $dataProvider = parent::fetch();
        if ($dataProvider instanceof CActiveDataProvider) {
            $criteria = $dataProvider->criteria;
            if (empty($criteria->order)) {
                $this->applySort($criteria);
            }
        }

        return $dataProvider;
    }
}
