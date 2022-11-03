<?php

// Always provide a TRAILING SLASH (/) AFTER A PATH
define('LIB', '../legacy_app/lib/');
define('UTIL', '../legacy_app/util/');
define('MODEL', '../legacy_app/model/');
define('CONTROLLER', '../legacy_app/controller/');
define('VIEW', '../legacy_app/view/');
define('PACKAGE', '../legacy_app/package/');
define('TMP_FOLDER', 'tmp/');
define('FONT_PATH', public_path('font'));
define('SMARTY_FOLDER', '../smarty/templates_c');
define('SITEDOWN', FALSE);
define('SITE_DOWN_CLASS', 'SiteDown');
define('SWIPEZ_UTIL_PATH', getenv('SWIPEZ_BASE') . 'swipezutil');

define('SUPPORT_LINK', 'https://helpdesk.swipez.in/help');


// The sitewide hashkey, do not change this because its used for passwords!
// This is for other hash keys... Not sure yet
define('HASH_GENERAL_KEY', 'MixitUp200');

// This is for database passwords only
define('HASH_PASSWORD_KEY', 'catsFLYhigh2000miles');

//This defines the max level of slashes that need to be traversed from browser
define('MVC_MAX_LEVEL', 4);

//This section defines config key values as per the config table
define('ACTIVE_PATRON', 2);
define('VERIFIED_MERCHANT', 12);
define('LEGAL_PENDING_MERCHANT', 13);
define('ACTIVE_MERCHANT', 12);

//This section is for mGage related URL
$baseurl = '.' . getenv('BASE_URL');
define('BATCH_CONFIG', false);
ini_set('error_reporting', 0);
ini_set('session.cookie_domain', $baseurl);
ini_set('session.cookie_httponly', True);
//this section is for get and post form list

set_exception_handler('exception_handler');

//header("X-Frame-Options: SAMEORIGIN");
function exception_handler($e) {
    
SwipezLogger::error("Config", "Exception caught in global exception handler : " . $e->getMessage());
    header('Location: /error/oops');
}
