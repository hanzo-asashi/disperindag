<?php
/**
 * Created by PhpStorm.
 * User: hanse
 * Date: 9/5/2015
 * Time: 11:23 PM
 */
class NewCommentEvent extends CModelEvent
{
    public $comment;
    public $post;
}