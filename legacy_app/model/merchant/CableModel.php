<?php

class CableModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function getSettopboxList($merchant_id, $from_date, $to_date, $status, $expiry_status) {
        try {
            $sql = "select s.id,s.updated_date,concat(c.first_name,' ',c.last_name) as customer_name,c.customer_code,s.name,s.narrative,s.cost,s.status,s.expiry_date from customer_service s"
                    . " inner join customer c on c.customer_id=s.customer_id where  DATE_FORMAT(s.updated_date,'%Y-%m-%d') >= :from_date and DATE_FORMAT(s.updated_date,'%Y-%m-%d') <= :to_date and s.merchant_id=:merchant_id and s.is_active=1";
            if ($status != '') {
                $sql .= " and status=" . $status;
            }

            $params = array(':merchant_id' => $merchant_id, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E145]Error while delete sms Template Error: for bulk id [' . $id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function getSettopboxListAll($merchant_id, $expiry_status) {
        try {
            $sql = "select s.id,s.updated_date,concat(c.first_name,' ',c.last_name) as customer_name,c.customer_code,s.name,s.narrative,s.cost,s.status,s.expiry_date from customer_service s"
                    . " inner join customer c on c.customer_id=s.customer_id where  s.merchant_id=:merchant_id and s.is_active=1";
            if ($expiry_status > 0) {
                if ($expiry_status == 1) {
                    $sql .= " and expiry_date<=" . date('Y-m-d') . " and expiry_date!='2014-01-01'";
                } else {
                    $sql .= " and expiry_date>" . date('Y-m-d') . " and expiry_date!='2014-01-01'";
                }
            }
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E145]Error while delete sms Template Error: for bulk id [' . $id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function getSettopboxPackageList($merchant_id) {
        try {
            $sql = "select concat(c.first_name,' ',c.last_name) as customer_name,c.customer_code,s.name,ch.channel_name,ph.package_name,s.narrative,p.cost,p.package_type
                 from customer_service s inner join customer c on c.customer_id=s.customer_id inner join customer_service_package p on p.service_id=s.id
                 left outer join cable_channel ch on p.channel_id=ch.channel_id left outer join cable_package ph on p.package_id=ph.package_id where s.merchant_id=:merchant_id and s.is_active=1
                 and p.is_active=1 order by package_type;";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E145]Error while delete sms Template Error: for bulk id [' . $id . '] ' . $e->getMessage());
            $this->setGenericError();
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
            throw new Exception("Stored procedure execution failed " . $ex->getMessage());
        }
    }

    public function updatesettings($merchant_id, $ncf_qty, $ncf_fee, $ncf_tax, $ncf_tax_name, $ncf_base_package, $ncf_addon_package, $ncf_alacarte_package, $user_id) {
        try {
            $sql = "update cable_setting set ncf_qty=:ncf_qty,ncf_fee=:ncf_fee,ncf_tax=:ncf_tax,ncf_tax_name=:ncf_tax_name ,ncf_base_package=:ncf_base_package ,ncf_addon_package=:ncf_addon_package,ncf_alacarte_package=:ncf_alacarte_package ,updated_by=:user_id where merchant_id=:id";
            $params = array(':id' => $merchant_id, ':ncf_qty' => $ncf_qty, ':ncf_fee' => $ncf_fee, ':ncf_tax' => $ncf_tax, ':ncf_tax_name' => $ncf_tax_name, ':ncf_base_package' => $ncf_base_package, ':ncf_addon_package' => $ncf_addon_package, ':ncf_alacarte_package' => $ncf_alacarte_package, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function savesettings($merchant_id, $ncf_qty, $ncf_fee, $ncf_tax, $ncf_tax_name, $ncf_base_package, $ncf_addon_package, $ncf_alacarte_package, $user_id) {
        try {
            $sql = "INSERT INTO`cable_setting`(`merchant_id`,`ncf_qty`,`ncf_fee`,`ncf_tax`,`ncf_tax_name`,`ncf_base_package`,`ncf_addon_package`,`ncf_alacarte_package`,`created_by`,`updated_by`,`created_date`)VALUES(:id,:ncf_qty,:ncf_fee,:ncf_tax,:ncf_tax_name,:ncf_base_package,:ncf_addon_package,:ncf_alacarte_package,:user_id,:user_id,CURRENT_TIMESTAMP());";
            $params = array(':id' => $merchant_id, ':ncf_qty' => $ncf_qty, ':ncf_fee' => $ncf_fee, ':ncf_tax' => $ncf_tax, ':ncf_tax_name' => $ncf_tax_name, ':ncf_base_package' => $ncf_base_package, ':ncf_addon_package' => $ncf_addon_package, ':ncf_alacarte_package' => $ncf_alacarte_package, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getServiceCustomerList($merchant_id, $column_name, $bulk_id, $where = '') {
        try {
            $column_name = implode('~', $column_name);
            $sql = "call get_customer_service_list(:merchant_id,:column_name,:where,'','',:bulk_id);";
            $params = array(':merchant_id' => $merchant_id, ':column_name' => $column_name, ':where' => $where, ':bulk_id' => $bulk_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E149]Error while getting customer list Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveSubscriptionJob($template_id, $start_date, $due_date, $service_id) {
        try {
            $time = time();
            $addtime = $time + 10;
            $sql = "INSERT INTO `jobs` (`queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`,`service_id`) VALUES ('default', '{\"displayName\":\"App/\/\Jobs/\/\SaveCableSubscription\",\"job\":\"Illuminate/\/\Queue/\/\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App/\/\Jobs/\/\SaveCableSubscription\",\"command\":\"O:30:\\\"App/\/\Jobs/\/\SaveCableSubscription\\\":11:{s:13:\\\"\\u0000*\\u0000start_date\\\";s:10:\\\"" . $start_date . "\\\";s:11:\\\"\\u0000*\\u0000due_date\\\";s:10:\\\"" . $due_date . "\\\";s:13:\\\"\\u0000*\\u0000service_id\\\";s:1:\\\"" . $service_id . "\\\";s:14:\\\"\\u0000*\\u0000template_id\\\";s:10:\\\"" . $template_id . "\\\";s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate/\/\Support/\/\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"" . date('Y-m-d h:i:s') . ".756135\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:13:\\\"Asia\/\Calcutta\\\";}s:7:\\\"chained\\\";a:0:{}}\"}}', '0', null, '" . $addtime . "', '" . $time . "','" . $service_id . "');";
            $params = array();
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E149]Error while save jobs Error:' . $e->getMessage());
            $this->setGenericError();
        }
    }

}

?>
