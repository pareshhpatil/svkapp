<?php

include_once ('bulkupload.php');

class SaveTransaction extends BulkUpload {

    function __construct() {
        parent::__construct();
        $this->saving();
    }

    function getBulktransaction($bulk_id, $merchant_id) {
        try {
            $sql = "select * from staging_offline_response where bulk_id=:bulk_id and merchant_id=:merchant_id and is_active=1";
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
        require_once MODEL . 'merchant/PaymentrequestModel.php';
        $requestModel = new PaymentRequestModel();
        $bulkuploadlist = $this->getbulkuploadlist(4, 5);
        require_once CONTROLLER . 'Notification.php';
        foreach ($bulkuploadlist as $bulkUpload) {
            try {
                $notification = new Notification('cron');
                $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 8);
                $merchant_id = $bulkUpload['merchant_id'];
                $notification->app_url = $this->getAppUrl($merchant_id);
                $bulk_customerList = $this->getBulktransaction($bulkUpload['bulk_upload_id'], $merchant_id);
                $count = 0;
                $is_failed = 0;
                $this->logger->info(__CLASS__, 'Saving transaction initiate bulk_id is : ' . $bulkUpload['bulk_upload_id']);
                $this->db->begin_Transaction();
                $row = 2;
                $errors = array();
                foreach ($bulk_customerList as $info) {
                    $detail = $this->common->getofflineTransaction_id($info['payment_request_id']);
                    if (!empty($detail)) {
                        $requestModel->respondUpdate($info['amount'], $info['bank_name'], $detail['offline_response_id'], $info['settlement_date'], $info['offline_response_type'], $info['bank_transaction_no'], $info['cheque_no'], '', $info['cash_paid_to'], $info['created_by']);
                        $result['offline_response_id'] = $detail['offline_response_id'];
                    } else {
                        $result = $requestModel->respond($info['amount'], $info['bank_name'], $info['payment_request_id'], $info['settlement_date'], $info['offline_response_type'], $info['bank_transaction_no'], $info['cheque_no'], $info['cash_paid_to'], 0, 0, $info['created_by']);
                        $this->common->genericupdate('offline_response', 'bulk_id', $bulkUpload['bulk_upload_id'], 'offline_response_id', $result['offline_response_id'], $info['created_by']);
                    }
                    if ($info['notify_patron'] == 1) {
                        $notification->sendMailReceipt($result['offline_response_id'], 0);
                    }
                }
                if ($is_failed == 0) {
                    $this->logger->info(__CLASS__, 'Bulk transaction saved sucessfully bulk id : ' . $bulkUpload['bulk_upload_id'] . ' Total transaction ' . $count . '');
                    $this->db->commit();
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 5);
                } else {
                    $this->db->rollback();
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 1, json_encode($errors));
                    $this->moveExcelFile($merchant_id, 'staging', $bulkUpload['system_filename']);
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                
$this->logger->error(__CLASS__, '[E6]Error while saving transaction ' . $bulkUpload['bulk_upload_id'] . ' Error: ' . $e->getMessage());
            }
        }
    }

    function getAppUrl($merchant_id) {
        $sql = "SELECT merchant_domain FROM merchant where merchant_id=:merchant_id";
        $params = array(':merchant_id' => $merchant_id);
        $this->db->exec($sql, $params);
        $row = $this->db->single();

        return $this->getConfigAppUrl($row['merchant_domain']);
    }

    function getConfigAppUrl($domain) {
        $sql = "SELECT config_value FROM config where config_type='merchant_domain' and config_key=:config_key";
        $params = array(':config_key' => $domain);
        $this->db->exec($sql, $params);
        $row = $this->db->single();
        return $row['config_value'];
    }

}

new SaveTransaction();
