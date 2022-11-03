<?php

class OnboardingModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function changeOnboardingStatus($user_id, $status) {
        try {
            $sql = "update merchant set merchant_status=:status where user_id=:user_id";
            $params = array(':user_id' => $user_id, ':status' => $status);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E212]Error while getting package details Error:  for user id[' . $user_id.']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    

}
