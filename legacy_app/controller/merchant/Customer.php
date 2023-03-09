<?php
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;
use DateTimeZone;
use League\ISO3166\ISO3166;
/**
 * @author Paresh
 * @version 2.0
 * Customer controller class to handle Merchants Customer data
 */
class Customer extends Controller
{

    private $customer_codes = array();

    function __construct()
    {
        parent::__construct();
        $this->validateSession('merchant');

        $this->view->js = array('customer');
        //find company column name from customer metadata
        if ($this->session->has('customer_company_label')) {
            $this->get_custom_company_col_name = $this->session->get('customer_company_label');
            $this->smarty->assign("company_column_name", $this->session->get('customer_company_label'));
        } else {
            $this->get_custom_company_col_name = $this->common->getRowValue('column_name', 'customer_column_metadata', 'column_datatype', 'company_name', 1, " and merchant_id='" . $this->merchant_id . "'");
            $this->get_custom_company_col_name =  ($this->get_custom_company_col_name != false) ? $this->get_custom_company_col_name : 'Company name';
            $this->smarty->assign("company_column_name", $this->get_custom_company_col_name);
            $this->session->set('customer_company_label', $this->get_custom_company_col_name);
        }
    }

    /**
     * Create structure for customer
     */
    function structure()
    {
        try {
            $this->hasRole(1, 15);
            $merchant_id = $this->merchant_id;
            $cable_enable = $this->session->get('cable_enable');
            $this->view->title = 'Customer structure';
            $this->smarty->assign('title', $this->view->title);
            $customer_column = $this->model->getCustomerBreakup($merchant_id);
            $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
            $this->smarty->assign("prefix", $merchant_setting['prefix']);
            $this->smarty->assign("merchant_setting", $merchant_setting);
            $this->smarty->assign("column", $customer_column);
            $this->smarty->assign("cable_enable", $cable_enable);
            $this->view->selectedMenu = array(14);
            $this->view->canonical = 'merchant/customer/structure';
            $this->view->header_file = ['profile'];
            $this->smarty->assign('title', $this->view->title);

            $old_links = $this->session->get('breadcrumbs');
            if (!empty($old_links) && $old_links['menu'] == 'create_customer') {
                $breadcumbs_array = array(
                    array('title' => 'Contacts ', 'url' => ''),
                    array('title' => 'Customer ', 'url' => ''),
                    array('title' => $old_links['title'], 'url' => '/merchant/customer/create'),
                    array('title' => $this->view->title, 'url' => '')
                );
                $cancel_url = "/merchant/customer/create";
            } else {
                $breadcumbs_array = array(
                    array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
                    array('title' => 'Contact setting', 'url' => ''),
                    array('title' => $this->view->title, 'url' => '')
                );
                $cancel_url = "/merchant/profile/settings";
            }
            $this->smarty->assign("links", $breadcumbs_array);
            $this->smarty->assign("cancel_url", $cancel_url);
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/customer/structure.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC001]Error while customer Structure Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    /**
     * Save customer structure 
     */
    function structuresave()
    {
        try {
            $this->hasRole(2, 15);
            $position = (empty($_POST['position'])) ? array() : $_POST['position'];
            $column_name = (empty($_POST['column_name'])) ? array() : $_POST['column_name'];
            $datatype = (empty($_POST['datatype'])) ? array() : $_POST['datatype'];

            $exist_col_id = (empty($_POST['exist_col_id'])) ? array() : $_POST['exist_col_id'];
            $exist_datatype = (empty($_POST['exist_datatype'])) ? array() : $_POST['exist_datatype'];
            $exist_col_name = (empty($_POST['exist_col_name'])) ? array() : $_POST['exist_col_name'];

            $prefix = ($_POST['prefix'] != '') ? $_POST['prefix'] : 'Cust';
            $is_autogenerate = ($_POST['is_autogenerate'] > 0) ? 1 : 0;

            $result = $this->model->saveCustomerStructure($this->user_id, $this->session->get('merchant_id'), $prefix, $is_autogenerate, $position, $column_name, $datatype, $exist_col_id, $exist_datatype, $exist_col_name);
            if ($result['message'] == 'success') {
                $this->session->set('successMessage', 'Customer structure has been saved.');
                header('Location:/merchant/customer/structure');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC002]Error while customer Structure save Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    #create customer from UI

    function create($param = null)
    {
        try {
            if ($param == 'GettingStarted') {
                $this->session->set('GettingStarted', true);
            }
            $this->hasRole(2, 15);
            $merchant_id = $this->session->get('merchant_id');
            $this->view->title = 'Create customer';
            $customer_column = $this->model->getCustomerBreakup($merchant_id);

            $int = 0;
            if (isset($_POST['column_value'])) {
                foreach ($_POST['column_value'] as $val) {
                    $customer_column[$int]['value'] = $val;
                    $int++;
                }
            }
            $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
            $merchant_state = $this->common->getMerchantProfile($merchant_id, 0, 'state');
            $this->smarty->assign("merchant_setting", $merchant_setting);
            $this->smarty->assign("column", $customer_column);
            $this->smarty->assign("merchant_state", $merchant_state);
            $state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
            $country_code = $this->common->getListValue('config', 'config_type', 'country_name');
            $this->smarty->assign("country_code", $country_code);
            $this->smarty->assign("state_code", $state_code);
            //store for customer structure breadcrumbs urls
            $breadcrumbs['menu'] = 'create_customer';
            $breadcrumbs['title'] = $this->view->title;
            $this->session->set('breadcrumbs', $breadcrumbs);

            //Breadcumbs array start
            $this->smarty->assign('title', 'Create customer');
            $breadcumbs_array = array(
                array('title' => 'Contacts', 'url' => ''),
                array('title' => 'Customer', 'url' => ''),
                array('title' => 'Create customer', 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end
            
            //find customer region setting for timezone
            $region_setting = $this->common->getSingleValue('preferences', 'user_id', $this->user_id);
            $selected_country = 'United States';
            $selected_mobile_code = '1';

            if($region_setting!='') {
                $dateTime = Carbon::now($region_setting['timezone']);
                // Get the country code for the timezone
                $timeZone = new DateTimeZone($dateTime->getTimezone()->getName());
                
                $countryCode = $timeZone->getLocation()['country_code'];
                if($countryCode!='??') {
                    // Get the country name from the country code
                    $iso = new ISO3166();
                    $countryInfo = $iso->alpha2($countryCode);
                    //find mobile code from config table 
                    $find_mobile_code = $this->common->getSingleValue('config', 'config_value', $countryInfo['name']);
                    
                    if($find_mobile_code!='') {
                        $selected_country = $find_mobile_code['config_value'];
                        $selected_mobile_code = $find_mobile_code['description'];
                    }
                }
            }
            
            $this->smarty->assign("selected_country", $selected_country);
            $this->smarty->assign("selected_mobile_code", $selected_mobile_code);
            $this->session->set('valid_ajax', 'country_code');
            $this->view->selectedMenu = array(2, 15, 68);
            $this->view->canonical = 'merchant/customer/structure';
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/customer/create.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC001]Error while customer Structure Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    #create customer from UI

    function update($link)
    {
        try {
            $this->hasRole(2, 15);
            $merchant_id = $this->session->get('merchant_id');
            $customer_id = $this->encrypt->decode($link);
            $details = $this->model->getCustomerDeatils($customer_id, $this->merchant_id);
            if (empty($details)) {
                $this->setInvalidLinkError();
            }
            $customer_column = $this->model->getCustomerBreakup($merchant_id);
            $column_values = $this->model->getCustomerCustomValues($customer_id, $this->merchant_id);

            //changed customer metadata array
            foreach ($customer_column as $ck => $col) {
                foreach ($column_values as $value) {
                    if ($value['column_id'] == $col['column_id'] && $col['column_datatype'] != 'company_name') {
                        $customer_column[$ck]['value'] = $value['value'];
                        $customer_column[$ck]['id'] = $value['id'];
                    }
                }
            }
            //Breadcumbs array start
            $this->view->title = 'Update customer';
            $this->smarty->assign('title', $this->view->title);
            $breadcumbs_array = array(
                array('title' => 'Contacts', 'url' => ''),
                array('title' => 'Customer', 'url' => ''),
                array('title' => 'Customer list', 'url' => '/merchant/customer/viewlist'),
                array('title' => 'Update customer', 'url' => '/merchant/customer/update/' . $link),
                array('title' => $details['first_name'] . ' ' . $details['last_name'], 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
            $this->smarty->assign("merchant_setting", $merchant_setting);
            
            $this->smarty->assign("column", $customer_column);
            $this->smarty->assign("link", $link);
            $state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
            $this->smarty->assign("state_code", $state_code);
            $country_code = $this->common->getListValue('config', 'config_type', 'country_name');
            $this->smarty->assign("country_code", $country_code);

            //find country code from config table
            if(isset($details['country']) && $details['country']!='India') {
                $get_country_code = $this->common->getRowValue('description', 'config', 'config_type', 'country_name', 0, " and config_value='" . $details['country'] . "'" );
                $details['country_mobile_code'] = '+'.$get_country_code;
            } else {
                $details['country_mobile_code'] = '+91';
            }

            $this->smarty->assign("detail", $details);
            $this->view->selectedMenu = array(2, 15, 69);
            $this->view->canonical = 'merchant/customer/update';
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/customer/update.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC001]Error while customer Structure Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    #create customer from UI

    function view($link)
    {
        try {
            
            $this->smarty->assign("currency", $this->session->get('currency')[0] == 'USD' ? '$' : $this->session->get('currency')[0]);
            $this->hasRole(1, 15);
            $customer_id = $this->encrypt->decode($link);
            $details = $this->model->getCustomerDeatils($customer_id, $this->merchant_id);
            if (empty($details)) {
                $this->setInvalidLinkError();
            }
            $ledger = $this->common->getListValue('contact_ledger', 'customer_id', $customer_id, 1);
            foreach ($ledger as $key => $row) {
                if ($row['ledger_type'] == 'DEBIT') {
                    $invoice_number =  $this->common->getRowValue('invoice_number', 'payment_request', 'payment_request_id', $row['reference_no']);
                    $ledger[$key]['invoice_number'] = $invoice_number;
                }
            }
            $customer_column = $this->model->getCustomerCustomValues($customer_id, $this->merchant_id);
            $this->smarty->assign("detail", $details);
            $this->smarty->assign("column", $customer_column);
            $this->smarty->assign("link", $link);
            $ledger = $this->generic->getEncryptedList($ledger, 'ref', 'reference_no');
            $this->smarty->assign("ledger", $ledger);
            $GettingStarted = $this->session->get('GettingStarted');
            if ($GettingStarted == true) {
                $this->session->set('GettingStarted', false);
                $this->smarty->assign("GettingStarted", true);
            }

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Contacts', 'url' => ''),
                array('title' => 'Customer', 'url' => ''),
                array('title' => 'Customer list', 'url' => '/merchant/customer/viewlist'),
                array('title' => 'View customer', 'url' => '/merchant/customer/view/' . $link),
                array('title' => $details['first_name'] . ' ' . $details['last_name'], 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->title = 'View customer';
            $this->view->selectedMenu = array(2, 15, 69);
            $this->view->canonical = 'merchant/customer/update';
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/customer/view.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC001]Error while customer Structure Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    /**
     * Save customer  
     */
    function customersave($popup = NULL)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $auto_generate = ($_POST['auto_generate'] > 0) ? 1 : 0;
            //dd($_POST);
            if ($auto_generate == 1) {
                $customer_code = $this->model->getCustomerCode($merchant_id);
                $_POST['customer_code'] = 'NULL';
            } else {
                $customer_code = $_POST['customer_code'];
            }
            $space_position = strpos($_POST['customer_name'], ' ');
            if ($space_position > 0) {
                $_POST['first_name'] = substr($_POST['customer_name'], 0, $space_position);
                $_POST['last_name'] = substr($_POST['customer_name'], $space_position);
            } else {
                $_POST['first_name'] = $_POST['customer_name'];
                $_POST['last_name'] = '';
            }
            $addressfull = $_POST['address'];
            if (strlen($addressfull) > 250) {
                $_POST['address'] = substr($addressfull, 0, 250);
                $_POST['address2'] = substr($addressfull, 250);
            } else {
                $_POST['address2'] = '';
            }
            require CONTROLLER . 'merchant/Customervalidator.php';
            $validator = new Customervalidator($this->model);
            $validator->validateCustomerSave($merchant_id);
            $hasErrors = $validator->fetchErrors();
            if (empty($hasErrors)) {
                $hasErrors = array();
            }
           
            $custom_column = $this->model->getCustomerBreakup($merchant_id);
            $cint = 0;
            foreach ($custom_column as $c_row) {
                if ($c_row['column_id'] > 0) {
                    $key = array_search($c_row['column_id'], $_POST['column_id']);
                    if ($c_row['column_datatype'] == 'password') {
                        $_POST['password'] = $_POST['column_value'][$key];
                    }
                    if ($c_row['column_datatype'] == 'gst') {
                        $_POST['GST'] = $_POST['column_value'][$key];
                    }
                    if ($c_row['column_datatype'] == 'stb') {
                        $_POST['settop_box'] = explode(',', $_POST['column_value'][$key]);
                    }
                    // if ($c_row['column_datatype'] == 'company_name') {
                    //     $_POST['company_name'] = $_POST['column_value'][$key];
                    // }
                    $cust_col[] = array('column_name' => $c_row['column_name'], 'value' => $_POST['column_value'][$key], 'column_id' => $c_row['column_id'], 'col_datatype' => $c_row['column_datatype']);
                }
                $cint++;
            }

            if (!empty($cust_col)) {
                $validator = new Customervalidator($this->model);
                $customint = 1;
                foreach ($cust_col as $c_col) {
                    $_POST['custom_col' . $customint] = $c_col['value'];
                    $validator->validateCustomUpload($c_col, 'custom_col' . $customint);
                    $customint++;
                }
                $custErrors = $validator->fetchErrors();
                if (!empty($custErrors)) {
                    $hasErrors = array_merge($hasErrors, $custErrors);
                }
            }
           
            if ($hasErrors == FALSE) {
                $column_id = (empty($_POST['column_id'])) ? array() : $_POST['column_id'];
                $column_value = (empty($_POST['column_value'])) ? array() : $_POST['column_value'];
                $_POST['country'] = (isset($_POST['country']) && $_POST['country']!='') ? $_POST['country'] : 'United States';
                $_POST['state'] = (isset($_POST['country']) && $_POST['country']=='India') ? $_POST['state'] : $_POST['state1'];

                $result = $this->model->saveCustomer($this->user_id, $merchant_id, $customer_code, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zipcode'], $column_id, $column_value, 0, $_POST['password'], $_POST['GST'], $_POST['company_name'],$_POST['country']);
                if ($result['message'] == 'success') {

                    if (isset($_POST['settop_box'])) {
                        $this->model->saveCustomerSettopBox($_POST['settop_box'], $result['customer_id'], $this->merchant_id, $this->user_id);
                    }
                    $this->session->set('successMessage', 'Customer has been saved.');
                    if ($popup == NULL) {
                        $link = $this->encrypt->encode($result['customer_id']);
                        header('Location:/merchant/customer/view/' . $link);
                    } else {
                        $template_id = $this->encrypt->decode($_POST['template_id']);
                        $customer['name'] = $customer_code . ' | ' . $_POST['first_name'] . ' ' . $_POST['last_name'];
                        $customer['id'] = $result['customer_id'];
                        $customer['status'] = 1;
                        $customer['link'] = $this->encrypt->encode($result['customer_id']);
                        $list = $this->model->getCustomerTemplateColumn($template_id, $result['customer_id']);
                        foreach ($list as $val) {
                            if ($val['save_table_name'] == 'customer') {

                                switch ($val['customer_column_id']) {
                                    case 1:
                                        $value = $customer_code;
                                        break;
                                    case 2:
                                        $value = $_POST['first_name'] . ' ' . $_POST['last_name'];
                                        break;
                                    case 3:
                                        $value = $_POST['email'];
                                        break;
                                    case 4:
                                        $value = $_POST['mobile'];
                                        break;
                                    case 5:
                                        $value = $_POST['address'];
                                        break;
                                    case 6:
                                        $value = $_POST['city'];
                                        break;
                                    case 7:
                                        $value = $_POST['state'];
                                        break;
                                    case 8:
                                        $value = $_POST['zipcode'];
                                        break;
                                }
                                $column[] = array('id' => $val['column_id'], 'value' => $value, 'datatype' => $val['column_datatype']);
                            } else {
                                $column[] = array('id' => $val['column_id'], 'value' => $val['value'], 'datatype' => $val['column_datatype']);
                            }
                        }
                        $customer['column_value'] = $column;
                        echo json_encode($customer);
                    }
                } else {
                }
            } else {
                if ($popup == NULL) {
                    $this->smarty->assign("haserrors", $hasErrors);
                    $this->smarty->assign("post", $_POST);
                    $this->create();
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
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC002]Error while customer Structure save Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    /**
     * Save customer  
     */
    function updatesave($popup = NULL)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $customer_id = $this->encrypt->decode($_POST['customer_id']);
            $customer_code = $_POST['customer_code'];
            $addressfull = $_POST['address'];
            $_POST['address'] = substr($addressfull, 0, 250);
            $_POST['address2'] = substr($addressfull, 250);
            $space_position = strpos($_POST['customer_name'], ' ');
            if ($space_position > 0) {
                $_POST['first_name'] = substr($_POST['customer_name'], 0, $space_position);
                $_POST['last_name'] = substr($_POST['customer_name'], $space_position);
            } else {
                $_POST['first_name'] = $_POST['customer_name'];
                $_POST['last_name'] = '';
            }
            require CONTROLLER . 'merchant/Customervalidator.php';
            $validator = new Customervalidator($this->model);
            $custom_column = $this->model->getCustomerBreakup($merchant_id);
            $cint = 0;
            foreach ($custom_column as $c_row) {
                if ($c_row['column_id'] > 0) {
                    $key = array_search($c_row['column_id'], $_POST['col_id']);
                    if ($c_row['column_datatype'] == 'password') {
                        $_POST['password'] = $_POST['values'][$key];
                    }
                    if ($c_row['column_datatype'] == 'gst') {
                        $_POST['GST'] = $_POST['values'][$key];
                    }
                    if ($c_row['column_datatype'] == 'stb') {
                        $_POST['settop_box'] = explode(',', $_POST['values'][$key]);
                    }
                    // if ($c_row['column_datatype'] == 'company_name') {
                    //     $_POST['company_name'] = $_POST['values'][$key];
                    // }
                    $cust_col[] = array('column_name' => $c_row['column_name'], 'value' => $_POST['values'][$key], 'column_id' => $c_row['column_id'], 'col_datatype' => $c_row['column_datatype']);
                }
                $cint++;
            }
            if (!empty($cust_col)) {
                $customint = 1;
                foreach ($cust_col as $c_col) {
                    $_POST['custom_col' . $customint] = $c_col['value'];
                    $validator->validateCustomUpload($c_col, 'custom_col' . $customint);
                    $customint++;
                }
                $custErrors = $validator->fetchErrors();
                if (!empty($custErrors)) {
                    $hasErrors = array_merge($hasErrors, $custErrors);
                }
            }



            $validator->validateCustomerUpdate($merchant_id, $customer_id);
            $hasErrors = $validator->fetchErrors();

            if (empty($hasErrors)) {
                $hasErrors = array();
            }


            if ($hasErrors == FALSE) {
                $excolumn_id = (empty($_POST['ids'])) ? array() : $_POST['ids'];
                $excolumn_value = (empty($_POST['values'])) ? array() : $_POST['values'];
                $column_id = (empty($_POST['column_id'])) ? array() : $_POST['column_id'];
                $column_value = (empty($_POST['column_value'])) ? array() : $_POST['column_value'];
                $_POST['country'] = (isset($_POST['country']) && $_POST['country']!='') ? $_POST['country'] : 'United States';
                $_POST['state'] = (isset($_POST['country']) && $_POST['country']=='India') ? $_POST['state'] : $_POST['state1'];

                $result = $this->model->updateCustomer($this->user_id, $customer_id, $customer_code, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zipcode'], $column_id, $column_value, $excolumn_id, $excolumn_value, $_POST['password'], $_POST['GST'], $_POST['company_name'], $_POST['country']);
                if ($result['message'] == 'success') {

                    $food_franchise_mids = explode(',', env('FOOD_FRANCHISE_MERCHANT_ID'));
                    if (in_array($this->merchant_id, $food_franchise_mids)) {
                        $this->model->updateFranchise($customer_id, $customer_code, $_POST['customer_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], $this->user_id);
                    }

                    if (isset($_POST['settop_box'])) {
                        $this->model->saveCustomerSettopBox($_POST['settop_box'], $customer_id, $this->merchant_id, $this->user_id);
                    }

                    $this->session->set('successMessage', 'Customer have been updated.');
                    if ($popup == NULL) {
                        $link = $this->encrypt->encode($customer_id);
                        header('Location:/merchant/customer/view/' . $link);
                    } else {
                        $template_id = $this->encrypt->decode($_POST['template_id']);
                        $customer['name'] = $customer_code . ' | ' . $_POST['first_name'] . ' ' . $_POST['last_name'];
                        $customer['id'] = $customer_id;
                        $customer['status'] = 1;
                        $customer['link'] = $this->encrypt->encode($customer_id);
                        $list = $this->model->getCustomerTemplateColumn($template_id, $result['customer_id']);
                        foreach ($list as $val) {
                            if ($val['save_table_name'] == 'customer') {
                                switch ($val['customer_column_id']) {
                                    case 1:
                                        $value = $customer_code;
                                        break;
                                    case 2:
                                        $value = $_POST['first_name'] . ' ' . $_POST['last_name'];
                                        break;
                                    case 3:
                                        $value = $_POST['email'];
                                        break;
                                    case 4:
                                        $value = $_POST['mobile'];
                                        break;
                                    case 5:
                                        $value = $_POST['address'];
                                        break;
                                    case 6:
                                        $value = $_POST['city'];
                                        break;
                                    case 7:
                                        $value = $_POST['state'];
                                        break;
                                    case 8:
                                        $value = $_POST['zipcode'];
                                        break;
                                }
                                $column[] = array('id' => $val['column_id'], 'value' => $value, 'datatype' => $val['column_datatype']);
                            } else {
                                $column[] = array('id' => $val['column_id'], 'value' => $val['value'], 'datatype' => $val['column_datatype']);
                            }
                        }
                        $customer['column_value'] = $column;
                        echo json_encode($customer);
                    }
                }
            } else {

                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->update($_POST['customer_id']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC002]Error while customer Structure save Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    /**
     * Display merchant customer list
     */
    public function viewlist($link = NULL)
    {
        try {
            $this->hasRole(1, 15);
            $merchant_id = $this->session->get('merchant_id');
            $column_list = $this->model->getCustomerBreakup($merchant_id);
            $addcolumn[] = array('column_name' => 'City');
            $addcolumn[] = array('column_name' => 'State');
            $addcolumn[] = array('column_name' => 'Country');
            $addcolumn[] = array('column_name' => 'Address');
            $addcolumn[] = array('column_name' => 'Zipcode');
            $column_list = array_merge($addcolumn, $column_list);
            $this->smarty->assign("column_list", $column_list);

            if (isset($_POST['column_name'])) {
                $rcol = $_POST['column_name'];
                $column_select = $_POST['column_name'];
            } else {

                $column_select = array();
            }
            if (count($column_select) > 5) {
                $column_select = array();
                $this->smarty->assign("haserrors", "Column name selected more than 5 values");
            }

            foreach ($column_select as $key => $value) {
                if ($value == 'City' || $value == 'State' || $value == 'Country' || $value == 'Address' || $value == 'Zipcode') {
                    unset($column_select[$key]);
                }
            }
            $status = isset($_POST['status']) ? $_POST['status'] : '';
            $payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : '';
            $bulk_id = 0;
            $is_bulk = 0;
            if ($link != NULL) {
                $bulk_id = $this->encrypt->decode($link);
                if (!is_numeric($bulk_id)) {
                    $this->setInvalidLinkError();
                }
                $is_bulk = 1;
            }
            $group = isset($_POST['group']) ? $_POST['group'] : '';

            //store last search criteria into Redis
            $redis_items = $this->getSearchParamRedis('customer_list');
            
            if(isset($redis_items['customer_list']['search_param']) && $redis_items['customer_list']['search_param']!=null) {
                $payment_status = $redis_items['customer_list']['search_param']['payment_status'];
                $column_select = $redis_items['customer_list']['search_param']['column_name'];
                $group = $redis_items['customer_list']['search_param']['group'];
            }
            //$this->view->showLastRememberSearchCriteria = true;
            
            $_SESSION['db_column'] = $column_select;
            $_SESSION['customer_status'] = $status;
            $_SESSION['payment_status'] = $payment_status;
            $_SESSION['customer_bulk_id'] = $bulk_id;
            $_SESSION['group'] = $group;
            $_SESSION['language'] = 'english';
            $_SESSION['display_column'] = $column_select;
            if (isset($_POST['export'])) {

                if ($group != '') {
                    $where = " where customer_group like ~%" . '{' . $group . '}' . '%~';
                } else {
                    $where = '';
                }
                $requestlist = $this->model->getCustomerList($merchant_id, $column_select, $bulk_id, $where);
                $this->common->excelexport('CustomerList', $requestlist, array('@totalcount'));
            }
            $this->view->selectedMenu = array(2, 15, 69);
            $this->smarty->assign("is_bulk", $is_bulk);
            if ($is_bulk == 1) {
                $title = 'Bulk customer list';
                $this->view->selectedMenu = array(2, 15, 70);
            } else {
                $title = 'Customer list';
            }
            //$group = isset($_POST['group']) ? $_POST['group'] : '';
            $this->smarty->assign("group", $group);
            //$_SESSION['group'] = $group;
            $where = '';
            $login_cust_group = $this->session->get('login_customer_group');
            if (isset($login_cust_group)) {
                $where = " and group_id in (" . implode(",", $login_cust_group) . ')';
            }
            $customer_group = $this->common->getListValue('customer_group', 'merchant_id', $this->merchant_id, 1, $where);
            $this->smarty->assign("customer_group", $customer_group);

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Contacts', 'url' => ''),
                array('title' => 'Customer', 'url' => ''),
                array('title' => 'Customer list', 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->smarty->assign("title", $title);
            $this->smarty->assign("status", $status);
            $this->smarty->assign("payment_status", $payment_status);
            $this->smarty->assign("column_select", $column_select);
            $this->view->title = 'Customer list';
            $this->setAjaxDatatableSession();
            $this->view->datatablejs = 'table-small';
            $this->view->canonical = 'merchant/customer/viewlist';
            $this->view->header_file = ['list'];
            $this->view->list_name = 'customer_list';
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/customer/customer_list.tpl');
            $this->view->render('footer/customer_list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E007]Error while Merchant customer list Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function savegroup()
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/customer/managegroup");
            }
            require_once CONTROLLER . 'merchant/Customervalidator.php';
            $validator = new Customervalidator($this->model);
            $validator->validateGroupSave($this->merchant_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $id = $this->model->createGroup($_POST['group_name'], $this->merchant_id, $this->user_id);
                $array['status'] = 1;
                $array['id'] = $id;
                $array['name'] = $_POST['group_name'];
            } else {
                $array['status'] = 0;
                $array['error'] = $hasErrors['group_name'][0] . ': ' . $hasErrors['group_name'][1];
            }
            echo json_encode($array);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB002]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function savecustomergroup()
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/customer/managegroup");
            }
            require_once CONTROLLER . 'merchant/Customervalidator.php';
            $validator = new Customervalidator($this->model);
            $validator->validateGroupCustomer();
            $hasErrors = $validator->fetchErrors();

            if ($hasErrors == false) {
                if (empty($_POST['customer_check'])) {
                    $array['status'] = 0;
                    $array['error'] = 'Customer empty: Please select customers.';
                } else {
                    $user_id = $this->user_id;
                    $group_id = '{' . $_POST['group_id'] . '}';
                    foreach ($_POST['customer_check'] as $customer_id) {
                        $customer_group = $this->common->getRowValue('customer_group', 'customer', 'customer_id', $customer_id);
                        if (isset($customer_group)) {
                            $array = json_decode($customer_group, true);
                            if (in_array($group_id, $array)) {
                            } else {
                                $array[] = $group_id;
                                $this->common->genericupdate('customer', 'customer_group', json_encode($array), 'customer_id', $customer_id, $user_id);
                            }
                        }
                    }

                    $array['status'] = 1;
                    $array['count'] = count($_POST['customer_check']);
                }
            } else {
                $array['status'] = 0;
                $array['error'] = $hasErrors['group_id'][0] . ': ' . $hasErrors['group_id'][1];
            }
            echo json_encode($array);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB002]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Display merchant customer list
     */
    public function managegroup()
    {
        try {
            $this->hasRole(2, 15);
            $merchant_id = $this->session->get('merchant_id');
            $column_list = $this->model->getCustomerBreakup($merchant_id);
            $addcolumn[] = array('column_name' => 'City');
            $addcolumn[] = array('column_name' => 'State');
            $addcolumn[] = array('column_name' => 'Address');
            $addcolumn[] = array('column_name' => 'Zipcode');
            $column_list = array_merge($addcolumn, $column_list);
            $this->smarty->assign("column_list", $column_list);
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
                $requestlist = $this->model->getCustomerList($merchant_id, $column_select, $bulk_id);
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

            $this->view->selectedMenu = array(2, 15, 71);
            $this->smarty->assign("is_bulk", $is_bulk);

            $title = 'Manage customer groups';
            $this->setAjaxDatatableSession();

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Contacts', 'url' => ''),
                array('title' => 'Customer', 'url' => ''),
                array('title' => 'Manage customer group', 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->smarty->assign("title", $title);
            $this->smarty->assign("status", $status);
            $this->smarty->assign("payment_status", $payment_status);
            $this->view->title = $title;
            $this->view->datatablejs = 'table-small';
            $this->view->ajaxpage = 'customer_group.php';
            $this->view->canonical = 'merchant/customer/viewlist';
            $this->view->ajaxpage = 'customer_group.php';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/customer/customer_group.tpl');
            $this->view->render('footer/customer_group');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E007]Error while Merchant customer list Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Bulk upload customer
     */ function bulkupload()
    {
        try {
            $this->hasRole(1, 15);
            $merchant_id = $this->session->get('merchant_id');
            $list = $this->model->getBulkuploadList($merchant_id);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['bulk_id'] = $this->encrypt->encode($item['bulk_upload_id']);
                $list[$int]['created_at'] = $this->generic->formatTimeString($item['created_date']);
                $int++;
            }

            //Breadcumbs array start
            $this->smarty->assign('title', 'Bulk upload customers');
            $breadcumbs_array = array(
                array('title' => 'Contacts', 'url' => ''),
                array('title' => 'Customer', 'url' => ''),
                array('title' => 'Bulk upload customers', 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->smarty->assign("bulklist", $list);
            $this->view->hide_first_col = true;
            $this->view->datatablejs = 'table-small-no-export-statesave'; //table-small-no-export
            $this->view->list_name = 'customer_bulk_upload_list';
            $this->view->selectedMenu = array(2, 15, 70);
            $this->view->title = 'Bulk upload customers';
            $this->view->canonical = 'merchant/customer/bulkupload';
            $this->view->header_file = ['bulkupload'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/customer/customerupload.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC001]Error while customer Structure Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    public function upload()
    {
        try {
            $this->hasRole(2, 15);
            $merchant_id = $this->session->get('merchant_id');
            if (isset($_FILES["fileupload"])) {
                require_once CONTROLLER . 'merchant/Uploadvalidator.php';
                $validator = new Uploadvalidator($this->model);
                $validator->validateExcelUpload();
                $hasErrors = $validator->fetchErrors();
                if ($hasErrors == false) {
                    if (isset($_POST['bulk_id'])) {
                        require_once(MODEL . 'merchant/BulkuploadModel.php');
                        $bulkupload = new BulkuploadModel();
                        $merchant_id = $this->session->get('merchant_id');
                        $bulk_id = $this->encrypt->decode($_POST['bulk_id']);
                        $detail = $bulkupload->getBulkuploaddetail($merchant_id, $bulk_id);
                        $bulkupload->updateBulkUploadStatus($bulk_id, 7);
                        $folder = ($detail['status'] == 2 || $detail['status'] == 3) ? 'staging' : 'error';
                        $bulkupload->moveExcelFile($merchant_id, $folder, $detail['system_filename']);
                    }
                    $this->bulk_upload($_FILES["fileupload"]);
                } else {
                    $this->smarty->assign("haserrors", $hasErrors);
                    $this->bulkupload();
                }
            } else {
                header('Location: /merchant/customer/bulkupload');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E270]Error while bulk upload submit Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function bulk_upload($inputFile, $bulk_upload_id = NULL, $rownumber = 2, $worksheetex = null, $merchant_id = null,$version = null)
    {
        try {
            if ($worksheetex == null) {
                if ($bulk_upload_id == NULL) {
                    $File = $inputFile['tmp_name'];
                } else {
                    $File = $inputFile;
                }
                $rownumber = 2;
                $merchant_id = $this->session->get('merchant_id');
                $inputFileType = PHPExcel_IOFactory::identify($File);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($File);
                $subject = $objPHPExcel->getProperties()->getSubject();
                $link = $this->encrypt->decode($subject);
                $merchant_id_ = substr($link, 0, 10);
                $update_date = substr($link, 10, 19);
                $version = substr($link, 29,2);
                $worksheet = $objPHPExcel->getSheet(0);
                //$worksheetTitle = $worksheet->getTitle();
            } else {
                $merchant_id = $merchant_id;
                $merchant_id_ = $merchant_id;
                $worksheet = $worksheetex;
            }

            $errors = array();
            $is_upload = TRUE;
            $templateinfo = array();


            require_once(MODEL . 'merchant/BulkuploadModel.php');
            $bulkupload = new BulkuploadModel();
            require_once(MODEL . 'merchant/CustomerModel.php');
            $customerModel = new CustomerModel();



            if ($merchant_id_ == $merchant_id) {
                $custom_column = $customerModel->getCustomerBreakup($merchant_id);
                $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
                $auto_generate = $merchant_setting['customer_auto_generate'];
                $total_rows = $bulkupload->gettotalrows($merchant_id, 2);
                if ($worksheet == null) {
                    if ($update_date != $custom_column[0]['last_update_date']) {
                        $errors[0][0] = 'Structure already updated';
                        $errors[0][1] = 'Download again excel format and  re-upload with customer data.';
                        $is_upload = FALSE;
                    }
                }
            } else {
                $errors[0][0] = 'Invalid excel sheet';
                $errors[0][1] = 'Download again excel Customer bulkupload and re-up load with customer data.';

                $is_upload = FALSE;
            }

            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;



            $getcolumnvalue = array();
            if (empty($errors)) {
                for ($rowno = $rownumber; $rowno <= $highestRow; ++$rowno) {
                    $cell = $worksheet->getCellByColumnAndRow(0, $rowno);
                    $val = $cell->getFormattedValue();
                    if ((string) $val != '') {
                        for ($col = 0; $col < $highestColumnIndex; ++$col) {
                            $cell = $worksheet->getCellByColumnAndRow($col, $rowno);
                            $val = $cell->getFormattedValue();
                            $getcolumnvalue[$rowno][] = $val;
                        }
                        $post_row = array();
                        $int = 0;

                        if ($auto_generate == 0) {
                            $post_row['customer_code'] = (string) $getcolumnvalue[$rowno][$int];
                            $int++;
                            if ($worksheetex != null) {
                                $post_row['customer_code'] = 'Temp' . time() . rand(1, 999) . $rowno;
                            }
                        } else {
                            $post_row['customer_code'] = 'Temp' . time() . rand(1, 999) . $rowno;
                        }

                        $post_row['customer_name'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['email'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['mobile'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['address'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['city'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['state'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['zipcode'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        if($version!='' && $version=='v2' || $version=='v3') {
                            $post_row['country'] = (string) $getcolumnvalue[$rowno][$int];
                            $int++;
                        }
                        
                        foreach ($custom_column as $row) {
                            if ($row['column_datatype'] == 'date') {
                                try {
                                    $excel_date = PHPExcel_Style_NumberFormat::toFormattedString($getcolumnvalue[$rowno][$int], 'Y-m-d');
                                    $excel_date = str_replace('/', '-', $excel_date);
                                } catch (Exception $e) {
                                    $excel_date = (string) $getcolumnvalue[$rowno][$int];
                                }

                                try {
                                    $excel_date = str_replace('-', '/', $excel_date);
                                    $date = new DateTime($excel_date);
                                } catch (Exception $e) {
                                    $excel_date = str_replace('/', '-', $excel_date);
                                }
                                try {
                                    $date = new DateTime($excel_date);
                                    $value = $date->format('Y-m-d');
                                } catch (Exception $e) {
                                    $value = (string) $getcolumnvalue[$rowno][$int];
                                }
                                $post_row['values'][] = $value;
                            } else {
                                $value = (string) $getcolumnvalue[$rowno][$int];
                                $post_row['values'][] = $value;
                            }
                            $post_row['ids'][] = $row['column_id'];
                            $post_row['datatypes'][] = $row['datatype'];
                            $post_row['column_name'][] = $row['column_name'];
                            $int = $int + 1;
                        }
                        $_POSTarray[] = $post_row;
                    }
                }
            }
            $rows_count = count($_POSTarray);
            $upload_limit = $merchant_setting['customer_bulk_upload_limit'];
            if ($rows_count + $total_rows > $upload_limit) {
                $is_upload = FALSE;
                $errors[0][0] = 'Bulk upload limit';
                if ($this->session->get('merchant_plan') == 2) {
                    $errors[0][1] = 'You are currently on the Free plan which allows only ' . $upload_limit . ' rows to be uploaded. <a href="/billing-software-pricing">Upgrade your plan</a>';
                } else {
                    $errors[0][1] = 'Maximum ' . $upload_limit . ' Rows can be uploaded in one day.';
                }
            }
            $errorrow = $rownumber;
            try {


                if (empty($_POSTarray) && empty($errors)) {
                    $is_upload = FALSE;
                    $errors[0][0] = 'Empty excel';
                    $errors[0][1] = 'Uploaded excel does not contain any customer.';
                }
                if (empty($errors)) {
                    foreach ($_POSTarray as $_POST) {
                        $result = $this->validateuploadcustomer($auto_generate, $customerModel,$_POST['country']);
                        if (!empty($result)) {
                            $result['row'] = $errorrow;
                            $errors[] = $result;
                        } else {
                        }
                        $errorrow++;
                    }
                }
            } catch (Exception $e) {
                Sentry\captureException($e);
            }
            if ($worksheetex != null) {
                $array['last_col'] = $int;
                $array['errors'] = $errors;
                return $array;
            }
            if (empty($errors) && $bulk_upload_id == NULL) {
                $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), FALSE, $rows_count, 2);
                $this->session->set('successMessage', '  Excel sheet uploaded.');
                header('Location: /merchant/customer/bulkupload');
            } else {
                if ($is_upload == TRUE && $bulk_upload_id == NULL) {
                    $bulk_id = $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), TRUE, 0, 2);
                    $this->common->genericupdate('bulk_upload', 'error_json', json_encode($errors), 'bulk_upload_id', $bulk_id, $this->user_id);
                    $bulk_id = $this->encrypt->encode($bulk_id);
                }

                if ($bulk_upload_id != NULL) {
                    $this->smarty->assign("bulk_id", $bulk_upload_id);
                    $this->smarty->assign("errors", $errors);
                    $this->view->render('nonlogoheader');
                    $this->smarty->display(VIEW . 'merchant/bulkupload/allerrors.tpl');
                    $this->view->render('nonfooter');
                } else {
                    $this->smarty->assign("haserrors", $errors);
                    if ($bulk_id != '') {

                        $this->reupload($bulk_id);
                    } else {
                        $this->bulkupload();
                    }
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E271]Error while uploading excel Error: for merchant [' . $merchant_id . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function reupload($bulk_id = '')
    {
        try {
            $this->hasRole(2, 15);
            $merchant_id = $this->session->get('merchant_id');
            if ($bulk_id != '') {
                $this->smarty->assign("bulk_id", $bulk_id);
                $this->view->title = 'Re-upload customer';
                $this->view->canonical = 'merchant/bulkupload/error/';
                $this->view->header_file = ['bulkupload'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/customer/reupload.tpl');
                $this->view->render('footer/bulkupload');
            } else {
                header('Location: /merchant/customer/bulkupload');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E269]Error while re-upload Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function validateuploadcustomer($auto_generate, $customerModel, $country=null)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $states_data = $customerModel->getStates();
            $countries_data = $customerModel->getCountries();
            require_once CONTROLLER . 'merchant/Customervalidator.php';
            $validator = new Customervalidator($customerModel);
            $validator->validateCustomerSave($merchant_id, $states_data,$countries_data, $country);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors['customer_code'] == false) {
                if ($auto_generate == 0) {
                    if (in_array($_POST['customer_code'], $this->customer_codes)) {
                        $hasErrors['customer_code'] = array('Customer code', 'Customer code already exists in excel sheet');
                    }
                }
            }
            $this->customer_codes[] = $_POST['customer_code'];

            if (empty($hasErrors)) {
                $hasErrors = array();
            }

            $custom_column = $customerModel->getCustomerBreakup($merchant_id);
            $cint = 0;
            foreach ($custom_column as $c_row) {
                if ($c_row['column_id'] > 0) {
                    $key = array_search($c_row['column_id'], $_POST['ids']);
                    if ($c_row['column_datatype'] == 'date') {
                        $c_row['column_datatype'] = 'apidate';
                    }
                    $cust_col[] = array('column_name' => $c_row['column_name'], 'value' => $_POST['values'][$key], 'column_id' => $c_row['column_id'], 'col_datatype' => $c_row['column_datatype']);
                }
                $cint++;
            }

            if (!empty($cust_col)) {
                $validator = new Customervalidator($this->model);
                $customint = 1;
                foreach ($cust_col as $c_col) {
                    $_POST['custom_col' . $customint] = $c_col['value'];
                    $validator->validateCustomUpload($c_col, 'custom_col' . $customint);
                    $customint++;
                }
                $custErrors = $validator->fetchErrors();
                if (!empty($custErrors)) {
                    $hasErrors = array_merge($hasErrors, $custErrors);
                }
            }

            if ($hasErrors == false) {
            } else {
                return $hasErrors;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E246]Error while sending payment request Error:' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Bulk upload customer
     */
    function download($type = null)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            require_once MODEL . 'merchant/CustomerModel.php';
            $customerModel = new CustomerModel();
            $custom_column = $customerModel->getCustomerBreakup($merchant_id);
            $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
            if ($this->session->get('customer_default_column')) {
                $customer_default_column = $this->session->get('customer_default_column');
            }
            $lbl_customer_code = (isset($customer_default_column['customer_code'])) ? $customer_default_column['customer_code'] : 'Customer code';
            $lbl_customer_name = (isset($customer_default_column['customer_name'])) ? $customer_default_column['customer_name'] : 'Customer name';
            $lbl_email = (isset($customer_default_column['email'])) ? $customer_default_column['email'] : 'Email ID';
            $lbl_mobile = (isset($customer_default_column['mobile'])) ? $customer_default_column['mobile'] : 'Mobile No';
            $lbl_address = (isset($customer_default_column['address'])) ? $customer_default_column['address'] : 'Address';
            $lbl_city = (isset($customer_default_column['city'])) ? $customer_default_column['city'] : 'City';
            $lbl_state = (isset($customer_default_column['state'])) ? $customer_default_column['state'] : 'State';
            $lbl_zipcode = (isset($customer_default_column['zipcode'])) ? $customer_default_column['zipcode'] : 'Zipcode';
            $lbl_country = (isset($customer_default_column['country'])) ? $customer_default_column['country'] : 'Country';
            $auto_generate = $merchant_setting['customer_auto_generate'];
            $column_names = array();
            if ($auto_generate == 0) {
                $column_names[] = $lbl_customer_code;
            }
            $column_names[] = $lbl_customer_name;
            $column_names[] = $lbl_email;
            $column_names[] = $lbl_mobile;
            $column_names[] = $lbl_address;
            $column_names[] = $lbl_city;
            $column_names[] = $lbl_state;
            $column_names[] = $lbl_zipcode;
            $column_names[] = $lbl_country;
            foreach ($custom_column as $row) {
                if ($row['column_datatype'] == 'date') {
                    $column_names[] = $row['column_name'];
                } else {
                    $column_names[] = $row['column_name'];
                }
            }
            if ($type == 'columns') {
                return $column_names;
            }
            //v2 for country column
            //v3 for sample rows  
            $link = $this->encrypt->encode($merchant_id . $custom_column[0]['last_update_date'].'v3');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("swipez")
                ->setLastModifiedBy("swipez")
                ->setTitle("swipez_C ustomer")
                ->setSubject($link)
                ->setDescription("swipez customer structure");
            
            #create array of excel column
            $first = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
            $column = array();
            $sample_column = array();
            $sample_values = array();
            foreach ($first as $s) {
                $sample_column[] = $s . '2';
                $sample_values[] = $s. '3';
                $column[] = $s . '1';
            }
            foreach ($first as $f) {
                foreach ($first as $s) {
                    $sample_column[] = $f . $s . '2';
                    $sample_values[] = $f . $s . '3';
                    $column[] = $f . $s . '1';
                }
            }

            //for customer input sheet 
            $int = 0;
            $sheet = 0;
            $int = $this->createExcelColumns($objPHPExcel,$sheet,$auto_generate,$int,$column,$lbl_customer_code,$lbl_customer_name,$lbl_email,$lbl_mobile,$lbl_address,$lbl_city,$lbl_state,$lbl_zipcode,$lbl_country);

            foreach ($custom_column as $row) {
                if ($row['column_datatype'] == 'date') {
                    $objPHPExcel->setActiveSheetIndex($sheet)->setCellValue($column[$int], $row['column_name'] . ' (yyyy-m-d)');
                    $column_names[] = $row['column_name'];
                } else {
                    $objPHPExcel->setActiveSheetIndex($sheet)->setCellValue($column[$int], $row['column_name']);
                    $column_names[] = $row['column_name'];
                }
                $int = $int + 1;
            }
            
            $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')->setSize(10);
            $objPHPExcel->setActiveSheetIndex($sheet)->setTitle('Customer Input');

            $this->setStyleExcelRows($objPHPExcel,$sheet,'A1',$column,$int,'AAAADD');

            $int++;
            $autosize = 0;
            while ($autosize < $int) {
                $objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension(substr($column[$autosize], 0, -1))->setAutoSize(true);
                $autosize++;
            }

            $this->sampleSheet_download($objPHPExcel,$auto_generate,$sample_column,$sample_values,$lbl_customer_code,$lbl_customer_name,$lbl_email,$lbl_mobile,$lbl_address,$lbl_city,$lbl_state,$lbl_zipcode,$lbl_country);
            $this->mastersheet_download($objPHPExcel);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Customer_structure' . time() . '.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);
            exit;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E235]Error while export excel Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function sampleSheet_download($objPHPExcel=null,$auto_generate,$sample_column,$sample_values,$lbl_customer_code,$lbl_customer_name,$lbl_email,$lbl_mobile,$lbl_address,$lbl_city,$lbl_state,$lbl_zipcode,$lbl_country) {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Sample Sheet');
            $objPHPExcel->addSheet($myWorkSheet, 1);
            $sheet = 1;
            $objPHPExcel->setActiveSheetIndex($sheet)->mergeCells('A1:K1');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'This is an illustrative example. *Please add your customer information in the Customer Input sheet');
            $styleFontArrayA1 = array(
                'font'  => array('color' => array('rgb' => '556B2F'),'size'  => 11,'name' => 'Verdana','bold' => true),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            );
            $objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleFontArrayA1);

            //sample row columns heading
            $i = $this->createExcelColumns($objPHPExcel,$sheet,$auto_generate,$i=0,$sample_column,$lbl_customer_code,$lbl_customer_name,$lbl_email,$lbl_mobile,$lbl_address,$lbl_city,$lbl_state,$lbl_zipcode,$lbl_country);
        
            //sample row columns values
            $j = $this->createExcelColumns($objPHPExcel,$sheet,$auto_generate,$j=0,$sample_values,'CXXXXX1','ABC','abc@gmail.com','90*****506','1/136, Dr. M.N. Saha Road','Kolkata','West Bengal','70***4','India');
            
            $styleBorderArray= array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
            $objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($styleBorderArray);

            //$objPHPExcel->setActiveSheetIndex($sheet)->mergeCells('A6:I6');
            //$objPHPExcel->setActiveSheetIndex($sheet)->SetCellValue('A6','*Add your customer data from row #8 onwards');
            $styleFontArrayA6 = array(
                'font'  => array('color' => array('rgb' => 'FFFFFF'),'size'  => 11,'name' => 'Verdana'),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            );
            $objPHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleFontArrayA6);

            $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);    
            // $objPHPExcel->getActiveSheet()->getStyle('A8:L50')->getProtection()->setLocked(
            //     PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
            // );
            
            $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')->setSize(10);
            $myWorkSheet->setTitle('Sample Sheet');
            $this->setStyleExcelRows($objPHPExcel,$sheet,'A1',$sample_column,$i,'7FFFD4');
            $this->setStyleExcelRows($objPHPExcel,$sheet,'A2',$sample_column,$i,'AAAADD');
            $this->setStyleExcelRows($objPHPExcel,$sheet,'A3',$sample_column,$i,'FFFFFF');
            //$this->setStyleExcelRows($objPHPExcel,$sheet,'A6',$sample_column,$i,'BC5339');

            $i++;
            $autosize = 0;
            while ($autosize < $i) {
                $objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension(substr($sample_column[$autosize], 0, -1))->setAutoSize(true);
                $autosize++;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E235]Error while download sample customer sheet Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function setStyleExcelRows($objPHPExcel,$sheet=0,$start,$array,$col,$color) {
        $objPHPExcel->setActiveSheetIndex($sheet)->getStyle($start.':' . $array[$col - 1])->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => $color)
                )
            )
        );
    }

    function createExcelColumns($objPHPExcel,$sheet=0,$auto_generate,$j,$array,$lbl_customer_code,$lbl_customer_name,$lbl_email,$lbl_mobile,$lbl_address,$lbl_city,$lbl_state,$lbl_zipcode,$lbl_country) {    
        if ($auto_generate == 0) {
            $objPHPExcel->setActiveSheetIndex($sheet)->setCellValue($array[$j], $lbl_customer_code);
            $j++;
        }
        $objPHPExcel->setActiveSheetIndex($sheet)->setCellValue($array[$j], $lbl_customer_name);
        $j++;
        $objPHPExcel->setActiveSheetIndex($sheet)->setCellValue($array[$j], $lbl_email);
        $j++;
        $objPHPExcel->setActiveSheetIndex($sheet)->setCellValue($array[$j], $lbl_mobile);
        $j++;
        $objPHPExcel->setActiveSheetIndex($sheet)->setCellValue($array[$j], $lbl_address);
        $j++;
        $objPHPExcel->setActiveSheetIndex($sheet)->setCellValue($array[$j], $lbl_city);
        $j++;
        $objPHPExcel->setActiveSheetIndex($sheet)->setCellValue($array[$j], $lbl_state);
        $j++;
        $objPHPExcel->setActiveSheetIndex($sheet)->setCellValue($array[$j], $lbl_zipcode);
        $j++;
        $objPHPExcel->setActiveSheetIndex($sheet)->setCellValue($array[$j], $lbl_country);
        $j++;
        return $j;
    }

    public function bulklist($link)
    {
        try {
            $this->hasRole(1, 15);
            $bulk_id = $this->encrypt->decode($link);
            if (!is_numeric($bulk_id)) {
                $this->setInvalidLinkError();
            }
            $merchant_id = $this->session->get('merchant_id');
            $column_list = $this->model->getCustomerBreakup($merchant_id);
            $auto_generate = $this->common->getRowValue('customer_auto_generate', 'merchant_setting', 'merchant_id', $merchant_id);
            $this->smarty->assign("auto_generate", $auto_generate);
            $this->smarty->assign("column_list", $column_list);
            if (isset($_POST['column_name'])) {
                $column_select = $_POST['column_name'];
            } else {
                $column_select = array();
            }
            if (count($column_select) > 5) {
                $column_select = array();
                $this->smarty->assign("haserrors", "Column name selected more than 5 values");
            }

            $requestlist = $this->model->getbulkCustomerList($bulk_id, $this->merchant_id, $column_select);
            $requestlist = $this->generic->getEncryptedList($requestlist, 'link', 'customer_id');
            $this->view->datatablejs = 'table-ellipsis-small';
            $this->view->selectedMenu = array(2, 15, 70);
            $this->smarty->assign("title", 'View Bulk Customer');
            $this->smarty->assign("column_select", $column_select);
            $this->smarty->assign("requestlist", $requestlist);
            $this->view->title = 'View bulk customer list';
            $this->view->canonical = 'merchant/paymentrequest/viewlist';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/customer/staging_customer_list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E007]Error while Bulk customer list Error: for bulk id [' . $bulk_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function bulkerror($bulk_upload_id)
    {
        try {
            $bulk_id = $this->encrypt->decode($bulk_upload_id);
            $merchant_id = $this->session->get('merchant_id');
            require_once(MODEL . 'merchant/BulkuploadModel.php');
            $bulkupload = new BulkuploadModel();
            $detail = $bulkupload->getBulkuploaddetail($merchant_id, $bulk_id);
            if ($detail['error_json'] != '') {
                $errors = json_decode($detail['error_json'], true);
                $this->smarty->assign("bulk_id", $bulk_upload_id);
                $this->smarty->assign("errors", $errors);
                $this->view->render('header/guest');
                $this->smarty->display(VIEW . 'merchant/bulkupload/allerrors.tpl');
                $this->view->render('footer/nonfooter');
            } else {

                if ($detail['status'] == 1) {
                    $folder = 'error';
                } elseif ($detail['status'] == 6) {
                    $folder = 'deleted';
                } else {
                    $folder = 'staging';
                }
                if (!empty($detail)) {

                    $file = 'uploads/Excel/' . $merchant_id . '/' . $folder . '/' . $detail['system_filename'];
                }
                $this->bulk_upload($file, $bulk_upload_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E272]Error while bulk upload error Error:for merchant [' . $merchant_id . '] and  bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function getcustomerdetails($customer_id, $template_id)
    {
        try {
            if (strlen($template_id) != 10) {
                $template_id = $this->encrypt->decode($template_id);
            }
            $customer = $this->model->getCustomerDeatils($customer_id, $this->merchant_id);
            $list = $this->model->getCustomerTemplateColumn($template_id, $customer_id);
            foreach ($list as $val) {
                if ($val['save_table_name'] == 'customer') {
                    switch ($val['customer_column_id']) {
                        case 1:
                            $value = $customer['customer_code'];
                            break;
                        case 2:
                            $value = $customer['first_name'] . ' ' . $customer['last_name'];
                            break;
                        case 3:
                            $value = $customer['email'];
                            break;
                        case 4:
                            $value = $customer['mobile'];
                            break;
                        case 5:
                            $value = $customer['address'];
                            break;
                        case 6:
                            $value = $customer['city'];
                            break;
                        case 7:
                            $value = $customer['state'];
                            break;
                        case 8:
                            $value = $customer['zipcode'];
                            break;
                        case 9: 
                            $value = $customer['country'];
                            break;
                    }
                    $column[] = array('column_name' => $val['column_name'], 'id' => $val['column_id'], 'value' => $value, 'datatype' => $val['column_datatype']);
                } else if (($val['save_table_name'] == 'customer_metadata') && ($val['column_datatype'] == 'company_name')) {
                    $column[] = array('column_name' => $val['column_name'], 'id' => $val['column_id'], 'value' => $customer['company_name'], 'datatype' => $val['column_datatype']);
                } else {
                    $column[] = array('column_name' => $val['column_name'], 'id' => $val['column_id'], 'value' => $val['value'], 'datatype' => $val['column_datatype']);
                }
            }
            $customer_col['column_value'] = $column;
            $customer_col['state'] = $customer['state'];
            $customer_col['balance'] = $customer['balance'];
            echo json_encode($customer_col);
            die();
            return $column;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E272]Error while bulk upload error Error:for merchant [' . $customer_id . '] and  bulk id [' . $bulk_id . ']' . $e->getMessage());
        }
    }

    public function getCustomerLedger($customer_id)
    {
        $ledger = '';
        $details = $this->model->getCustomerDeatils($customer_id, $this->merchant_id);
        if (!empty($details)) {
            $invoiceledger = $this->common->getListValue('payment_request', 'customer_id', $customer_id, 1, ' and payment_request_status <>3 and payment_request_type <> 4 order by created_date desc ');
            $creditnoteledger = $this->common->getListValue('credit_debit_note', 'customer_id', $customer_id, 1, ' order by created_date desc ');
            $cledger = array();
            foreach ($invoiceledger as $row) {
                $array['reference_no'] = $row['payment_request_id'];
                $array['created_date'] = $row['created_date'];
                $array['description'] = 'Invoice for bill date ' . $this->generic->htmlDate($row['bill_date']);
                $array['ledger_type'] = 'Invoice';
                $array['amount'] = $row['absolute_cost'];
                $array['status'] = $row['payment_request_status'];
                $array['invoice_number'] = $row['invoice_number'];
                $array['due_date'] = $row['due_date'];
                $cledger[] = $array;
            }
            foreach ($creditnoteledger as $row) {
                $array['reference_no'] = $row['id'];
                $array['created_date'] = $row['created_date'];
                $array['description'] = 'Credit note for date ' . $this->generic->htmlDate($row['date']);
                $array['ledger_type'] = 'Credit note';
                $array['amount'] = $row['total_amount'];
                $array['invoice_number'] = $row['invoice_no'];
                $cledger[] = $array;
            }
            $keys = array_column($cledger, 'created_date');
            array_multisort($keys, SORT_DESC, $cledger);
            foreach ($cledger as $key => $row) {
                $enc = $this->encrypt->encode($row['reference_no']);
                $ledger .= '<tr>
                <td class="td-c">
                    ' . $this->generic->htmlDate($row['created_date']) . '
                </td>
                <td class="td-c">
                    ' . $row['description'] . '
                </td>
                <td class="td-c">
                ';
                if ($row['ledger_type'] == 'Invoice') {
                    if ($row['status'] == 1) {
                        $ledger .= '<span class="badge badge-pill status paid_online">PAID ONLINE</span>';
                    } elseif ($row['status'] == 2) {
                        $ledger .= '<span class="badge badge-pill status paid_offline">PAID OFFLINE</span>';
                    } else {
                        if (strtotime($row['due_date']) < strtotime(date('Y-m-d'))) {
                            $ledger .= '<span class="badge badge-pill status overdue">OVERDUE</span>';
                        } else {
                            $ledger .= '<span class="badge badge-pill status unpaid">UNPAID</span>';
                        }
                    }
                } else {
                    $ledger .= '<span class="badge badge-pill status paid_online">CREDIT NOTE</span>';
                }
                $ledger .= '
                </td>
                <td class="td-c">
                ' . $row['amount'] . '
                </td>
                <td class="td-c">';
                if ($row['ledger_type'] == 'Invoice') {
                    $ledger .= '<a href="/merchant/paymentrequest/view/' . $enc . '"
                    class="iframe"  target="_BLANK">';
                    if ($row['invoice_number'] != '') {
                        $ledger .= $row['invoice_number'];
                    } else {
                        $ledger .= $row['reference_no'];
                    }
                    $ledger .= '</a>';
                } else {
                    $ledger .= '<a href="/merchant/creditnote/view/' . $enc . '"
                    class="iframe" target="_BLANK">' . $row['reference_no'] . '</a>';
                }
                $ledger .= '</td></tr>';
            }

            $array['customer_name'] = $details['first_name'] . ' ' . $details['last_name'];
            $array['customer_code'] = $details['customer_code'];
            $array['balance'] = $details['balance'];
            $array['ledger'] = $ledger;
            echo json_encode($array);
        }
    }

    function isexist($customer_code)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $result = $this->model->isExistCustomerCode($merchant_id, $customer_code);
            if ($result != FALSE) {
                $array['customer_code'] = 1;
                $array['email'] = 0;
                $array['mobile'] = 0;
                $array['customer_id'] = $this->encrypt->encode($result);
                $details[] = $this->model->getCustomerDeatils($result, $this->merchant_id);
                $details[0]['customer_id'] = $this->encrypt->encode($result);
                $array['customer_detail'] = $details;
                echo json_encode($array);
            } else {
                $result = $this->model->isExistContactdetails($merchant_id, $_POST['email'], $_POST['mobile']);
                if ($result != FALSE) {
                    $int = 0;
                    foreach ($result as $cid) {
                        $details[$int] = $this->model->getCustomerDeatils($cid['customer_id'], $this->merchant_id);
                        $details[$int]['customer_id'] = $this->encrypt->encode($cid['customer_id']);
                        $int++;
                    }
                    $array['customer_code'] = 0;
                    $array['email'] = ($_POST['email'] == $details['email']) ? 1 : 0;
                    $array['mobile'] = ($_POST['mobile'] == $details['mobile']) ? 1 : 0;
                    $array['customer_id'] = $this->encrypt->encode($result);
                    $array['customer_detail'] = $details;
                    echo json_encode($array);
                } else {
                    echo 'false';
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E272]Error while bulk upload error Error:for merchant [' . $merchant_id . '] and  bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function delete($link)
    {
        $this->deletecustomer($link);
    }

    function deletecustomer($link)
    {
        try {
            $this->hasRole(3, 15);
            $customer_id = $this->encrypt->decode($link);
            $this->model->deleteCustomer($this->merchant_id, $customer_id, $this->user_id);
            $this->session->set('successMessage', 'Customer deleted successfully.');
            header('Location:/merchant/customer/viewlist');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E272]Error while delete customer error Error:for merchant [' . $this->merchant_id . '] and  bulk id [' . $customer_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function deletegroup($link)
    {
        try {
            $this->hasRole(3, 15);
            $group_id = $this->encrypt->decode($link);
            $this->model->deleteGroup($this->merchant_id, $group_id, $this->user_id);
            $this->session->set('successMessage', 'Group deleted successfully.');
            header('Location:/merchant/customer/managegroup');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E272]Error while delete group error Error:for merchant [' . $this->merchant_id . '] and  group id [' . $group_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function saveCustomerJson($version)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            require_once CONTROLLER . 'api/Api.php';
            $api = new Api(TRUE);
            $jsonArray = $api->getcustomerSaveJson($version, $merchant_id);
            header("Content-type: text/plain");
            header("Content-Disposition: attachment; filename=customer_save.json");
            print json_encode($jsonArray);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC001-3]Error while get saveCustomerJson Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    function updateCustomerJson($version)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            require_once CONTROLLER . 'api/Api.php';
            $api = new Api(TRUE);
            $jsonArray = $api->getcustomerUpdateJson($version, $merchant_id);
            header("Content-type: text/plain");
            header("Content-Disposition: attachment; filename=customer_update.json");
            print json_encode($jsonArray);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC001-3]Error while get saveCustomerJson Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    public function register()
    {
        try {
            if (isset($_POST['register'])) {
                if (count($_POST['customer_check']) > 0) {
                    $_POST['customer_id'] = 1;
                } else {
                    $_POST['customer_id'] = '';
                }
                require CONTROLLER . 'Paymentvalidator.php';
                $validator = new Paymentvalidator($this->model);
                $validator->validateCustomerRegister();
                $hasErrors = $validator->fetchErrors();
                if ($_POST['notification'] == 1) {
                    $type = $this->common->getRowValue('sms_gateway_type', 'merchant_setting', 'merchant_id', $this->merchant_id);
                    if ($type != 1) {
                        $smscount = $this->model->getSMSCount($this->merchant_id);
                        if (count($_POST['customer_check']) > $smscount) {
                            $hasErrors[0][0] = "SMS";
                            $hasErrors[0][1] = "You have " . $smscount . " SMS available. Please recharge your SMS pack";
                        }
                    }
                }
                if ($hasErrors == false) {
                    if ($this->env == 'PROD') {
                        $api_url = 'https://intapi.swipez.in/api/v1/customer/addregisterjobs';
                    } else {
                        $api_url = 'https://h7sak-api.swipez.in/api/v1/customer/addregisterjobs';
                    }

                    $array['password_char_number'] = $_POST['password_char_number'];
                    $array['password_combination'] = $_POST['password_combination'];
                    $array['notification'] = $_POST['notification'];
                    $array['sms'] = $_POST['sms'];
                    $array['ids'] = $_POST['customer_check'];
                    $array['merchant_id'] = $this->merchant_id;
                    $post_string = json_encode($array);
                    $this->apisrequest($api_url, $post_string);
                    $this->session->set("successMessage", ' You have created logins for ' . count($_POST['customer_check']) . ' customers. It would take 5 minutes for these logins to start reflecting under the Customer Logins screen <a  href="/merchant/customer/logins" class="btn btn-sm green">Logins created</a>.');
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
            if ($this->view->cable_enable == 1) {
                $loginurl = 'https://go.swipez.in/cable';
            } else {
                $display_url = $this->common->getRowValue('display_url', 'merchant', 'merchant_id', $this->merchant_id);
                $loginurl = 'https://swipez.in/m/' . $display_url . '/booking';
            }
            $this->smarty->assign("loginurl", $loginurl);
            $this->smarty->assign("filter_by", $filter_by);
            $this->smarty->assign("filter_condition", $filter_condition);
            $this->smarty->assign("filter_value", $filter_value);
            $this->smarty->assign("group", $group);
            $this->smarty->assign("company_name", $this->session->get('company_name'));
            $_SESSION['group'] = $group;
            $_SESSION['type'] = 'group';
            $_SESSION['db_column'] = $column_select;
            $_SESSION['customer_status'] = $status;
            $_SESSION['payment_status'] = $payment_status;
            $_SESSION['customer_bulk_id'] = $bulk_id;

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
            $this->smarty->assign("customer_group", $customer_group);

            $this->view->selectedMenu = array(2, 15, 72);
            $this->smarty->assign("is_bulk", $is_bulk);
            $this->setAjaxDatatableSession();
            $this->smarty->assign("title", 'Create logins');
            $this->smarty->assign("status", $status);
            $this->smarty->assign("payment_status", $payment_status);
            $this->view->title = 'Create logins';

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Contacts', 'url' => ''),
                array('title' => 'Customer', 'url' => ''),
                array('title' => 'Customer logins', 'url' => ''),
                array('title' => 'Create logins', 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small';
            $this->view->canonical = 'merchant/customer/viewlist';
            $this->view->ajaxpage = 'customer_register.php';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/customer/register.tpl');
            $this->view->render('footer/customer_group');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E007]Error while Merchant customer list Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function logins()
    {
        try {
            if (isset($_POST['register'])) {
                if (count($_POST['customer_check']) > 0) {
                    $_POST['customer_id'] = 1;
                } else {
                    $_POST['customer_id'] = '';
                }
                require CONTROLLER . 'Paymentvalidator.php';
                $validator = new Paymentvalidator($this->model);
                $validator->validateCustomerRegister();
                $hasErrors = $validator->fetchErrors();
                $type = $this->common->getRowValue('sms_gateway_type', 'merchant_setting', 'merchant_id', $this->merchant_id);
                if ($type != 1) {
                    $smscount = $this->model->getSMSCount($this->merchant_id);
                    if (count($_POST['customer_check']) > $smscount) {
                        $hasErrors[0][0] = "SMS";
                        $hasErrors[0][1] = "You have " . $smscount . " SMS available. Please recharge your SMS pack";
                    }
                }
                if ($hasErrors == false) {
                    if ($this->env == 'PROD') {
                        $api_url = 'https://intapi.swipez.in/api/v1/customer/addloginsmsjobs';
                    } else {
                        $api_url = 'https://h7sak-api.swipez.in/api/v1/customer/addloginsmsjobs';
                    }
                    $array['sms'] = $_POST['sms'];
                    $array['ids'] = $_POST['customer_check'];
                    $array['merchant_id'] = $this->merchant_id;
                    $post_string = json_encode($array);
                    $this->apisrequest($api_url, $post_string);
                    $this->session->set("successMessage", 'Login SMS have been sent successfully');
                    header('Location: /merchant/customer/logins', 301);
                    die();
                } else {
                    $this->smarty->assign("haserrors", $hasErrors);
                }
            }

            $merchant_id = $this->session->get('merchant_id');
            $column_list = $this->model->getCustomerBreakup($merchant_id);
            $addcolumn[] = array('column_name' => 'City');
            $addcolumn[] = array('column_name' => 'State');
            $addcolumn[] = array('column_name' => 'Address');
            $addcolumn[] = array('column_name' => 'Zipcode');
            $column_list = array_merge($addcolumn, $column_list);
            $this->smarty->assign("column_list", $column_list);
            $this->session->set('valid_ajax', 'custlogins');
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
            $this->smarty->assign("company_name", $this->session->get('company_name'));

            $_SESSION['group'] = $group;
            $_SESSION['type'] = 'group';
            $_SESSION['db_column'] = $column_select;
            $_SESSION['customer_status'] = $status;
            $_SESSION['payment_status'] = $payment_status;
            $_SESSION['customer_bulk_id'] = $bulk_id;

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
            $this->smarty->assign("customer_group", $customer_group);

            $this->view->selectedMenu = array(2, 15, 72);
            $this->smarty->assign("is_bulk", $is_bulk);

            if ($this->view->cable_enable == 1) {
                $loginurl = 'https://go.swipez.in/cable';
            } else {
                $display_url = $this->common->getRowValue('display_url', 'merchant', 'merchant_id', $this->merchant_id);
                $loginurl = 'https://swipez.in/m/' . $display_url . '/booking';
            }
            $this->smarty->assign("loginurl", $loginurl);
            $this->setAjaxDatatableSession();
            $this->smarty->assign("title", 'Manage logins');
            $this->smarty->assign("status", $status);
            $this->smarty->assign("payment_status", $payment_status);
            $this->view->title = 'Manage logins';

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Contacts', 'url' => ''),
                array('title' => 'Customer', 'url' => ''),
                array('title' => 'Customer logins', 'url' => ''),
                array('title' => 'Manage logins', 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small';
            $this->view->canonical = 'merchant/customer/viewlist';
            $this->view->ajaxpage = 'customer_logins.php';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/customer/logins.tpl');
            $this->view->render('footer/customer_group');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E007]Error while Merchant customer list Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function mastersheet_download($objPHPExcel=null)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            require_once MODEL . 'merchant/CustomerModel.php';
            $customerModel = new CustomerModel();
            $states_data = $customerModel->getStates();
            $countries_data = $customerModel->getCountries();

            $lbl_country = (isset($customer_default_column['country'])) ? $customer_default_column['country'] : 'Country';
            $lbl_state = (isset($customer_default_column['state'])) ? $customer_default_column['state'] : 'State';

            $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Supported States & Country');
            $objPHPExcel->addSheet($myWorkSheet, 2);

            $first = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
            $column = array();
            foreach ($first as $s) {
                $column[] = $s . '1';
            }
            foreach ($first as $f) {
                foreach ($first as $s) {
                    $column[] = $f . $s . '1';
                }
            }

            $int = 0;
            $objPHPExcel->setActiveSheetIndex(2)->setCellValue($column[$int], $lbl_country);
            $int++;
            $objPHPExcel->setActiveSheetIndex(2)->setCellValue($column[$int], $lbl_state);
            $int++;

            $rint = 2;
            foreach ($countries_data as $val) {
                $objPHPExcel->setActiveSheetIndex(2)->setCellValueByColumnAndRow($column[$int],$rint,ucwords($val));
                $rint++;
            }
            $pint = 2;
            foreach ($states_data as $val) {
                $objPHPExcel->setActiveSheetIndex(2)->setCellValueByColumnAndRow(1,$pint,ucwords($val));
                $pint++;
            }

            $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')->setSize(10);
            $myWorkSheet->setTitle('Country & State List');
            $this->setStyleExcelRows($objPHPExcel,2,'A1',$column,$int,'AAAADD');
            $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
            $int++;
            $autosize = 0;
            while ($autosize < $int) {
                $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension(substr($column[$autosize], 0, -1))->setAutoSize(true);
                $autosize++;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E235]Error while download customer excel Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    } 
}
