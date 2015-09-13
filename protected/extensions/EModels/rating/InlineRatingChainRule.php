<?php

/**
 * InlineRatingChainRule class.
 *
 * PHP version 5
 *
 * @category   Packages
 *
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link       https://jviba.com/packages/php/docs
 */
/**
 * InlineRatingChainRule is the rating rule class for
 * calculating rating part through rating chain inline
 * method calling.
 * 
 * PHP version 5
 * 
 * @category   Packages
 *
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 *
 * @link       https://jviba.com/packages/php/docs
 */
class InlineRatingChainRule extends RatingChainRule
{
    /**
     * Rating chain model.
     *
     * @var RatingModelChain
     */
    public $model;

    /**
     * Model's method name used for calculating rating part.
     *
     * @var string
     */
    public $method;

    /**
     * Inline rating part calculation.
     * 
     * @return mixed rating part value
     *
     * @see IRatingChainRule::calc()
     */
    public function calc()
    {
        if (!$model = $this->model) {
            throw new Exception('Rating model chain is undefined.');
        }
        if (!$method = $this->method) {
            throw new Exception('Rating model chain calculation method is undefined.');
        }

        return $model->$method();
    }
}
