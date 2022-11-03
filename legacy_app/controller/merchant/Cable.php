<?php

/**
 * Plan controller class to handle Merchants Plans
 */
class Cable extends Controller {

    function __construct() {
        parent::__construct();
        $this->validateSession('merchant');
    }

    /**
     * Display merchant suppliers list
     */
    function settopboxlist() {
        try {
            $this->hasRole(1, 24);
            $filter_type = 1;
            $expiry_status = 0;
            if (!empty($_POST)) {
                $status = $_POST['status'];
                $filter_type = isset($_POST['filter_type']) ? $_POST['filter_type'] : 0;
                $expiry_status = ($_POST['expiry_status'] > 0) ? $_POST['expiry_status'] : 0;
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $status = '';
            }
            $_SESSION['filter_type'] = $filter_type;
            $_SESSION['settopbox_status'] = $status;
            $_SESSION['expiry_status'] = $expiry_status;
            $this->smarty->assign("filter_type", $filter_type);
            $this->smarty->assign("status", $status);
            $this->smarty->assign("expiry_status", $expiry_status);

            $this->setAjaxDatatableSession();
            $this->smarty->assign("title", "Set top box list");
            $this->view->title = "Set top box list";

            //Breadcumbs array start
            $breadcumbs_array = array(
            array('title' => 'Cable','url' => ''),
            array('title'=> $this->view->title, 'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->selectedMenu = array(110, 111);
            $this->view->ajaxpage ='settopbox_list.php';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/cable/settopboxlist.tpl');
            $this->view->render('footer/customer_group');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    function packagelist() {
        try {
            $this->hasRole(1, 24);
            $list = $this->model->getSettopboxPackageList($this->merchant_id);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Customer Package list");
            $this->view->title = "Customer Package list";

            //Breadcumbs array start
            $breadcumbs_array = array(
            array('title' => 'Cable','url' => ''),
            array('title'=> $this->view->title, 'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->selectedMenu = array(110, 112);
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/cable/packagelist.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    function packageApprove($link) {
        $this->hasRole(2, 24);
        $service_id = $this->encrypt->decode($link);
        $this->common->genericupdate('customer_service', 'status', 1, 'id', $service_id, null);
    }

    function detail($link) {
        try {
            if (isset($_POST['link'])) {
                $this->packageApprove($_POST['link']);
            }
            $this->hasRole(1, 24);
            $settopbox_id = $this->encrypt->decode($link);
            $settopbox_detail = $this->common->getSingleValue('customer_service', 'id', $settopbox_id, 1);
            $settopbox_status = $settopbox_detail['status'];
            $settopbox_name = $settopbox_detail['name'];
            $service_details = $this->common->getListValue('customer_service_package', 'service_id', $settopbox_id, 1, ' order by cost desc');
            $service_history = $this->common->getListValue('customer_service_package', 'service_id', $settopbox_id, 0, ' and is_active=0 order by updated_date desc');
            $history = array();
            foreach ($service_history as $det) {
                if ($det['package_type'] == 2) {
                    $chann = $this->common->getSingleValue('cable_channel', 'channel_id', $det['channel_id'], 1);
                    $detail['name'] = $chann['channel_name'] . ' (' . $chann['language'] . ')';
                    $detail['type'] = 'Channel';
                } else {
                    $pkg = $this->common->getSingleValue('cable_package', 'package_id', $det['package_id'], 1);
                    $detail['name'] = $pkg['package_name'];
                    $detail['type'] = 'Package';
                }
                $detail['cost'] = $det['cost'];
                $detail['add_date'] = $det['created_date'];
                $detail['end_date'] = $det['updated_date'];
                $history[] = $detail;
            }

            $channels = array();
            $packages = array();
            $total_cost = 0;
            foreach ($service_details as $det) {
                if ($det['package_type'] == 2) {
                    $chann = $this->common->getSingleValue('cable_channel', 'channel_id', $det['channel_id'], 1);
                    $chann['exist_id'] = $det['id'];
                    $chann['cost'] = $det['cost'];
                    $total_cost = $total_cost + $chann['cost'];
                    $channels[] = $chann;
                } else {
                    $pkg = $this->common->getSingleValue('cable_package', 'package_id', $det['package_id'], 1);
                    $pkg['exist_id'] = $det['id'];
                    $pkgchannels = $this->model->getPackageChannels($det['package_id']);
                    if ($pkg['sub_package_id'] > 0) {
                        $spkg = $this->common->getSingleValue('cable_package', 'package_id', $pkg['sub_package_id'], 1);
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
            $this->view->selectedMenu = array(110, 111);
            $this->smarty->assign("settopbox_name", $settopbox_name);
            $this->smarty->assign("settopbox_status", $settopbox_status);
            $this->smarty->assign("history", $history);
            $this->smarty->assign("link", $link);
            $this->smarty->assign("packages", $packages);
            $this->smarty->assign("channels", $channels);
            $this->smarty->assign("channels_cost", $total_cost);
            $this->view->title = 'Cable Set Top Box';
            $this->view->header_file = ['list'];
        $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/cable/customerpackage.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    function setting() {
        try {
            $this->hasRole(2, 24);
            $setting = $this->common->getSingleValue('cable_setting', 'merchant_id', $this->merchant_id);
            $this->smarty->assign("se", $setting);
            $this->smarty->assign("title", "Cable Settings");
            $this->view->title = "Cable Settings";

            //Breadcumbs array start
            $breadcumbs_array = array(
            array('title' => 'Cable','url' => ''),
            array('title'=> $this->view->title, 'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->selectedMenu = array(110, 114);
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/cable/setting.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    function updatesetting() {

        $_POST['ncf_base_package'] = ($_POST['ncf_base_package'] == 1) ? 1 : 0;
        $_POST['ncf_addon_package'] = ($_POST['ncf_addon_package'] == 1) ? 1 : 0;
        $_POST['ncf_alacarte_package'] = ($_POST['ncf_alacarte_package'] == 1) ? 1 : 0;

        if ($_POST['id'] != '') {
            $this->model->updatesettings($this->merchant_id, $_POST['ncf_qty'], $_POST['ncf_fee'], $_POST['ncf_tax'], $_POST['ncf_tax_name'], $_POST['ncf_base_package'], $_POST['ncf_addon_package'], $_POST['ncf_alacarte_package'], $this->user_id);
        } else {
            $this->model->savesettings($this->merchant_id, $_POST['ncf_qty'], $_POST['ncf_fee'], $_POST['ncf_tax'], $_POST['ncf_tax_name'], $_POST['ncf_base_package'], $_POST['ncf_addon_package'], $_POST['ncf_alacarte_package'], $this->user_id);
        }
        $this->session->set("successMessage", ' Cable settings updated successfully');
        header('Location: /merchant/cable/setting');
        die();
    }

    function packagechannels($package_id) {
        try {
            $this->hasRole(1, 24);
            $package_name = $this->common->getRowValue('package_name', 'cable_package', 'package_id', $package_id);
            require_once MODEL . 'CablePackageModel.php';
            $cable_model = new CablePackageModel();
            $channels = $cable_model->getPackageChannels($package_id);
            $this->smarty->assign("title", $package_name . ' (' . count($channels) . ' Channels)');
            $this->smarty->assign("channels", $channels);
            $this->view->title = "Cable Settings";
            $this->view->datatablejs = 'table-no-pagignation';
            $this->view->selectedMenu = array(110, 114);
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'merchant/cable/packagechannel.tpl');
            $this->view->render('footer/nonfooter');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    function selectpackage($link) {
        try {
            $this->hasRole(1, 24);
            require_once MODEL . 'CablePackageModel.php';
            $cable_model = new CablePackageModel();
            $settopbox_id = $this->encrypt->decode($link);
            require_once CONTROLLER . 'CableWrapper.php';
            $cableWrapper = new CableWrapper();
            $smarty = $cableWrapper->setSelectpackage($settopbox_id, $this->common, $cable_model);
            foreach ($smarty as $key => $value) {
                $this->smarty->assign($key, $value);
            }
            $error = $this->session->get('errorMessage');
            if (isset($error)) {
                $this->session->remove('errorMessage');
                $this->smarty->assign("error", $error);
            }
            $this->view->selectedMenu = array(110, 111);
            $this->smarty->assign('link', $link);
            $this->view->datatablejs = 'table-no-pagignation';
            $this->view->js = array('plan');
            $this->view->title = 'Cable Set Top Box';
            $this->view->header_file = ['list'];
        $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/cable/updatepackage.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            SwipezLogger ::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function packagesaved() {
        try {
            $this->hasRole(2, 24);
            $link = $this->encrypt->encode($_POST['settopbox_id']);
            require_once CONTROLLER . 'CableWrapper.php';
            $cableWrapper = new CableWrapper();
            require_once MODEL . 'CablePackageModel.php';
            $cable_model = new CablePackageModel();
            $user_id = $this->session->get('system_user_id');
            $response = $cableWrapper->savePackage($this->common, $cable_model, $user_id);
            if ($response['status'] == 0) {
                $this->session->set('errorMessage', $response['error']);
                header("Location:/merchant/cable/selectpackage/" . $link);
                die();
            } else {
                $this->session->set('successMessage', 'Packages have been saved successfully.');
                $this->packageApprove($link);
                header("Location:/merchant/cable/detail/" . $link);
                die();
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            SwipezLogger ::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function success() {
        try {
            $this->view->title = "Cable subscription saved";
            $this->view->selectedMenu = 'cable_subscription';
            $this->view->header_file = ['profile'];
$this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/cable/success.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing suppliers Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    public function subscription() {
        try {
            $this->hasRole(2, 24);
            if (isset($_POST['subscription'])) {
                if (count($_POST['customer_check']) > 0) {
                    $_POST['settopbox'] = 1;
                } else {
                    $_POST['settopbox'] = '';
                }
                require CONTROLLER . 'Paymentvalidator.php';
                $validator = new Paymentvalidator($this->model);
                $validator->validateCableSubscription();
                $hasErrors = $validator->fetchErrors();
                if ($hasErrors == false) {

                    if ($this->env == 'PROD') {
                        $api_url = 'https://intapi.swipez.in';
                    } else {
                        $api_url = 'https://h7sak-api.swipez.in';
                    }

                    $start_date = new DateTime($_POST['bill_date']);
                    $due_date = new DateTime($_POST['due_date']);
                    $array['start_date'] = $start_date->format('Y-m-d');
                    $array['due_date'] = $due_date->format('Y-m-d');
                    $array['template_id'] = $_POST['template_id'];
                    $array['ids'] = $_POST['customer_check'];
                    $post_string = json_encode($array);
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $api_url . "/api/v1/cable/addjobs",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $post_string,
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);

                    $this->session->set("successMessage", ' You have created subscriptions for ' . count($_POST['customer_check']) . ' customers. It would take 5 minutes for these subscriptions to start reflecting under the Subscriptions screen <a  href="/merchant/subscription/viewlist" class="btn btn-sm green">Subscriptions created</a>.');
                    header('Location: /merchant/cable/success', 301);
                    die();
                } else {
                    $this->smarty->assign("haserrors", $hasErrors);
                }
            }

            require_once MODEL . 'merchant/CustomerModel.php';
            $customer_model = new CustomerModel();
            $merchant_id = $this->session->get('merchant_id');
            $column_list = $customer_model->getCustomerBreakup($merchant_id);
            $addcolumn[] = array('column_name' => 'City');
            $addcolumn[] = array('column_name' => 'State');
            $addcolumn[] = array('column_name' => 'Address');
            $addcolumn[] = array('column_name' => 'Zipcode');
            $column_list = array_merge($addcolumn, $column_list);
            $this->smarty->assign("column_list", $column_list);

            $user_id = $this->session->get('userid');
            $sub_franchise_id = $this->session->get('sub_franchise_id');
            $templatelist = $this->common->getTemplateList($user_id, $sub_franchise_id);
            $this->smarty->assign("templatelist", $templatelist);

            if (isset($_POST['column_name'])) {
                $column_select = $_POST['column_name'];
            } else {
                $column_select = array();
            }

            if (count($column_select) > 5) {
                $column_select = array();
                $this->smarty->assign("haserrors", "Column name selected more than 5 values");
            }

            $this->smarty->assign("column_select", $column_select);

            $_SESSION['display_column'] = $column_select;

            foreach ($column_select as $key => $value) {
                if ($value == 'City' || $value == 'State' || $value == 'Address' || $value == 'Zipcode') {
                    unset($column_select[$key]);
                }
            }
            $status = isset($_POST['status']) ? $_POST['status'] : '';
            $payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : '';
            $filter_by = isset($_POST['filter_by']) ? $_POST['filter_by'] : '';
            $filter_condition = isset($_POST['filter_condition']) ? $_POST['filter_condition'] : '';
            $filter_value = isset($_POST['filter_value']) ? $_POST['filter_value'] : '';
            $group = isset($_POST['group']) ? $_POST['group'] : '';
            $bulk_id = 0;
            $is_bulk = 0;
            if ($link != NULL) {
                $bulk_id = $this->encrypt->decode($link);
                $is_bulk = 1;
            }

            $this->smarty->assign("filter_by", $filter_by);
            $this->smarty->assign("filter_condition", $filter_condition);
            $this->smarty->assign("filter_value", $filter_value);
            $this->smarty->assign("group", $group);

            $_SESSION['group'] = $group;
            $_SESSION['type'] = 'group';
            $_SESSION['db_column'] = $column_select;
            $_SESSION['customer_status'] = $status;
            $_SESSION['payment_status'] = $payment_status;
            $_SESSION['customer_bulk_id'] = $bulk_id;

            $_SESSION['filter_by'] = $filter_by;
            $_SESSION['filter_condition'] = $filter_condition;
            $_SESSION['filter_value'] = $filter_value;

            if (isset($_POST['export'])) {
                $requestlist = $this->model->getServiceCustomerList($merchant_id, $column_select, $bulk_id);
                $this->common->excelexport('CustomerList', $requestlist);
            }

            $customer_group = $this->common->getListValue('customer_group', 'merchant_id', $this->merchant_id, 1);
            $int = 0;
            foreach ($customer_group as $item) {
                $enc = $this->encrypt->encode($item['group_id']);
                $customer_group[$int]['link'] = $enc;
                $int++;
            }
            $this->smarty->assign("customer_group", $customer_group);

            $this->view->selectedMenu = array(110, 113);
            $this->smarty->assign("is_bulk", $is_bulk);

            $title = 'Cable Subscription';
            $this->smarty->assign("title", $title);
            $this->smarty->assign("status", $status);
            $this->smarty->assign("payment_status", $payment_status);
            $this->setAjaxDatatableSession();
            $this->view->title = 'Cable Subscription';

            //Breadcumbs array start
            $breadcumbs_array = array(
            array('title' => 'Cable','url' => ''),
            array('title'=> $this->view->title, 'url'=>'')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small';
            $this->view->canonical = 'merchant/customer/viewlist';
            $this->view->ajaxpage ='cable_subscription.php';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/cable/subscription.tpl');
            $this->view->render('footer/customer_group');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E007]Error while Merchant customer list Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

}

?>
