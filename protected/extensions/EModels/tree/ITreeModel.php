<?php
/**
 * ITreeModel interface
 *
 * PHP version 5
 * 
 * @category   Models
 * @package    Ext.model
 * @subpackage Ext.model.tree
 * @author     Evgeniy Marilev <marilev@jviba.com>
 */
/**
 * ITreeModel is the interface with basic functionality
 * for working with tree structured models
 * 
 * @category   Models
 * @package    Ext.model
 * @subpackage Ext.model.tree
 * @author     Evgeniy Marilev <marilev@jviba.com>
 */
interface ITreeModel
{
    /**
     * Returns array with children tree nodes
     * 
     * @return array node's children
     */
    public function getChildren();
    
    /**
     * Returns whether tree node is leaf
     * 
     * @return bool whether node is leaf
     */
    public function getIsLeaf();
}