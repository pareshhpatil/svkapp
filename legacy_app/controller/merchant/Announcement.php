<?php

class Announcement extends Controller {

    function __construct() {
        parent::__construct();
    }

    function index($name) {
        try {
            $this->smarty->display(UTIL . 'batch/announcement/' . $name . '.tpl');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E083]Error announcement Error:  for file [' . $name . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

}
