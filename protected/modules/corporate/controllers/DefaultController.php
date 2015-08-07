<?php

class DefaultController extends Controller
{
    //public $layout = '//frontend/layouts/column1';

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionContact()
	{
		$this->render('contact');
	}
}