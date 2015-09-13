<?php

/**
 * Created by PhpStorm.
 * User: hanse
 * Date: 9/6/2015
 * Time: 1:07 AM
 */
class SecureController extends Controller
{
    public function filters(){
        return array(
            'accessControl',
        );
    }

    public function accessRules(){
        return array(
            array('allow',
                'users'=>array('@'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }
}