<?php

/**
 * Franchise controller class to handle Merchants franchise
 */
class Franchise extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->selectedMenu = array(2, 17);
    }

    /**
     * Display merchant franchises list
     */
    function viewlist($bulk_id = null)
    {
        try {
            $this->hasRole(1, 21);
            if ($bulk_id != null) {
                $bulk_id = $this->encrypt->decode($bulk_id);
                $list = $this->common->getListValue('franchise', 'merchant_id', $this->merchant_id, 1, ' and bulk_id=' . $bulk_id);
            } else {
                $list = $this->common->getListValue('franchise', 'merchant_id', $this->merchant_id, 1);
            }

            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['franchise_id']);
                $list[$int]['created_date'] = $this->generic->formatTimeString($item['created_date']);
                $int++;
            }
            $this->view->selectedMenu = array(2, 17, 78);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Franchise list");
            $this->view->title = "Franchise list";
            $this->view->canonical = 'merchant/franchise/viewlist';
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Contacts', 'url' => ''),
                array('title' => 'Franchise ', 'url' => ''),
                array('title' => 'Franchise list', 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->smarty->display(VIEW . 'merchant/franchise/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing franchises Error: for user id [' . $this->session->get('userid') . '] ' . $e->getMessage());
        }
    }

    function bulklist($bulk_id = NULL)
    {
        try {
            $this->hasRole(1, 21);
            $bulk_id = $this->encrypt->decode($bulk_id);
            $list = $this->common->getListValue('staging_franchise', 'bulk_id', $bulk_id);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['franchise_id']);
                $int++;
            }
            $this->view->selectedMenu = array(2, 17, 79);
            $this->smarty->assign("type", 'franchise');
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Bulk franchise list");
            $this->view->title = "Franchise list";
            $this->view->canonical = 'merchant/franchise/viewlist';
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/franchise/bulklist.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing franchise Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function onlineSettlementStatus($merchant_id)
    {
        $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $merchant_id, 1);
        $enable_online_settlement = 0;
        if (!empty($security_key)) {
            if ($security_key['cashfree_client_id'] != '') {
                $enable_online_settlement = 1;
            }
        }
        return $enable_online_settlement;
    }

    /**
     * Create new franchise for merchant
     */
    function create()
    {
        $this->hasRole(2, 21);
        $this->view->title = 'Create a franchise';
        require_once MODEL . 'merchant/SubuserModel.php';
        $this->smarty->assign("enable_online_settlement", $this->onlineSettlementStatus($this->merchant_id));
        $submodel = new SubuserModel();
        $list = $submodel->getRoleList($this->user_id);
        $int = 0;
        foreach ($list as $item) {
            $list[$int]['functions'] = $submodel->getControllersName($item['view_controllers']);
            $list[$int]['encrypted_id'] = $this->encrypt->encode($item['role_id']);
            $int++;
        }
        $this->view->selectedMenu = array(2, 17, 77);
        $this->smarty->assign("roles", $list);
        $food_franchise_mids = explode(',', env('FOOD_FRANCHISE_MERCHANT_ID'));
        if (in_array($this->merchant_id, $food_franchise_mids)) {
            $this->smarty->assign("food_franchise", true);
        }

        $food_franchise_mids = explode(',', env('FOOD_FRANCHISE_NON_BRAND'));
        if (in_array($this->merchant_id, $food_franchise_mids)) {
            $this->smarty->assign("non_brand_food_franchise", true);
        }

        $controllerlist = $submodel->getControllers();
        $this->smarty->assign("list", $controllerlist);
        $customer_group = $this->common->getListValue('customer_group', 'merchant_id', $this->merchant_id, 1);
        $this->smarty->assign("customer_group", $customer_group);

        //Breadcumbs array start
        $this->smarty->assign('breadcumb_title', 'Create franchise');
        $breadcumbs_array = array(
            array('title' => 'Contacts', 'url' => ''),
            array('title' => 'Franchise ', 'url' => ''),
            array('title' => 'Create franchise', 'url' => ''),
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->js = array('setting');
        $this->view->header_file = ['profile'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/franchise/create.tpl');
        $this->view->render('footer/profile');
    }

    /**
     * Save new franchise 
     */
    function franchisesave($ajax = null)
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/franchise/create");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateFranchiseSave();
            $hasErrors = $validator->fetchErrors();
            $franchise_email = $_POST['email'];
            if ($hasErrors == false) {
                $is_login = ($_POST['is_login'] == 1) ? 1 : 0;
                if ($is_login == 1) {
                    require_once MODEL . 'merchant/SubuserModel.php';
                    $submodel = new SubuserModel();
                    $_POST['email'] = $_POST['login_email'];
                    $space_position = strpos($_POST['contact_person_name'], ' ');
                    if ($space_position > 0) {
                        $_POST['first_name'] = substr($_POST['contact_person_name'], 0, $space_position);
                        $_POST['last_name'] = substr($_POST['contact_person_name'], $space_position);
                    } else {
                        $_POST['first_name'] = $_POST['contact_person_name'];
                        $_POST['last_name'] = ' ';
                    }
                    require_once CONTROLLER . 'merchant/Subuservalidator.php';
                    $validator = new Subuservalidator($submodel);
                    $validator->validateSubmerchantSave($this->user_id);
                    $hasErrors = $validator->fetchErrors();
                }
            }
            if ($hasErrors == false) {
                $status = 0;
                /* $settlements_to_franchise = $this->common->getRowValue('settlements_to_franchise', 'merchant_setting', 'merchant_id', $this->merchant_id);
                  if ($settlements_to_franchise == 0) {
                  $status = 1;
                  } else {
                  $status = 0;
                  } */

                $pg_vendor_id = '';
                $online_settlement = ($_POST['online_settlement'] == 1) ? 1 : 0;
                if ($online_settlement == 1) {
                    require_once CONTROLLER . 'merchant/Vendor.php';
                    $ven = new Vendor();
                    $_POST['vendor_name'] = $_POST['contact_person_name'];
                    $_POST['city'] = 'Na';
                    $_POST['state'] = 'Na';
                    $_POST['zipcode'] = '000000';
                    $res = $ven->savePGVendor(null, 'Franchise');
                    if ($res != false) {
                        if ($res['status'] == 1) {
                            $pg_vendor_id = $res['pg_vendor_id'];
                        } else {
                            $hasErrors = $res['errors'];
                        }
                    }
                }

                if ($hasErrors == false) {
                    $franchise_id = $this->model->createFranchise($pg_vendor_id, $this->merchant_id, $_POST['franchise_name'], $franchise_email, $_POST['email2'], $_POST['mobile'], $_POST['address'], $_POST['mobile2'], $_POST['contact_person_name'], $_POST['contact_person_name2'], $_POST['account_holder_name'], $_POST['account_number'], $_POST['bank_name'], $_POST['account_type'], $_POST['ifsc_code'], $_POST['pan'], $_POST['adhar_card'], $_POST['gst'], $status, $_POST, $this->user_id);
                    if ($is_login == 1) {
                        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $result = $submodel->savesubMerchant($this->user_id, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['mob_country_code'], $_POST['password'], $_POST['role'], $franchise_id, $_POST['group']);
                        if ($result['message'] == 'success') {
                            $this->common->genericupdate('franchise', 'has_login', 1, 'franchise_id', $franchise_id, $this->user_id);
                            require_once CONTROLLER . 'merchant/Subuser.php';
                            $subuser = new Subuser();
                            $subuser->sendMail($result['usertimestamp'], $_POST['email']);
                        } else {
                            SwipezLogger::error(__CLASS__, '[E289-54]Error while creating franchise login Error: ' . json_encode($result));
                            Sentry\captureMessage('Creating franchise login Error: ' . json_encode($result) . ' Merchant id:' . $this->merchant_id);
                        }
                    }

                    if ($_POST['food_franchise'] == 1) {
                        $this->saveFoodConfiguration($franchise_id);
                    }

                    if ($this->env == 'PROD') {
                        $toEmail_ = "sarang@swipez.in";
                        $emailWrapper = new EmailWrapper();
                        $subject = "Franchise added by merchant";
                        $body_message = "Company Name: " . $this->session->get('company_name') . " <br>Franchise name: " . $_POST['franchise_name'] . "<br>" . "Merchant ID: " . $this->merchant_id;
                        $emailWrapper->sendMail($toEmail_, "", $subject, $body_message, $body_message, $this->session->get('email_id'));
                    }
                    if ($ajax == null) {
                        $this->session->set('successMessage', 'Franchise details have been saved.');
                        header("Location:/merchant/franchise/viewlist");
                    } else {
                        $data['status'] = 1;
                        $data['id'] = $franchise_id;
                        $data['name'] = $_POST['franchise_name'];
                        echo json_encode($data);
                    }
                } else {
                    if ($ajax == null) {
                        $this->smarty->assign("haserrors", $hasErrors);
                        $this->smarty->assign("post", $_POST);
                        $this->create();
                    } else {
                        $data['status'] = 0;
                        $data['errors'] = $hasErrors;
                        echo json_encode($data);
                    }
                }
            } else {
                if ($ajax == null) {
                    $this->smarty->assign("haserrors", $hasErrors);
                    $this->smarty->assign("post", $_POST);
                    $this->create();
                } else {
                    $data['status'] = 0;
                    $data['errors'] = $hasErrors;
                    echo json_encode($data);
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E289]Error while creating franchise Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function saveFoodConfiguration($franchise_id)
    {
        $names = $this->generic->getLastName($_POST['contact_person_name']);
        require_once MODEL . 'merchant/CustomerModel.php';
        $customer_model = new CustomerModel();
        if ($_POST['customer_id'] > 0) {
            $customer_id = $_POST['customer_id'];
            $this->model->updateFranchiseCustomer($_POST['customer_id'], $names['first_name'], $names['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], $this->user_id);
        } else {
            $response = $customer_model->saveCustomer($this->user_id, $this->merchant_id, $_POST['franchise_code'], $names['first_name'], $names['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], '', '', '', '', '', '');
            $customer_id = $response['customer_id'];
        }
        $_POST['non_brand_franchise_fee_comm'] = ($_POST['non_brand_franchise_fee_comm'] > 0) ? $_POST['non_brand_franchise_fee_comm'] : 0;
        $_POST['non_brand_franchise_fee_waiver'] = ($_POST['non_brand_franchise_fee_waiver'] > 0) ? $_POST['non_brand_franchise_fee_waiver'] : 0;
        $this->model->updateSaleConfig($franchise_id, $_POST['franchise_code'], $_POST['franchise_fee_comm'], $_POST['franchise_fee_waiver'], $_POST['non_brand_franchise_fee_comm'], $_POST['non_brand_franchise_fee_waiver'], $_POST['due_penalty'], $_POST['min_penalty'], $_POST['default_sale'], $customer_id, $this->user_id);
    }

    function savePGFranchise()
    {
        $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $this->merchant_id, 1);
        if (!empty($security_key)) {
            $vendor = $this->common->querysingle("select generate_sequence('PG_vendor_id') as vendor_id");
            $pg_vendor_id = $vendor['vendor_id'];
            require_once UTIL . 'CashfreeAPI.php';
            $cashfree = new CashfreeAPI($security_key['cashfree_client_id'], $security_key['cashfree_client_secret']);
            $data['vendorId'] = $pg_vendor_id;
            $data['name'] = $_POST['franchise_name'];
            $data['email'] = $_POST['email'];
            $data['phone'] = $_POST['mobile'];
            $data['commission'] = "0";
            $data['bankAccount'] = $_POST['account_number'];
            $data['accountHolder'] = $_POST['account_holder_name'];
            $data['ifsc'] = $_POST['ifsc_code'];
            $data['panNo'] = '';
            $data['aadharNo'] = '';
            $data['gstin'] = '';
            $data['address1'] = $_POST['address'];
            $data['address2'] = '';
            $data['city'] = '';
            $data['state'] = '';
            $data['pincode'] = '';
            $response = $cashfree->saveVendor(json_encode($data));
            SwipezLogger::info(__CLASS__, 'Cashfree Response: ' . json_encode($response));
            if ($response['status'] == 0) {
                SwipezLogger::error(__CLASS__, '[Ev001]Error from cashfree vendor save Error: ' . $response['error']);
                Sentry\captureMessage('Error from cashfree vendor save Error: ' . $response['error'] . ' Merchant id:' . $this->merchant_id);
                $hasErrors['PG'] = array('PG', $response['error']);
            } else {
                if ($response['response']['status'] != 'SUCCESS') {
                    $hasErrors['PG'] = array('PG', $response['response']['message']);
                }
            }
            if ($hasErrors == FALSE) {
                $array['status'] = 1;
                $array['pg_vendor_id'] = $pg_vendor_id;
            } else {
                $array['status'] = 0;
                $array['errors'] = $hasErrors;
            }
            return $array;
        } else {
            return false;
        }
    }

    function pendingsettlement()
    {
        try {
            $this->hasRole(2, 22);
            $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $this->merchant_id, 1);
            if (!empty($security_key)) {
                require_once UTIL . 'CashfreeAPI.php';
                $cashfree = new CashfreeAPI($security_key['cashfree_client_id'], $security_key['cashfree_client_secret']);
                $response = $cashfree->getLedgerBalance();
                if (isset($response['response']['balance'])) {
                    $ledger_balance = $response['response']['balance'];
                } else {
                    SwipezLogger::error(__CLASS__, 'Cashfree response Get ledger balance : ' . json_encode($response) . ' Merchant id:' . $this->merchant_id);
                    Sentry\captureMessage('Cashfree response Get ledger balance : ' . json_encode($response) . ' Merchant id:' . $this->merchant_id);
                }
            }
            $time = time();
            $this->session->set('post_key', $time);
            $franchise_list = $this->common->getListValue('franchise', 'merchant_id', $this->merchant_id, 1, ' and status=1 and online_pg_settlement=1');
            $vendor_list = $this->common->getListValue('vendor', 'merchant_id', $this->merchant_id, 1, ' and status=1 and online_pg_settlement=1');
            $list = $this->model->getPendingSettlement($this->merchant_id);
            $total_settlement_amount = 0;
            foreach ($list as $l) {
                $total_settlement_amount = $total_settlement_amount + $l['settlement_amount'];
            }

            $max_amount = $ledger_balance;

            $cashfree_transfer_charge = $this->common->getRowValue('config_value', 'config', 'config_type', 'cashfree_transfer_charge');
            $this->view->selectedMenu = array(131, 4, 26);
            $this->smarty->assign("franchise_list", $franchise_list);
            $this->smarty->assign("vendor_list", $vendor_list);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("cashfree_transfer_charge", $cashfree_transfer_charge);
            $this->smarty->assign("ledger_balance", $ledger_balance);
            $this->smarty->assign("max_amount", $max_amount);
            $this->smarty->assign("post_key", $time);
            $this->smarty->assign("title", "Pending settlement");
            $this->view->title = "Pending settlement";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Payout', 'url' => ''),
                array('title' => 'Split payouts ', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end
            $this->view->js = array('transaction');
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/franchise/settlement.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E289]Error while custom settlement: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function nodalledger()
    {
        try {
            $this->hasRole(1, 22);
            $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $this->merchant_id, 1);
            if (!empty($security_key)) {
                require_once UTIL . 'CashfreeAPI.php';
                $cashfree = new CashfreeAPI($security_key['cashfree_client_id'], $security_key['cashfree_client_secret']);
                $response = $cashfree->getLedgerBalance();
                if (isset($response['response']['balance'])) {
                    $ledger_balance = $response['response']['balance'];
                } else {
                    SwipezLogger::error(__CLASS__, 'Cashfree response Get ledger balance : ' . json_encode($response));
                    Sentry\captureMessage('Cashfree response Get ledger balance : ' . json_encode($response) . ' Merchant id:' . $this->merchant_id);
                }
                $maxReturn = 200;
                $lastReturnId = null;
                $run = 1;
                $statements = array();
                while ($run == 1) {
                    $response = $cashfree->getLedgerStatement($maxReturn, $lastReturnId);
                    if (isset($response['response']['ledger'])) {
                        if (count($response['response']['ledger']) == $maxReturn) {
                            $lastReturnId = $response['response']['lastId'];
                            $run = 1;
                        } else {
                            $run = 0;
                        }
                        $statements = array_merge($statements, $response['response']['ledger']);
                    } else {
                        SwipezLogger::error(__CLASS__, 'Cashfree response Get ledger Statement : ' . json_encode($response));
                        Sentry\captureMessage('Cashfree response Get ledger Statement : ' . json_encode($response) . ' Merchant id:' . $this->merchant_id);
                        $run = 0;
                    }
                }
            }
            $flist = $this->common->getListValue('franchise', 'merchant_id', $this->merchant_id, 0, " and pg_vendor_id is not null and pg_vendor_id<>''");
            $vlist = $this->common->getListValue('vendor', 'merchant_id', $this->merchant_id, 0, " and pg_vendor_id is not null and pg_vendor_id<>''");
            $vendor_list = array();
            $franchise_list = array();
            foreach ($flist as $l) {
                $franchise_list[$l['pg_vendor_id']] = array('name' => $l['franchise_name'], 'id' => $l['franchise_id']);
            }
            foreach ($vlist as $l) {
                $vendor_list[$l['pg_vendor_id']] = array('name' => $l['vendor_name'], 'id' => $l['vendor_id']);
            }

            foreach ($statements as $key => $row) {
                if ($row['particulars'] == 'VENDOR_PAYOUT') {
                    $vendor_id = substr($statements[$key]['remarks'], 8, 10);
                    if (isset($vendor_list[$vendor_id])) {
                        $statements[$key]['remarks'] = 'Vendor: ' . $vendor_id . ' [' . $vendor_list[$vendor_id]['id'] . '] - ' . $vendor_list[$vendor_id]['name'];
                    }
                    if (isset($franchise_list[$vendor_id])) {
                        $statements[$key]['remarks'] = 'Franchise: ' . $vendor_id . ' [' . $franchise_list[$vendor_id]['id'] . '] - ' . $franchise_list[$vendor_id]['name'];
                    }
                }
            }
            if (isset($_POST['export']) && !empty($statements)) {
                $this->common->excelexport('Ledger-statement', $statements);
            }

            $int = 0;
            foreach ($statements as $row) {
                $statements[$int]['amount'] = $this->moneyFormatIndia($row['amount']);
                $statements[$int]['closingBalance'] = $this->moneyFormatIndia($row['closingBalance']);
                $int++;
            }
            $this->view->selectedMenu = array(131, 4, 27);
            $this->smarty->assign("ledger_balance", $ledger_balance);
            $this->smarty->assign("statements", $statements);
            $this->smarty->assign("title", "Nodal ledger statements");
            $this->view->title = "Nodal ledger statements";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Payout', 'url' => ''),
                array('title' => 'Split payouts ', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/franchise/nodalledger.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E289]Error while custom settlement: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function savesettlement()
    {
        if ($this->session->get('post_key') != $_POST['post_key']) {
            header("Location:/merchant/franchise/pendingsettlement", 301);
            die();
        }
        require_once CONTROLLER . 'merchant/Vendor.php';
        $vendor = new Vendor();
        require_once MODEL . 'merchant/VendorModel.php';
        $vendorModel = new VendorModel();
        $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $this->merchant_id, 1);
        require_once UTIL . 'CashfreeAPI.php';
        $cashfree = new CashfreeAPI($security_key['cashfree_client_id'], $security_key['cashfree_client_secret']);
        $withdraw_amount = 0;
        foreach ($_POST['settlement_id'] as $key => $id) {
            if ($_POST['beneficiary_ids'][$key] == '0') {
                $withdraw_amount = $withdraw_amount + $_POST['request_amount'][$key];
                if ($id > 0) {
                    $this->common->queryexecute("update settlement_detail set franchise_id=0,vendor_id=0,requested_settlement_amount='" . $_POST['request_amount'][$key] . "', status=1,bank_reference='NA' where id=" . $id);
                } else {
                    $this->model->insertBespokeSettlemet($this->merchant_id, 0, 0, $this->session->get('company_name'), 'NA', date('Y-m-d'), $_POST['request_amount'][$key], 0);
                }
            } else {
                $benef_text = $_POST['beneficiary_ids'][$key];
                $benef_id = substr($benef_text, 1);
                if (substr($benef_text, 0, 1) == 'v') {
                    $column_name = 'vendor_id';
                    $_POST['vendor_id'] = $benef_id;
                    $_POST['franchise_id'] = 'no';
                } elseif (substr($benef_text, 0, 1) == 'f') {
                    $column_name = 'franchise_id';
                    $_POST['vendor_id'] = 'no';
                    $_POST['franchise_id'] = $benef_id;
                }

                $_POST['amount'] = $_POST['request_amount'][$key];
                $_POST['narrative'] = 'Payment settlement';
                $response = $vendor->vendorTransfer($vendorModel);
                if ($response['status'] == 0) {
                    $hasErrors[$id] = $response['response']['message'];
                } else {
                    $cashfree_transfer_id = $response['response']['vendorTransferId'];
                    if ($_POST['franchise_id'] == 'no') {
                        $_POST['franchise_id'] = 0;
                        $_POST['transfer_type'] = 1;
                    }
                    if ($_POST['vendor_id'] == 'no') {
                        $_POST['vendor_id'] = 0;
                        $_POST['transfer_type'] = 2;
                    }
                    $_POST['narrative'] = 'Settlement';
                    $vendorModel->saveTransfer(1, $this->merchant_id, $_POST, $cashfree_transfer_id, $this->user_id);
                    if ($id > 0) {
                        $this->common->queryexecute("update settlement_detail set " . $column_name . "=" . $benef_id . ",requested_settlement_amount='" . $_POST['amount'] . "',status=0,cashfree_transfer_id='" . $cashfree_transfer_id . "' where id=" . $id);
                    } else {
                        $this->model->insertBespokeSettlemet($this->merchant_id, $_POST['franchise_id'], $_POST['vendor_id'], $this->session->get('company_name'), '', date('Y-m-d'), $_POST['amount'], $cashfree_transfer_id);
                    }
                }
            }
        }
        if ($withdraw_amount > 0) {
            $response = $cashfree->withdrawAmount($withdraw_amount, 'Payment settlement');
            SwipezLogger::info(__CLASS__, 'Cashfree Response[V1] : ' . json_encode($response));
            if ($response['status'] == 0) {
                $hasErrors['Withdraw'] = $response['error'];
            } else {
                if ($response['response']['status'] != 'SUCCESS') {
                    $hasErrors['Withdraw'] = $response['response']['message'];
                }
            }
        }
        if ($hasErrors == FALSE) {
            if (!empty($_POST['settlement_id'])) {
                $this->session->set('successMessage', 'Settlement save successfully');
            }
            header("Location:/merchant/franchise/pendingsettlement");
            die();
        } else {
            $this->smarty->assign("haserrors", $hasErrors);
            $this->pendingsettlement();
        }
    }

    /**
     * Delete merchant franchise
     */
    function delete($franchise_id, $type = '')
    {
        try {
            $this->hasRole(3, 21);
            $franchise_id = $this->encrypt->decode($franchise_id);
            if (!is_numeric($franchise_id)) {
                $this->setInvalidLinkError();
            }
            if ($type == 'staging') {
                $this->common->genericupdate('staging_franchise', 'is_active', '0', 'franchise_id', $franchise_id, $this->user_id, " and merchant_id='" . $this->merchant_id . "'");
                $this->session->set('successMessage', 'Franchise have been deleted successfully.');
                header("Location:/merchant/franchise/bulkupload");
            } else {
                $this->common->genericupdate('franchise', 'is_active', '0', 'franchise_id', $franchise_id, $this->user_id, " and merchant_id='" . $this->merchant_id . "'");
                $this->session->set('successMessage', 'Franchise have been deleted successfully.');
                header("Location:/merchant/franchise/viewlist");
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E290]Error while deleting franchise Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Franchise update initiate
     */
    function update($link)
    {
        try {
            $this->hasRole(2, 21);
            $franchise_id = $this->encrypt->decode($link);
            if (!is_numeric($franchise_id)) {
                $this->setInvalidLinkError();
            }
            $franchisedetails = $this->common->getSingleValue('franchise', 'franchise_id', $franchise_id, 1, " and merchant_id='" . $this->merchant_id . "'");
            if (empty($franchisedetails)) {
                SwipezLogger::error(__CLASS__, '[E291]Error while update franchise profile fetching franchise details ID:' . $franchise_id);
                Sentry\captureMessage('Fetching franchise empty details ID:' . $franchise_id . ' Merchant id:' . $this->merchant_id);

                $this->setInvalidLinkError();
            }
            if ($franchisedetails['has_login'] == 0) {
                require_once MODEL . 'merchant/SubuserModel.php';
                $submodel = new SubuserModel();
                $list = $submodel->getRoleList($this->user_id);
                $customer_group = $this->common->getListValue('customer_group', 'merchant_id', $this->merchant_id, 1);
                $this->smarty->assign("customer_group", $customer_group);
                $int = 0;
                foreach ($list as $item) {
                    $list[$int]['functions'] = $submodel->getControllersName($item['view_controllers']);
                    $list[$int]['encrypted_id'] = $this->encrypt->encode($item['role_id']);
                    $int++;
                }
                $this->smarty->assign("roles", $list);

                $controllerlist = $submodel->getControllers();
                $this->smarty->assign("list", $controllerlist);
            }
            $food_franchise_mids = explode(',', env('FOOD_FRANCHISE_MERCHANT_ID'));
            if (in_array($this->merchant_id, $food_franchise_mids)) {
                $this->smarty->assign("food_franchise", true);
            }
            $food_franchise_mids = explode(',', env('FOOD_FRANCHISE_NON_BRAND'));
            if (in_array($this->merchant_id, $food_franchise_mids)) {
                $this->smarty->assign("non_brand_food_franchise", true);
            }
            $this->view->js = array('setting');
            $this->view->selectedMenu = array(2, 17, 78);
            $this->smarty->assign("enable_online_settlement", $this->onlineSettlementStatus($this->merchant_id));
            $this->smarty->assign("det", $franchisedetails);
            $this->view->title = 'Update franchise';
            $this->smarty->assign('title', $this->view->title);
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Contacts', 'url' => ''),
                array('title' => 'Franchise ', 'url' => ''),
                array('title' => 'Franchise list', 'url' => '/merchant/franchise/viewlist'),
                array('title' => 'Update franchise', 'url' => '/merchant/franchise/update/' . $link),
                array('title' => $franchisedetails['franchise_name'], 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->smarty->display(VIEW . 'merchant/franchise/update.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E292]Error while updating franchise Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Franchise update initiate
     */
    function bulkupdate($link)
    {
        try {
            $this->hasRole(2, 21);
            $franchise_id = $this->encrypt->decode($link);
            if (!is_numeric($franchise_id)) {
                $this->setInvalidLinkError();
            }
            $franchisedetails = $this->common->getSingleValue('staging_franchise', 'franchise_id', $franchise_id, 1, " and merchant_id='" . $this->merchant_id . "'");
            if (empty($franchisedetails)) {
                SwipezLogger::error(__CLASS__, '[E291]Error while update franchise profile fetching franchise details ID:' . $franchise_id);
                Sentry\captureMessage('Fetching franchise empty details ID:' . $franchise_id . ' Merchant id:' . $this->merchant_id);
                $this->setInvalidLinkError();
            }

            $this->view->js = array('setting');
            $this->smarty->assign("enable_online_settlement", $this->onlineSettlementStatus($this->merchant_id));
            $this->smarty->assign("det", $franchisedetails);
            $this->view->title = 'Update franchise';
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/franchise/bulkupdate.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E292]Error while updating franchise Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Update save franchise
     */
    function saveupdate()
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/franchise/viewlist");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateFranchiseSave();
            $hasErrors = $validator->fetchErrors();
            $franchise_id = $_POST['franchise_id'];

            $franchise_email = $_POST['email'];
            if ($hasErrors == false) {
                $is_login = ($_POST['is_login'] == 1) ? 1 : 0;
                if ($is_login == 1) {
                    require_once MODEL . 'merchant/SubuserModel.php';
                    $submodel = new SubuserModel();
                    $_POST['email'] = $_POST['login_email'];
                    $space_position = strpos($_POST['contact_person_name'], ' ');
                    if ($space_position > 0) {
                        $_POST['first_name'] = substr($_POST['contact_person_name'], 0, $space_position);
                        $_POST['last_name'] = substr($_POST['contact_person_name'], $space_position);
                    } else {
                        $_POST['first_name'] = $_POST['contact_person_name'];
                        $_POST['last_name'] = ' ';
                    }
                    require_once CONTROLLER . 'merchant/Subuservalidator.php';
                    $validator = new Subuservalidator($submodel);
                    $validator->validateSubmerchantSave($this->user_id);
                    $hasErrors = $validator->fetchErrors();
                }
            }

            if ($hasErrors == false) {
                $pg_vendor_id = '';
                $online_settlement = ($_POST['online_settlement'] == 1) ? 1 : 0;
                $v_detail = $this->common->getSingleValue('franchise', 'franchise_id', $franchise_id);
                $pg_vendor_id = $v_detail['pg_vendor_id'];
                if ($online_settlement == 1 && $v_detail['online_pg_settlement'] == 0) {
                    require_once CONTROLLER . 'merchant/Vendor.php';
                    $ven = new Vendor();
                    $_POST['vendor_name'] = $_POST['contact_person_name'];
                    $_POST['city'] = 'Na';
                    $_POST['state'] = 'Na';
                    $_POST['zipcode'] = '000000';
                    $res = $ven->savePGVendor(null, 'Franchise');
                    if ($res != false) {
                        if ($res['status'] == 1) {
                            $pg_vendor_id = $res['pg_vendor_id'];
                        } else {
                            $hasErrors = $res['errors'];
                        }
                    }
                }

                if ($hasErrors == false) {
                    $this->model->updateFranchise($franchise_id, $pg_vendor_id, $_POST, $_POST['franchise_name'], $franchise_email, $_POST['email2'], $_POST['mobile'], $_POST['address'], $_POST['mobile2'], $_POST['contact_person_name'], $_POST['contact_person_name2'], $_POST['account_holder_name'], $_POST['account_number'], $_POST['bank_name'], $_POST['account_type'], $_POST['ifsc_code'], $_POST['pan'], $_POST['adhar_card'], $_POST['gst'], $this->user_id);
                    if ($is_login == 1) {
                        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $result = $submodel->savesubMerchant($this->user_id, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['mob_country_code'], $_POST['password'], $_POST['role'], $franchise_id, $_POST['group']);
                        if ($result['message'] == 'success') {
                            $this->common->genericupdate('franchise', 'has_login', 1, 'franchise_id', $franchise_id, $this->user_id);
                            require_once CONTROLLER . 'merchant/Subuser.php';
                            $subuser = new Subuser();
                            $subuser->sendMail($result['usertimestamp'], $_POST['email']);
                        } else {
                            SwipezLogger::error(__CLASS__, '[E289-54]Error while creating franchise login Error: ' . json_encode($result));
                            Sentry\captureMessage('Creating franchise login Error: ' . json_encode($result) . ' Merchant id:' . $this->merchant_id);
                        }
                    }
                    if ($_POST['food_franchise'] == 1) {
                        $this->saveFoodConfiguration($franchise_id);
                    }
                    $this->session->set('successMessage', 'Franchise details have been updated.');
                    header("Location:/merchant/franchise/viewlist");
                    die();
                }
            }
            if ($hasErrors != false) {
                $this->view->title = 'Franchise Update';
                $this->view->_POST = $_POST;
                $this->view->setError($hasErrors);
                $this->smarty->assign("haserrors", $hasErrors);
                $link = $this->encrypt->encode($franchise_id);
                $this->update($link);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E293]Error while updating franchise Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Update save franchise
     */
    function savebulkupdate()
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/franchise/viewlist");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateFranchiseSave();
            $hasErrors = $validator->fetchErrors();
            $franchise_id = $_POST['franchise_id'];

            $franchise_email = $_POST['email'];
            if ($hasErrors == false) {
                $pg_vendor_id = '';
                $online_settlement = ($_POST['online_settlement'] == 1) ? 1 : 0;
                $this->model->updateStagingFranchise($franchise_id, $_POST['franchise_name'], $franchise_email, $_POST['email2'], $_POST['mobile'], $_POST['address'], $_POST['mobile2'], $_POST['contact_person_name'], $_POST['contact_person_name2'], $_POST['account_holder_name'], $_POST['account_number'], $_POST['bank_name'], $_POST['account_type'], $_POST['ifsc_code'], $this->user_id);
                $this->session->set('successMessage', 'Franchise details have been updated.');
                header("Location:/merchant/franchise/bulkupload");
            } else {
                $this->view->title = 'Franchise Update';
                $this->view->_POST = $_POST;
                $this->view->setError($hasErrors);
                $this->smarty->assign("haserrors", $hasErrors);
                $link = $this->encrypt->encode($franchise_id);
                $this->bulkupdate($link);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E293]Error while updating franchise Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function download()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $link = $this->encrypt->encode($merchant_id);
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("swipez")
                ->setLastModifiedBy("swipez")
                ->setTitle("swipez_franchise")
                ->setSubject($link)
                ->setDescription("swipez franchise");
            #create array of excel column
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
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Franchise name');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Person name');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Email ID');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Mobile No');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Address');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Contact #2 name');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Contact #2 email');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Contact #2 mobile');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Account holder name');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Account number');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Bank name');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Account Type(Current/Saving)');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'IFSC Code');
            $int++;
            if ($this->onlineSettlementStatus($this->merchant_id) == 1) {
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Online settlement via Swipez (Yes/No)');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Commision type (Percentage/Fixed)');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Commision');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Settlement type (Automatic/Manual)');
                $int++;
            }
            $objPHPExcel->getActiveSheet()->setTitle('Franchise structure');

            $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')
                ->setSize(10);

            $objPHPExcel->getActiveSheet()->getStyle('A1:' . $column[$int - 1])->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'AAAADD')
                    )
                )
            );
            $int++;
            $autosize = 0;
            while ($autosize < $int) {
                $objPHPExcel->getActiveSheet()->getColumnDimension(substr($column[$autosize], 0, -1))->setAutoSize(true);
                $autosize++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="franchise_structure' . time() . '.xlsx"');
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

    function bulkupload()
    {
        try {
            $this->hasRole(2, 21);
            $type_ = 6;
            require_once MODEL . 'merchant/VendorModel.php';
            $vendorModel = new VendorModel();
            $this->smarty->assign("type", "franchise");
            $this->smarty->assign("type_", $type_);
            $list = $vendorModel->getBulkuploadList($this->merchant_id, $type_);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['bulk_id'] = $this->encrypt->encode($item['bulk_upload_id']);
                $int++;
            }
            $this->view->hide_first_col = true;
            $this->smarty->assign("bulklist", $list);
            $this->view->datatablejs = 'table-small-no-export';
            $this->view->selectedMenu = array(2, 17, 79);
            $this->view->title = 'Bulk upload franchise';
            $this->smarty->assign('title', $this->view->title);
            $this->view->header_file = ['bulkupload'];

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Contacts', 'url' => ''),
                array('title' => 'Franchise ', 'url' => ''),
                array('title' => 'Bulk upload franchise', 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/franchise/upload.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC001]Error while vendor bulkupload Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    public function upload()
    {
        try {
            $this->hasRole(2, 21);
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
                    $this->franchisebulk_upload($_FILES["fileupload"]);
                } else {
                    $this->smarty->assign("hasErrors", $hasErrors);
                    $this->bulkupload($_POST['type']);
                }
            } else {
                header('Location: /merchant/franchise/bulkupload');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E270]Error while bulk upload submit Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
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
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E272]Error while bulk upload error Error:for merchant [' . $merchant_id . '] and  bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function franchisebulk_upload($inputFile, $bulk_upload_id = NULL)
    {
        try {
            if ($bulk_upload_id == NULL) {
                $File = $inputFile['tmp_name'];
            } else {
                $File = $inputFile;
            }
            $merchant_id = $this->session->get('merchant_id');
            $inputFileType = PHPExcel_IOFactory::identify($File);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($File);
            $subject = $objPHPExcel->getProperties()->getSubject();
            $link = $this->encrypt->decode($subject);
            $merchant_id_ = substr($link, 0, 10);
            $worksheet = $objPHPExcel->getSheet(0);
            $worksheetTitle = $worksheet->getTitle();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;
            $errors = array();
            $is_upload = TRUE;
            $templateinfo = array();
            require_once(MODEL . 'merchant/BulkuploadModel.php');
            $bulkupload = new BulkuploadModel();
            if ($merchant_id_ == $merchant_id && substr($worksheetTitle, 0, 9) == 'Franchise') {
            } else {
                $errors[0][0] = 'Invalid excel sheet';
                $errors[0][1] = 'Download again excel Franchise bulkupload and re-up load with Franchise data.';
                $is_upload = FALSE;
            }
            $getcolumnvalue = array();
            if (empty($errors)) {
                for ($rowno = 2; $rowno <= $highestRow; ++$rowno) {
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

                        $post_row['franchise_name'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['contact_person_name'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['email'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['mobile'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['address'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['contact_person_name2'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['email2'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['mobile2'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['account_holder_name'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['account_number'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['bank_name'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['account_type'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['ifsc_code'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        if ($this->onlineSettlementStatus($this->merchant_id) == 1) {
                            $val = (string) $getcolumnvalue[$rowno][$int];
                            if ($val == '' || strtolower($val) == 'yes' || strtolower($val) == 'no') {
                                $post_row['online_settlement'] = (strtolower($val) == 'yes') ? 1 : 0;
                            } else {
                                $post_row['online_settlement'] = 2;
                            }
                            $int++;
                            $val = (string) $getcolumnvalue[$rowno][$int];
                            if ($val == '' || strtolower($val) == 'percentage' || strtolower($val) == 'fixed') {
                                $post_row['commision_type'] = (strtolower($val) == 'percentage') ? 1 : 2;
                            } else {
                                $post_row['commision_type'] = 3;
                            }
                            $int++;
                            if ($post_row['commision_type'] == 1) {
                                $post_row['commision_percent'] = (string) $getcolumnvalue[$rowno][$int];
                                $post_row['commision_amount'] = 0;
                            } else if ($post_row['commision_type'] == 2) {
                                $post_row['commision_amount'] = (string) $getcolumnvalue[$rowno][$int];
                                $post_row['commision_percent'] = 0;
                            } else {
                                $post_row['commision_amount'] = 0;
                                $post_row['commision_percent'] = 0;
                            }
                            $int++;
                            $val = (string) $getcolumnvalue[$rowno][$int];
                            if ($val == '' || strtolower($val) == 'automatic' || strtolower($val) == 'manual') {
                                $post_row['settlement_type'] = (strtolower($val) == 'manual') ? 1 : 2;
                            } else {
                                $post_row['settlement_type'] = 3;
                            }
                            $int++;
                        } else {
                            $post_row['settlement_type'] = 0;
                            $post_row['commision_amount'] = 0;
                            $post_row['commision_percent'] = 0;
                            $post_row['commision_type'] = 0;
                            $post_row['online_settlement'] = 0;
                        }
                        $_POSTarray[] = $post_row;
                    }
                }
            }
            $rows_count = count($_POSTarray);
            $errorrow = 2;
            try {
                if (empty($_POSTarray) && empty($errors)) {
                    $is_upload = FALSE;
                    $errors[0][0] = 'Empty excel';
                    $errors[0][1] = 'Uploaded excel does not contain any vendor.';
                }
                if (empty($errors)) {
                    foreach ($_POSTarray as $_POST) {
                        $result = $this->validateuploadfranchise();
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

                SwipezLogger::error(__CLASS__, '[E275]Error while validating excel Error: for merchant [' . $merchant_id . '] and ' . $e->getMessage());
            }
            if (empty($errors) && $bulk_upload_id == NULL) {
                $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), FALSE, $rows_count, 6);
                $this->session->set('successMessage', '  Excel sheet uploaded.');
                header('Location: /merchant/franchise/bulkupload');
                die();
            } else {
                if ($is_upload == TRUE && $bulk_upload_id == NULL) {
                    $bulk_id = $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), TRUE, 0, 6);
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
            $this->hasRole(2, 21);
            $merchant_id = $this->session->get('merchant_id');
            if ($bulk_id != '') {
                $this->smarty->assign("type", 'franchise');
                $this->smarty->assign("bulk_id", $bulk_id);
                $this->view->title = 'Re-upload franchise';
                $this->view->selectedMenu = array(2, 17, 79);
                $this->view->canonical = 'merchant/bulkupload/error/';
                $this->view->header_file = ['bulkupload'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/franchise/reupload.tpl');
                $this->view->render('footer/bulkupload');
            } else {
                header('Location: /merchant/franchise/bulkupload');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E269]Error while re-upload Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function validateuploadfranchise()
    {
        try {
            require_once CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateBulkFranchiseSave($this->merchant_id);
            $hasErrors = $validator->fetchErrors();

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
}
