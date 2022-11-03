<?php

/**
 * Payment request functionality for viewing payment requests, confirming and invoking payment gateway
 * 
 */
require_once MODEL . 'CablePackageModel.php';

class Cable extends Controller {

    private $model = null;

    function __construct() {
        parent::__construct();
        $this->model = new CablePackageModel();
        $this->validateCableSession();
    }

    function validateCableSession() {
        $this->view->display_name = $this->session->get('display_name');
        $this->view->is_loggedin = $this->session->get('logged_in');
        $this->user_id = $this->session->get('userid');
    }

    function setLogo($merchant_id) {
        $logo = $this->common->getRowValue('logo', 'merchant_landing', 'merchant_id', $merchant_id);
        $this->view->company_name = $this->common->getRowValue('company_name', 'merchant', 'merchant_id', $merchant_id);
        $this->view->logo = $logo;
    }

    function logout() {
        $this->session->destroy();
        header('Location: /cable/login');
        exit;
    }

    function login() {
        header('location: /login', 301);
        exit;
        if (!empty($_POST)) {
            $result = $this->model->loginCheck($_POST['mobile'], $_POST['password']);
            if (empty($result)) {
                $this->smarty->assign("error", 'Please enter valid Mobile and Password');
            } else {
                if ($result['user_status'] != 2) {
                    $this->smarty->assign("error", 'User is not verified');
                } else {
                    $this->session->set('cable_logged_in', true);
                    $this->session->set('user_name', $result['first_name'] . ' ' . $result['last_name']);
                    $this->session->set('userid', $result['user_id']);
                    header('location: /cable/settopbox');
                    exit;
                }
            }
        }
        $this->view->title = 'Cable Set Top Box';
        $this->view->render('header/cable');
        $this->smarty->display(VIEW . 'patron/cable/login.tpl');
        $this->view->render('footer/cable');
    }

    function reset() {
        $this->validateSession('patron');
        if (!empty($_POST)) {
            $password = $this->common->getRowValue('password', 'user', 'user_id', $this->user_id);
            if (password_verify($_POST['exist_password'], $password)) {
                if ($_POST['password'] != $_POST['re_password']) {
                    $this->smarty->assign("error", 'Confirm password does not matched');
                } else {
                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    if (strlen($this->user_id) == 10 && substr($this->user_id, 0, 1) == 'U') {
                        $this->common->genericupdate('user', 'password', $password_hash, 'user_id', $this->user_id);
                        header('location: /cable/logout');
                        exit;
                    }
                }
            } else {
                $this->smarty->assign("error", 'Current password is incorect');
            }
        }
        $this->view->title = 'Password Reset';
        $this->view->render('header/cable');
        $this->smarty->display(VIEW . 'patron/cable/reset.tpl');
        $this->view->render('footer/cable');
    }

