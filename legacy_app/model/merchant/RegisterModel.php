<?php

/**
 * Register patron model works on tables required for Patron registration
 *
 * @author Shuhaid
 */
class RegisterModel extends Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * Method saves a new merchant to the database
     * 
     * @param type $email
     * @param type $fname
     * @param type $lname
     * @param type $mobile
     * @param type $landline
     * @param type $password
     * @param type $dob
     * @param type $address
     * @param type $city
     * @param type $state
     * @param type $zip
     * @return string
     */
    public function createMerchant($email, $first_name, $last_name, $mobile_code, $mobile, $password, $company, $plan_id, $campaign_id, $license_id, $service_id) {
        try {
            $sql = "call `merchant_register`(:email,:first_name,:last_name,:mob_code,:mobile,:password,:company,:plan_id,:campaign_id,:license_id,:service_id);";
            $params = array(':email' => $email, ':first_name' => $first_name, ':last_name' => $last_name, ':mob_code' => $mobile_code, ':mobile' => $mobile,
                ':password' => $password, ':company' => $company, ':plan_id' => $plan_id, ':campaign_id' => $campaign_id, ':license_id' => $license_id, ':service_id' => $service_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            $this->setDisplayURL($row['merchant_id'], $company);
            $this->db->closeStmt();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E122]Error while creating merchant Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function setDisplayURL($merchant_id, $company_name) {
        try {
            $company_name = str_replace(' ', '', $company_name);
            $display_url = strtolower($company_name);
            $is_exist = true;
            $int = 1;
            while ($is_exist == true) {
                $sql = 'select display_url from merchant where display_url=:display_url';
                $params = array(':display_url' => $display_url);
                $this->db->exec($sql, $params);
                $row = $this->db->single();
                if (empty($row)) {
                    $is_exist = false;
                } else {
                    $display_url = $display_url . $int;
                    $int++;
                }
            }
            $this->updateDisplayURL($merchant_id, $display_url);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E105-78]Error while getInvoiceNumber auto id ' . $auto_invoice_id . ' user id:' . $user_id . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateDisplayURL($merchant_id, $display_url) {
        try {
            $sql = "update merchant set display_url=:display_url where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id, ':display_url' => $display_url);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E212]Error while getting package details Error:  for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function createMerchantEntity($merchant_id, $address, $address2, $city, $state, $country, $zip, $type, $industrytype, $company, $registration_no, $pan, $current_address, $current_address2, $current_city, $current_state, $current_country, $current_zip, $business_email, $business_contact_code, $business_contact) {
        try {
            $zero = 0;
            $empty = '';
            $sql = "call `entity_save`(:username,:address,:address2,:city,:state,:country,:zip,:type,:industry_type,:company,:company_registration_number,:pan,:current_address,:current_address2,:current_city,:current_state,:current_country,:current_zip,:business_email,:business_contact_code,:business_contact);";
            $params = array(':username' => $merchant_id, ':address' => $address, ':address2' => $address2, ':city' => $city, ':state' => $state, ':country' => $country, ':zip' => $zip, ':type' => $type, ':industry_type' => $industrytype, ':company' => $company, ':company_registration_number' => $registration_no, ':pan' => $pan, ':current_address' => $current_address, ':current_address2' => $current_address2, ':current_city' => $current_city, ':current_state' => $current_state, ':current_country' => $current_country, ':current_zip' => $current_zip, ':business_email' => $business_email, ':business_contact_code' => $business_contact_code, ':business_contact' => $business_contact);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (isset($row['email_id']) && $row['email_id'] != '') {
                $row['Message'] = 'success';
            } else {
                $sql = "show errors";
                $params = array();
                $this->db->exec($sql, $params);
                $row = $this->db->single();
            }
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E123]Error while saving entity details Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Method is used to sending email
     * 
     * @param type $str
     */
    public function sendMail($concatStr_, $toEmail_, $type, $company_name = NULL, $first_name = NULL, $last_name = NULL, $contact = NULL) {
        try {
            $emailWrapper = new EmailWrapper();
            if ($type == 'verify') {
                $baseurl = $this->host . '://' . $_SERVER['SERVER_NAME'];
                $verifyemailurl = $baseurl . '/merchant/register/verifyemail/' . $concatStr_ . '';
                $mailcontents = $emailWrapper->fetchMailBody("user.verifyemail");
                if (isset($mailcontents[0]) && isset($mailcontents[1])) {
                    $message = $mailcontents[0];
                    $message = str_replace('__EMAILID__', $toEmail_, $message);
                    $message = str_replace('__LINK__', $verifyemailurl, $message);
                    $message = str_replace('__BASEURL__', $baseurl, $message);

                    #($toEmail_, $toName_, $subject_, $messageHTML_, $messageText_ = NULL)
                    $emailWrapper->sendMail($toEmail_, "", $mailcontents[1], $message);
                } else {
                    SwipezLogger::warn("Mail could not be sent with verify email link to : " . $toEmail_);
                }
            } else {
                $mailcontents = $emailWrapper->fetchMailBody("merchant.welcomemail");
                if (isset($mailcontents[0]) && isset($mailcontents[1])) {
                    $message = $mailcontents[0];
                    $message = str_replace('__BASEURL__', $baseurl, $message);

                    #($toEmail_, $toName_, $subject_, $messageHTML_, $messageText_ = NULL)
                    $emailWrapper->sendMail($toEmail_, "", $mailcontents[1], $message);
                } else {
                    SwipezLogger::warn("Mail could not be sent with verify email link to : " . $toEmail_);
                }
                /*
                 * Mail to support@swipez.in after new merchant signup
                 */
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E124]Error while sending mail Error: ' . $e->getMessage());
        }
    }

    /**
     * Method is used to fetch concatenated userid and created_date from DB 
     * 
     * @param type $userid
     * @return userid in string format 
     */
    public function getUserTimeStamp($userid) {
        try {
            $sql = "select  CONCAT(user_id, created_date) as username from user where user_id=:user_id";
            $params = array(':user_id' => $userid);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['username'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E125]Error while getting user timespam Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    /*
     * Checks patrons user id and created date against the link clicked by the user
     * 
     * @param type $url
     * @return userid in string format
     */

    public function validateEmailVerificationLink($link) {
        try {
            $converter = new Encryption;
            $url = $converter->decode($link);

            $user = substr($url, 0, 10);
            $credate = substr($url, 10, 19);
            $returnurl = substr($url, 29);
            $sql = "SELECT u.user_id,u.user_status,u.email_id,m.merchant_id FROM `user` u left outer join merchant m on m.user_id=u.user_id where u.user_id=:user_id and u.created_date=:credate and u.user_status in(11,19,15)";
            $params = array(':user_id' => $user, ':credate' => $credate);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['user_id'] != '') {
                if ($row['user_status'] == 11) {
                    $this->updateMerchantUserStatus(15, $row['user_id']);
                    $this->changeOnboardingStatus($row['user_id'], 2);
                    $row['type'] = 1;
                } else if ($row['user_status'] == 19) {
                    $this->updateMerchantUserStatus(20, $row['user_id']);
                    $row['type'] = 2;
                }
                $row['plan_id'] = $returnurl;
                return $row;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E126]Error while validate email verification link Error: ' . $e->getMessage());
        }
    }

    /**
     * method is used to update user status 
     * 
     * @param type $status int
     * @param type $username string
     */
    public function updateMerchantUserStatus($status, $username) {
        try {
            $sql = 'UPDATE `user` SET `prev_status`=user_status,`user_status`=:status,last_updated_by=`user_id`'
                    . ', last_updated_date=CURRENT_TIMESTAMP() WHERE user_id=:user_id';

            $params = array(':status' => $status, ':user_id' => $username);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E127]Error while updating merchant user status Error: ' . $e->getMessage());
        }
    }

    public function changeOnboardingStatus($user_id, $status) {
        try {
            $sql = "update merchant set merchant_status=:status where user_id=:user_id";
            $params = array(':user_id' => $user_id, ':status' => $status);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E212]Error while getting package details Error:  for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function emailAlreadyExists($emailId_, $user_id = null) {
        try {
            $sql = "SELECT `user_id` FROM `user` where email_id=:email";
            $params = array(':email' => $emailId_);

            $this->db->exec($sql, $params);
            $row = $this->db->single();

            if (isset($row['user_id'])) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E128]Error while checking email exist Error: ' . $e->getMessage());
        }
    }

    public function getEntityType() {
        try {
            $sql = "select config_key, config_value from config where config_type='user_type'";
            $params = array();
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E129]Error while fetching entity type list Error: ' . $e->getMessage());
        }
    }

    public function getIndustryType() {
        try {
            $sql = "select config_key, config_value from config where config_type='industry_type'";
            $params = array();
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E130]Error while fetching entity type list Error: ' . $e->getMessage());
        }
    }

    public function getUserId($email) {
        try {
            $sql = "select user_id from user where email_id=:email_id and user_status>11";
            $params = array(':email_id' => $email);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return false;
            } else {
                return $row['user_id'];
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E131]Error while fetching user list data Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function getUserList($user_id) {
        try {
            $sql = "select email_id,mob_country_code,mobile_no from user where user_id=:user_id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->single();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E131]Error while fetching user list data Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function saveCampaignAcquisitions($campaign_id, $type, $merchant_id, $company_name, $email, $mobile, $utm_source, $utm_campaign, $utm_adgroup, $utm_term) {
        try {
            $sql = "INSERT INTO `campaign_acquisitions`(`campaign_id`,`type`,`merchant_id`,`company_name`,`email`,`mobile`,`utm_source`,`utm_campaign`,`utm_adgroup`,`utm_term`,`created_date`)
VALUES(:campaign_id,:type,:merchant_id,:company_name,:email,:mobile,:utm_source,:utm_campaign,:utm_adgroup,:utm_term,CURRENT_TIMESTAMP());";
            $params = array(':campaign_id' => $campaign_id, ':type' => $type, ':merchant_id' => $merchant_id, ':company_name' => $company_name
                , ':email' => $email, ':mobile' => $mobile, ':utm_source' => $utm_source, ':utm_campaign' => $utm_campaign, ':utm_adgroup' => $utm_adgroup, ':utm_term' => $utm_term);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E130+56]Error while save Campaign Acquisitions Error: ' . $e->getMessage());
        }
    }

}
