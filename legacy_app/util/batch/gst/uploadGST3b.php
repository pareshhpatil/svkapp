<?php

include_once('../config.php');
require_once MODEL . 'CommonModel.php';

class Save3BFile {

    public $db = NULL;
    public $logger = NULL;
    public $common = NULL;
    public $encrypt = NULL;

    function __construct() {
        $this->db = new DBWrapper();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->common = new CommonModel();
        $this->encrypt = new Encryption();
        $this->saving();
        $this->status();
    }

    function getBulkfile() {
        try {
            $sql = "select * from iris_3b_upload where status=0";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function get3blist() {
        try {
            $sql = "select * from iris_3b_upload where status=5";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function updateStatus($bulk_id, $file_id, $status) {
        try {
            $sql = "update iris_3b_upload set status=:status,iris_id=:file_id where bulk_upload_id=:bulk_upload_id";
            $params = array(':status' => $status, ':file_id' => $file_id, ':bulk_upload_id' => $bulk_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getIRISGSTDetails() {
        try {
            $sql = "select * from config where config_type='IRIS_GST_DATA'";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function saving() {
        $irisdetails = $this->getIRISGSTDetails();
        require_once UTIL . 'IRISAPI.php';
        $irisapi = new IRISAPI($irisdetails);
        $list = $this->getBulkfile();
        foreach ($list as $row) {
            $irisapi->deleteGSTR($row['fp'], $row['gstin'],'GSTR3B');
            $response = $irisapi->submitGSTR3b($row['system_filename'], $row['fp'], $row['gstin']);
            $this->logger->info(__CLASS__, 'Iris Responce: ' . json_encode($response));
            if ($response['response']['status'] == 'UPLOADED') {
                $this->updateStatus($row['bulk_upload_id'], $response['response']['id'], 1);
            } else {
                $this->updateStatus($row['bulk_upload_id'], $response['response']['id'], 2);
                $this->logger->error(__CLASS__, 'Failed GST3B Upload Responce: ' . json_encode($response));
            }

            unlink($row['system_filename']);
        }
    }

    function status() {
        $irisdetails = $this->getIRISGSTDetails();
        require_once UTIL . 'IRISAPI.php';
        $irisapi = new IRISAPI($irisdetails);
        $list = $this->get3blist();
        foreach ($list as $row) {
            $response = $irisapi->getGSTSaveSTatus($row['fp'], $row['gstin'], 'GSTR3B');
            //$response = $irisapi->getGSTData($row['gstin'], $row['fp'],'RETSUM', 'GSTR3B');
            $this->logger->info(__CLASS__, '3B submit Responce: ' . json_encode($response));
            if ($response['response']['status'] == 4) {
                $this->updateStatus($row['bulk_upload_id'], $row['iris_id'], 6);
            }
        }
    }

}

new Save3BFile();
