<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name' => 'My Console Application',
    'modules'=>array(
	    'admin'=>array(
		    # encrypting method (php hash function)
		    'hash' => 'md5',

		    # send activation email
		    'sendActivationMail' => false,

		    # allow access for non-activated users
		    'loginNotActiv' => false,

		    # activate user on registration (only sendActivationMail = false)
		    'activeAfterRegister' => true,

		    # automatically login from registration
		    'autoLogin' => true,

		    # registration path
		    'registrationUrl' => array('/admin/user/registration'),

		    # recovery password path
		    'recoveryUrl' => array('/admin/user/recovery'),

		    # login form path
		    'loginUrl' => array('/admin/user/login'),

		    # page after login
		    'returnUrl' => array('admin/user/profile'),

		    # page after logout
		    'returnLogoutUrl' => array('/admin/user/login'),
	    ),
    ),

    // preloading 'log' component
    'preload' => array('log'),

    // application components
    'components' => array(

        // database settings are configured in database.php
        'db' => require(dirname(__FILE__).'/database.php'),

        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),

    ),
);
