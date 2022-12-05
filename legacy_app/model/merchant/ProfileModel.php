<?php

class ProfileModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getPersonalDetails($userid)
    {
        try {
            $sql = "select first_name,last_name,email_id,user.mob_country_code,user.mobile_no from user where user.user_id=:user_id";
            $params = array(':user_id' => $userid);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E115]Error while fetching personal details Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getMerchantDetails($merchant_id)
    {
        try {
            $sql = "select m.merchant_id,m.is_legal_complete,m.merchant_website ,entity_type,industry_type,p.company_name,p.gst_number,p.pan,p.tan,p.cin_no,city,state,country,zipcode,address,business_email,business_contact,reg_address,reg_state,reg_city,reg_zipcode,reg_country from merchant m inner join merchant_billing_profile p on m.merchant_id=p.merchant_id and p.is_default=1 where m.merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E116]Error while fetching merchant details Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getMerchantSettings($merchant_id)
    {
        try {
            $sql = "select * from merchant_setting where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E117]Error while fetching merchant details Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getEntityType()
    {
        try {
            $sql = "select config_key, config_value from config where config_type='user_type'";
            $params = array();
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E118]Error while fetching entity type list Error: ' . $e->getMessage());
        }
    }

    public function getIndustryType()
    {
        try {
            $sql = "select config_key, config_value from config where config_type='industry_type'";
            $params = array();
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E119]Error while fetching entity type list Error: ' . $e->getMessage());
        }
    }

    public function getMerchantCurrency($currency)
    {
        try {
            $sql = "select * from currency where code in (" . $currency . ")";
            $params = array();
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E119]Error while Merchant Currency Error: ' . $e->getMessage());
        }
    }

    public function getBankDetails($merchant_id)
    {
        try {
            $sql = "select * from merchant_bank_detail where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E120]Error while fetching personal details Error:for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function getPGDetails($merchant_id)
    {
        try {
            $sql = "select * from merchant_fee_detail where merchant_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E121]Error while fetching personal details Error:for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function pesonalUpdate(
        $user_id,
        $merchant_id,
        $email,
        $firstName,
        $lastName,
        $mobcountryCode,
        $mobile,
        $address,
        $address2,
        $city,
        $state,
        $country,
        $zip,
        $type,
        $industry_type,
        $company,
        $company_registration_number,
        $gst_number,
        $cin_no,
        $pan,
        $tan,
        $current_address,
        $current_address2,
        $current_city,
        $current_state,
        $current_country,
        $current_zip,
        $business_email,
        $country_code,
        $business_contact,
        $phone_on_invoice
    ) {
        try {
            $sql = "call merchantProfileUpdate(:user_id,:merchant_id,:firstName,:lastName,:mobile,:address,"
                . ":city,:state,:country,:zip,:type,:industry_type,:company,:company_registration_number,:gst_number,:cin_no,:pan,:tan,:current_address,:current_city,:current_state,:current_country"
                . ",:current_zip,:business_email,:country_code,:business_contact)";
            $params = array(
                ':user_id' => $user_id, ':merchant_id' => $merchant_id, ':firstName' => ucfirst($firstName), ':lastName' => ucfirst($lastName),
                ':mobile' => $mobile, ':address' => $address, ':city' => $city, ':state' => $state,
                ':country' => $country, ':zip' => $zip, ':type' => $type, ':industry_type' => $industry_type, ':company' => $company, ':company_registration_number' => $company_registration_number, ':gst_number' => $gst_number, ':cin_no' => $cin_no, ':pan' => $pan, ':tan' => $tan,
                ':current_address' => $current_address, ':current_city' => $current_city, ':current_state' => $current_state,
                ':current_country' => $current_country, ':current_zip' => $current_zip, ':business_email' => $business_email, ':country_code' => $country_code, ':business_contact' => $business_contact
            );
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['message'] == 'success') {
                return $row['message'];
            } else {
                $sql = "show errors";
                $params = array();
                $this->db->exec($sql, $params);
                $row = $this->db->single();
                return $row['Message'];
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E122]Error while Updating personal details Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function completeRegister(
        $user_id,
        $merchant_id,
        $f_name,
        $l_name,
        $address,
        $address2,
        $city,
        $state,
        $country,
        $zip,
        $type,
        $industry_type,
        $company_registration_number,
        $pan,
        $current_address,
        $current_address2,
        $current_city,
        $current_state,
        $current_country,
        $current_zip,
        $business_email,
        $country_code,
        $business_contact
    ) {
        try {
            $sql = "call merchant_info_saved(:user_id,:merchant_id,:f_name,:l_name,:address,"
                . ":city,:state,:country,:zip,:type,:industry_type,:company_registration_number,:pan,:current_address,:current_city,:current_state,:current_country"
                . ",:current_zip,:business_email,:country_code,:business_contact)";
            $params = array(':user_id' => $user_id, ':merchant_id' => $merchant_id, ':f_name' => $f_name, ':l_name' => $l_name, ':address' => $address, ':city' => $city, ':state' => $state, ':country' => $country, ':zip' => $zip, ':type' => $type, ':industry_type' => $industry_type, ':company_registration_number' => $company_registration_number, ':pan' => $pan, ':current_address' => $current_address, ':current_city' => $current_city, ':current_state' => $current_state, ':current_country' => $current_country, ':current_zip' => $current_zip, ':business_email' => $business_email, ':country_code' => $country_code, ':business_contact' => $business_contact);

            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['message'] == 'success') {
                return $row['message'];
            } else {
                $sql = "show errors";
                $params = array();
                $this->db->exec($sql, $params);
                $row = $this->db->single();
                return $row['Message'];
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E123]Error while complete merchant Register Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function saveBankDetail($merchant_id, $account_number, $account_holder_number, $ifsc_code, $bank_name, $account_type, $doc_adhar_card, $doc_pan_card, $doc_cancelled_cheque, $gst_cer, $gst_available)
    {
        try {
            $sql = "INSERT INTO `merchant_bank_detail`(`merchant_id`,`account_no`,`account_holder_name`,`ifsc_code`,`account_type`,`bank_name`,
`adhar_card`,`pan_card`,`cancelled_cheque`,`gst_certificate`,`gst_available`,`created_by`,`created_date`,`last_update_by`)VALUES(:merchant_id,:account_no,:account_holder_name,:ifsc_code,:account_type,:bank_name,:adhar_card,:pan_card,:cancelled_cheque,:gst_certificate,:gst_available,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";
            $params = array(':merchant_id' => $merchant_id, ':account_no' => $account_number, ':account_holder_name' => $account_holder_number, ':ifsc_code' => $ifsc_code, ':bank_name' => $bank_name, ':adhar_card' => $doc_adhar_card, ':pan_card' => $doc_pan_card, ':cancelled_cheque' => $doc_cancelled_cheque, ':gst_certificate' => $gst_cer, ':gst_available' => $gst_available, ':account_type' => $account_type);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E123+90]Error while save Bank Detail Error: for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function updateBankDetail($bank_det_id, $merchant_id, $account_number, $account_holder_number, $ifsc_code, $bank_name, $account_type = 1)
    {
        try {
            $sql = "update `merchant_bank_detail` set `account_no`=:account_no,`account_holder_name`=:account_holder_name,`ifsc_code`=:ifsc_code
                ,`bank_name`=:bank_name,`account_type`=:account_type, `last_update_by`=:merchant_id where bank_detail_id=:bank_det_id;";

            $params = array(
                ':bank_det_id' => $bank_det_id, ':merchant_id' => $merchant_id, ':account_no' => $account_number, ':account_holder_name' => $account_holder_number,
                ':ifsc_code' => $ifsc_code, ':bank_name' => $bank_name, ':account_type' => $account_type
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E123+91]Error while update Bank Detail Error: for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function updateMerchantWebsite($merchant_id, $website)
    {
        try {
            $sql = "update `merchant` set `merchant_website`=:website where merchant_id=:merchant_id;";

            $params = array(':website' => $website, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E123+91]Error while update Merchant Website Error: for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function saveMerchantPGdetails($merchant_id)
    {
        try {
            $sql = "call save_merchant_PG_details(:merchant_id)";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E124]Error while saveMerchantPGdetails Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function updateMerchantDoc($merchant_id, $doc_col, $value)
    {
        try {
            if ($value != '') {
                $sql = "select bank_detail_id from merchant_bank_detail where merchant_id=:merchant_id";
                $params = array(':merchant_id' => $merchant_id);
                $this->db->exec($sql, $params);
                $row = $this->db->single();
                if (empty($row)) {
                    $sql = "INSERT INTO `merchant_bank_detail`(`merchant_id`,`account_holder_name`,`account_no`,`ifsc_code`,
`account_type`,`bank_name`,`adhar_card`,`pan_card`,`cancelled_cheque`,`gst_certificate`,`is_active`,`created_by`,`created_date`,`last_update_by`)
VALUES(:merchant_id,'','','',0,'','','','','',1,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";
                    $params = array(':merchant_id' => $merchant_id);
                    $this->db->exec($sql, $params);
                    $detail_id = $this->db->lastInsertId();
                } else {
                    $detail_id = $row['bank_detail_id'];
                }
                $sql = "update merchant_bank_detail set " . $doc_col . "='" . $value . "' where bank_detail_id=:bank_detail_id";
                $params = array(':bank_detail_id' => $detail_id);
                $this->db->exec($sql, $params);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E124+96]Error while updateMerchantDoc Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function uploadDocument($image_file, $merchant_id, $type)
    {
        try {
            if (empty($image_file)) {
                return '';
            }
            if ($image_file['name'] == '') {
                return '';
            }
            $filename = 'uploads/documents/' . $merchant_id;
            if (file_exists($filename)) {
            } else {
                mkdir($filename, 0755);
            }
            $merchant_filename = basename($image_file['name']);
            $merchant_filename = preg_replace('/[^\p{L}\p{N}\s]/u', '', $merchant_filename);
            $merchant_filename = str_replace(' ', '_', $merchant_filename);
            $filename = $image_file['name'];
            $ext = substr($filename, strrpos($filename, '.') + 1);
            $system_filename = $type . '_' . time() . '.' . $ext;
            $newname = 'uploads/documents/' . $merchant_id . '/' . $system_filename;
            if ((move_uploaded_file($image_file['tmp_name'], $newname))) {
                return $system_filename;
            } else {

                SwipezLogger::error(__CLASS__, '[E251]Error while uploading documents Error: for merchant id [' . $merchant_id . '] ');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E252]Error while uploading documents Error: for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function settingUpdate($user_id, $merchant_id, $show_add, $auto_approve, $is_reminder, $password_validation)
    {
        try {
            $sql = "update merchant_setting set show_ad=:show_ad,auto_approve=:auto_approve,is_reminder=:is_reminder,password_validation=:password_validation,last_update_by=:user_id where merchant_id=:merchant_id";
            $params = array(':show_ad' => $show_add, ':auto_approve' => $auto_approve, ':is_reminder' => $is_reminder, ':password_validation' => $password_validation, ':user_id' => $user_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            return 1;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+1]Error while update merchant setting details Error:for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function updatetaxation($user_id, $merchant_id, $taxation_type)
    {
        try {
            $sql = "update merchant_setting set product_taxation=:taxation_type, last_update_by=:user_id where merchant_id=:merchant_id";
            $params = array(':taxation_type' => $taxation_type,  ':user_id' => $user_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            return 1;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+1]Error while update merchant setting details Error:for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function updateProfileCompany($merchant_id, $entity, $industry, $pan, $gst_number, $user_id)
    {
        try {
            $sql = "update merchant set entity_type=:entity_type,industry_type=:industry_type,last_update_by=:user_id where merchant_id=:merchant_id";
            $params = array(':entity_type' => $entity, ':industry_type' => $industry, ':user_id' => $user_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);

            $sql = "update merchant_billing_profile set pan=:pan,gst_number=:gst_number,last_update_by=:user_id where merchant_id=:merchant_id and is_default=1";
            $params = array(':pan' => $pan, ':gst_number' => $gst_number, ':user_id' => $user_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            return 1;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+169]Error while update Profile company Error:for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function updateProfileUserContact($first_name, $last_name, $user_id)
    {
        try {
            $sql = "update user set first_name=:first_name,last_name=:last_name,last_updated_by=:user_id where user_id=:user_id";
            $params = array(':first_name' => $first_name, ':last_name' => $last_name, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+170]Error while update Profile Contact Error:for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

    public function updateProfileMerchantContact($merchant_id, $business_email, $business_contact, $address1, $city, $state, $zipcode, $country, $user_id, $reg_address, $reg_city, $reg_state, $reg_zipcode, $reg_country)
    {
        try {
            $sql = "update merchant_billing_profile set business_email=:business_email,business_contact=:business_contact,address=:address1,city=:city,state=:state,zipcode=:zipcode,country=:country,reg_address=:reg_address,reg_city=:reg_city,reg_state=:reg_state,reg_zipcode=:reg_zipcode,reg_country=:reg_country,last_update_by=:user_id where merchant_id=:merchant_id and is_default=1";
            $params = array(':business_email' => $business_email, ':business_contact' => $business_contact, ':address1' => $address1, ':city' => $city, ':state' => $state, ':zipcode' => $zipcode, ':country' => $country, ':reg_address' => $reg_address, ':reg_city' => $reg_city, ':reg_state' => $reg_state, ':reg_zipcode' => $reg_zipcode, ':reg_country' => $reg_country, ':merchant_id' => $merchant_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return 1;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+170]Error while update Profile Contact Error:for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

    public function existPrefix($merchant_id, $subscript)
    {
        try {
            $sql = "select count(*) as excount from merchant_auto_invoice_number where merchant_id=:merchant_id and prefix=:prefix and is_active=1";
            $params = array(':merchant_id' => $merchant_id, ':prefix' => $subscript);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['excount'] > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E125]Error while fetching personal details Error:for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function saveInvoiceNumber($user_id, $merchant_id, $subscript, $val, $type = 1)
    {
        try {
            $type = ($type > 1) ? $type : 1;
            $sql = "INSERT INTO `merchant_auto_invoice_number`(`merchant_id`,`prefix`,`val`,`type`,`created_by`,`created_date`,`last_update_by`)
                VALUES(:merchant_id,:subscript,:val,:type,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(':merchant_id' => $merchant_id, ':subscript' => $subscript, ':val' => $val, ':type' => $type, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+2]Error while update merchant setting details Error:for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function saveServiceRequest($service_id, $merge_menu, $merchant_id, $user_id)
    {
        try {
            $sql = "INSERT INTO `merchant_active_apps`(`merchant_id`,`user_id`,`service_id`,`merge_menu`,`status`,`created_by`,`created_date`,`last_update_by`)
                VALUES(:merchant_id,:user_id,:service_id,:merge_menu,:status,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(':merchant_id' => $merchant_id, ':service_id' => $service_id, ':merge_menu' => $merge_menu, ':status' => 2, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+2]Error while update merchant setting details Error:for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function updateInvoiceNumber($user_id, $merchant_id, $subscript, $val, $id)
    {
        try {
            $sql = "update merchant_auto_invoice_number set prefix=:subscript, val=:val , last_update_by=:user_id where auto_invoice_id=:id and merchant_id=:merchant_id;";
            $params = array(':merchant_id' => $merchant_id, ':subscript' => $subscript, ':val' => $val, ':id' => $id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return 1;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+3]Error while update merchant setting details Error:for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function updatePlan($plan, $merchant_id, $user_id)
    {
        try {
            $sql = "update account set is_active=0 , last_update_by=:user_id where merchant_id=:merchant_id;";
            $params = array(':merchant_id' => $merchant_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);

            $sql = "update merchant set merchant_plan=:plan , last_update_by=:user_id where merchant_id=:merchant_id;";
            $params = array(':merchant_id' => $merchant_id, ':plan' => $plan, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->addPackageAccount($plan, $merchant_id, $user_id);
            return 1;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+4]Error while update merchant setting details Error:for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function addPackageAccount($plan_id, $merchant_id, $user_id)
    {
        try {
            $sql = "INSERT INTO `account`(`merchant_id`,`package_id`,`fee_transaction_id`,`amount_paid`,`individual_invoice`,
`bulk_invoice`,`free_sms`,`pg_integration`,`site_builder`,`brand_keyword`,`total_invoices`,`merchant_role`,`coupon`,`supplier`,`start_date`,`end_date`,`created_by`,`created_date`,`last_update_by`)
select :merchant_id,package_id,'',0,`individual_invoice`,`bulk_invoice`,`free_sms`,`pg_integration`,`site_builder`,`brand_keyword`,`total_invoices`,`merchant_role`,coupon,supplier,NOW(),DATE_ADD(NOW(), INTERVAL `duration` MONTH),:user_id,CURRENT_TIMESTAMP(),:user_id from package where package_id=:plan_id";
            $params = array(':merchant_id' => $merchant_id, ':plan_id' => $plan_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return 1;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+6]Error while update merchant setting details Error:for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function deleteInvoiceNumber($user_id, $merchant_id, $id)
    {
        try {
            $sql = "update merchant_auto_invoice_number set is_active=0 , last_update_by=:user_id where auto_invoice_id=:id and merchant_id=:merchant_id;";
            $params = array(':merchant_id' => $merchant_id, ':id' => $id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return 1;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+5]Error while update merchant setting details Error:for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function getPackageTransaction($merchant_id)
    {
        try {
            $sql = "select * from package_transaction where merchant_id=:merchant_id and payment_transaction_status=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E118+989]Error while fetching entity type list Error: ' . $e->getMessage());
        }
    }

    public function getAddons($id)
    {
        try {
            $sql = "select p.package_name,p.package_id,a.license_bought from merchant_addon a inner join package p on a.package_id=p.package_id where a.package_transaction_id=:id";
            $params = array(':id' => $id);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E118+989]Error while fetching entity type list Error: ' . $e->getMessage());
        }
    }

    public function updatepreferences($user_id, $sms, $email)
    {
        try {
            $sql = "update preferences set send_sms=:sms,send_email=:email where user_id=:user_id";
            $params = array(':user_id' => $user_id, ':sms' => $sms, ':email' => $email);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E192]Error while updating preferences Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function updateKeyDetail($id, $merchant_id, $user_id)
    {
        try {
            $access_key = sha1($merchant_id . $user_id . rand(9999, 999999));
            $secret_key = sha1($user_id . $merchant_id . rand(9999, 999999));
            $sql = "update merchant_security_key set access_key_id=:access_key,secret_access_key=:secret_key where key_id=:id";
            $params = array(':id' => $id, ':access_key' => $access_key, ':secret_key' => $secret_key);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E192]Error while updating security key Error: for user id[' . $id . ']' . $e->getMessage());
        }
    }

    public function saveKeyDetail($merchant_id, $user_id)
    {
        try {
            $access_key = sha1($merchant_id . $user_id . rand(9999, 999999));
            $secret_key = sha1($user_id . $merchant_id . rand(9999, 999999));
            $sql = "INSERT INTO `merchant_security_key`(`merchant_id`,`user_id`,`access_key_id`,`secret_access_key`,`created_date`)"
                . "VALUES(:merchant_id,:user_id,:access_key,:secret_key,CURRENT_TIMESTAMP());";
            $params = array(':merchant_id' => $merchant_id, ':user_id' => $user_id, ':access_key' => $access_key, ':secret_key' => $secret_key);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E123+90]Error while save key Detail Error: for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function saveMerchantData($merchant_id, $user_id, $key, $value, $exist)
    {
        try {
            if ($exist == true) {
                $sql = "update `merchant_config_data` set value=:value where merchant_id=:merchant_id and `key`=:key";
                $params = array(':merchant_id' => $merchant_id, ':key' => $key, ':value' => $value);
            } else {
                $sql = "INSERT INTO `merchant_config_data`(`merchant_id`,`user_id`,`key`,`value`,`created_date`)"
                    . "VALUES(:merchant_id,:user_id,:key,:value,CURRENT_TIMESTAMP());";
                $params = array(':merchant_id' => $merchant_id, ':user_id' => $user_id, ':key' => $key, ':value' => $value);
            }
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E123+90]Error while save key Detail Error: for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function getStates()
    {
        try {
            $sql = "select config_value from config where config_type=:gst_state_type";
            $params = array(':gst_state_type' => 'gst_state_code');
            $this->db->exec($sql, $params);
            $result = $this->db->resultSet();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E123+90]Error while getting states' . $e->getMessage());
        }
    }

    public function convertMerchant($user_id)
    {
        try {
            $sql = "call `patron_register`('',:user_id,'','','','','','',0,'',1);";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E123+90]Error while save key Detail Error: for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function saveExpenseMaster($table, $name, $merchant_id, $user_id)
    {
        try {
            $sql = "INSERT INTO `" . $table . "`(`merchant_id`,`name`,`created_by`,`last_update_by`,`created_date`)"
                . "VALUES(:merchant_id,:name,:user_id,:user_id,CURRENT_TIMESTAMP());";
            $params = array(':merchant_id' => $merchant_id, ':name' => $name, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E123+90]Error while save Bank Detail Error: for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    function getToken($merchant_id)
    {
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

            SwipezLogger::error(__CLASS__, '[E2]Error while get token Error: ' . $e->getMessage());
        }
    }

    public function getBeneficiaryId($merchant_id, $bank_account_no, $ifsc_code)
    {
        try {
            $sql = "select beneficiary_id from beneficiary where merchant_id=:merchant_id and bank_account_no=:bank_account_no and ifsc_code=:ifsc_code and status=1";
            $params = array(':merchant_id' => $merchant_id, ':bank_account_no' => $bank_account_no, ':ifsc_code' => $ifsc_code);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return false;
            } else {
                return $row['beneficiary_id'];
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, '[E120]Error while fetching beneficiary details Error:for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function saveGST($merchant_id, $user_id, $profile_name, $gst_number, $company_name, $state, $address, $business_email, $business_contact, $seq_id, $currency)
    {
        try {
            $sql = "INSERT INTO `merchant_billing_profile`(`merchant_id`,`profile_name`,`gst_number`,`company_name`,`state`,`address`,`invoice_seq_id`,`business_email`,`business_contact`,`currency`,`created_by`,`created_date`,`last_update_by`)"
                . "VALUES(:merchant_id,:profile_name,:gst_number,:company_name,:state,:address,:seq_id,:business_email,:business_contact,:currency,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(
                ':profile_name' => $profile_name, ':merchant_id' => $merchant_id, ':gst_number' => $gst_number, ':company_name' => $company_name, ':state' => $state, ':address' => $address, ':business_email' => $business_email, ':business_contact' => $business_contact, ':currency' => $currency, ':seq_id' => $seq_id,
                ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
            return 1;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+3]Error while update merchant setting details Error:for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function updateGST($id, $profile_name, $merchant_id, $user_id, $address, $business_email, $business_contact, $seq_id)
    {
        try {
            $sql = "update merchant_billing_profile set profile_name=:profile_name,address=:address,business_email=:business_email,business_contact=:business_contact,invoice_seq_id=:seq_id,last_update_by=:user_id where id=:id and merchant_id=:merchant_id";
            $params = array(
                ':id' => $id, ':profile_name' => $profile_name, ':merchant_id' => $merchant_id, ':address' => $address, ':business_email' => $business_email, ':business_contact' => $business_contact, ':seq_id' => $seq_id,
                ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);

            return 1;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E1236+3]Error while update merchant setting details Error:for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }
}