    public function settopbox() {
        try {
            $this->validateSession('patron');
            $cust_details = $this->common->getListValue('customer', 'user_id', $this->user_id, 1);
            $this->setLogo($cust_details[0]['merchant_id']);
            $settopbox = array();
            $customer_ids = array();
            foreach ($cust_details as $ct) {
                $customer_ids[] = $ct['customer_id'];
            }
            if (!empty($customer_ids)) {
                $settopbox = $this->model->getCustomerSetTopBox($customer_ids);
                $settopbox = $this->generic->getEncryptedList($settopbox, 'link', 'id');
            }
            $this->smarty->assign("settopbox", $settopbox);
            $this->view->title = 'Cable Set Top Box';
            $this->view->render('header/cable');
            $this->smarty->display(VIEW . 'patron/cable/settopbox.tpl');
            $this->view->render('footer/cable');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E039]Error while listing patron payment request Error: for user id [' . $this->session->get('userid') . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function customerpackages($link) {
        try {
            $this->validateSession('patron');
            $settopbox_id = $this->encrypt->decode($link);
            $sev_det = $this->common->getSingleValue('customer_service', 'id', $settopbox_id);
            $merchant_id = $sev_det['merchant_id'];
            $cable_setting = $this->common->getSingleValue('cable_setting', 'merchant_id', $merchant_id);
            $grand_total = $sev_det['cost'];
            $this->setLogo($merchant_id);
            $service_details = $this->common->getListValue('customer_service_package', 'service_id', $settopbox_id, 1, ' order by cost desc');
            if (empty($service_details)) {
                header("Location:/cable/selectpackage/" . $link);
                die();
            }
            $channels = array();
            $packages = array();
            $total_cost = 0;
            foreach ($service_details as $det) {
                //$grand_total = $grand_total + $det['cost'];
                if ($det['package_type'] == 2) {
                    $chann = $this->common->getSingleValue('cable_channel', 'channel_id', $det['channel_id'], 1);
                    $chann['cost'] = $det['cost'];
                    $chann['exist_id'] = $det['id'];
                    $total_cost = $total_cost + $det['cost'];
                    $channels[] = $chann;
                } else {
                    $pkg = $this->model->getPackageDetails($det['package_id']);

                    $pkg['exist_id'] = $det['id'];
                    $pkgchannels = $this->model->getPackageChannels($det['package_id']);
                    if ($pkg['sub_package_id'] > 0) {
                        $spkg = $this->model->getPackageDetails($pkg['sub_package_id']);
                        $spkgchannels = $this->model->getPackageChannels($pkg['sub_package_id']);
                        $spkg['default'] = 1;
                        $spkg['package_cost'] = 0;
                        $packages[$pkg['sub_package_id']] = $spkg;
                        $packages[$pkg['sub_package_id']]['channels'] = $spkgchannels;

                        $pkg['default'] = 0;
                        $pkg['package_cost'] = $det['cost'];
                        $packages[$pkg['package_id']] = $pkg;
                        $packages[$pkg['package_id']]['channels'] = $pkgchannels;
                    } else {
                        $pkg['default'] = 0;
                        $pkg['package_cost'] = $det['cost'];
                        $packages[$pkg['package_id']] = $pkg;
                        $packages[$pkg['package_id']]['channels'] = $pkgchannels;
                    }
                }
            }

            $this->smarty->assign("link", $link);
            $this->smarty->assign("packages", $packages);
            $this->smarty->assign("channels", $channels);
            $this->smarty->assign("grand_total", $grand_total);
            $this->smarty->assign("channels_cost", $total_cost);
            if (!empty($cable_setting)) {
                $this->smarty->assign("cable_setting", json_encode($cable_setting));
            }
            $this->view->title = 'Cable Set Top Box';
            $this->view->render('header/cable');
            $this->smarty->display(VIEW . 'patron/cable/customerpackages.tpl');
            $this->view->render('footer/cable');
        } catch (Exception $e) {
Sentry\captureException($e);
            SwipezLogger ::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function selectpackage($link) {
        try {
            $this->validateSession('patron');
            $settopbox_id = $this->encrypt->decode($link);
            require_once CONTROLLER . 'CableWrapper.php';
            $cableWrapper = new CableWrapper();
            $smarty = $cableWrapper->setSelectpackage($settopbox_id, $this->common, $this->model);
            foreach ($smarty as $key => $value) {
                $this->smarty->assign($key, $value);
            }
            $this->setLogo($smarty['merchant_id']);
            $this->smarty->assign('link', $link);
            $error = $this->session->get('errorMessage');
            if (isset($error)) {
                $this->session->remove('errorMessage');
                $this->smarty->assign("error", $error);
            }
            $this->view->title = 'Cable Set Top Box';
            $this->view->render('header/cable');
            $this->smarty->display(VIEW . 'patron/cable/selectpackage.tpl');
            $this->view->render('footer/cable');
        } catch (Exception $e) {
Sentry\captureException($e);
            SwipezLogger ::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function packagesaved() {
        try {
            $this->validateSession('patron');
            $link = $this->encrypt->encode($_POST['settopbox_id']);
            require_once CONTROLLER . 'CableWrapper.php';
            $cableWrapper = new CableWrapper();
            $response = $cableWrapper->savePackage($this->common, $this->model, $this->user_id);
            if ($response['status'] == 0) {
                $this->session->set('errorMessage', $response['error']);
                header("Location:/cable/selectpackage/" . $link);
                die();
            } else {
                $this->session->set('successMessage', 'Your packages have been saved successfully.');
                header("Location:/cable/customerpackages/" . $link);
                die();
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            SwipezLogger ::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function updatepackage() {
        try {
            $this->validateSession('patron');
            $service_id = $this->encrypt->decode($_POST['service_id']);
            $this->model->deletePackage($service_id);
            $packages = $_POST['exist_id'];

            foreach ($packages as $pkg) {
                if ($pkg > 0) {
                    $this->common->genericupdate('customer_service_package', 'is_active', 1, 'id', $pkg, null, " and service_id=" . $service_id);
                }
            }

            $det = $this->common->getSingleValue('customer_service', 'id', $service_id);
            $cable_setting = $this->common->getSingleValue('cable_setting', 'merchant_id', $det['merchant_id']);
            $group_id = $this->common->getRowValue('group_id', 'cable_group', 'merchant_id', $det['merchant_id'], 1, " and min_package_selection=1");
            if ($group_id > 0) {
                $count = $this->model->getMinPackage($group_id, $service_id);
                if ($count == 0) {
                    $default_pkg = $this->common->getSingleValue('cable_package', 'merchant_id', $det['merchant_id'], 1, " and is_default=1 and package_group=" . $group_id);
                    $this->model->saveCustomerService($service_id, $det['customer_id'], $det['merchant_id'], 1, $default_pkg['package_id'], 0, $default_pkg['total_cost'], $this->user_id);
                }
            }

            $total_cost = $this->common->getRowValue('sum(cost)', 'customer_service_package', 'service_id', $service_id, 1);

            $packages = $this->model->getCustomerPackageList($service_id);

            $ncf_channels = 0;

            foreach ($packages as $pkg) {
                if ($pkg['group_type'] == 1 && $cable_setting['ncf_base_package'] == 1) {
                    $package_channel = $this->common->getRowValue('count(id)', 'cable_package_channel', 'package_id', $pkg['package_id'], 1);
                    $ncf_channels = $ncf_channels + $package_channel;
                }

                if ($pkg['group_type'] == 2 && $cable_setting['ncf_addon_package'] == 1) {
                    $package_channel = $this->common->getRowValue('count(id)', 'cable_package_channel', 'package_id', $pkg['package_id'], 1);
                    $ncf_channels = $ncf_channels + $package_channel;
                }
            }

            if ($cable_setting['ncf_alacarte_package'] == 1) {
                $package_channel = $this->common->getRowValue('count(id)', 'customer_service_package', 'service_id', $service_id, 1, ' and package_type=2');
                $ncf_channels = $ncf_channels + $package_channel;
            }

            if ($ncf_channels > 0) {
                $nfc_amt = $cable_setting['ncf_fee'] + ($cable_setting['ncf_fee'] * $cable_setting['ncf_tax'] / 100);
                while ($ncf_channels > 0) {
                    $total_cost = $total_cost + $nfc_amt;
                    $ncf_channels = $ncf_channels - $cable_setting['ncf_qty'];
                }
            }


            $this->common->genericupdate('customer_service', 'cost', $total_cost, 'id', $service_id, null);
            $this->common->genericupdate('customer_service', 'status', 0, 'id', $service_id, null);

            $this->session->set('successMessage', 'Packages have been modified successfully.');
            header("Location:/cable/customerpackages/" . $_POST['service_id']);
        } catch (Exception $e) {
Sentry\captureException($e);
            SwipezLogger ::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

}
