<?php

include_once ('bulkupload.php');

class SaveTransfer extends BulkUpload {

    function __construct() {
        parent::__construct();
        $this->saving();
        $this->savingPayout();
    }

    function getBulkTransfer($bulk_id, $merchant_id) {
        try {
            $sql = "select * from staging_vendor_transfer where bulk_id=:bulk_id and merchant_id=:merchant_id and is_active=1";
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
        $bulkuploadlist = $this->getbulkuploadlist(4, 4);
        foreach ($bulkuploadlist as $bulkUpload) {
            try {
                $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 8);
                $merchant_id = $bulkUpload['merchant_id'];
                $bulk_transferList = $this->getBulkTransfer($bulkUpload['bulk_upload_id'], $merchant_id);
                $count = 0;
                $is_failed = 0;
                $this->logger->info(__CLASS__, 'Saving transfer initiate bulk_id is : ' . $bulkUpload['bulk_upload_id']);
                $row = 2;
                $errors = false;
                foreach ($bulk_transferList as $info) {
                    $info['transfer_type'] = $info['beneficiary_type'];
                    $info['response_type'] = $info['offline_response_type'];
                    $info['date'] = $info['transfer_date'];
                    $status = 0;
                    if ($info['type'] == 1) {
                        if ($info['vendor_id'] > 0) {
                            $pg_vendor_id = $this->common->getRowValue('pg_vendor_id', 'vendor', 'vendor_id', $info['vendor_id']);
                        }

                        if ($info['franchise_id'] > 0) {
                            $pg_vendor_id = $this->common->getRowValue('pg_vendor_id', 'franchise', 'franchise_id', $info['franchise_id']);
                        }
                        $adjustment_id = $vendor->saveAdjustment($merchant_id, $info, 'CREDIT', $pg_vendor_id, $merchant_id);
                        $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $merchant_id, 1);


                        if (!empty($security_key)) {
                            $cashfree = new CashfreeAPI($security_key['cashfree_client_id'], $security_key['cashfree_client_secret']);
                            $response = $cashfree->adjustVendorBalance($pg_vendor_id, $adjustment_id, 'CREDIT', $info['narrative'], $info['amount']);
                            $this->logger->info(__CLASS__, 'Cashfree Response[V1] : ' . json_encode($response));
                            if ($response['status'] == 0) {
                                $this->logger->error(__CLASS__, '[Ev002]Error from cashfree vendor adjustment save Error: ' . $response['error']);
                                $err = array('PG', $response['error']);
                                $errors[] = array($this->rchar() => $err, 'row' => $row);
                                $is_failed = 1;
                            } else {
                                if ($response['response']['status'] != 'SUCCESS') {
                                    $hasErrors['PG'] = array('PG', $response['response']['message']);
                                    $err = array('PG', $response['response']['message']);
                                    $errors[] = array($this->rchar() => $err, 'row' => $row);
                                    $is_failed = 1;
                                } else {
                                    
                                }
                            }
                            if ($errors == FALSE) {
                                $response = $cashfree->transferVendor($pg_vendor_id, $info['amount']);
                                $this->logger->info(__CLASS__, 'Cashfree Response[V2] : ' . json_encode($response));
                                if ($response['status'] == 0) {
                                    $this->logger->error(__CLASS__, '[Ev003]Error from cashfree vendor transfer save Error: ' . $response['error']);
                                    $err = array('PG', $response['error']);
                                    $errors[] = array($this->rchar() => $err, 'row' => $row);
                                    $is_failed = 1;
                                } else {
                                    if ($response['response']['status'] != 'SUCCESS') {
                                        $hasErrors['PG'] = array('PG', $response['response']['message']);
                                        $adjustment_id = $vendor->saveAdjustment($merchant_id, $info, 'DEBIT', $pg_vendor_id, $merchant_id);
                                        $response = $cashfree->adjustVendorBalance($pg_vendor_id, $adjustment_id, 'DEBIT', $info['narrative'], $info['amount']);
                                        $err = array('PG', $response['message']);
                                        $errors[] = array($this->rchar() => $err, 'row' => $row);
                                        $this->logger->info(__CLASS__, 'Cashfree Response[V3] : ' . json_encode($response));
                                        $is_failed = 1;
                                    }
                                }
                            }
                        }

                        $cashfree_transfer_id = $response['response']['vendorTransferId'];
                        if (isset($cashfree_transfer_id)) {
                            $vendor->saveTransfer(1, $merchant_id, $info, $cashfree_transfer_id, $merchant_id, $info['bulk_id']);
                        }
                    } else {
                        $vendor->saveTransfer(2, $merchant_id, $info, 0, $merchant_id, $info['bulk_id']);
                    }
                    $count++;
                    $row++;
                }
                if ($is_failed == 0) {
                    $this->logger->info(__CLASS__, 'Bulk transfer saved sucessfully bulk id : ' . $bulkUpload['bulk_upload_id'] . ' Total vendor ' . $count . '');
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 5);
                } else {
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 1, json_encode($errors));
                    $this->moveExcelFile($merchant_id, 'staging', $bulkUpload['system_filename']);
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                
$this->logger->error(__CLASS__, '[E6]Error while saving transfer ' . $bulkUpload['bulk_upload_id'] . ' Error: ' . $e->getMessage());
            }
        }
    }

    function savingPayout() {
        $api_base = getenv('SWIPEZ_API_URL');
        if ($api_base == '') {
            $api_base = 'http://swipez.api/api/';
        }
        $bulkuploadlist = $this->getbulkuploadlist(4, 9);
        foreach ($bulkuploadlist as $bulkUpload) {
            try {
                $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 8);
                $merchant_id = $bulkUpload['merchant_id'];
                $bulk_transferList = $this->getBulkTransfer($bulkUpload['bulk_upload_id'], $merchant_id);
                $login_token = $this->getToken($merchant_id);
                $json = $this->apisrequest($api_base . 'token', 'login_token=' . $login_token, array());
                $array = json_decode($json, 1);
                $api_token = $array['success']['token'];
                $header = array(
                    "Authorization: Bearer " . $api_token
                );
                $count = 0;
                $is_failed = 0;
                $this->logger->info(__CLASS__, 'Saving transfer initiate bulk_id is : ' . $bulkUpload['bulk_upload_id']);
                $row = 2;
                $errors = false;
                foreach ($bulk_transferList as $info) {
                    $info_array['beneficiary_id'] = $info['beneficiary_id'];
                    $info_array['amount'] = $info['amount'];
                    $info_array['narrative'] = $info['narrative'];
                    $info_array['mode'] = $info['mode'];
                    $json = json_encode($info_array);
                    $result = $this->apisrequest($api_base . 'v1/beneficiary/transfer', $json, $header);
                    $array = json_decode($result, 1);
                    if ($array['status'] == 0) {
                        $error = array();
                        if (is_array($array['error'])) {
                            $error_msg = $array['error'][0];
                        } else {
                            $error_msg = $array['error'];
                        }
                        $error[$count] = array($info['beneficiary_id'], $error_msg);
                        $error['row'] = $count + 1;
                        $errors[] = $error;
                        $is_failed = 1;
                        $this->logger->error(__CLASS__, 'error while saving beneficiary : error ' . $error_msg . '');
                    } else {
                        $success = 1;
                    }
                    $count++;
                    $row++;
                }
                if ($is_failed == 0) {
                    $this->logger->info(__CLASS__, 'Bulk payout saved sucessfully bulk id : ' . $bulkUpload['bulk_upload_id'] . ' Total payout ' . $count . '');
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 5);
                } else {
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 1, json_encode($errors));
                    $this->moveExcelFile($merchant_id, 'staging', $bulkUpload['system_filename']);
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                
$this->logger->error(__CLASS__, '[E6]Error while saving transfer ' . $bulkUpload['bulk_upload_id'] . ' Error: ' . $e->getMessage());
            }
        }
    }

}

new SaveTransfer();
