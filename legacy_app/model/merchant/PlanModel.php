<?php

class PlanModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getCategory($merchant_id)
    {
        try {
            $sql = "select distinct category from prepaid_plan where merchant_id=:merchant_id and category is not null and category<>'' ";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E29496]Error while fetching Category  list Error: ' . $e->getMessage());
        }
    }

    public function getSource($merchant_id)
    {
        try {
            $sql = "select distinct source from prepaid_plan where merchant_id=:merchant_id and source is not null and source<>'' and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E29498]Error while fetching Source  list Error: ' . $e->getMessage());
        }
    }

    public function isExistTemplate($templatename, $user_id)
    {
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

    public function createPlan($merchant_id, $source, $category, $plan_name, $speed, $data, $duration, $price, $tax1_text, $tax2_text, $tax1, $tax2, $tax1_id, $tax2_id, $user_id)
    {
        try {
            $sql = "INSERT INTO `prepaid_plan`(`merchant_id`,`source`,`category`,`plan_name`,`speed`,`data`,`duration`,`price`,tax1_text,tax2_text,tax1_percent,tax2_percent,tax1_id,tax2_id,`is_active`,`created_by`,`last_update_by`,`created_date`)VALUES(:merchant_id,:source,:category,:plan_name,:speed,:data,:duration,:price,:tax1_text,:tax2_text,:tax1_percent,:tax2_percent,:tax1_id,:tax2_id,1,:user_id,:user_id,CURRENT_TIMESTAMP());";
            $params = array(':merchant_id' => $merchant_id, ':source' => $source, ':category' => $category, ':plan_name' => $plan_name, ':speed' => $speed, ':data' => $data, ':duration' => $duration, ':price' => $price, ':tax1_text' => $tax1_text, ':tax2_text' => $tax2_text, ':tax1_percent' => $tax1, ':tax2_percent' => $tax2, ':tax1_id' => $tax1_id, ':tax2_id' => $tax2_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPlanList($merchant_id)
    {
        try {
            $sql = "select * from prepaid_plan where merchant_id=:merchant_id and is_active=1 and type=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E296]Error while fetching supplier list Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function deletePlan($plan_id, $user_id, $merchant_id)
    {
        try {
            $sql = "UPDATE `prepaid_plan` SET `is_active` = 0 , last_update_by=:user_id WHERE plan_id=:plan_id and merchant_id=:merchant_id";
            $params = array(':plan_id' => $plan_id, ':user_id' => $user_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return true;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E297]Error while update bulk upload status Error: for bulk id [' . $bulk_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updatePlan($plan_id, $user_id, $source, $category, $plan_name, $speed, $data, $duration, $price, $tax1, $tax1_text, $tax2, $tax2_text, $tax1_id, $tax2_id)
    {
        try {
            $sql = "UPDATE `prepaid_plan` SET `source` = :source,`category` = :category,`plan_name` = :plan_name,`speed` = :speed,`data` = :data,`duration` = :duration,`price` = :price,tax1_text=:tax1_text,tax2_text=:tax2_text,tax1_percent=:tax1_percent,tax2_percent=:tax2_percent,tax1_id=:tax1_id,tax2_id=:tax2_id,`last_update_by` = :user_id WHERE `plan_id` = :plan_id";
            $params = array(':plan_id' => $plan_id, ':source' => $source, ':category' => $category, ':plan_name' => $plan_name, ':speed' => $speed, ':data' => $data, ':duration' => $duration, ':price' => $price, ':tax1_text' => $tax1_text, ':tax2_text' => $tax2_text, ':tax1_percent' => $tax1, ':tax2_percent' => $tax2, ':tax1_id' => $tax1_id, ':tax2_id' => $tax2_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E298]Error while update plan Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPlanDetails($id)
    {
        try {
            $sql = "select * from prepaid_plan where plan_id=:plan_id";
            $params = array(':plan_id' => $id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E299]Error while fetching supplier details Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function updatePlanSetting($invoice_create, $invoice_send, $template_id, $merchant_id)
    {
        try {
            $sql = "UPDATE `merchant_setting` SET `plan_invoice_create` = :plan_invoice_create , plan_invoice_send=:plan_invoice_send,plan_invoice_template=:plan_invoice_template  WHERE merchant_id=:merchant_id";
            $params = array(':plan_invoice_create' => $invoice_create, ':plan_invoice_send' => $invoice_send, ':plan_invoice_template' => $template_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return true;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E297]Error while update bulk upload status Error: for bulk id [' . $bulk_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
