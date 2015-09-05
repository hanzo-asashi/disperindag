<?php

/**
 * Created by PhpStorm.
 * User: hanse
 * Date: 9/6/2015
 * Time: 1:17 AM
 */
class DeleteAction extends CAction
{
    public $pk = 'userid';
    public $redirectTo = 'index';
    public $modelClass;

    function run(){
        if(empty($_GET[$this->pk]))
            throw new CHttpException(404);

        $model = CActiveRecord::model($this->modelClass)->findByPk($_GET[$this->pk]);
        if(!$model)
            throw new CHttpException(404);
        if($model->delete())
            $this->redirect($this->redirectTo);

        throw new CHttpException(500);
    }
}