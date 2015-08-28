<?php

class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/frontend/site/index.php'
        // using the default layout 'protected/views/frontend/layouts/main.php'
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }
    
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * Displays the contact page.
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?'.base64_encode($model->name).'?=';
                $subject = '=?UTF-8?B?'.base64_encode($model->subject).'?=';
                $headers = "From: $name <{$model->email}>\r\n".
                        "Reply-To: {$model->email}\r\n".
                        "MIME-Version: 1.0\r\n".
                        'Content-Type: text/plain; charset=UTF-8';

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page.
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_login') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        # Response Data Array
        $resp = array();


        // Fields Submitted
        $username = !empty($_POST["username"]) ? $_POST["username"] : "";
        $password = !empty($_POST["password"]) ? $_POST["password"] : "";

        // This array of data is returned for demo purpose, see assets/js/neon-forgotpassword.js
        $resp['submitted_data'] = !empty($_POST) ? $_POST : "";

        // Login success or invalid login data [success|invalid]
        // Your code will decide if username and password are correct
        $login_status = 'invalid';
        
        // collect user input data
        if (!empty($resp['submitted_data'])) {
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
                        setcookie("logged_in", "user_id");
                        $_SESSION["logged_user"] = "user_id";

                // Set the redirect url after successful login
                //$resp['redirect_url'] = '';
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        echo json_encode($resp);
        
        // display the login form
        $this->renderPartial('login', array(
            //'model' => $model
        ));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}
