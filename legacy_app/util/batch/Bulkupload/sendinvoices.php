<?php

include_once('../config.php');

class SendInvoice extends Batch
{

    public $logger = NULL;

    function __construct()
    {
        parent::__construct();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->sending();
    }

    function gebulkuploadlist()
    {
        try {
            $sql = "SELECT * FROM bulk_upload where status=:status and type=1";
            $params = array(':status' => 4);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE102]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function hasEnventory($merchant_id)
    {
        try {
            $sql = "select id from merchant_active_apps where merchant_id=:merchant_id and service_id=15 and status=1;";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE102]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    public function updateBulkUploadStatus($bulk_id, $status, $error = '')
    {
        try {
            $sql = "update bulk_upload set status=:status,error_json=:error where bulk_upload_id=:bulk_id";
            $params = array(':status' => $status, ':error' => $error, ':bulk_id' => $bulk_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E4]Error while update bulk upload status Bulk id: ' . $bulk_id . ' Status: ' . $status . ' Error: ' . $e->getMessage());
        }
    }

    public function moveExcelFile($merchant_id, $folder, $filename)
    {
        try {
            $filename2 = '../../../../public/uploads/Excel/' . $merchant_id . '/' . $folder . '/' . $filename . '';
            $movepath = '../../../../public/uploads/Excel/' . $merchant_id . '/error/' . $filename . '';
            if (file_exists($filename2)) {
                if ((copy($filename2, $movepath))) {
                    return True;
                } else {
                    $this->logger->error(__CLASS__, '[E274-2]Error while move excel Error: file 1' . $filename2 . '/ file 2' . $movepath);
                }
            } else {
                $this->logger->error(__CLASS__, '[E274-1]Error file not found ' . $filename2);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E274]Error while move excel Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function sending()
    {
        try {
            require_once MODEL . 'CommonModel.php';
            $common = new CommonModel();
            require_once CONTROLLER . 'Notification.php';
            $bulkuploadlist = $this->gebulkuploadlist();
            $einvoice = array();
            foreach ($bulkuploadlist as $bulkUpload) {
                try {
                    $notification = new Notification('cron');
                    $notification->app_url =  $this->getAppUrl($bulkUpload['merchant_id']);
                    $has_inventory = $this->hasEnventory($bulkUpload['merchant_id']);
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 8);
                    $this->logger->info(__CLASS__, 'Sending invoices initiate bulk_id is : ' . $bulkUpload['bulk_upload_id']);
                    $res = $common->isValidPackageInvoice($bulkUpload['merchant_id'], $bulkUpload['total_rows'], 'bulk');
                    if ($res == false) {
                        $errors[]['Invoice_upload_limit'] = array('Package Expired', 'Package has expired. Please upgrade your package to send more invoices.');
                        $this->logger->error(__CLASS__, '[E285-1]Error while validating package invoice count Error: excel rows more than ' . substr($res, 2) . 'for merchant [' . $bulkUpload['merchant_id'] . ']');
                        $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 1, json_encode($errors));
                        $this->moveExcelFile($bulkUpload['merchant_id'], 'staging', $bulkUpload['system_filename']);
                    } else {
                        $sql = "call savebulkUpload_invoice(:bulk_id);";
                        $params = array(':bulk_id' => $bulkUpload['bulk_upload_id']);
                        $this->db->exec($sql, $params);
                        $request_detail = $this->db->resultset();

                        if (isset($request_detail[0]['message'])) {
                            $error['invoice_number'] = array('Invoice number', 'Invoice number already exist.');
                            $errors[] = $error;
                            $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 1, json_encode($errors));
                            $this->moveExcelFile($bulkUpload['merchant_id'], 'staging', $bulkUpload['system_filename']);
                        } else {

                            if (!isset($request_detail[0]['payment_request_id'])) {
                                throw new Exception(json_encode($request_detail));
                            }

                            foreach ($request_detail as $detail) {
                                $plugin = json_decode($detail['plugin_value'], 1);
                                if ($detail['has_custom_reminder'] == 1) {
                                    $reminders = $plugin['reminders'];
                                    foreach ($reminders as $key => $rm) {
                                        $duedate = new DateTime($detail['due_date']);
                                        $duedate = strtotime("-" . $key . " days", strtotime($duedate->format('Y-m-d')));
                                        $rdate = date('Y-m-d', $duedate);
                                        $this->saveReminder($detail['payment_request_id'], $rdate, $rm['email_subject'], $rm['sms'], $detail['merchant_id'], $detail['user_id']);
                                    }
                                }
                                if ($plugin['has_e_invoice'] == 1) {
                                    $notify = ($plugin['notify_e_invoice'] == 1) ? 1 : 0;
                                    $einvoice[] = array('id' => $detail['payment_request_id'], 'notify' => $notify);
                                }
                                if ($detail['notify_patron'] == 1) {
                                    $this->logger->debug(__CLASS__, 'Sending email initiate payment_request_id is : ' . $detail['payment_request_id']);
                                    $notification->sendInvoiceNotification($detail['payment_request_id'], 0, 1);
                                }
                                if ($detail['merchant_id'] == PENALTY_MERCHANT_ID) {
                                    $this->expiredInvoice($detail['merchant_id'], $detail['customer_id'], $detail['payment_request_id']);
                                }
                                if ($has_inventory == true) {
                                    $common->queryexecute("call `stock_management`('" . $detail['merchant_id'] . "','" . $detail['payment_request_id'] . "',3,1);");
                                }
                                if ($detail['due_mode'] == 'auto_calculate') {
                                    $common->queryexecute("select `carry_forward_dues`('" . $detail['merchant_id'] . "','" . $detail['customer_id'] . "','" . $detail['payment_request_id'] . "');");
                                }

                                if ($detail['payment_request_type'] == 4) {
                                    define('req_type', $detail['payment_request_id']);
                                    require_once UTIL . 'batch/subscription/subscription.php';
                                }
                            }
                            if (!empty($einvoice)) {
                                include_once('bulkupload.php');
                                $bulkUpload = new BulkUpload();
                                $array['source'] = 'Bulkupload';
                                $array['data'] = $einvoice;
                                $bulkUpload->apisrequest(SWIPEZ_APP_URL . 'api/v2/einvoice/queue', json_encode($array), []);
                            }
                        }
                    }
                } catch (Exception $e) {
                    Sentry\captureException($e);

                    $this->logger->error(__CLASS__, '[BE104]Error while sending ivoices Error: ' . $e->getMessage());
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE105]Error while sending ivoices Error: ' . $e->getMessage());
        }
    }

    public function saveNewStructure($template_id, $req_id, $template_type, $merchant_id)
    {
        try {
            $sql = "call save_new_structure(1,:template_id,:req_id,:template_type,:merchant_id);";
            $params = array(':template_id' => $template_id, ':req_id' => $req_id, ':template_type' => $template_type, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E103]Error while save Reminder ' . $req_id . ' Error: ' . $e->getMessage());
        }
    }

    function expiredInvoice($merchant_id, $customer_id, $payment_request_id)
    {
        $date = date('Y-m-d', strtotime("-1 days"));
        $sql = "update payment_request set expiry_date=:date where merchant_id=:merchant_id and customer_id=:customer_id and due_date<curdate() and payment_request_id<>:payment_request_id";
        $params = array(':date' => $date, ':customer_id' => $customer_id, ':merchant_id' => $merchant_id, ':payment_request_id' => $payment_request_id);
        $this->db->exec($sql, $params);
    }

    public function saveReminder($payment_request_id, $date, $subject, $sms, $merchant_id, $user_id)
    {
        try {
            $sql = "INSERT INTO `invoice_custom_reminder`(`payment_request_id`,`date`,`subject`,`sms`,`merchant_id`,`created_by`,`last_update_by`,`created_date`)"
                . "VALUES(:request_id,:date,:subject,:sms,:merchant_id,:user_id,:user_id,CURRENT_TIMESTAMP());";
            $params = array(':request_id' => $payment_request_id, ':date' => $date, ':subject' => $subject, ':sms' => $sms, ':merchant_id' => $merchant_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E103]Error while save Reminder ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }
}

new SendInvoice();
