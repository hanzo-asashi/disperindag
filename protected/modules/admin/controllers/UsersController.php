<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UsersController extends Controller
{

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            //'postOnly + delete', // we only allow deletion via POST request
        );
    }
    
//    public function accessRules()
//    {
//        return array(
//            array('allow',  // allow all users to perform 'index' and 'view' actions
//                'actions' => array('index','view'),
//                'users' => array('*'),
//            ),
//            array('allow', // allow authenticated user to perform 'create' and 'update' actions
//                'actions' => array('create','update'),
//                'users' => array('@'),
//            ),
//            array('allow', // allow admin user to perform 'admin' and 'delete' actions
//                'actions' => array('admin','delete'),
//                'users' => array('admin'),
//            ),
//            array('deny',  // deny all users
//                'users' => array('*'),
//            ),
//        );
//    }

    public function actionIndex()
    {
        $this->render("index", array(
        ));
    }

    /**
     * Displays a particular model.
     *
     * @param int $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }
    
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new User();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }
    
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     *
     * @param int $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }
    
    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     *
     * @return User the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = User::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    /**
     * Displays the login page.
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        // if it is ajax validation request
//        if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_login') {
//            echo CActiveForm::validate($model);
//            Yii::app()->end();
//        }
        # Response Data Array
        $resp = array();

        //var_dump($username,$password);exit;
        // This array of data is returned for demo purpose, see assets/js/neon-forgotpassword.js
        $resp['submitted_data'] = !empty($_POST) ? $_POST : array();

        // Login success or invalid login data [success|invalid]
        // Your code will decide if username and password are correct
        $login_status = 'invalid';
        //var_dump($username,$password);exit;
        // collect user input data
        if (!empty($resp['submitted_data'])) {
            // Fields Submitted
            $username = $_POST["username"];
            $password = $_POST["password"];
            $model->attributes = $resp['submitted_data'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $login_status = 'success';
            }

            $resp['login_status'] = $login_status;
            // Login Success URL
            if($login_status == 'success')
            {
                // If you validate the user you may set the user cookies/sessions here
                    setcookie("logged_in", $username);
                    $_SESSION["logged_user"] = $username;
                // Set the redirect url after successful login
                $resp['redirect_url'] = $this->redirect(Yii::app()->user->returnUrl);
                //$this->redirect(Yii::app()->user->returnUrl);
            }
        }
        //echo json_encode($resp);
        // display the login form
        $this->renderPartial('login', array(
            //'model' => $model
        ));
    }
}
