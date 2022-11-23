<?php

include_once('../config.php');
require_once MODEL . 'CommonModel.php';

class BulkUpload {

    public $db = NULL;
    public $logger = NULL;
    public $common = NULL;
    public $encrypt = NULL;

    function __construct() {

        $this->db = new DBWrapper();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->common = new CommonModel();
        $this->encrypt = new Encryption();
    }

    function getbulkuploadlist($staus, $type) {
        try {
            $sql = "SELECT * FROM bulk_upload where status=:status and type=:type";
            $params = array(':status' => $staus, ':type' => $type);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E1]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
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

    public function moveExcelFile($merchant_id, $folder, $filename) {
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

    public function updateBulkUploadStatus($bulk_id, $status, $error = '') {
        try {
            $sql = "update bulk_upload set status=:status,error_json=:error where bulk_upload_id=:bulk_id";
            $params = array(':status' => $status, ':error' => $error, ':bulk_id' => $bulk_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E4]Error while update bulk upload status Bulk id: ' . $bulk_id . ' Status: ' . $status . ' Error: ' . $e->getMessage());
        }
    }

    function onlineSettlementStatus($merchant_id) {
        $security_key = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $merchant_id, 1);
        $enable_online_settlement = 0;
        if (!empty($security_key)) {
            if ($security_key['cashfree_client_id'] != '') {
                $enable_online_settlement = 1;
            }
        }
        return $enable_online_settlement;
    }

    function rchar() {
        return substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
    }

    function getToken($merchant_id) {
        try {
            $sql = "select u.user_id,u.email_id from merchant m inner join user u on u.user_id=m.user_id where m.merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            $token = hash("sha256", rand());
            $sql = "INSERT INTO `login_token`(`email`,`user_id`,`token`,`created_by`,`created_date`)"
                    . "VALUES(:email,:user_id,:token,:user_id,CURRENT_TIMESTAMP());";
            $params = array(':email' => $row['email_id'], ':user_id' => $row['user_id'], ':token' => $token);
            $this->db->exec($sql, $params);
            return $token;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    public function apisrequest($api_url, $post_string, $header) {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_string,
                CURLOPT_HTTPHEADER => $header,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            return $response;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E003]Error while swipez apis curl Error: ' . $e->getMessage());
        }
    }

    public function filterPost() {
        $ignore_array = array('description', 'overview', 'cancellation_policy', 'terms_condition', 'about_us', 'event_tnc', 'body', 'custom_covering', 'tnc', 'exist_cc');
        if (!isset($_POST['site_builder'])) {
            foreach ($_POST as $key => $value) {
                if (is_array($_POST[$key])) {
                    if (!in_array($key, $ignore_array)) {
                        $filterarray = array();
                        foreach ($_POST[$key] as $postarray) {
                            $string = $this->replaceHTMLTags($postarray);
                            $filterarray[] = $this->xss_clean($string);
                        }
                        $_POST[$key] = $filterarray;
                    }
                } else {
                    if (!in_array($key, $ignore_array)) {
                        $string = $this->replaceHTMLTags($_POST[$key]);
                        $_POST[$key] = $string;
                    }
                }
            }
            foreach ($_POST as $key => $value) {
                if (is_array($_POST[$key])) {
                    if (!in_array($key, $ignore_array)) {
                        $filterarray = array();
                        foreach ($_POST[$key] as $postarray) {
                            $string = $this->replaceHTMLTags($postarray);
                            $filterarray[] = strip_tags($string);
                        }
                        $_POST[$key] = $filterarray;
                    }
                } else {
                    if (!in_array($key, $ignore_array)) {
                        $string = $this->replaceHTMLTags($_POST[$key]);
                        $_POST[$key] = strip_tags($string);
                    }
                }
            }
        }

        foreach ($ignore_array as $key) {
            if (isset($_POST[$key])) {
                $_POST[$key] = str_replace(array("'", "~"), "", $_POST[$key]);
            }
        }
    }

    function replaceHTMLTags($value) {
        $string = str_replace(array("\r\n", "\r", "\n"), "<br>", $value);
        $string = str_replace("%20", " ", $string);
        return $string;
    }

    public function xss_clean($data) {
        // Fix &entity\n;
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        // we are done...
        $data = str_replace('">', '', $data);
        $data = str_replace("'>", '', $data);
        $data = str_replace("'", '', $data);
        $data = str_replace('"', '', $data);
        $data = str_replace('onMouseOver=', '', $data);
        return $data;
    }

}
