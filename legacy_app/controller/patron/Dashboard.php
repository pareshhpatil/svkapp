<?php

/**
 * Dashboard controller class to handle dashboard requests for patrons
 */
class Dashboard extends Controller
{

    function __construct()
    {
        parent::__construct();

        //TODO : Check if using static function is causing any problems!
        $this->validateSession('patron');
        //$this->view->js = array('dashboard/js/default.js');
        $this->view->disable_talk = 1;
        $this->view->selectedMenu = array(1);
    }

    /**
     * Display patrons dashboard
     */
    function index()
    {
        try {
            $service_id = $this->session->get('service_id');
            if ($service_id == null) {
                $this->session->remove('menus');
                $this->session->set('service_id', 1);
                header("Location:/patron/dashboard");
                exit;
            }
            $notification = $this->model->getNotification($this->session->get('userid'));
            $this->view->notification = $notification;
            $this->view->title = 'Patron dashboard';
            $this->view->user_type = 'patron';
            $this->view->render('header/app');
            $this->view->render('patron/dashboard/dashboard');
            $this->view->render('footer/mDashboard');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E037]Error while patron dashboard initiate Error:for user id [' . $this->session->get('userid') . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function update($type)
    {
        try {
            $this->model->seenNotification($this->session->get('userid'), $type);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E038]Error while update patron notification Error: for user id [' . $this->session->get('userid') . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
