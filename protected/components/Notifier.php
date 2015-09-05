<?php

/**
 * Created by PhpStorm.
 * User: hanse
 * Date: 9/5/2015
 * Time: 11:38 PM
 */
class Notifier
{
    function comment($event){
        $text = "There is a new comment from {$event->comment->author} on post {$event->post->post_title}";
        mail('admin@example.com', 'New Comment', $text);
    }
}