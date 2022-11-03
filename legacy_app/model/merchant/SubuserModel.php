<?php

class SubuserModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getControllers() {
        try {
            $sql = "select * from controller where is_active=1";
            $params = array();
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E7001]Error while fetching industry type list Error: ' . $e->getMessage());
        }
    }

    public function getControllersName($controllers) {
        try {
            $sql = "select name from controller where controller_id in (" . $controllers . ")";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            foreach ($rows as $row) {
                $controller .= $row['name'] . ', ';
            }
            return substr($controller, 0, -1);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E7002]Error while fetching industry type list Error: ' . $e->getMessage());
        }
    }

    public function isRoleCount($merchant_id) {
        try {
            $sql = 'select count(role_id) count_r from roles WHERE merchant_id=:merchant_id and is_active=1';
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            $exist_count = $result['count_r'];

            $sql = 'select merchant_role  from account WHERE merchant_id=:merchant_id and is_active=1 limit 1';
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            $total_count = $result['merchant_role'];
            if ($exist_count >= $total_count) {
                return TRUE;
            }
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E138]Error while isRoleCount Error:for user id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function isExistTemplate($name, $user_id) {
        try {
            $sql = 'select role_id from roles WHERE name=:name and merchant_id=:user_id and is_active=1';
            $params = array(':name' => $name, ':user_id' => $user_id);
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

    public function saveRole($user_id, $merchant_id, $name, $view_controllers, $edit_controllers, $delete_controllers) {
        try {
            $view_controllers = implode(',', $view_controllers);
            $edit_controllers = implode(',', $edit_controllers);
            $delete_controllers = implode(',', $delete_controllers);
            $sql = "insert into roles(user_id,merchant_id,name,view_controllers,update_controllers,delete_controllers,created_by,created_date,last_update_by)values(:user_id,:merchant_id,:name,:view_controllers,:edit_controllers,:delete_controllers,:user_id,CURRENT_TIMESTAMP(),:user_id)";
            $params = array(':user_id' => $user_id, ':merchant_id' => $merchant_id, ':name' => $name, ':view_controllers' => $view_controllers, ':edit_controllers' => $edit_controllers, ':delete_controllers' => $delete_controllers);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateRole($role_id, $name, $view_controllers, $edit_controllers, $delete_controllers) {
        try {
            $view_controllers = implode(',', $view_controllers);
            $edit_controllers = implode(',', $edit_controllers);
            $delete_controllers = implode(',', $delete_controllers);
            $sql = "update roles set name=:name,view_controllers=:view_controllers,update_controllers=:edit_controllers,delete_controllers=:delete_controllers where role_id=:role_id";
            $params = array(':role_id' => $role_id, ':name' => $name, ':view_controllers' => $view_controllers, ':edit_controllers' => $edit_controllers, ':delete_controllers' => $delete_controllers);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function savesubMerchant($user_id, $first_name, $last_name, $email, $mobile, $mobile_code, $password, $role, $franchise_id = 0, $customer_group = array()) {
        try {
            $customer_group = implode(',', $customer_group);
            $sql = "call save_sub_merchant(:user_id,:email,:first_name,:last_name,:mobile_code,:mobile,:password,:role,:franchise_id,:group)";
            $params = array(':user_id' => $user_id, ':email' => $email, ':first_name' => $first_name, ':last_name' => $last_name, ':mobile_code' => $mobile_code, ':mobile' => $mobile, ':password' => $password, ':role' => $role, ':franchise_id' => $franchise_id,':group'=>$customer_group);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating submerchant Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getRoleDeatails($role_id) {
        try {
            $sql = "select * from roles where role_id=:role_id";
            $params = array(':role_id' => $role_id);
            $this->db->exec($sql, $params);
            $list = $this->db->single();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E296]Error while fetching supplier list Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getRoleList($userid) {
        try {
            $sql = "select * from roles where user_id=:user_id and is_active=:active;";
            $params = array(':user_id' => $userid, ':active' => 1);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E296]Error while fetching supplier list Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function updateTicketStatus($status, $user_id) {
        try {
            $sql = "UPDATE `user` SET `enable_ticket` = :status  WHERE user_id=:user_id";
            $params = array(':status' => $status, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return true;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E297]Error while update Ticket Status Error: for user id [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function deleterole($role_id, $user_id) {
        try {
            $sql = "UPDATE `roles` SET `is_active` = 0 , last_update_by=`user_id`  WHERE role_id=:role_id and user_id=:user_id";
            $params = array(':role_id' => $role_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return true;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E297]Error while delete role Error: for role id [' . $role_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function emailAlreadyExists($emailId_, $user_id) {
        try {
            $sql = "SELECT `user_id` FROM `user` where email_id=:email and user_status not in(21,22) and created_by=:user_id";
            $params = array(':email' => $emailId_, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();

            if (isset($row['user_id'])) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E170]Error while checking email exist Error: for email id [' . $emailId_ . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function existUserId($user_id, $created_by) {
        $sql = "select count(user_id) countuser from user where user_id=:user_id and created_by=:created_by and user_status in(19,20)";
        $params = array(':user_id' => $user_id, ':created_by' => $created_by);
        $this->db->exec($sql, $params);
        $row = $this->db->single();
        if ($row['countuser'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getSubuserList($user_id) {
        try {
            $sql = "call get_sub_userlist(:user_id);";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E299]Error while fetching supplier details Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

}

?>
