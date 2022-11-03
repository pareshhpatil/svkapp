<?php

class CommentsModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getCommentsList($parent_id) {
        try {
            $sql = "select * from comments where parent_id=:parent_id and is_active=:active order by last_update_by desc";
            $params = array(':parent_id' => $parent_id, ':active' => 1);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E296]Error while fetching supplier list Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getCommentsDetail($id) {
        try {
            $sql = "select * from comments where id=:id";
            $params = array(':id' => $id);
            $this->db->exec($sql, $params);
            $list = $this->db->single();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E296]Error while fetching supplier list Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function saveComment($user_id, $parent_id, $comment, $name) {
        try {
            $sql = "INSERT INTO `comments`(`parent_id`,`comment`,`name`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)VALUES(:parent_id,:comment,:name,:user_id,CURRENT_TIMESTAMP(),:user_id,CURRENT_TIMESTAMP())";
            $params = array(':parent_id' => $parent_id, ':comment' => $comment, ':name' => $name, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295-d]Error while creating Coupon Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateComment($user_id, $id, $comment, $name) {
        try {
            $sql = "update `comments` set comment=:comment,name=:name,last_update_by=:user_id where id=:id";
            $params = array(':id' => $id, ':comment' => $comment, ':name' => $name, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295-d]Error while creating Coupon Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }
    public function deleteComment($user_id, $id) {
        try {
            $sql = "update `comments` set is_active=0 ,last_update_by=:user_id where id=:id";
            $params = array(':id' => $id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295-d]Error while creating Coupon Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    

}

?>
