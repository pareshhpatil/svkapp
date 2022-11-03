<?php

class SupplierModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getIndustryType() {
        try {
            $sql = "select config_key, config_value from config where config_type='industry_type'";
            $params = array();
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E294]Error while fetching industry type list Error: ' . $e->getMessage());
        }
    }

    public function createSupplier($user_id, $email1, $email2, $mob_country_code1, $mobile1, $mob_country_code2, $mobile2, $industrytype, $supplier_company_name, $contact_person_name,$contact_person_name2, $company_website) {
        try {
            $sql = "call `supplier_save`(:user_id,:email1,:email2,:mob_country_code1,:mobile1,:mob_country_code2,:mobile2,:industrytype, :supplier_company_name,:contact_person_name ,:contact_person_name2 , :company_website);";
            $params = array(':user_id' => $user_id, ':email1' => $email1, ':email2' => $email2, ':mob_country_code1' => $mob_country_code1, ':mobile1' => $mobile1,
                ':mob_country_code2' => $mob_country_code2,
                ':mobile2' => $mobile2, ':industrytype' => $industrytype, ':supplier_company_name' => ucfirst($supplier_company_name), ':contact_person_name' => ucfirst($contact_person_name), ':contact_person_name2' => ucfirst($contact_person_name2),':company_website' => $company_website);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['message'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getSupplierList($merchant_id) {
        try {
            $sql = "select supplier_id,supplier_company_name,contact_person_name,contact_person_name2,config_value from supplier inner join config on config.config_key=supplier.industry_type where supplier.merchant_id=:merchant_id and is_active=:active and config.config_type='industry_type' order by supplier_id desc ;";
            $params = array(':merchant_id' => $merchant_id, ':active' => 1);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E296]Error while fetching supplier list Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function deletesupplier($supplier_id, $user_id) {
        try {
            $sql = "UPDATE `supplier` SET `is_active` = 0 , last_updated_by=`user_id` , last_updated_date=CURRENT_TIMESTAMP() WHERE supplier_id=:supplier_id and user_id=:user_id";
            $params = array(':supplier_id' => $supplier_id, ':user_id' => $user_id);

            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return true;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E297]Error while update bulk upload status Error: for bulk id [' . $bulk_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updatesupplier($supplier_id, $user_id, $email1, $email2, $mob_country_code1, $mobile1, $mob_country_code2, $mobile2, $industrytype, $supplier_company_name, $contact_person_name,$contact_person_name2, $company_website) {
        try {

            $sql = "call `supplier_update`(:supplier_id,:user_id,:email1,:email2,:mob_country_code1,:mobile1,:mob_country_code2,:mobile2,:industrytype, :supplier_company_name ,:contact_person_name ,:contact_person_name2 , :company_website);";
            $params = array(':supplier_id' => $supplier_id, ':user_id' => $user_id, ':email1' => $email1, ':email2' => $email2, ':mob_country_code1' => $mob_country_code1, ':mobile1' => $mobile1,
                ':mob_country_code2' => $mob_country_code2,
                ':mobile2' => $mobile2, ':industrytype' => $industrytype, ':supplier_company_name' => ucfirst($supplier_company_name), ':contact_person_name' => ucfirst($contact_person_name),':contact_person_name2' => ucfirst($contact_person_name2), ':company_website' => $company_website);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['message'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E298]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getSupplierDetails($supplier_id, $user_id) {
        try {

            $sql = "select email_id1,email_id2,supplier_company_name,contact_person_name,contact_person_name2,mob_country_code1,mobile1,mob_country_code2,mobile2,industry_type,company_website from supplier where supplier_id=:supplier_id and user_id=:user_id ";
            $params = array(':user_id' => $user_id, ':supplier_id' => $supplier_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            //print_r($row);
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E299]Error while fetching supplier details Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }
    
    public function isExistTemplate($templatename, $user_id) {
        try {
            $sql = "select plan_id from prepaid_plan WHERE plan_name=:templatename and merchant_id=:user_id and is_active=1";
            $params = array(':templatename' => $templatename, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return TRUE;
            }
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error:for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }


}

?>
