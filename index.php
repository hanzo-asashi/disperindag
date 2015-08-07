<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/protected/vendor/yii/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
$app = Yii::createWebApplication($config)->runEnd('frontend');
//$app = Yii::createWebApplication($config)->run();

//
// Yii::import("ext.yiiext.components.zendAutoloader.EZendAutoloader", true);
//
// // you can load not only Zend classes but also other classes with the same naming
// // convention
// EZendAutoloader::$prefixes = array('Zend', 'Custom');
// Yii::registerAutoloader(array("EZendAutoloader", "loadClass"), true);

// $app = run();
