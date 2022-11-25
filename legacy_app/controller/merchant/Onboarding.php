<?php

/**
 * Onboarding controller class to handle merchant onboarding process
 */
class Onboarding extends Controller {

    function __construct() {
        parent::__construct();

        //TODO : Check if using static function is causing any problems!
        $this->validateSession('merchant');
        //$this->view->js = array('dashboard/js/default.js');
    }

    /**
     * Display merchant onboarding
     */
    function index() {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $merchant_status = $this->common->getRowValue('merchant_status', 'merchant', 'merchant_id', $merchant_id);
            if ($merchant_status == 5 || $merchant_status == 6) {
                $merchant_details = $this->model->getMerchantDetails($this->session->get('merchant_id'));
                $this->view->merchant_address = $merchant_details['address'];
                $this->view->courier_tracking_number = ($merchant_details['courier_tracking_number'] == '') ? '<span>To be updated as soon as its available</span>' : $merchant_details['courier_tracking_number'];
            }
            if ($merchant_status == 1) {
                $packagelist = $this->common->getListValue('package', 'is_active', 1);
                if (empty($packagelist)) {
SwipezLogger::error(__CLASS__, '[E214]Error while fetching package list');
                    $this->setGenericError();
                }
                $this->view->packagelist = $packagelist;
            }
            $this->view->title = 'Onboarding';
            $this->renderHeader();
            $this->view->render('withmenu');
            $this->view->render('merchant/onboarding/step' . $merchant_status);
            if ($merchant_status == 1) {
                $this->view->render('paymentgateways/package');
                echo ' </div></div>';
            }
            $this->view->render('footer/footer');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E001]Error while merchant dashboard initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function step4() {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location:/merchant/onboarding');
            }
            if ($_POST['selectdoc'] == 5 || $_POST['selectdoc'] == 6) {
                $this->model->changeOnboardingStatus($this->session->get('userid'), $_POST['selectdoc']);
                header('Location:/merchant/onboarding');
            } else {
SwipezLogger::error(__CLASS__, '[E209]Error select wrong document option.for merchant [' . $merchant_id . ']and  User_id: ' . $this->session->get('userid'));
                $this->setGenericError();
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E210]Error while document select option Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

}
