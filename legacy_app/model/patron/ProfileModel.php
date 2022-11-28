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

            SwipezLogger::error(__CLASS__, '[E162]Error while fetching personal details Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function pesonalUpdate($userid, $f_name, $l_name, $mobile)
    {
        try {
            $sql = "update user set first_name=:f_name,last_name=:l_name,mobile_no=:mobile,last_updated_by=:user_id where user_id=:user_id";
            $params = array(':user_id' => $userid, ':f_name' => $f_name, ':l_name' => $l_name, ':mobile' => $mobile);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E162]Error while fetching personal details Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    
}
