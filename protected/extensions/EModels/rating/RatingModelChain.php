<?php
/**
 * RatingModelChain class
 *
 * PHP version 5
 *
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.rating
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/packages/php/docs
 */
/**
 * RatingModelChain is the base model class for rating models
 * which has linear rating calculation strategy. This is tupical
 * models, which calculates rating as summ of rating parts with
 * or without applying some coefficients for each part.
 * 
 * PHP version 5
 * 
 * @category   Packages
 * @package    Ext.model
 * @subpackage Ext.model.rating
 * @author     Evgeniy Marilev <marilev@jviba.com>
 * @license    http://www.gnu.org/licenses/lgpl.html LGPL
 * @link       https://jviba.com/packages/php/docs
 * @abstract
 */
abstract class RatingModelChain extends RatingModel
{
    /**
     * Rating calculation rules chain
     * @var CList
     */
    private $_rules;
    
    /**
     * Returns calculation rules chain confugiration
     * 
     * @return array calculation configuration
     */
    public abstract function chain();
    
    /**
     * Creates and returns calculation rules chain
     * 
     * @return CList calculation rules chain
     */
    protected function createChain()
    {
        if (!isset($this->_rules)) {
            $chain = $this->chain();
            $this->_rules = new CList();
            foreach ($chain as $item) {
                $rule = $this->createChainRule($item);
                $this->_rules->add($rule);
            }
        }
        return $this->_rules;
    }
    
    /**
     * Creates and returns rating chain rule object
     * by given configuration
     * 
     * @param mixed $config rule configuration (array or string)
     * 
     * @return RatingChainRule rating chain rule
     */
    protected function createChainRule($config)
    {
        if (is_array($config)) {
            $rule = $item;
        } else if (is_string($config)) {
            $rule = array(
                'class' => 'InlineRatingChainRule',
                'model' => $this,
                'method' => $config,
            );
        }
        return Yii::createComponent($rule);
    }
    
    /**
     * Process rating calculation as summ of rating chain items' results
     * @see RatingModel::calc()
     */
    protected function calc()
    {
        $ret = 0;
        if ($chain = $this->createChain()) {
            $i = 0;
            while ($chain->offsetExists($i)) {
                $rule = $chain->itemAt($i++);
                if (($value = $rule->calc()) !== false) {
                    $ret += $value;
                } else {
                    break;
                }
            }
        }
        return $ret;
    }
}