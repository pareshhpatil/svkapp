<?php

/**
 * Supplier controller class to handle Merchants supplier
 */
class Promotions extends Controller {

    function __construct() {
        parent::__construct();
        $this->validateSession('merchant');
    }

    /**
     * Display merchant suppliers list
     */
    function viewlist() {
        try {
            $list = $this->common->getListValue('merchant_outgoing_sms', 'merchant_id', $this->merchant_id, 1);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['promotion_id']);
                $list[$int]['created_at'] = $this->generic->formatTimeString($item['created_date']);
                $int++;
            }
            $this->view->hide_first_col = true;
            $this->view->selectedMenu = array(10, 44);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Promotions list");
            $this->view->title = "Promotions list";

            //Breadcumbs array start
            $breadcumbs_array = array(
            array('title' => 'Promotions','url' => ''),
            array('title'=> $this->view->title, 'url'=> ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/promotions/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    /**
     * Create new supplier for merchant
     */
    function create() {
        $merchant_id = $this->session->get('merchant_id');
        $this->view->js = array('promotions');
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        $payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : '';
        $filter_by = isset($_POST['filter_by']) ? $_POST['filter_by'] : '';
        $filter_condition = isset($_POST['filter_condition']) ? $_POST['filter_condition'] : '';
        $filter_value = isset($_POST['filter_value']) ? $_POST['filter_value'] : '';
        $group = isset($_POST['group']) ? $_POST['group'] : '';
        $numbers = isset($_POST['number']) ? $_POST['number'] : '';
        $bulk_id = 0;
        $is_bulk = 0;
        $column_select = array();

        $this->smarty->assign("numbers", $numbers);
        $this->smarty->assign("filter_by", $filter_by);
        $this->smarty->assign("filter_condition", $filter_condition);
        $this->smarty->assign("filter_value", $filter_value);
        $this->smarty->assign("group", $group);

        $_SESSION['group'] = $group;
        $_SESSION['db_column'] = $column_select;
        $_SESSION['type'] = 'promo';
        $_SESSION['customer_status'] = $status;
        $_SESSION['payment_status'] = $payment_status;
        $_SESSION['customer_bulk_id'] = $bulk_id;
        $_SESSION['display_column'] = $column_select;
        $_SESSION['filter_by'] = $filter_by;
        $_SESSION['filter_condition'] = $filter_condition;
        $_SESSION['filter_value'] = $filter_value;

        $customer_group = $this->common->getListValue('customer_group', 'merchant_id', $this->merchant_id, 1);
        $int = 0;
        foreach ($customer_group as $item) {
            $enc = $this->encrypt->encode($item['group_id']);
            $customer_group[$int]['link'] = $enc;
            $int++;
        }

        $promo_sms = $this->common->getListValue('merchant_sms_template', 'merchant_id', $this->merchant_id, 1);
        $int = 0;
        foreach ($promo_sms as $item) {
            $enc = $this->encrypt->encode($item['id']);
            $promo_sms[$int]['link'] = $enc;
            $int++;
        }

        $this->smarty->assign("promo_sms", $promo_sms);
        $this->smarty->assign("customer_group", $customer_group);

        $this->view->selectedMenu = array(10, 43);
        $this->smarty->assign("is_bulk", $is_bulk);

        $this->setAjaxDatatableSession();
        $this->view->custom_empty_message ='Currently there are no customers in the selected group <a href="/merchant/customer/create" class="btn btn-xs green">Add a new customer</a>  or <a href="/merchant/customer/managegroup" class="btn btn-xs green">Manage customer groups</a>';
        $this->view->title = 'Send promotional SMS';
        $this->smarty->assign("title", $this->view->title);
        $this->smarty->assign("status", $status);
        $this->smarty->assign("payment_status", $payment_status);
        
        //Breadcumbs array start
        $breadcumbs_array = array(
        array('title' => 'Promotions','url' => ''),
        array('title'=> $this->view->title, 'url'=> ''),
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->datatablejs = 'table-small';
        $this->view->canonical = 'merchant/customer/viewlist';
        $this->view->ajaxpage ='customer_group.php';
        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/promotions/create.tpl');
        $this->view->render('footer/customer_group');
    }

    function validatepromotion() {

        $mobile_numbers = explode(',', $_POST['numbers']);
        $mobile_numbers = array_filter($mobile_numbers);
        $_POST['customer_check'] = empty($_POST['customer_check']) ? array() : $_POST['customer_check'];
        $numbers = array_merge($_POST['customer_check'], $mobile_numbers);
        $_POST['total_records'] = $numbers;
        require_once CONTROLLER . 'merchant/Genericvalidator.php';
        $validator = new Genericvalidator($this->model);
        $validator->validatePromotionSave($this->merchant_id);
        $hasErrors = $validator->fetchErrors();

        if (empty($_POST['customer_check']) && count($mobile_numbers) < 1) {
            $hasErrors[0][0] = 'Mobile numbers empty';
            $hasErrors[0][1] = 'Please select customers Or enter mobile numbers';
        }
        if ($hasErrors == FALSE) {
            $customer['status'] = 1;
            echo json_encode($customer);
        } else {
            foreach ($hasErrors as $error_name) {
                $error = '<b>' . $error_name[0] . '</b> -';
                $int = 1;
                while (isset($error_name[$int])) {
                    $error .= '' . $error_name[$int];
                    $int++;
                }
                $err[]['value'] = $error;
            }
            $haserror['error'] = $err;
            echo json_encode($haserror);
        }
    }

    function savepromotion() {
        $mobile_numbers = explode(',', $_POST['numbers']);
        $mobile_numbers = array_filter($mobile_numbers);
        $_POST['customer_check'] = empty($_POST['customer_check']) ? array() : $_POST['customer_check'];
        $numbers = array_merge($_POST['customer_check'], $mobile_numbers);
        $_POST['total_records'] = $numbers;
        require_once CONTROLLER . 'merchant/Genericvalidator.php';
        $validator = new Genericvalidator($this->model);
        $validator->validatePromotionSave($this->merchant_id);
        $hasErrors = $validator->fetchErrors();
        if ($numbers < 1) {
            $hasErrors[0][0] = 'Mobile numbers empty';
            $hasErrors[0][1] = 'Please select customers Or enter mobile numbers';
        }
        if ($hasErrors == FALSE) {
            $_POST['customer_check'] = empty($_POST['customer_check']) ? array() : $_POST['customer_check'];
            $promotion_id = $this->model->createPromotion($_POST['promotion_name'], $_POST['template_id'], $_POST['template_name'], $_POST['sms'], count($numbers), $this->merchant_id, $this->user_id);
            $this->model->saveOutgoingSMSDetail($promotion_id, $_POST['customer_check'], $mobile_numbers, $this->user_id);
            $this->session->set('successMessage', 'Promotion has been saved successfully.');
            header("Location:/merchant/promotions/viewlist");
        } else {
SwipezLogger::error(__CLASS__, '[E290]Error while validate promotion');
            header("Location:/merchant/promotions/create");
        }
    }

    /**
     * Delete merchant supplier
     */
    function records($link) {
        try {
            $id = $this->encrypt->decode($link);
            $list = $this->common->getListValue('merchant_outgoing_sms_detail', 'promotion_id', $id);
            $this->smarty->assign("list", $list);
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'merchant/promotions/records.tpl');
            $this->view->render('footer/nonfooter');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E290]Error while deleting sms template Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Delete merchant supplier
     */
    function deletesms($link) {
        try {
            $id = $this->encrypt->decode($link);
            $result = $this->model->deleteTemplate($id, $this->user_id);
            if ($result == true) {
                $this->session->set('successMessage', 'SMS template has been deleted successfully.');
                header("Location:/merchant/promotions/create");
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E290]Error while deleting sms template Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function delete($link) {
        try {
            $id = $this->encrypt->decode($link);
            $result = $this->model->deletePromotion($id, $this->user_id);
            if ($result == true) {
                $this->session->set('successMessage', 'Promotion has been deleted successfully.');
                header("Location:/merchant/promotions/viewlist");
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E290]Error while delete Promotion Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function downloadreport($link) {
        try {
            $id = $this->encrypt->decode($link);
            $rows = $this->model->getSMSReport($id);
            if (!empty($rows)) {
                $result = $this->common->excelexport('Swipez SMS Report', $rows);
            }
            exit();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E290]Error while delete Promotion Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function fetchreport($link) {
        try {
            $id = $this->encrypt->decode($link);
            $result = $this->common->genericupdate('merchant_outgoing_sms', 'swipez_status', 4, 'promotion_id', $id, $this->user_id);
            if ($result == true) {
                $this->session->set('successMessage', 'The requested data is too large to be downloaded instantaneously. We have started your download and the requested file will soon appear in "Download" section. Refresh page to view progress');
                header("Location:/merchant/promotions/viewlist");
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E290]Error while delete Promotion Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function savetemplate() {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/promotions/create");
            }

            require_once CONTROLLER . 'merchant/Genericvalidator.php';
            $validator = new Genericvalidator($this->model);
            $validator->validatePromoTemplate($this->merchant_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $id = $this->model->createSMSTemplate($_POST['template_name'], $_POST['sms'], $this->merchant_id, $this->user_id);
                $array['status'] = 1;
                $array['sms'] = $_POST['sms'];
                $array['template_id'] = $id;
                $array['name'] = $_POST['template_name'];
            } else {
                $array['status'] = 0;
                foreach ($hasErrors as $error_name) {
                    $error = '<b>' . $error_name[0] . '</b> -';
                    $int = 1;
                    while (isset($error_name[$int])) {
                        $error .= '' . $error_name[$int];
                        $int++;
                    }
                    $err[]['value'] = $error;
                }
                $array['error'] = $err;
            }
            echo json_encode($array);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[EB002]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

}

?>
