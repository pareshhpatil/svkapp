<?php

include('../config.php');

class downloadfile extends Batch {

    public $logger = NULL;

    function __construct() {
        parent::__construct();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->downloadzipfile();
        $this->deletezipfile();
    }

    function getPendingDownloads() {
        try {
            $sql = "select * from form_builder_download_request where status=0";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[VAPI1]Error while get Pending Vendors Error: ' . $e->getMessage());
        }
    }

    function getPendingDelete() {
        try {
            $sql = "select * from form_builder_download_request where status=1 and DATE_ADD(last_update_date, INTERVAL 7 DAY) < now()";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[VAPI1]Error while get Pending Vendors Error: ' . $e->getMessage());
        }
    }

    function downloadzipfile() {
        try {
            set_time_limit(0);
            $deleteArray = array();
            $rows = $this->getPendingDownloads();
            require_once MODEL . 'CommonModel.php';
            $common = new CommonModel();
            $converter = new Encryption;
            foreach ($rows as $row) {
                $folder_path = $row['merchant_id'] . '/' . $row['id'];
                $zip = new ZipArchive(); // Load zip library 
                $zip_name = TMP_FOLDER . $converter->encode($row['id']) . ".zip";
                if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
                    SwipezLogger ::error(__CLASS__, '[E1011+96]Error: ZIP creation failed at this time. Form transaction id: ' . $form_transaction_id);
                }
                $deleteArray[] = $zip_name;
                $id_array = explode(',', $row['form_transaction_id']);
                foreach ($id_array as $id) {
                    $file_path = $common->getRowValue('zip_file_path', 'form_builder_transaction', 'id', $id);
                    $file_name = TMP_FOLDER . $id . ".zip";
                    $file = file_get_contents($file_path);
                    file_put_contents($file_name, $file);
                    $zip->addFile($file_name, $id . ".zip");
                    $deleteArray[] = $file_name;
                }
                $zip->close();
                $fileup['tmp_name'] = $zip_name;
                $fileup['name'] = $converter->encode($row['id']) . ".zip";
                $zip_path = $this->uploadFile($fileup, $folder_path, $converter->encode($row['id']), 'downloads');
                $days = 7;
                $email = $common->getMerchantProfile($row['merchant_id'], 0, 'business_email');
                $date = strtotime(date("d M Y") . ' ' . $days . ' days');
                $date = date('d M Y', $date);
                $this->sendDownloadMail($email, $date, $zip_path);
                $this->updateStatus($zip_path, $row['id']);
            }
            foreach ($deleteArray as $file) {
                unlink($file);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[VAPI3]Error update vendor status Error: ' . $e->getMessage());
        }
    }

    function deletezipfile() {
        try {
            $bucket = getenv('FORM_BUILDER_BUCKET');
            $rows = $this->getPendingDelete();
            require_once UTIL . 'SiteBuilderS3Bucket.php';
            $aws = new SiteBuilderS3Bucket();
            foreach ($rows as $row) {
                $folder_path = 'downloads/' . $row['merchant_id'] . '/' . $row['id'];
                $aws->deleteBucketfile($bucket, $folder_path);
                $this->updateDeleteStatus($row['id']);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[VAPI3]Error update vendor status Error: ' . $e->getMessage());
        }
    }

    function updateDeleteStatus($id) {
        try {
            $sql = "update form_builder_download_request set status=2 where id=" . $id;
            $params = array();
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[VAPI1]Error while get Pending Vendors Error: ' . $e->getMessage());
        }
    }

    function updateStatus($url, $id) {
        try {
            $sql = "update form_builder_download_request set status=1,url='" . $url . "' where id=" . $id;
            $params = array();
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[VAPI1]Error while get Pending Vendors Error: ' . $e->getMessage());
        }
    }

    function uploadFile($upload_file, $path, $file_name, $folder = 'uploads') {
        $bucket = getenv('FORM_BUILDER_BUCKET');
        require_once UTIL . 'SiteBuilderS3Bucket.php';
        $aws = new SiteBuilderS3Bucket();
        $file = $upload_file['name'];
        $ext = substr($file, strrpos($file, '.') + 1);
        $keyname = $folder . '/' . $path . '/' . $file_name . '.' . $ext;
        $fileurl = $aws->putFile($bucket, $keyname, $upload_file['tmp_name']);
        return $fileurl;
    }

    function sendDownloadMail($email, $date, $url) {
        $emailWrapper = new EmailWrapper();
        $mailcontents = $emailWrapper->fetchMailBody("merchant.download_file");
        $mailcontents = str_replace('__DATE__', $date, $mailcontents);
        $mailcontents = str_replace('__URL__', $url, $mailcontents);
        $subject = $mailcontents[1];
        $message = $mailcontents[0];
        $emailWrapper->sendMail($email, "", $subject, $message, $message);
    }

}

$ab = new downloadfile();
