<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SwipezLegacyFrameworkServiceProvider extends ServiceProvider {

    protected $defer = true;

    public function register() {
        $this->app->singleton("SwipezLegacyFramework", function ($app) {

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
            require LIB . 'DBWrapper.php';
            include(UTIL . 'Secretkey.php');
            require LIB . 'Bootstrap.php';
            require LIB . 'Controller.php';
            require LIB . 'Model.php';
            require LIB . 'View.php';
            require LIB . 'SessionLegacy.php';
            require LIB . 'Validator.php';
            require LIB . 'Generic.php';
            require MODEL . 'CommonModel.php';
            require_once UTIL . 're_captcha/autoload.php';
            require UTIL . 'Csrf.php';
            // Load the Bootstrap!
            $bootstrap = new \Bootstrap();

            // Optional Path Settings
            //$bootstrap->setControllerPath();
            //$bootstrap->setModelPath();
            //$bootstrap->setDefaultFile();
            //$bootstrap->setErrorFile();

            $bootstrap->init();
        });
    }

}
