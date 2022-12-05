<?php

/**
 * Franchise controller class to handle Merchants franchise
 */
class Payout extends Controller {

    function __construct() {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->selectedMenu = array(2, 16);
        $enable_payout = $this->onlineSettlementStatus($this->merchant_id);
        if ($enable_payout == 0) {
            $this->setError('Access denied', 'You do not have access to this feature. If you need access to this feature please contact Swipez support.');
            header("Location:/error");
        }
    }

    /**
     * Display merchant franchises list
     */
    function viewlist($bulk_id = NULL) {
        try {
            $this->hasRole(1, 20);
            if ($bulk_id != NULL) {
                $bulk_id = $this->encrypt->decode($bulk_id);
                $list = $this->common->getListValue('vendor', 'merchant_id', $this->merchant_id, 1, ' and bulk_id=' . $bulk_id);
            } else {
                $list = $this->common->getListValue('vendor', 'merchant_id', $this->merchant_id, 1);
            }

            if (isset($_POST['export'])) {
                foreach ($list as $k => $v) {
                    $vlist[$k]['vendor_id'] = $v['vendor_id'];
                    $vlist[$k]['vendor_name'] = $v['vendor_name'];
                    $vlist[$k]['online_pg_settlement'] = ($v['online_pg_settlement'] == 1) ? 'Yes' : 'No';
                    $vlist[$k]['email_id'] = $v['email_id'];
                    $vlist[$k]['mobile'] = $v['mobile'];
                    $vlist[$k]['pan'] = $v['pan'];
                    $vlist[$k]['adhar_card'] = $v['adhar_card'];
                    $vlist[$k]['gst_number'] = $v['gst_number'];
                    $vlist[$k]['address'] = $v['address'];
                    $vlist[$k]['city'] = $v['city'];
                    $vlist[$k]['state'] = $v['state'];
                    $vlist[$k]['zipcode'] = $v['zipcode'];
                    $vlist[$k]['bank_holder_name'] = $v['bank_holder_name'];
                    $vlist[$k]['bank_account_no'] = "'" . $v['bank_account_no'] . "'";
                    $vlist[$k]['bank_name'] = $v['bank_name'];
                    $vlist[$k]['account_type'] = $v['account_type'];
                    $vlist[$k]['ifsc_code'] = $v['ifsc_code'];
                    $vlist[$k]['status'] = ($v['status'] == 1) ? 'Activated' : 'To be activated';
                }
                $this->common->excelexport('VendorList', $vlist);
            }

            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['vendor_id']);
                $int++;
            }
            $this->view->selectedMenu = array(2, 16, 75);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Vendor list");
            $this->view->title = "Vendor list";
            $this->view->canonical = 'merchant/vendor/viewlist';
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
        $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/vendor/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function bulklist($type, $bulk_id = NULL) {
        try {
            $this->hasRole(1, 20);
            $bulk_id = $this->encrypt->decode($bulk_id);
            if ($type == 'vendor') {
                $_type = 'vendor';
                $list = $this->common->getListValue('staging_' . $_type, 'bulk_id', $bulk_id);
                $this->view->selectedMenu = array(2, 16, 75);
            } else {
                $_type = 'vendor_transfer';
                $list = $this->model->getBulkTransferList($this->merchant_id, $bulk_id, 'staging_vendor_transfer');
            }
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item[$type . '_id']);
                $int++;
            }
            $this->view->selectedMenu = 'bulk_' . $type;
            $this->smarty->assign("type", $type);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Bulk " . $type . " list");
            $this->view->title = $type . " list";
            $this->view->canonical = 'merchant/vendor/viewlist';
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
        $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/vendor/bulk' . $type . 'list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function transferlist($link = null) {
        try {
            $this->hasRole(1, 22);
            $last_date = $this->getLast_date();
            $current_date = date('d M Y');
            $bulk_id = 0;
            if ($link != null) {
                $bulk_id = $this->encrypt->decode($link);
            }
            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $bulk_id = $_POST['bulk_id'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
            }
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);

            $bulk_list = $this->common->getListValue('bulk_upload', 'merchant_id', $this->merchant_id, 0, ' and status=5 and type=4');

            $this->view->selectedMenu = array(4, 24);
            $list = $this->model->getTransferList($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $bulk_id);
            $this->smarty->assign("enable_online_settlement", $this->onlineSettlementStatus($this->merchant_id));
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date) );
            $this->smarty->assign("to_date",  $this->generic->formatDateString($from_date) );
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $this->smarty->assign("bulk_id", $bulk_id);
            $this->smarty->assign("bulk_list", $bulk_list);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Transfer list");
            $this->view->title = "Transfer list";
            $this->view->canonical = 'merchant/vendor/transferlist';
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
        $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/vendor/transferlist.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor transfer Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function onlineSettlementStatus($merchant_id) {
        $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $merchant_id, 1);
        $enable_online_settlement = 0;
        if (!empty($security_key)) {
            if ($security_key['payout_client_id'] != '') {
                $enable_online_settlement = 1;
            }
        }
        return $enable_online_settlement;
    }

    /**
     * Create new franchise for merchant
     */
    function create() {
        $this->hasRole(2, 20);
        $this->view->title = 'Create Beneficiary';
        $this->view->selectedMenu = array(2, 16, 74);
        $this->view->js = array('setting');
        $this->view->header_file = ['profile'];
$this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/payout/create.tpl');
        $this->view->render('footer/profile');
    }

    /**
     * Save new vendor 
     */
    function beneficiarysave() {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/vendor/create");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateVendorSave($this->merchant_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $status = 1;
                $pg_vendor_id = '';
                $online_settlement = ($_POST['online_settlement'] == 1) ? 1 : 0;
                if ($online_settlement == 1) {
                    $res = $this->savePGVendor();
                    if ($res != false) {
                        if ($res['status'] == 1) {
                            $pg_vendor_id = $res['pg_vendor_id'];
                        } else {
                            $hasErrors = $res['errors'];
                        }
                    }
                } else {
                    $status = 1;
                }
                if ($hasErrors == false) {
                    $this->model->createVendor($pg_vendor_id, $this->merchant_id, $_POST, $status, $this->user_id);
                    $this->session->set('successMessage', 'Vendor details have been saved.');
                    header("Location:/merchant/vendor/viewlist");
                } else {
                    $this->smarty->assign("haserrors", $hasErrors);
                    $this->smarty->assign("post", $_POST);
                    $this->create();
                }
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->create();
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E289]Error while creating vendor Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function savePGVendor() {
        $pg_vendor_id = '';
        $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $this->merchant_id, 1);
        if (!empty($security_key)) {
            $vendor = $this->common->querysingle("select generate_sequence('PG_vendor_id') as vendor_id");
            $pg_vendor_id = $vendor['vendor_id'];
            require_once UTIL . 'CashfreeAPI.php';
            $cashfree = new CashfreeAPI($security_key['cashfree_client_id'], $security_key['cashfree_client_secret']);
            $data['vendorId'] = $pg_vendor_id;
            $data['name'] = $_POST['vendor_name'];
            $data['email'] = $_POST['email'];
            $data['phone'] = $_POST['mobile'];
            $data['commission'] = "0";
            $data['bankAccount'] = $_POST['account_number'];
            $data['accountHolder'] = $_POST['account_holder_name'];
            $data['ifsc'] = $_POST['ifsc_code'];
            $data['panNo'] = $_POST['pan'];
            $data['aadharNo'] = $_POST['adhar_card'];
            $data['gstin'] = $_POST['gst'];
            $data['address1'] = $_POST['address'];
            $data['address2'] = '';
            $data['city'] = $_POST['city'];
            $data['state'] = $_POST['state'];
            $data['pincode'] = $_POST['zipcode'];
            $response = $cashfree->saveVendor(json_encode($data));
            SwipezLogger::info(__CLASS__, 'Cashfree Response: ' . json_encode($response));
            if ($response['status'] == 0) {
SwipezLogger::error(__CLASS__, '[Ev001]Error from cashfree vendor save Error: ' . $response['error']);
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

    /**
     * Delete merchant franchise
     */
    function delete($id, $type = '') {
        try {
            $this->hasRole(3, 20);
            $id = $this->encrypt->decode($id);
            if (!is_numeric($id)) {
                $this->setInvalidLinkError();
            }
            if ($type == 'staging') {
                $table = 'staging_vendor';
            } else {
                $table = 'vendor';
            }
            $this->common->genericupdate($table, 'is_active', '0', 'vendor_id', $id, $this->user_id, " and merchant_id='" . $this->merchant_id . "'");
            $this->session->set('successMessage', 'Vendor have been deleted successfully.');
            header("Location:/merchant/vendor/viewlist");
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E290]Error while deleting vendor Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Franchise update initiate
     */
    function update($link) {
        try {
            $this->hasRole(2, 20);
            $id = $this->encrypt->decode($link);
            if (!is_numeric($id)) {
                $this->setInvalidLinkError();
            }
            $details = $this->common->getSingleValue('vendor', 'vendor_id', $id, 1, " and merchant_id='" . $this->merchant_id . "'");
            if (empty($details)) {
SwipezLogger::error(__CLASS__, '[E291]Error while fetching vendor details ID:' . $id);
                $this->setInvalidLinkError();
            }
            $this->smarty->assign("enable_online_settlement", $this->onlineSettlementStatus($this->merchant_id));
            $this->view->js = array('setting');
            $this->view->selectedMenu = array(2, 16, 75);
            $this->smarty->assign("det", $details);
            $this->view->title = 'Update Vendor';
            $this->view->header_file = ['profile'];
$this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/vendor/update.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E292]Error while updating vendor Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Franchise update initiate
     */
    function bulkupdate($link) {
        try {
            $this->hasRole(2, 20);
            $id = $this->encrypt->decode($link);
            if (!is_numeric($id)) {
                $this->setInvalidLinkError();
            }
            $details = $this->common->getSingleValue('staging_vendor', 'vendor_id', $id, 1, " and merchant_id='" . $this->merchant_id . "'");
            if (empty($details)) {
SwipezLogger::error(__CLASS__, '[E291]Error while update vendor fetching vendor details ID:' . $id);
                $this->setInvalidLinkError();
            }
            $this->smarty->assign("enable_online_settlement", $this->onlineSettlementStatus($this->merchant_id));
            $this->view->js = array('setting');
            $this->view->selectedMenu = array(2, 16, 76);
            $this->smarty->assign("det", $details);
            $this->view->title = 'Update Vendor';
            $this->view->header_file = ['profile'];
$this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/vendor/bulk_update.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E292]Error while updating vendor Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Update save franchise
     */
    function saveupdate() {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/vendor/viewlist");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateVendorUpdate();
            $hasErrors = $validator->fetchErrors();
            $vendor_id = $_POST['vendor_id'];
            if ($hasErrors == false) {
                $pg_vendor_id = '';
                $online_settlement = ($_POST['online_settlement'] == 1) ? 1 : 0;
                $v_detail = $this->common->getSingleValue('vendor', 'vendor_id', $vendor_id);
                $pg_vendor_id = $v_detail['pg_vendor_id'];
                $status = $v_detail['status'];
                if ($online_settlement == 1 && $v_detail['online_pg_settlement'] == 0) {
                    $status = 0;
                    $res = $this->savePGVendor();
                    if ($res != false) {
                        if ($res['status'] == 1) {
                            $pg_vendor_id = $res['pg_vendor_id'];
                        } else {
                            $hasErrors = $res['errors'];
                        }
                    }
                }
                if ($hasErrors == false) {
                    $this->model->updateVendor($vendor_id, $pg_vendor_id, $_POST, $status, $this->user_id);
                    $this->session->set('successMessage', 'Vendor details have been updated.');
                    header("Location:/merchant/vendor/viewlist");
                }
            }
            if ($hasErrors != false) {
                $this->view->title = 'Vendor Update';
                $this->view->_POST = $_POST;
                $this->view->setError($hasErrors);
                $this->smarty->assign("haserrors", $hasErrors);
                $link = $this->encrypt->encode($vendor_id);
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
    function savebulkupdate() {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/vendor/viewlist");
            }
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateVendorSave($this->merchant_id);
            $hasErrors = $validator->fetchErrors();
            $vendor_id = $_POST['vendor_id'];

            if ($hasErrors == false) {
                $this->model->updateStagingVendor($vendor_id, $_POST, $this->user_id);
                $this->session->set('successMessage', 'Vendor details have been updated.');
                header("Location:/merchant/vendor/bulkupload/vendor");
            } else {
                $this->view->title = 'Vendor Update';
                $this->view->_POST = $_POST;
                $this->view->setError($hasErrors);
                $this->smarty->assign("haserrors", $hasErrors);
                $link = $this->encrypt->encode($vendor_id);
                $this->update($link);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E293]Error while updating franchise Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    /**
     * Tranfer to vendor account
     */
    function transfer() {
        $this->hasRole(2, 22);
        $vendor = $this->common->getListValue('vendor', 'merchant_id', $this->merchant_id, 1, " and status=1");
        $franchise = $this->common->getListValue('franchise', 'merchant_id', $this->merchant_id, 1, " and status=1");
        $banklist = $this->common->querylist("select config_key, config_value from config where config_type='Bank_name'");
        foreach ($banklist as $value) {
            $bank_ids[] = $value['config_key'];
            $bank_values[] = $value['config_value'];
        }
        $this->smarty->assign("enable_online_settlement", $this->onlineSettlementStatus($this->merchant_id));
        $this->smarty->assign("has_vendor", $this->session->get('vendor_enable'));
        $this->smarty->assign("has_franchise", $this->session->get('has_franchise'));
        $this->smarty->assign("bank_id", $bank_ids);
        $this->smarty->assign("bank_value", $bank_values);
        $this->view->title = 'Vendor transfer';
        $this->view->selectedMenu = array(4, 23);
        $this->smarty->assign("vendors", $vendor);
        $this->smarty->assign("franchise", $franchise);
        $this->view->js = array('setting', 'transaction');
        $this->view->header_file = ['profile'];
$this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/vendor/transfer.tpl');
        $this->view->render('footer/profile');
    }

    function offlinetransfer() {
        $this->hasRole(2, 22);
        $vendor = $this->common->getListValue('vendor', 'merchant_id', $this->merchant_id, 1, " and status=1");
        $this->view->title = 'Offline Vendor transfer';
        $this->view->selectedMenu = 'vendor_transfer';
        $banklist = $this->common->querylist("select config_key, config_value from config where config_type='Bank_name'");
        foreach ($banklist as $value) {
            $bank_ids[] = $value['config_key'];
            $bank_values[] = $value['config_value'];
        }
        $this->smarty->assign("bank_id", $bank_ids);
        $this->smarty->assign("bank_value", $bank_values);
        $this->smarty->assign("vendors", $vendor);
        $this->view->js = array('transaction');
        $this->view->header_file = ['profile'];
$this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/vendor/offlinetransfer.tpl');
        $this->view->render('footer/profile');
    }

    function offlinetransfersave() {
        $this->model->saveTransfer(2, $this->merchant_id, $_POST, 0, $this->user_id);
        $this->session->set('successMessage', 'Offline transfer has been save successfully.');
        header("Location:/merchant/vendor/transferlist");
    }

    /**
     * Save online transfer 
     */
    function transfersave() {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/vendor/transfer");
            }
            $_POST['vendor_id'] = ($_POST['vendor_id'] == '') ? 'no' : $_POST['vendor_id'];
            $_POST['franchise_id'] = ($_POST['franchise_id'] == '') ? 'no' : $_POST['franchise_id'];
            require CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateVendorTransfer($this->merchant_id);
            $hasErrors = $validator->fetchErrors();

            if ($hasErrors == false) {
                $response = $this->vendorTransfer($this->model);
                if ($response['status'] == 0) {
                    $hasErrors['PG'] = array('PG', $response['error']);
                }
                if ($hasErrors == false) {
                    $cashfree_transfer_id = $response['response']['vendorTransferId'];
                    $_POST['response_type'] = 0;
                    $this->model->saveTransfer(1, $this->merchant_id, $_POST, $cashfree_transfer_id, $this->user_id);
                    $this->session->set('successMessage', 'Transfer details have been saved.');
                    header("Location:/merchant/vendor/transfer");
                } else {
                    $this->smarty->assign("haserrors", $hasErrors);
                    $this->smarty->assign("post", $_POST);
                    $this->transfer();
                }
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->transfer();
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E289]Error while creating vendor Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function vendorTransfer($vendormodel) {
        $status = 0;
        if ($_POST['vendor_id'] > 0) {
            $pg_vendor_id = $this->common->getRowValue('pg_vendor_id', 'vendor', 'vendor_id', $_POST['vendor_id']);
        }
        if ($_POST['franchise_id'] > 0) {
            $pg_vendor_id = $this->common->getRowValue('pg_vendor_id', 'franchise', 'franchise_id', $_POST['franchise_id']);
        }
        $adjustment_id = $vendormodel->saveAdjustment($this->merchant_id, $_POST, 'CREDIT', $pg_vendor_id, $this->user_id);
        $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $this->merchant_id, 1);

        if (!empty($security_key)) {
            require_once UTIL . 'CashfreeAPI.php';
            $cashfree = new CashfreeAPI($security_key['cashfree_client_id'], $security_key['cashfree_client_secret']);
            $response = $cashfree->adjustVendorBalance($pg_vendor_id, $adjustment_id, 'CREDIT', $_POST['narrative'], $_POST['amount']);
            SwipezLogger::info(__CLASS__, 'Cashfree Response[V1] : ' . json_encode($response));
            if ($response['status'] == 0) {
                $hasErrors['PG'] = array('PG', $response['error']);
            } else {
                if ($response['response']['status'] != 'SUCCESS') {
                    $hasErrors['PG'] = array('PG', $response['response']['message']);
                } else {
                    
                }
            }
            if ($hasErrors == FALSE) {
                $response = $cashfree->transferVendor($pg_vendor_id, $_POST['amount']);
                SwipezLogger::info(__CLASS__, 'Cashfree Response[V2] : ' . json_encode($response));
                if ($response['status'] == 0) {
SwipezLogger::error(__CLASS__, '[Ev003]Error from cashfree vendor transfer save Error: ' . $response['error']);
                    return $response;
                } else {
                    if ($response['response']['status'] != 'SUCCESS') {
                        $hasErrors['PG'] = array('PG', $response['response']['message']);
                        $adjustment_id = $vendormodel->saveAdjustment($this->merchant_id, $_POST, 'DEBIT', $pg_vendor_id, $this->user_id);
                        $response = $cashfree->adjustVendorBalance($pg_vendor_id, $adjustment_id, 'DEBIT', $_POST['narrative'], $_POST['amount']);
                        SwipezLogger::info(__CLASS__, 'Cashfree Response[V3] : ' . json_encode($response));
                    }
                }
            }
            return $response;
        }
    }

    /**
     * Bulk upload vendor
     */ function bulkupload($type) {
        try {
            $this->hasRole(2, 20);
            if ($type == 'vendor') {
                $type_ = 3;
                $this->view->selectedMenu = array(2, 16, 76);
                $this->view->title = 'Vendor bulk upload';
            } else {
                $type_ = 4;
                $this->view->selectedMenu = array(4, 25);
                $this->view->title = 'Transfer bulk upload';
            }
            $this->smarty->assign("type", $type);
            $this->smarty->assign("type_", $type_);
            $list = $this->model->getBulkuploadList($this->merchant_id, $type_);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['bulk_id'] = $this->encrypt->encode($item['bulk_upload_id']);
                $int++;
            }
            $this->view->hide_first_col = true;
            $this->smarty->assign("bulklist", $list);
            $this->view->datatablejs = 'table-small-no-export';
            $this->view->title = 'Bulk upload ' . $type;
            $this->view->header_file = ['bulkupload'];
        $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/vendor/vendorupload.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[EC001]Error while vendor bulkupload Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    function download($type) {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $link = $this->encrypt->encode($merchant_id);
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("swipez")
                    ->setLastModifiedBy("swipez")
                    ->setTitle("swipez_" . $type)
                    ->setSubject($link)
                    ->setDescription("swipez " . $type);
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
            if ($type == 'vendor') {
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Vendor name');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Email ID');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Mobile No');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Address');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'City');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'State');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Zipcode');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Pan');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Adhar Number');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'GSTIN');
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
                $objPHPExcel->getActiveSheet()->setTitle('Vendor structure');
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Type (Franchise/Vendor)');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Vendor ID/Franchise ID');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Amount');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Mode (Payment gateway/Cash/Cheque/NEFT/Online payment)');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Paid date');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Bank name');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Bank ref no');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Cheque no');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Cash paid to');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Narrative');
                $int++;
                $objPHPExcel->getActiveSheet()->setTitle('Transfer structure');
            }
            $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')
                    ->setSize(10);

            $objPHPExcel->getActiveSheet()->getStyle('A1:' . $column[$int - 1])->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'AAAADD')
            )));
            $int++;
            $autosize = 0;
            while ($autosize < $int) {
                $objPHPExcel->getActiveSheet()->getColumnDimension(substr($column[$autosize], 0, -1))->setAutoSize(true);
                $autosize++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $type . '_structure' . time() . '.xlsx"');
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

    public function upload() {
        try {
            $this->hasRole(2, 20);
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
                    if ($_POST['type'] == 'vendor') {
                        $this->vendorbulk_upload($_FILES["fileupload"]);
                    } else {
                        $this->transferbulk_upload($_FILES["fileupload"]);
                    }
                } else {
                    $this->smarty->assign("hasErrors", $hasErrors);
                    $this->bulkupload($_POST['type']);
                }
            } else {
                header('Location: /merchant/vendor/bulkupload/' . $_POST['type']);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E270]Error while bulk upload submit Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function bulkerror($bulk_upload_id) {
        try {
            $bulk_id = $this->encrypt->decode($bulk_upload_id);
            $merchant_id = $this->session->get('merchant_id');
            require_once(MODEL . 'merchant/BulkuploadModel.php');
            $bulkupload = new BulkuploadModel ();
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

    public function vendorbulk_upload($inputFile, $bulk_upload_id = NULL) {
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
            require_once( MODEL . 'merchant/BulkuploadModel.php');
            $bulkupload = new BulkuploadModel();
            if ($merchant_id_ == $merchant_id && substr($worksheetTitle, 0, 6) == 'Vendor') {
                
            } else {
                $errors[0][0] = 'Invalid excel sheet';
                $errors[0][1] = 'Download again excel Vendor bulkupload and re-up load with Vendor data.';
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
                            $getcolumnvalue[$rowno] [] = $val;
                        }
                        $post_row = array();
                        $int = 0;
                        $post_row['vendor_name'] = (string) $getcolumnvalue[$rowno][$int];
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
                        $post_row['pan'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['adhar_card'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['gst'] = (string) $getcolumnvalue[$rowno][$int];
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
SwipezLogger::error(__CLASS__, '[E286]Error while validating excel Error: empty excel');
                }
                if (empty($errors)) {
                    foreach ($_POSTarray as $_POST) {
                        $result = $this->validateuploadvendor();
                        if (!empty($result)) {
                            $result['row'] = $errorrow;
                            $errors [] = $result;
                        } else {
                            
                        } $errorrow++;
                    }
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                
SwipezLogger::error(__CLASS__, '[E275]Error while validating excel Error: for merchant [' . $merchant_id . '] and ' . $e->getMessage());
            }


            if (empty($errors) && $bulk_upload_id == NULL) {

                $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), FALSE, $rows_count, 3);

                $this->session->set('successMessage', '  Excel sheet uploaded.');
                header('Location: /merchant/vendor/bulkupload/vendor');
                die();
            } else {
                if ($is_upload == TRUE && $bulk_upload_id == NULL) {
                    $bulk_id = $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), TRUE, 0, 3);
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
                        $this->reupload('vendor', $bulk_id);
                    } else {
                        $this->bulkupload('vendor');
                    }
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E271]Error while uploading excel Error: for merchant [' . $merchant_id . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function transferbulk_upload($inputFile, $bulk_upload_id = NULL) {
        try {
            $this->hasRole(2, 22);
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
            require_once( MODEL . 'merchant/BulkuploadModel.php');
            $bulkupload = new BulkuploadModel();
            if ($merchant_id_ == $merchant_id && substr($worksheetTitle, 0, 8) == 'Transfer') {
                
            } else {
                $errors[0][0] = 'Invalid excel sheet';
                $errors[0][1] = 'Download again excel Vendor bulkupload and re-up load with Transfer data.';
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
                            $getcolumnvalue[$rowno] [] = $val;
                        }
                        $post_row = array();
                        $int = 0;

                        $ttype = (string) $getcolumnvalue[$rowno][$int];
                        if (strtolower($ttype) == 'franchise') {
                            $post_row['transfer_type'] = 2;
                        } elseif (strtolower($ttype) == 'vendor') {
                            $post_row['transfer_type'] = 1;
                        } else {
                            $post_row['transfer_type'] = 3;
                        }
                        $int++;
                        $b_id = (string) $getcolumnvalue[$rowno][$int];
                        if ($post_row['transfer_type'] == 1) {
                            if ($b_id > 0) {
                                $post_row['vendor_id'] = $b_id;
                            } else {
                                $post_row['vendor_id'] = '';
                            }
                            $post_row['franchise_id'] = 'no';
                        } else {
                            $post_row['vendor_id'] = 'no';
                            if ($b_id > 0) {
                                $post_row['franchise_id'] = $b_id;
                            } else {
                                $post_row['franchise_id'] = '';
                            }
                        }
                        $int++;
                        $post_row['amount'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $type = 2;
                        $payment_mode = (string) $getcolumnvalue[$rowno][$int];

                        switch (strtolower($payment_mode)) {
                            case 'payment gateway':
                                $mode = 0;
                                $type = 1;
                            case 'neft':
                                $mode = 1;
                                break;
                            case 'cheque':
                                $mode = 2;
                                break;
                            case 'cash':
                                $mode = 3;
                                break;
                            case 'online payment':
                                $mode = 5;
                                break;
                            default :
                                $mode = 50;
                                break;
                        }
                        $post_row['type'] = $type;
                        $post_row['mode'] = $mode;
                        $int++;
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
                            try {
                                $date = new DateTime($excel_date);
                            } catch (Exception $e) {
                                $value = (string) $getcolumnvalue[$rowno][$int];
                            }
                        }
                        try {
                            if (isset($date)) {
                                $value = $date->format('Y-m-d');
                            }
                        } catch (Exception $e) {
                            $value = (string) $getcolumnvalue[$rowno][$int];
                        }
                        $post_row['date'] = $value;
                        $int++;
                        $post_row['bank_name'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['bank_transaction_no'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['cheque_no'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['cash_paid_to'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['narrative'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
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
                    $errors[0][1] = 'Uploaded excel does not contain any transfer.';
SwipezLogger::error(__CLASS__, '[E286]Error while validating excel Error: empty excel');
                }
                if (empty($errors)) {
                    foreach ($_POSTarray as $_POST) {
                        $result = $this->validateuploadtransfer();
                        if (!empty($result)) {
                            $result['row'] = $errorrow;
                            $errors [] = $result;
                        } else {
                            
                        } $errorrow++;
                    }
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                
SwipezLogger::error(__CLASS__, '[E275]Error while validating excel Error: for merchant [' . $merchant_id . '] and ' . $e->getMessage());
            }


            if (empty($errors) && $bulk_upload_id == NULL) {

                $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), FALSE, $rows_count, 4);

                $this->session->set('successMessage', '  Excel sheet uploaded.');
                header('Location: /merchant/vendor/bulkupload/transfer');
                die();
            } else {
                if ($is_upload == TRUE && $bulk_upload_id == NULL) {
                    $bulk_id = $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), TRUE, 0, 4);
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
                        $this->reupload('transfer', $bulk_id);
                    } else {
                        $this->bulkupload('transfer');
                    }
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E271]Error while uploading excel Error: for merchant [' . $merchant_id . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function validateuploadvendor() {
        try {
            require_once CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateBulkVendorSave();
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

    function validateuploadtransfer() {
        try {
            require_once CONTROLLER . 'merchant/Suppliervalidator.php';
            $validator = new Suppliervalidator($this->model);
            $validator->validateVendorTransfer($this->merchant_id);
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

    public function reupload($type, $bulk_id = '') {
        try {
            $this->hasRole(2, 20);
            $merchant_id = $this->session->get('merchant_id');
            if ($bulk_id != '') {
                $this->smarty->assign("type", $type);
                $this->smarty->assign("bulk_id", $bulk_id);
                $this->view->title = 'Re-upload vendor';
                $this->view->selectedMenu = array(2, 16, 76);
                $this->view->canonical = 'merchant/bulkupload/error/';
                $this->view->header_file = ['bulkupload'];
            $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/vendor/reupload.tpl');
                $this->view->render('footer/bulkupload');
            } else {
                header('Location: /merchant/vendor/bulkupload/' . $type);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E269]Error while re-upload Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

}

?>
