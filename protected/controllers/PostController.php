<?php

/**
 * Created by PhpStorm.
 * User: hanse
 * Date: 9/5/2015
 * Time: 11:42 PM
 */
class PostController extends  Controller
{
    function addComment(){
        $post = Post::model()->findByPk(10);
        $notifier = new Notifier();

        // Attached event handler
        $post->onNewComment = array($notifier,'comment');

        $comment = new Comment();
        $comment->author = 'Hansen Makangiras';
        $comment->text = 'Testing Yii Events';

        // Add Comment
        $post->addComment($comment);
    }

    function actions(){
        return array(
            'delete' => array(
                'class' => 'DeleteAction',
                'modelClass' => 'Post',
            ),
        );
    }
}