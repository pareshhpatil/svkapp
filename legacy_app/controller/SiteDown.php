<?php

class SiteDown extends Controller {

    function __construct() {
        parent::__construct();
    }

    function index($type = NULL) {
        try {
            $this->view->title = 'Planned maintenance';
            $this->view->canonical = 'sitedown';
            $this->view->type = "sitedown";
            $this->view->restore_time = $this->getSystemRestoreDateTime();
            $this->view->js_counter_time = $this->getJSCounterTime();
            
            $this->view->render('sitedown/index');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while showing site down Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }
    
    private function getSystemRestoreDateTime()
    {
        // Remove this once cache is implemented
        return "20th June 2020 3 am IST";
    }

    private function getJSCounterTime()
    {
        // Remove this once cache is implemented
        return "2020, 06 - 1, 20, 3";
    }
}
