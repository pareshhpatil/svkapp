<?php

class ProductModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function createProduct($product_name, $price, $description, $type, $unit_type, $sac_code, $taxes, $merchant_id, $user_id) {
        try {
            $sql = "INSERT INTO `merchant_product`(`merchant_id`,`product_name`,`description`,`price`,`type`,unit_type,`sac_code`,`gst_percent`,`created_by`,`created_date`,`last_update_by`)"
                    . "VALUES(:merchant_id,:product_name,:description,:price,:type,:unit_type,:sac_code,:taxes,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(':product_name' => $product_name, ':price' => $price, ':type' => $type, ':unit_type' => $unit_type, ':sac_code' => $sac_code, ':taxes' => $taxes, ':description' => $description, ':merchant_id' => $merchant_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateProduct($product_id, $product_name, $price, $description, $type,$unit_type, $sac_code, $taxes, $merchant_id, $user_id) {
        try {
            $sql = "update `merchant_product` set `product_name`=:product_name,`description`=:description,`price`=:price,`type`=:type,unit_type=:unit_type,`sac_code`=:sac_code,`gst_percent`=:taxes,`last_update_by`=:user_id where product_id=:product_id";
            $params = array(':product_name' => $product_name, ':price' => $price, ':description' => $description, ':type' => $type, ':unit_type' => $unit_type, ':sac_code' => $sac_code, ':taxes' => $taxes, ':product_id' => $product_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function deleteProduct($product_id, $user_id, $merchant_id) {
        try {
            $sql = "UPDATE `merchant_product` SET `is_active` = 0 , last_update_by=:user_id WHERE product_id=:product_id and merchant_id=:merchant_id";
            $params = array(':product_id' => $product_id, ':user_id' => $user_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return true;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E297]Error while update bulk upload status Error: for bulk id [' . $bulk_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function isExistTemplate($templatename, $user_id, $id = null) {
        try {

            $sql = "select product_id from merchant_product WHERE product_name=:templatename and merchant_id=:user_id and is_active=1";
            if ($id != null) {
                $sql .= ' and product_id<>' . $id;
            }
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

    public function getProductDetailsByName($product_name, $merchant_id) {
        try {

            $sql = "select * from merchant_product WHERE product_name=:product_name and merchant_id=:merchant_id and is_active=1";
           
            $params = array(':product_name' => $product_name, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            $this->db->closeStmt();
            return $result;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error:for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

}
