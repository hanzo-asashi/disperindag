<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionUser()
	{
		$this->render('user');
	}
}