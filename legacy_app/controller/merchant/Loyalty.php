<?php

/**
 * Supplier controller class to handle Merchants supplier
 */
class Loyalty extends Controller {

    function __construct() {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->selectedMenu = 'loyalty';
    }

    function scanqrcode() {
        $has_qr = $this->common->getRowValue('loyalty_enable', 'merchant_setting', 'merchant_id', $this->merchant_id);
        if ($has_qr == 0) {
            $this->setError('Access denied', 'You do not have access to this feature. If you need access to this feature please contact to support@swipez.in');
            header("Location:/error");
            exit;
        }

        $this->view->title = 'Scan QR code';
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title'=> 'Loyalty ', 'url'=>''),
            array('title'=> $this->view->title, 'url'=>'')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

       
        $this->view->render('merchant/loyalty/qrcode');
    }

    function points($type) {
        try {
            $last_date = $this->getLast_date();
            $current_date = date('d M Y');
            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $status = $_POST['status'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $status = '';
            }
            $this->smarty->assign("from_date", $from_date);
            $this->smarty->assign("to_date", $to_date);
            $this->smarty->assign("status", $status);

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($type == 'earn') {
                $ptype = 1;
                $this->view->title = "Earned points list";
                $this->view->selectedMenu = array(11, 46);
            } else {
                $ptype = 2;
                $this->view->title = "Redeemed points list";
                $this->view->selectedMenu = array(11, 47);
            }

            $list = $this->model->getPointList($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $ptype);

            $this->smarty->assign("list", $list);
            $this->smarty->assign('title',$this->view->title);

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title'=> 'Loyalty ', 'url'=>''),
                array('title'=> $this->view->title, 'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/loyalty/' . $type . 'list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    function setting() {
        try {
            $setting = $this->common->getSingleValue('merchant_loyalty_setting', 'merchant_id', $this->merchant_id);
            $this->smarty->assign("se", $setting);
            // if(isset($setting['title'])){
            //     $title = $setting['title'];
            // } else {
            //     $title = "Setup your loyalty points program";
            // }
            
            $this->smarty->assign("title", 'Settings');
            $this->view->title = "Settings";
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title'=> 'Loyalty ', 'url'=>''),
                array('title'=> $this->view->title, 'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end
            $this->view->datatablejs = 'table-no-export';
            $this->view->selectedMenu = array(11, 48);
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/loyalty/setting.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    function updatesetting() {
        if ($_POST['id'] != '') {
            $this->model->updatesettings($_POST['id'], $_POST['nomenclature'], $_POST['earning_logic_rs'], $_POST['earning_logic_points'], $_POST['redeem_logic_points'], $_POST['redeem_logic_rs'], $_POST['threshold'], $_POST['expiry']);
        } else {
            $this->model->savesettings($this->merchant_id, $_POST['nomenclature'], $_POST['earning_logic_rs'], $_POST['earning_logic_points'], $_POST['redeem_logic_points'], $_POST['redeem_logic_rs'], $_POST['threshold'], $_POST['expiry']);
        }
        $this->session->set("successMessage", ' Loyalty settings updated successfully');
        header('Location: /merchant/loyalty/setting');
        die();
    }

    function detail($link) {
        $id = $this->encrypt->decode($link);
        if (substr($id, 0, 1) == 'M') {
            $merchant_id = substr($id, 0, 10);
            $user_id = substr($id, 10, 10);
            $customer_id = substr($id, 20);
            $loyalty_setting = $this->common->getSingleValue('merchant_loyalty_setting', 'merchant_id', $merchant_id);
            $customer_detail = $this->common->getSingleValue('customer', 'customer_id', $customer_id);

            $points = $this->model->getCustomerPoints($customer_id);
            $redeem_logic = $loyalty_setting['redeeming_logic_points'] / $loyalty_setting['redeeming_logic_rs'];
            if ($points > 0) {
                $point_rs = round(($points / $redeem_logic), 2);
            } else {
                $points = 0;
                $point_rs = 0;
            }
            $this->view->title = 'Customer detail';
            $this->smarty->assign("customer_detail", $customer_detail);
            $this->smarty->assign("loyalty_setting", $loyalty_setting);
            $this->smarty->assign("link", $link);
            $this->smarty->assign("points", $points);
            $this->smarty->assign("points_rs", $point_rs);
        } else {
            $this->smarty->assign("error", "Invalid QR Code");
        }
        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/loyalty/customerqr.tpl');
        $this->view->render('footer/list');
    }

    function saveearnpoints() {
        $id = $this->encrypt->decode($_POST['link']);
        $merchant_id = substr($id, 0, 10);
        $user_id = substr($id, 10, 10);
        $customer_id = substr($id, 20);
        $loyalty_setting = $this->common->getSingleValue('merchant_loyalty_setting', 'merchant_id', $merchant_id);
        $earn_logic = $loyalty_setting['earning_logic_rs'] / $loyalty_setting['earning_logic_points'];
        $points = $_POST['amount'] / $earn_logic;
        $this->model->savePoints($merchant_id, $user_id, $customer_id, 1, $_POST['amount'], $points, $points, 0, $_POST['narrative'], $this->user_id);
        $this->session->set("successMessage", ' ' . $points . ' Points have been added');
        header('Location: /merchant/loyalty/detail/' . $_POST['link']);
        die();
    }

    function saveredeempoints() {
        $id = $this->encrypt->decode($_POST['link']);
        $merchant_id = substr($id, 0, 10);
        $user_id = substr($id, 10, 10);
        $customer_id = substr($id, 20);
        $loyalty_setting = $this->common->getSingleValue('merchant_loyalty_setting', 'merchant_id', $merchant_id);
        $balance_points = $this->common->querylist('select id,balance_points from customer_loyalty_points where type=1 and is_active=1 and customer_id=' . $customer_id . ' and balance_points>0');
        $redeem_logic = $loyalty_setting['redeeming_logic_points'] / $loyalty_setting['redeeming_logic_rs'];
        $points = $_POST['amount'] * $redeem_logic;
        $redeem_points = $points;
        foreach ($balance_points as $bb) {
            if ($redeem_points > 0) {
                if ($redeem_points > $bb['balance_points']) {
                    $balance_point = 0;
                    #full redeem
                    $status = 1;
                } else {
                    $balance_point = $bb['balance_points'] - $redeem_points;
                    #partially redeem
                    $status = 2;
                }
                $redeem_points = $redeem_points - $bb['balance_points'];
                $this->model->updatePoints($bb['id'], $balance_point, $status, $this->user_id);
            } else {
                break;
            }
        }
        $this->model->savePoints($merchant_id, $user_id, $customer_id, 2, $_POST['amount'], $points, 0, 0, $_POST['narrative'], $this->user_id);
        $this->session->set("successMessage", ' ' . $points . ' Points redeem successfully');
        header('Location: /merchant/loyalty/detail/' . $_POST['link']);
        die();
    }

}

?>
