<?php

class LandingModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getMerchantLandingDetails($merchant_id) {
        try {
            $sql = "select m.user_id,m.disable_online_payment,m.is_legal_complete,overview,publishable,gst_number,merchant_website,"
                    . "display_url,p.company_name,p.company_name as display_name,terms_condition,cancellation_policy,about_us,"
                    . "office_location,contact_no,email_id,logo,l.booking_hide_menu, "
                    . "banner from merchant m left outer join merchant_landing "
                    . " l on m.merchant_id=l.merchant_id inner join merchant_billing_profile p on p.merchant_id=m.merchant_id and is_default=1 where m.merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E505]Error while fetching merchant overview Error: for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    // getting industry details
    public function getMerchantIndustry($merchant_id) {
        $row = array();
        $industry_type = "select industry_type from merchant where merchant_id=:merchant_id";
        $params = array(':merchant_id' => $merchant_id);
        $this->db->exec($industry_type, $params);
        $industry = $this->db->single();
        $sql = "select config_value from config where config_type='industry_type' and config_key=:industry_type";
        $params = array(':industry_type' => $industry['industry_type']);
        $this->db->exec($sql, $params);
        $industry_name = $this->db->single();
        return $industry_name['config_value'];
    }

    // getting merchant details
    public function getMerchantDetails($merchant_id) {
        $row = array();
        $industry_type = "select employee_count, customer_count from merchant where merchant_id=:merchant_id";
        $params = array(':merchant_id' => $merchant_id);
        $this->db->exec($industry_type, $params);
        $details = $this->db->single();
        return $details;
    }

    public function getMerchantWebsite($merchant_id) {
        try {
            $sql = "select merchant_domain from website_live where merchant_id=:merchant_id and status>1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E505]Error while fetching merchant overview Error: for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function getMerchantPlan($merchant_id, $type = 1) {
        try {
            $sql = "SELECT * FROM prepaid_plan where merchant_id=:merchant_id and type=:type and is_active=1 group by source,category,speed,data order by plan_id;";
            $params = array(':merchant_id' => $merchant_id, ':type' => $type);
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E505]Error while fetching merchant overview Error: for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function isMerchantPlan($merchant_id, $type) {
        try {
            if ($type == 2) {
                $sql = "SELECT count(*) as mcount FROM booking_slots where merchant_id=:merchant_id and is_active=1";
            } else {
                $sql = "SELECT count(*) as mcount FROM prepaid_plan where merchant_id=:merchant_id and is_active=1 and type=1";
            }
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['mcount'] > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E505]Error while fetching merchant overview Error: for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function getdurationPlan($merchant_id, $source, $category, $speed, $data) {
        try {
            if ($source == '' || $source == null) {
                $sql = "SELECT distinct duration FROM prepaid_plan where merchant_id=:merchant_id and (source='' or source is null) and category=:category and speed=:speed and data=:data and is_active=1";
                $params = array(':merchant_id' => $merchant_id, ':category' => $category, ':speed' => $speed, ':data' => $data);
            } else {
                $sql = "SELECT distinct duration FROM prepaid_plan where merchant_id=:merchant_id and source=:source and category=:category and speed=:speed and data=:data and is_active=1";
                $params = array(':merchant_id' => $merchant_id, ':source' => $source, ':category' => $category, ':speed' => $speed, ':data' => $data);
            }
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E505]Error while fetching merchant overview Error: for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function getUserDetails($user_id) {
        try {
            $sql = "select first_name,last_name,email_id,mobile_no from user where user_id=:user_id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E105-12]Error while List value Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getMerchantCustomerDetails($customer_code, $merchant_id) {
        try {
            $sql = "select customer_id,customer_code,mobile from customer where customer_code=:customer_code and merchant_id=:merchant_id";
            $params = array(':customer_code' => $customer_code, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E105-12]Error while List value Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getplantemplate($user_id) {
        try {
            $sql = "select template_id from invoice_template where user_id=:user_id and template_name='rFbX57ttRqtrWYQWxfEw0w' limit 1;";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return $this->createPlanTemplate($user_id);
            } else {
                return $row['template_id'];
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E105-12]Error while List value Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function createPlanTemplate($user_id) {
        try {
            $sql = "select generate_sequence('Template_id') as _template_id";
            $params = array();
            $this->db->exec($sql, $params);
            $temp = $this->db->single();
            $template_id = $temp['_template_id'];

            $sql = "INSERT INTO `invoice_template`(`template_id`,`user_id`,`template_name`,`template_type`,`is_active`,`created_by`,`created_date`,`last_update_by`)
            VALUES(:template_id,:user_id,'rFbX57ttRqtrWYQWxfEw0w','society',0,:user_id,CURRENT_TIMESTAMP(),:user_id)";
            $params = array(':template_id' => $template_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);

            $sql = "INSERT INTO `invoice_column_metadata`(`template_id`,`column_datatype`,`column_position`,`sort_order`,`position`,`column_name`,`default_column_value`,
`column_type`,`customer_column_id`,`is_mandatory`,`is_delete_allow`,`save_table_name`,`is_template_column`,`function_id`,`column_group_id`,`is_active`,`created_by`,
`created_date`,`last_update_by`)select :template_id,`column_datatype`,`column_position`,`sort_order`,`position`,`column_name`,`default_column_value`,
`column_type`,`customer_column_id`,`is_mandatory`,`is_delete_allow`,`save_table_name`,`is_template_column`,`function_id`,`column_group_id`,`is_active`,:user_id,
`created_date`,:user_id from invoice_column_metadata where template_id='E000000117' and is_template_column=1";
            $params = array(':template_id' => $template_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $template_id;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[EB100869]Error while createPlanTemplate Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getCategoryList($merchant_id) {
        try {
            $sql = 'select category_id,category_name from booking_categories WHERE merchant_id=:merchant_id and is_active=1 and category_active=1 order by category_order';
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[EB10087]Error while geting category list Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getMembership($category_id, $customer_id) {
        $sql = 'select * from customer_membership WHERE category_id=:category_id and status=1 and customer_id=:customer_id order by end_date desc limit 3';
        $params = array(':category_id' => $category_id, ':customer_id' => $customer_id);
        $this->db->exec($sql, $params);
        $list = $this->db->resultset();
        return $list;
    }

    public function saveSecurityKey($merchant_id, $user_id, $access_key, $secret_key) {
        $sql = "INSERT INTO `merchant_security_key`(`merchant_id`,`user_id`,`access_key_id`,`secret_access_key`,`created_date`)"
                . " VALUES(:merchant_id,:user_id,:access_key,:secret_key,CURRENT_TIMESTAMP());";
        $params = array(':merchant_id' => $merchant_id, ':user_id' => $user_id, ':access_key' => $access_key, ':secret_key' => $secret_key);
        $this->db->exec($sql, $params);
    }

}
