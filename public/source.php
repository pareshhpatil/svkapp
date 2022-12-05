<?php

    $url = substr($_SERVER['REDIRECT_URL'], 1);
    $_GET['url'] = str_replace('source/', 'home/', $url);


require '../legacy_app/util/Config.php';
require '../vendor/autoload.php';
require LIB . 'SwipezLogger.php';
require UTIL . 'Encrypt.php';
require UTIL . 'SMS/SMSMessage.php';
require UTIL . 'SMS/SMSSender.php';
require UTIL . 'Email/EmailMessage.php';
require UTIL . 'Email/EmailWrapper.php';
require UTIL . 'Helpdesk/HelpdeskMessage.php';
date_default_timezone_set("Asia/Kolkata");
//require UTIL . 'Auth.php';
//require 'util/Auth.php';
// Also spl_autoload_register (Take a look at it if you like)
require LIB . 'DBWrapper.php';
require LIB . 'Bootstrap.php';
require LIB . 'Controller.php';
require LIB . 'Model.php';
require LIB . 'View.php';
require LIB . 'Session.php';
require LIB . 'Validator.php';
require LIB . 'Generic.php';
require MODEL . 'CommonModel.php';
require_once UTIL . 're_captcha/autoload.php';


// Load the Bootstrap!
$bootstrap = new Bootstrap();

// Optional Path Settings
//$bootstrap->setControllerPath();
//$bootstrap->setModelPath();
//$bootstrap->setDefaultFile();
//$bootstrap->setErrorFile();

$bootstrap->init();
