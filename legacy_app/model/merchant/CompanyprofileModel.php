<?php

/**
 * This is merchant company profile class for display and save merchant company profile
 * @author Paresh
 */
class CompanyprofileModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Save merchant overview
     */
    public function saveHome($merchant_id, $overview, $display_url, $website)
    {
        try {
            $sql = "update merchant set merchant_website=:website,display_url=:display_url where merchant_id=:merchant_id;";
            $params = array(':website' => $website, ':display_url' => $display_url, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);

            $row = array();
            $sql = "select merchant_landing_id from merchant_landing where merchant_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                $sql = "INSERT INTO `swipez`.`merchant_landing`(`merchant_id`,`overview`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)Values(:merchant_id,:overview,:created_by,CURRENT_TIMESTAMP(),:update_by,CURRENT_TIMESTAMP())";
                $params = array(':merchant_id' => $merchant_id, ':overview' => $overview, ':created_by' => $merchant_id, ':update_by' => $merchant_id);
                $this->db->exec($sql, $params);
            } else {
                $sql = "update merchant_landing set overview=:overview,last_update_by=:update_by,last_update_date=CURRENT_TIMESTAMP() where merchant_landing_id=:landing_id;";
                $params = array(':overview' => $overview, ':update_by' => $merchant_id, ':landing_id' => $row['merchant_landing_id']);
                $this->db->exec($sql, $params);
            }

            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E098]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    /**
     * Check merchant display url is exist
     */
    public function UrlExists($url, $merchant_id)
    {
        $row = array();
        $sql = "select merchant_id from merchant where merchant_id<>:merchant_id and display_url=:url";
        $params = array(':merchant_id' => $merchant_id, ':url' => $url);
        $this->db->exec($sql, $params);
        $row = $this->db->single();
        if (empty($row)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Save merchant policies
     */
    public function savePolicies($merchant_id, $terms_condition, $cancelation)
    {
        try {
            $row = array();
            $sql = "select merchant_landing_id from merchant_landing where merchant_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                $sql = "INSERT INTO `swipez`.`merchant_landing`(`merchant_id,``terms_condition`,`cancellation_policy`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)Values(:merchant_id,:terms_condition,:cancellation_policy,:created_by,CURRENT_TIMESTAMP(),:update_by,CURRENT_TIMESTAMP())";
                $params = array(':merchant_id' => $merchant_id, ':terms_condition' => $terms_condition, ':cancellation_policy' => $cancelation, ':created_by' => $merchant_id, ':update_by' => $merchant_id);
                $this->db->exec($sql, $params);
            } else {
                $sql = "update merchant_landing set terms_condition=:terms_condition,cancellation_policy=:cancellation_policy,last_update_by=:last_update_by,last_update_date=CURRENT_TIMESTAMP() where merchant_landing_id=:landing_id;";
                $params = array(':terms_condition' => $terms_condition, ':cancellation_policy' => $cancelation, ':last_update_by' => $merchant_id, ':landing_id' => $row['merchant_landing_id']);
                $this->db->exec($sql, $params);
            }

            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E098]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    /**
     * Save merchant about
     */
    public function saveAboutus($merchant_id, $aboutus)
    {
        try {
            $row = array();
            $sql = "select merchant_landing_id from merchant_landing where merchant_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                $sql = "INSERT INTO `swipez`.`merchant_landing`(`merchant_id`,`about_us`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)Values(:merchant_id,:aboutus,:created_by,CURRENT_TIMESTAMP(),:update_by,CURRENT_TIMESTAMP())";
                $params = array(':merchant_id' => $merchant_id, ':aboutus' => $aboutus, ':created_by' => $merchant_id, ':update_by' => $merchant_id);
                $this->db->exec($sql, $params);
            } else {
                $sql = "update merchant_landing set about_us=:aboutus,last_update_by=:last_update_by,last_update_date=CURRENT_TIMESTAMP() where merchant_landing_id=:landing_id;";
                $params = array(':aboutus' => $aboutus, ':last_update_by' => $merchant_id, ':landing_id' => $row['merchant_landing_id']);
                $this->db->exec($sql, $params);
            }

            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E098]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    /**
     * Save merchant contact
     */
    public function saveContactus($merchant_id, $location, $contact_no, $email_id)
    {
        try {
            $row = array();
            $sql = "select merchant_landing_id from merchant_landing where merchant_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                $sql = "INSERT INTO `merchant_landing`(`merchant_id`,`office_location`,`contact_no`,`email_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)Values(:merchant_id,:aboutus,:created_by,CURRENT_TIMESTAMP(),:update_by,CURRENT_TIMESTAMP())";
                $params = array(':merchant_id' => $merchant_id, ':aboutus' => $aboutus, ':created_by' => $merchant_id, ':update_by' => $merchant_id);
                $this->db->exec($sql, $params);
            } else {
                $sql = "update merchant_landing set office_location=:office_location,contact_no=:contact_no,email_id=:email_id,last_update_by=:last_update_by,last_update_date=CURRENT_TIMESTAMP() where merchant_landing_id=:landing_id;";
                $params = array(':office_location' => $location, ':contact_no' => $contact_no, ':email_id' => $email_id, ':last_update_by' => $merchant_id, ':landing_id' => $row['merchant_landing_id']);
                $this->db->exec($sql, $params);
            }

            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E098]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    /**
     * Save merchant logo and banner images
     */
    public function saveImageBanner($merchant_id, $logo, $banner)
    {
        try {
            $logoimage = basename($logo['name']);
            $logoext = '.' . substr($logoimage, strrpos($logoimage, '.') + 1);
            $converter = new Encryption;
            if ($logoext != '.') {
                $logoname = $converter->encode(time()) . $logoext;
                $this->uploadImage($logo, $logoname, $merchant_id);
            } else {
                $logoname = NULL;
            }
            $bannerimage = basename($banner['name']);
            $bannerext = '.' . substr($bannerimage, strrpos($bannerimage, '.') + 1);
            if ($bannerext != '.') {
                $bannername = $converter->encode(time() . 'b') . $bannerext;
                $this->uploadImage($banner, $bannername, $merchant_id);
            } else {
                $bannername = NULL;
            }


            $row = array();
            $sql = "select merchant_landing_id,logo,banner from merchant_landing where merchant_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();

            if (empty($row)) {
                $sql = "INSERT INTO `merchant_landing`(`merchant_id`,`logo`,`banner`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)Values(:merchant_id,:logo,:banner,:created_by,CURRENT_TIMESTAMP(),:update_by,CURRENT_TIMESTAMP())";
                $params = array(':merchant_id' => $merchant_id, ':logo' => $logoname, ':banner' => $bannername, ':created_by' => $merchant_id, ':update_by' => $merchant_id);
                $this->db->exec($sql, $params);
            } else {
                $logoname = ($logoname == NULL) ? $row['logo'] : $logoname;
                $bannername = ($bannername == NULL) ? $row['banner'] : $bannername;
                $sql = "update merchant_landing set logo=:logo,banner=:banner,last_update_by=:last_update_by,last_update_date=CURRENT_TIMESTAMP() where merchant_landing_id=:landing_id;";
                $params = array(':logo' => $logoname, ':banner' => $bannername, ':last_update_by' => $merchant_id, ':landing_id' => $row['merchant_landing_id']);
                $this->db->exec($sql, $params);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E098]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    /**
     * Upload logo and banner images
     */
    public function uploadImage($image_file, $name, $merchant_id)
    {
        try {
            $logoimage = basename($name);
            $logoext = '.' . substr($logoimage, strrpos($logoimage, '.') + 1);
            $converter = new Encryption;
            if ($logoext != '.') {
                $logoname = $name;
                $newname = 'uploads/images/landing/' . $logoname;
                //Check if the file with the same name is already exists on the server
                if (file_exists($newname)) {
                    unlink($newname);
                }
                //Attempt to move the uploaded file to it's new place
                if ((move_uploaded_file($image_file['tmp_name'], $newname))) {
                    return '/' . $newname;
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E136]Error while uploading template logo Error: ' . $e->getMessage());
        }
    }
}
