<?php

class DashboardModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getNotification($userid) {
        try {
            $sql = "select distinct  count(notification_type) as count ,link, notification_type as type,concat(message1,' ',message2) as message from  notification where user_id=:user_id and is_dismissed=0 and to_date >= CURRENT_TIMESTAMP() GROUP BY notification_type";
            $params = array(':user_id' => $userid);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();

            $sql = "update notification set is_shown=1 where user_id=:user_id and is_dismissed=0 and to_date >= CURRENT_TIMESTAMP()";
            $params = array(':user_id' => $userid);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            $notification = array();
            $int = 0;
            foreach ($rows as $row) {
                $notification[$int]['type'] = $row['type'];
                $notification[$int]['link'] = $row['link'];
                $message=str_replace("count", '', $row['message']);
                $notification[$int]['message'] = ucfirst(trim($message));
                $int++;
            }
            return $notification;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E145]Error while fetching notification Error: for user id[' . $userid.']' . $e->getMessage());
        }
    }

    public function seenNotification($user_id, $type) {
        try {
            $sql = "update notification set is_dismissed=:status where user_id=:user_id and notification_type=:type and is_shown=1 and is_dismissed=0;";
            $params = array(':status' => 1, ':user_id' => $user_id, ':type' => $type);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E146]Error while updating notification Error: for user id[' . $user_id.']' . $e->getMessage());
        }
    }

}
