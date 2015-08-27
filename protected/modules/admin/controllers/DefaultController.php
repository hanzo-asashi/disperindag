<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
            $this->render('index');
	}
       
        public function actionLogout()
	{
            Yii::app()->user->logout();
            Yii::app()->session->clear();
            $this->redirect("/");
	}
}