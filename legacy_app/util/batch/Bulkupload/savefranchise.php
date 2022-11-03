<?php

include_once ('bulkupload.php');

class SaveFranchise extends BulkUpload {

    function __construct() {
        parent::__construct();
        $this->saving();
    }

    function getBulkFranchise($bulk_id, $merchant_id) {
        try {
            $sql = "select * from staging_franchise where bulk_id=:bulk_id and merchant_id=:merchant_id and is_active=1";
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
        require_once MODEL . 'merchant/FranchiseModel.php';
        require_once UTIL . 'CashfreeAPI.php';
        $franchise = new FranchiseModel();
        $bulkuploadlist = $this->getbulkuploadlist(4, 6);
        foreach ($bulkuploadlist as $bulkUpload) {
            try {
                $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 8);
                $merchant_id = $bulkUpload['merchant_id'];
                $bulk_franchiseList = $this->getBulkFranchise($bulkUpload['bulk_upload_id'], $merchant_id);
                $count = 0;
                $is_failed = 0;
                $this->logger->info(__CLASS__, 'Saving vendor initiate bulk_id is : ' . $bulkUpload['bulk_upload_id']);
                $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 8);
                $row = 2;
                $errors = array();
                foreach ($bulk_franchiseList as $info) {
                    $status = 0;
                    $pg_vendor_id = '';
                    $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $merchant_id, 1);
                    if (!empty($security_key) && $info['online_pg_settlement'] == 1) {
                        $vendor_det = $this->common->querysingle("select generate_sequence('PG_vendor_id') as vendor_id");
                        $pg_vendor_id = $vendor_det['vendor_id'];
                        $cashfree = new CashfreeAPI($security_key['cashfree_client_id'], $security_key['cashfree_client_secret']);
                        $data['vendorId'] = $pg_vendor_id;
                        $data['name'] = $info['vendor_name'];
                        $data['email'] = $info['email_id'];
                        $data['phone'] = $info['mobile'];
                        $data['commission'] = $info['commission_percentage'];
                        $data['bankAccount'] = $info['bank_account_no'];
                        $data['accountHolder'] = $info['bank_holder_name'];
                        $data['ifsc'] = $info['ifsc_code'];
                        $data['panNo'] = '';
                        $data['aadharNo'] = '';
                        $data['gstin'] = '';
                        $data['address1'] = $info['address'];
                        $data['address2'] = '';
                        $data['city'] = '';
                        $data['state'] = '';
                        $data['pincode'] = '';
                        $response = $cashfree->saveVendor(json_encode($data));
                        $this->logger->info(__CLASS__, 'Cashfree Response: ' . json_encode($response));
                        if ($response['status'] == 0) {
                            $this->logger->error(__CLASS__, '[Ev001]Error from cashfree vendor save Error: ' . $response['error']);
                            $err = array('Cashfree', $response['error']);
                            $errors[] = array($this->rchar() => $err, 'row' => $row);
                            $is_failed = 1;
                        } else {
                            if ($response['response']['status'] != 'SUCCESS') {
                                $err = array('PG', $response['response']['message']);
                                $errors[] = array($this->rchar() => $err, 'row' => $row);
                                $is_failed = 1;
                            }
                        }
                    }
                    $info['commission_percent'] = $info['commission_percentage'];
                    $info['online_settlement'] = $info['online_pg_settlement'];
                    $franchise->createFranchise($pg_vendor_id, $merchant_id, $info['franchise_name'], $info['email_id'], $info['email_id2'], $info['mobile'], $info['address'], $info['mobile2'], $info['contact_person_name'], $info['contact_person_name2']
                            , $info['bank_holder_name'], $info['bank_account_no'], $info['bank_name'], $info['account_type'], $info['ifsc_code'], $status, $info, $info['created_by'], $bulkUpload['bulk_upload_id']);
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

}

new SaveFranchise();
