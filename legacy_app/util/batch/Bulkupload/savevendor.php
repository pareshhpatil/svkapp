<?php

include_once ('bulkupload.php');

class SaveVendor extends BulkUpload {

    function __construct() {
        parent::__construct();
        $this->saving();
        $this->savingBeneficiary();
    }

    function getBulkVendor($bulk_id, $merchant_id) {
        try {
            $sql = "select * from staging_vendor where bulk_id=:bulk_id and merchant_id=:merchant_id and is_active=1";
            $params = array(':bulk_id' => $bulk_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getBulkBeneficiary($bulk_id, $merchant_id) {
        try {
            $sql = "select beneficiary_code,type,name,email_id,mobile,address,city,state,zipcode as pincode,bank_account_no as account_number,ifsc_code as ifsc,upi from staging_beneficiary where bulk_id=:bulk_id and merchant_id=:merchant_id and is_active=1";
            $params = array(':bulk_id' => $bulk_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function saving() {
        require_once MODEL . 'merchant/VendorModel.php';
        require_once UTIL . 'CashfreeAPI.php';
        $vendor = new VendorModel();
        $bulkuploadlist = $this->getbulkuploadlist(4, 3);
        foreach ($bulkuploadlist as $bulkUpload) {
            try {
                $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 8);
                $merchant_id = $bulkUpload['merchant_id'];
                $bulk_customerList = $this->getBulkVendor($bulkUpload['bulk_upload_id'], $merchant_id);
                $count = 0;
                $is_failed = 0;
                $this->logger->info(__CLASS__, 'Saving vendor initiate bulk_id is : ' . $bulkUpload['bulk_upload_id']);
                $row = 2;
                $errors = array();
                foreach ($bulk_customerList as $info) {
                    $status = 1;
                    $pg_vendor_id = '';
                    $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $merchant_id, 1);
                    $info['email'] = $info['email_id'];
                    $info['gst'] = $info['gst_number'];
                    $info['account_number'] = $info['bank_account_no'];
                    $info['account_holder_name'] = $info['bank_holder_name'];
                    $info['commision_percent'] = $info['commission_percentage'];
                    $info['online_settlement'] = $info['online_pg_settlement'];
                    $success = 1;
                    if (!empty($security_key) && $info['online_pg_settlement'] == 5) {
                        $status = 0;
                        $vendor_det = $this->common->querysingle("select generate_sequence('PG_vendor_id') as vendor_id");
                        $pg_vendor_id = $vendor_det['vendor_id'];
                        $cashfree = new CashfreeAPI($security_key['cashfree_client_id'], $security_key['cashfree_client_secret']);
                        $data['vendorId'] = $pg_vendor_id;
                        $data['name'] = $info['vendor_name'];
                        $data['email'] = $info['email_id'];
                        $data['phone'] = $info['mobile'];
                        $data['commission'] = $info['commission_percentage'];
                        $data['bankAccount'] = $info['account_number'];
                        $data['accountHolder'] = $info['bank_holder_name'];
                        $data['ifsc'] = $info['ifsc_code'];
                        $data['panNo'] = $info['pan'];
                        $data['aadharNo'] = $info['adhar_card'];
                        $data['gstin'] = $info['gst'];
                        $data['address1'] = $info['address'];
                        $data['address2'] = '';
                        $data['city'] = $info['city'];
                        $data['state'] = $info['state'];
                        $data['pincode'] = $info['zipcode'];
                        $response = $cashfree->saveVendor(json_encode($data));
                        $this->logger->info(__CLASS__, 'Cashfree Response: ' . json_encode($response));
                        if ($response['status'] == 0) {
                            $this->logger->error(__CLASS__, '[Ev001]Error from cashfree vendor save Error: ' . $response['error']);
                            $err = array('Cashfree', $response['error']);
                            $errors[] = array($this->rchar() => $err, 'row' => $row);
                            $is_failed = 1;
                            $success = 0;
                        } else {
                            if ($response['response']['status'] != 'SUCCESS') {
                                $err = array('PG', $response['response']['message']);
                                $errors[] = array($this->rchar() => $err, 'row' => $row);
                                $is_failed = 1;
                                $success = 0;
                            }
                        }
                    }
                    if ($success == 1) {
                        $auto_generate_code = $this->common->getRowValue('vendor_code_auto_generate', 'merchant_setting', 'merchant_id', $merchant_id);
                        if ($auto_generate_code == 1) {
                            $auto_seq_id = $this->common->getRowValue('auto_invoice_id', 'merchant_auto_invoice_number', 'merchant_id', $merchant_id, 1, ' and type=7');
                            $info['vendor_code'] = $vendor->getVendorCode($auto_seq_id);
                        }
                        $vendor->createVendor($pg_vendor_id, $merchant_id, $info, $status, $merchant_id, $bulkUpload['bulk_upload_id']);
                    }
                    $count++;
                    $row++;
                }
                if ($is_failed == 0) {
                    $this->logger->info(__CLASS__, 'Bulk vendor saved sucessfully bulk id : ' . $bulkUpload['bulk_upload_id'] . ' Total vendor ' . $count . '');
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 5);
                } else {
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 1, json_encode($errors));
                    $this->moveExcelFile($merchant_id, 'staging', $bulkUpload['system_filename']);
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                
$this->logger->error(__CLASS__, '[E6]Error while saving vendor ' . $bulkUpload['bulk_upload_id'] . ' Error: ' . $e->getMessage());
                $this->db->rollback();
                $errors[] = array($this->rchar() => $e->getMessage(), 'row' => 0);
                $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 1, json_encode($errors));
                $this->moveExcelFile($merchant_id, 'staging', $bulkUpload['system_filename']);
            }
        }
    }

    function savingBeneficiary() {
        $bulkuploadlist = $this->getbulkuploadlist(4, 8);
        $api_base = getenv('SWIPEZ_API_URL');
        if ($api_base == '') {
            $api_base = 'http://swipez.api/api/';
        }
        foreach ($bulkuploadlist as $bulkUpload) {
            try {
                $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 8);
                $merchant_id = $bulkUpload['merchant_id'];
                $login_token = $this->getToken($merchant_id);
                $json = $this->apisrequest($api_base . 'token', 'login_token=' . $login_token, array());
                $array = json_decode($json, 1);
                $api_token = $array['success']['token'];
                $bulk_beneList = $this->getBulkBeneficiary($bulkUpload['bulk_upload_id'], $merchant_id);
                $count = 0;
                $is_failed = 0;
                $this->logger->info(__CLASS__, 'Saving vendor initiate bulk_id is : ' . $bulkUpload['bulk_upload_id']);
                $header = array(
                    "Authorization: Bearer " . $api_token
                );
                $errors = array();
                foreach ($bulk_beneList as $info) {
                    $json = json_encode($info);
                    $result = $this->apisrequest($api_base . 'v1/beneficiary/save', $json, $header);
                    $array = json_decode($result, 1);
                    if ($array['status'] == 0) {
                        $error = array();
                        if (is_array($array['error'])) {
                            $error_msg = $array['error'][0];
                        } else {
                            $error_msg = $array['error'];
                        }
                        $error[$count] = array($info['name'], $error_msg);
                        $error['row'] = $count + 1;
                        $errors[] = $error;
                        $is_failed = 1;
                        $this->logger->error(__CLASS__, 'error while saving beneficiary : error ' . $error_msg . '');
                    }
                    $count++;
                }
                if ($is_failed == 0) {
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 5);
                } else {
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 1, json_encode($errors));
                    $this->moveExcelFile($merchant_id, 'staging', $bulkUpload['system_filename']);
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                
$this->logger->error(__CLASS__, '[E6]Error while saving beneficiary ' . $bulkUpload['bulk_upload_id'] . ' Error: ' . $e->getMessage());
                $errors[] = array($this->rchar() => $e->getMessage(), 'row' => 0);
                $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 1, json_encode($errors));
            }
        }
    }

}

new SaveVendor();
