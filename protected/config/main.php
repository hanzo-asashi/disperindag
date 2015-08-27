<?php

// uncomment the following to define a path alias
//Yii::setPathOfAlias('dashboard','@app/modules/dashboard');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name' => 'Disperindag',   

    // preloading 'log' component
    'preload' => array('log','session'),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),

    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'disperindag',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1','::1'),
        ),

        'admin'=>array(

        ), //Daftar module admin dalam config

    ),

    // application components
    'components' => array(

        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        
//        'request' => array(
//            'class' => 'application.components.DHttpRequest',
//            'csrfTokenName' => 'd!5p3r!nd@g',
//            'enableCsrfValidation' => true,
//        ),

        // uncomment the following to enable URLs in path-format

        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '' => 'site/index',
                'login' => 'site/login',
                'logout' => 'admin/logout',
                
                // Admin ROute
                'admin/berita/tambah-berita' => 'admin/berita/create',
                                
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

    		'gii'=>'gii',
               	'gii/<controller:\w+>'=>'gii/<controller>',
              	'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
            ),
        ),

        // database settings are configured in database.php
        'db' => require(dirname(__FILE__).'/database.php'),
//        'cache' => array(
//            'class' => 'system.caching.CApcCache',            
//            'keyPrefix' => 'd!5p3r!nd@g',
//        ),
//        'sessionCache' => array(
//            'class' => 'CApcCache',
//        ),
        // Handling Session
        'session' => array(
            //'class' => 'CDbHttpSession',
            'class' => 'CHttpSession',
            //'autoStart' => false,
            'sessionName' => 'DISPSESSID',
//            'connectionID' => 'db',            
//            'sessionTableName' => 'sys_session',
//            'autoCreateSessionTable' => false,
//            'useTransparentSessionID' => true,
            //'useTransparentSessionID' => ($_POST['DISPSESSID']) ? true : false,
            'cookieMode' => 'only',
            //'savePath' => dirname(__DIR__).'\runtime\session',
            'timeout' => Yii::app()->params['sessionTimeout'],
            'gCProbability' => 100,
        ),

        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),

        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                
                array(
                    'class'=>'CWebLogRoute',
                ),
                
            ),
        ),

    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'cacheDuration' => 2592000,
        'sessionTimeout' => 3600 * 12,
        'itemPerPage' => 10,
        'themeBasePath' => 'themes'
    ),
);
