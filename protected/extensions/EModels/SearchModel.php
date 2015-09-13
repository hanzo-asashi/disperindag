<?php

/**
 * SearchModel class.
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
Yii::import('packages.extensions.models.interfaces.ISearchModel');
Yii::import('packages.extensions.models.interfaces.IPaginateable');
Yii::import('packages.extensions.models.interfaces.ISortable');
Yii::import('packages.extensions.models.interfaces.IFilterable');
Yii::import('packages.extensions.models.interfaces.IFilterModel');
/**
 * SearchModel is the base search model class used for searching data.
 * 
 * @category  Packages
 *
 * @author    Evgeniy Marilev <marilev@jviba.com>
 * @copyright 2011 5-SOFT
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link      https://jviba.com/packages/php/docs
 */
abstract class SearchModel extends CFormModel implements ISearchModel,
                                                         IPaginateable,
                                                         ISortable,
                                                         IFilterable,
                                                         IFilterModel
{
    const DEFAULT_PAGE_SIZE = 20;

    const SORT_ORDER_ALPHABETICAL = 'alpha';
    const SORT_ORDER_DATE = 'date';
    const SORT_ORDER_RANDOM = 'random';

    const SORT_DIRECTION_ASC = 'asc';
    const SORT_DIRECTION_DESC = 'desc';

    /**
     * Model class name used for searching.
     *
     * @var string
     */
    private $_itemClass;

    /**
     * Cached data provider.
     *
     * @var CDataProvider
     */
    private $_dataProvider;

    /**
     * Count of items per page.
     *
     * @var int
     */
    private $_count;

    /**
     * Sort order.
     *
     * @var string|array
     */
    private $_order;

    /**
     * Sort direction.
     *
     * @var string
     */
    private $_direction;

    /**
     * Active page index.
     *
     * @var int
     */
    private $_page;

    /**
     * Scopes cache.
     *
     * @var array
     */
    private $_scopes = array();

    /**
     * Attached filters.
     *
     * @var array
     */
    private $_filters = array();

    /**
     * Whether required to throw validation errors as exceptions.
     *
     * @var bool
     */
    public $displayValidationErrorsAsExceptions = true;

    /**
     * Whether required to cache builded data provider.
     *
     * @var bool
     */
    public $cacheData = false;

    /**
     * Custom seleted field.
     *
     * @var string|array
     */
    public $select;

    /**
     * Cache life time (in seconds).
     *
     * @var int
     */
    public $cacheDuration = 3600;

    /**
     * Initializes default values.
     */
    public function init()
    {
        parent::init();
        //$this->setPageIndex(0);
        $this->setPageSize($this->getDefaultPageSize());
        $this->setScopes($this->getDefaultScopes());
        $this->setSortOrder($this->getDefaultSortOrder());
        $this->setSortDirection($this->getDefaultSortDirection());
    }

    /**
     * Returns validation rules map.
     *
     * @return array validation rules
     *
     * @see CModel::rules()
     */
    public function rules()
    {
        return array(
            array('pageIndex, pageSize', 'numerical', 'integerOnly' => true, 'min' => 0),
            array('sortDirection', 'in', 'range' => $this->getAvailableSortDirections()),
            array('sortOrder', 'in', 'range' => $this->getAvailableSortOrders()),
        );
    }

    /**
     * Sets result set item class name.
     * 
     * @param string $class item class name
     */
    public function setItemClass($class)
    {
        $this->_itemClass = $class;
    }

    /**
     * Returns result set item object class.
     * 
     * @return string model class name
     */
    public function getItemClass()
    {
        return $this->_itemClass;
    }

    /**
     * Returns cache ID of search model.
     * 
     * @return string cache ID
     */
    public function getCacheId()
    {
        return get_class($this)
             .'_pg_'.$this->getPageIndex()
             .'_cnt_'.$this->getPageSize()
             .'_ord_'.$this->buildSortOrderHash('md5')
             .'_dir_'.$this->getSortDirection()
             .'_scps_'.implode(',', $this->getScopes())
             .'_attrs_'.$this->buildAttributesHash('md5');
    }

    /**
     * Builds unique hash of current attributes of the model.
     * 
     * @param string hash algorithm name
     * @param string $algorithm hash algorithm name. Null means no raw data should be returned.
     * 
     * @return string attributes unique hash
     */
    public function buildAttributesHash($algorithm = null)
    {
        $data = '';
        foreach ($this->getAttributes() as $name => $value) {
            $data .= '_'.$name.'_'.$value;
        }

        return $algorithm === null ? $data : hash($algorithm, $data);
    }

    /**
     * Builds sort order hash string.
     * 
     * @param  string $algorithm hash algorithm name. Null means no raw data should be returned.
     *                            
     * @return string sort order unique hash
     */
    public function buildSortOrderHash($algorithm = null)
    {
        $order = $this->getSortOrder();
        if (is_array($order)) {
            $data = '';
            foreach ($order as $item) {
                if (is_string($item)) {
                    $data .= $item;
                } elseif (is_array($item)) {
                    list($name, $value) = $item;
                    $data .= '_'.$name.'_'.$value;
                }
            }
        } else {
            $data = $order;
        }

        return $algorithm === null ? $data : hash($algorithm, $data);
    }

    /**
     * Returns cache duration of model search results.
     * 
     * @return int duration in seconds
     */
    public function getCacheDuration()
    {
        return $this->cacheDuration;
    }

    /**
     * Returns cache dependency of current search.
     * 
     * @return CDbCacheDependency dependency
     */
    public function getCacheDependency()
    {
        return;
    }

    /**
     * Searchs data in result set with applying post-filtering.
     * 
     * @param bool whether required to refresh search results
     * 
     * @return CDataProvider filled provider by completely filtered data
     *
     * @throws Exception
     */
    public function search($refresh = false)
    {
        if ($refresh || !isset($this->_dataProvider)) {
            if ($dataProvider = $this->fetch()) {
                if ($this->cacheData && property_exists($dataProvider, 'model')) {
                    Yii::trace('Search model trying to serve/push results from cache.');
                    $dataProvider->model->cache($this->getCacheDuration(), $this->getCacheDependency(), 2);
                }
                if ($data = $dataProvider->getData()) {
                    $this->postFilter($data);
                    $dataProvider->setData($data);
                }
            }
            $this->_dataProvider = $dataProvider;
        }

        return $this->_dataProvider;
    }

    /**
     * Returns configured data provider.
     * 
     * @return CDataProvider configured data provider
     *
     * @throws Exception
     */
    public function fetch()
    {
        if (!isset($this->_dataProvider)) {
            if ($this->validate()) {
                $dataProvider = $this->build();
                if (isset($dataProvider->criteria)) {
                    $this->preFilter($dataProvider->criteria);
                }
                $dataProvider->pagination = $this->getPagination();
                $this->_dataProvider = $dataProvider;
            } elseif ($this->displayValidationErrorsAsExceptions) {
                $errors = $this->getErrors();
                $this->displayErrors($errors);
            }
        }

        return $this->_dataProvider;
    }

    /**
     * Displays validation errors.
     * 
     * @param array &$data errors map
     * 
     * @throws Exception
     */
    protected function displayErrors(&$data)
    {
        $errors = array();
        foreach ($data as $name => $error) {
            $errors = array_merge($errors, $error);
        }
        $params = array('{ERRORS}' => implode($errors));
        $message = Yii::t('assert', 'There are following model validation errors: "{ERRORS}"', $params);
        throw new ValidationException($message);
    }

    /**
     * Returns default page size.
     * 
     * @return int default page size
     *
     * @see IPaginateable::getDefaultPageSize()
     */
    public function getDefaultPageSize()
    {
        return self::DEFAULT_PAGE_SIZE;
    }

    /**
     * Returns page size.
     * 
     * @return int page size
     *
     * @see IPaginateable::getPageSize()
     */
    public function getPageSize()
    {
        return $this->_count;
    }

    /**
     * Returns page size.
     * 
     * @param int $count page size (0 for unlimited)
     * 
     * @see IPaginateable::setPageSize()
     */
    public function setPageSize($count)
    {
        $this->_count = $count;
    }

    /**
     * Returns active page index.
     * 
     * @return int active page index
     *
     * @see IPaginateable::getPageIndex()
     */
    public function getPageIndex()
    {
        return $this->_page;
    }

    /**
     * Returns active page index.
     * 
     * @param int $index active page index
     * 
     * @see IPaginateable::setPageIndex()
     */
    public function setPageIndex($index)
    {
        $this->_page = $index;
    }

    /**
     * Returns sort order.
     * 
     * @return mixed sort order (false if disabled)
     *
     * @see ISortable::getSortOrder()
     */
    public function getSortOrder()
    {
        return $this->_order;
    }

    /**
     * Changes sort order.
     * 
     * @param mixed $order sort order (false for disabling)
     * 
     * @see ISortable::setSortOrder()
     */
    public function setSortOrder($order)
    {
        $this->_order = $order;
    }

    /**
     * Adds into sort order expression a manual field with defined direction.
     *
     * @param string $fieldName sort field name
     * @param string $direction sort direction
     */
    public function addSortField($fieldName, $direction = self::SORT_DIRECTION_ASC)
    {
        if (is_string($this->_order)) {
            $this->_order = array($this->_order);
        }
        $this->_order[] = array($fieldName, $direction);
    }

    /**
     * Returns default sort order.
     * 
     * @return mixed sort order
     *
     * @see ISortable::getDefaultSortOrder()
     */
    public function getDefaultSortOrder()
    {
        return false;
    }

    /**
     * Returns sort direction.
     * 
     * @return mixed sort direction (false if disabled)
     *
     * @see ISortable::getSortDirection()
     */
    public function getSortDirection()
    {
        return $this->_direction;
    }

    /**
     * Returns sort direction.
     * 
     * @param mixed $mode sort direction (false for disabling)
     * 
     * @see ISortable::setSortMode()
     */
    public function setSortDirection($mode)
    {
        $this->_direction = $mode;
    }

    /**
     * Returns default sort direction.
     * 
     * @return mixed sort direction
     *
     * @see ISortable::getDefaultSortDirection()
     */
    public function getDefaultSortDirection()
    {
        return false;
    }

    /**
     * Returns available sort directions.
     * 
     * @return array sort directions
     *
     * @see ISortable::getAvailableSortOrders()
     */
    public function getAvailableSortDirections()
    {
        return array(
            self::SORT_DIRECTION_ASC,
            self::SORT_DIRECTION_DESC,
            false,
        );
    }

    /**
     * Returns available sort orders.
     * 
     * @return array sort orders
     *
     * @see ISortable::getAvailableSortOrders()
     */
    public function getAvailableSortOrders()
    {
        return array(
            self::SORT_ORDER_ALPHABETICAL,
            self::SORT_ORDER_DATE,
            false,
        );
    }

    /**
     * Returns pagination configuration.
     * 
     * @return array pagination config
     *
     * @see IPaginateable::getPagination()
     */
    public function getPagination()
    {
        if (!$count = $this->getPageSize()) {
            return false;
        } else {
            $ret = array(
                'pageSize' => $count,
            );
            if ($page = $this->getPageIndex()) {
                $ret['currentPage'] = $page <= 0 ? 0 : $page - 1;
            }

            return $ret;
        }
    }

    /**
     * Sets active scopes set.
     * 
     * @param mixed $scopes scopes set (comma separated string or array)
     */
    public function setScopes($scopes)
    {
        if (is_string($scopes)) {
            $scopes = explode(',', $scopes);
        } elseif (!is_array($scopes)) {
            throw new Exception('Invalid scopes expression.');
        }
        $this->_scopes = $scopes;
    }

    /**
     * Adds new scope.
     * 
     * @param string $scope scope
     */
    public function addScope($scope)
    {
        if (!in_array($scope, $this->_scopes)) {
            $this->_scopes[] = $scope;
        }
    }

    /**
     * Returns active scopes set.
     * 
     * @return array scopes set
     */
    public function getScopes()
    {
        return $this->_scopes;
    }

    /**
     * Returns default scopes.
     * 
     * @return array default scopes
     */
    public function getDefaultScopes()
    {
        return array();
    }

    /**
     * Returns whether model has active scope with
     * specified name.
     * 
     * @param string $name scope name
     * 
     * @return bool whether scope is active
     */
    public function hasScope($name)
    {
        return in_array($name, $this->_scopes);
    }

    /**
     * Attachs search filter to current model.
     * 
     * @param string       $id     filter identifier
     * @param IFilterModel $filter search filter instance
     */
    public function attachFilter($id, IFilterModel $filter)
    {
        $this->_filters[$id] = $filter;
    }

    /**
     * Detachs search filter by identifier.
     * 
     * @param string $id filter identifier
     */
    public function detachFilter($id)
    {
        unset($this->_filters[$id]);
    }

    /**
     * (non-PHPdoc).
     * 
     * @return array filters map
     *
     * @see IFilterable::getFilters()
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * (non-PHPdoc).
     * 
     * @param string $id filter identifier
     * 
     * @return IFilterModel filter instance
     *
     * @see IFilterable::getFilter()
     */
    public function getFilter($id)
    {
        return isset($this->_filters[$id]) ? $this->_filters[$id] : null;
    }

    /**
     * (non-PHPdoc).
     * 
     * @param CDbCriteria $criteria database criteria
     * 
     * @see IFilterModel::preFilter()
     */
    public function preFilter($criteria)
    {
        foreach ($this->getFilters() as $id => $filter) {
            $filter->preFilter($criteria);
        }
    }

    /**
     * (non-PHPdoc).
     * 
     * @param array &$data pointer to data collection to filter
     * 
     * @see IFilterModel::postFilter()
     */
    public function postFilter(&$data)
    {
        foreach ($this->getFilters() as $id => $filter) {
            $filter->postFilter($data);
        }
    }

    /**
     * Builds data provider used for fetching data.
     * 
     * @return CDataProvider configured data provider
     */
    abstract protected function build();

    /**
     * Asserts whether model object is instance of search model.
     * 
     * @param object $model model instance
     * 
     * @throws Exception
     */
    public static function assertSearchModel($model)
    {
        if (!$model instanceof ISearchModel) {
            $params = array(
                '{CLASS}' => get_class($model),
                '{INTERFACE}' => 'ISearchModel',
            );
            $message = Yii::t('assert', 'The class "{CLASS}" must implements "{INTERFACE}"!', $params);
            throw new Exception($message);
        }
    }
}
