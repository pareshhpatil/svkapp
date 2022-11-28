<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class CablePackageModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getCustomerSetTopBox($customer_ids) {
        try {

            $sql = 'select * from customer_service where customer_id IN (' . implode(',', $customer_ids) . ")";
            $params = array();
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, "Error While  get Customer SetTopBox" . $ex->getMessage());
        }
    }

    public function getPackageChannels($package_id) {
        try {
            $sql = "select channel_name,logo,p.channel_id,c.cost,c.language from cable_package_channel p inner join cable_channel c on p.channel_id=c.channel_id where p.package_id=:package_id";
            $params = array(':package_id' => $package_id);
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, "Error While  get Package Channels" . $ex->getMessage());
        }
    }

    public function getGroupPackage($merchant_id) {
        try {
            $sql = "select package_name,package_cost,total_cost,group_name,package_id,package_group,min_package_selection,max_package_selection,group_type,sub_package_id from cable_package p inner join cable_group g on p.package_group=g.group_id where p.is_active=1 and p.merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, "Error While  get Group Package" . $ex->getMessage());
        }
    }

    public function getCustomerPackageList($service_id) {
        try {
            $sql = "select s.package_id,g.group_type from customer_service_package s inner join cable_package c"
                    . " on c.package_id=s.package_id inner join cable_group g on g.group_id=c.package_group where s.is_active=1 and s.package_type=1 and s.service_id=:service_id";
            $params = array(':service_id' => $service_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, "Error While  get Customer Package List" . $ex->getMessage());
        }
    }

    public function getPackageDetails($package_id) {
        try {
            $sql = "SELECT p.*,g.group_type FROM cable_package p inner join cable_group g on p.package_group=g.group_id where p.package_id=:package_id and p.is_active=1";
            $params = array(':package_id' => $package_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, "Error While get Package Details " . $ex->getMessage());
        }
    }

    public function getCustomerPackage($service_id) {
        try {
            $sql = "SELECT s.package_id,sub_package_id,channel_id FROM customer_service_package s left outer join cable_package p on s.package_id=p.package_id where service_id=:service_id and s.is_active=1";
            $params = array(':service_id' => $service_id);
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, "Error While  get Customer Package" . $ex->getMessage());
        }
    }

    public function getPackageChannelsID($package_ids) {
        try {
            $sql = 'select channel_id from cable_package_channel where package_id IN (' . implode(',', $package_ids) . ") and is_active=1";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, "Error While  get Package Channels ID" . $ex->getMessage());
        }
    }

    public function deletePackage($service_id) {
        try {
            $sql = "update customer_service_package set is_active=0 where service_id=:service_id";
            $params = array(':service_id' => $service_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, "Error While  delete Package" . $ex->getMessage());
        }
    }

    public function saveCustomerService($service_id, $customer_id, $merchant_id, $package_type, $package_id, $channel_id, $cost, $user_id) {
        try {
            $sql = "INSERT INTO `customer_service_package`(`service_id`,`customer_id`,`merchant_id`,`package_type`,`package_id`,`channel_id`,`cost`,`created_by`,`created_date`,`updated_by`,`updated_date`)"
                    . "VALUES(:service_id,:customer_id,:merchant_id,:package_type,:package_id,:channel_id,:cost,:user_id,CURRENT_TIMESTAMP(),:user_id,CURRENT_TIMESTAMP());";
            $params = array(':service_id' => $service_id, ':customer_id' => $customer_id, ':merchant_id' => $merchant_id, ':package_type' => $package_type, ':package_id' => $package_id, ':channel_id' => $channel_id, ':cost' => $cost, ':user_id' => $user_id);
            SwipezLogger::debug(__CLASS__, "Param Json" . json_encode($params));
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, "Error While save customer Service " . $ex->getMessage());
        }
    }

    public function loginCheck($mobile, $password) {
        $sql = "SELECT u.email_id,u.first_name,u.last_name,u.user_status,u.user_id,u.mobile_no,u.password FROM user u where u.mobile_no=:mobile and u.login_type=2;";
        $params = array(':mobile' => $mobile);
        $this->db->exec($sql, $params);
        $rows = $this->db->resultset();
       
        foreach ($rows as $row) {
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }
    }

    public function getMinPackage($group_id, $service_id) {
        try {
            $sql = "SELECT count(s.package_id) as cnt FROM customer_service_package s inner join cable_package p on s.package_id=p.package_id where service_id=:service_id and p.package_group=:group_id and s.is_active=1";
            $params = array(':group_id' => $group_id, ':service_id' => $service_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['cnt'] > 0) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, "Error While get Min Package " . $ex->getMessage());
        }
    }

}
