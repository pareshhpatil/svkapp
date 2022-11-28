<?php

class Mybills extends Controller {

    function __construct() {
        parent::__construct();
        $this->view->selectedMenu = array(119);
    }

    function index() {
        try {
            if (isset($_POST['user_id']) && $_POST['user_id'] != '') {

                if (isset($_POST['g-recaptcha-response'])) {
                    $result = $this->validateCaptcha($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                    if ($result) {
                        
                    } else {
                        $hasErrors = "Invalid captcha please click on captcha box";
                        $this->smarty->assign("haserrors", $hasErrors);
                    }
                }


                $user_input = $_POST['user_id'];
                if ($hasErrors == FALSE) {
                    $type = $this->isEmail($user_input);
                    if ($type == 0) {
                        $type = $this->isMobile($user_input);
                        if ($type == 0) {
                            $type = 3;
                        }
                    }

                    $rows = $this->model->getMybills($user_input, $type);
                    if ($rows[0]['message'] == 'empty') {
                        SwipezLogger::info(__CLASS__, 'Record empty for Input :' . $user_input . ' Type :' . $type);
                        $this->smarty->assign("empty_message", 'No records found.');
                    } else {
                        $int = 0;
                        foreach ($rows as $item) {
                            $rows[$int]['paylink'] = '/patron/paymentrequest/view/' . $this->encrypt->encode($item['payment_request_id']);
                            $int++;
                        }
                        $this->smarty->assign("requestlist", $rows);
                    }
                }

                $this->smarty->assign("selected", $user_input);
            }
            $this->view->title = 'My bills';
            $this->view->canonical = 'mybills';
            $this->smarty->assign('title',$this->view->title);

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title'=> $this->view->title, 'url'=>'/mybills')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            if ($this->session->get('logged_in')) {
                $this->validateSession('patron');
                $this->view->header_file = ['list'];
        		$this->view->render('header/app');
                $this->smarty->display(VIEW . 'mybills/patronmybills.tpl');
            } else {
                $this->view->render('header/guest');
                $this->smarty->display(VIEW . 'mybills/mybills.tpl');
            }
            $this->view->render('footer/footer');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E003]Error while merchant invoice create initiate Error: for merchant [' . $merchant_id . '] and for template [' . $this->view->templateselected . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function isEmail($user_id) {
        if (filter_var($user_id, FILTER_VALIDATE_EMAIL) == false && $user_id != '') {
            return 0;
        } else {
            return 1;
        }
    }

    function isMobile($user_id) {
        if (preg_match('/^\d{10}$/', $user_id) && substr($user_id, 0, 1) != 0) {
            return 2;
        } else {
            return 0;
        }
    }

    function isLandline($user_id) {
        return 3;
    }

    function getplandetails($link, $value) {
        try {
            $merchant_id = substr($link, 0, 10);
            $plan_id = substr($link, 10);
            $details = $this->common->getSingleValue('prepaid_plan', 'plan_id', $plan_id);
            // print_r($details);
            $plan_details = $this->model->getMyPlanDetails($merchant_id, $details['category'], $details['source'], $details['speed'], $details['data'], $value);
            $plan_details['plan_link'] = $this->encrypt->encode($plan_details['plan_id']);
            $plan_details['tax_amount']=0;
            if($plan_details['tax1_percent']>0)
            {
                $tax=$plan_details['tax1_percent']+$plan_details['tax2_percent'];
                $plan_details['base_amount']=round(($plan_details['price']*100)/(100+$tax),2);
                $plan_details['tax_amount']=round($plan_details['price']-$plan_details['base_amount'],2);
            }
            echo json_encode($plan_details);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E45]Error while getplandetails ' . $e->getMessage());
        }
    }

}
