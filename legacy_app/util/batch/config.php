<?php

date_default_timezone_set("Asia/Kolkata");
require_once '../../../../vendor/autoload.php';
define('LIB', '../../../lib/');
define('UTIL', '../../../util/');
define('MODEL', '../../../model/');
define('CONTROLLER', '../../../controller/');
define('VIEW', '../../../view/');
define('PACKAGE', '../../../package/');
define('TMP_FOLDER', '../../../../public/tmp/');
define('FONT_PATH', getenv('SWIPEZ_BASE').'swipez-web/public/font');
define('SMARTY_FOLDER', '../../../../smarty/templates_c');
define('SWIPEZ_UTIL_PATH', getenv('SWIPEZ_BASE') . 'swipezutil');
define('req_type', 'cron');
include(LIB . 'DBWrapper.php');
include(UTIL . 'Secretkey.php');
require LIB . 'SwipezLogger.php';
require UTIL . 'Encrypt.php';
require UTIL . 'SMS/SMSMessage.php';
require UTIL . 'SMS/SMSSender.php';
require UTIL . 'Email/EmailMessage.php';
require UTIL . 'Email/EmailWrapper.php';
require UTIL . 'Helpdesk/HelpdeskMessage.php';
require LIB . 'Model.php';
require_once MODEL . 'CommonModel.php';
require '../Batch.php';
ini_set('error_reporting', 0);
define('BATCH_CONFIG', true);

// Sentry\init(['dsn' => SENTRY_DSN,
//              'release'=>SENTRY_RELEASE,
//              'environment'=> SENTRY_ENV]);


$url = $host . getenv('BASE_URL');
putenv("APP_URL=" . $url);

