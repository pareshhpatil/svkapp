<?php

class Logout extends Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        try {
            $this->session->setCookie('cookie_user_status', NULL);
            $this->session->destroy();
            header('Location: /login');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E074]Error while logout Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

}
