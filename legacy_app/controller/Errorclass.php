<?php

class Errorclass extends Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->logincheck();
        $title = $this->session->get('errorTitle');
        $errorMessage = $this->session->get('errorMessage');
        if (!isset($errorMessage)) {
            if ($_GET['url'] != '404') {
                header('Location:/404', TRUE, 301);
                exit;
            }
            $title = '404';
            $link = 'error/index.tpl';
        } else {
            $this->smarty->assign("title", $title);
            $this->smarty->assign("message", $errorMessage);
            $this->session->remove('errorTitle');
            $this->session->remove('errorMessage');
            $link = 'error/message.tpl';
        }
        if ($title == 'Access denied') {
            $this->smarty->assign("message", $errorMessage);
            $this->view->render('header/mDashboard');
            $this->smarty->display(VIEW . 'error/message_denied.tpl');
            $this->view->render('footer/mDashboard');
        } else {
            $this->view->title = $title;
            $this->session->remove('errorMessage');
            $this->session->remove('errorTitle');
            if ($title == 'Payment failed') {
                $this->view->render('header/guest');
                $this->smarty->display(VIEW . 'secure/failed.tpl');
                $this->view->render('footer/nonfooter');
            } else {
                if ($title == '404') {
                    $this->view->render('error/fourzezofour');
                } else {
                    $this->view->render('header/app');
                    $this->smarty->display(VIEW . $link);
                }

                $this->view->render('footer/error');
            }
        }
    }

    function oops() {
        $this->view->render('error/oops');
    }

    function accessdenied() {
        $this->view->render('header/mDashboard');
        $this->view->render('error/message_denied');
        $this->view->render('footer/mDashboard');
    }

    function forbidden() {
        $this->view->render('header/guest');
        $this->view->render('error/accessdenied');
        $this->view->render('footer/footer');
    }

    function logincheck() {
        if ($this->session->get('logged_in') == TRUE) {
            $this->view->isLoggedIn = TRUE;
            if ($this->session->get('user_status') != ACTIVE_PATRON) {
                $this->view->usertype = 'merchant';
                $merchant_type = $this->session->get('merchant_type');
                $this->view->showWhygopaid = ($merchant_type == 1) ? TRUE : FALSE;
            } else {
                $this->view->usertype = 'patron';
                $this->view->merchantType = FALSE;
            }
            //$this->view->usertype= ($this->session->get('user_status') != ACTIVE_PATRON) ? 'merchant' : 'patron';
        } else {
            $this->view->isLoggedIn = FALSE;
        }
    }

}
