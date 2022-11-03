<?php

class CoveringnoteModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function createCoveringNote($merchant_id, $template_name, $body, $subject, $invoice_label, $pdf_enable, $user_id) {
        try {
            $pdf_enable = ($pdf_enable == 1) ? 1 : 0;
            $sql = "INSERT INTO `covering_note`(`merchant_id`,`template_name`,`body`,`subject`,`invoice_label`,`pdf_enable`,`created_by`,`created_date`,
                    `last_update_by`)VALUES(:merchant_id,:template_name,:body,:subject,:invoice_label,:pdf_enable,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(':merchant_id' => $merchant_id, ':template_name' => $template_name, ':body' => $body, ':subject' => $subject, ':invoice_label' => $invoice_label,
                ':pdf_enable' => $pdf_enable, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function deleteCoveringnote($covering_id, $user_id, $merchant_id) {
        try {
            $sql = "UPDATE `covering_note` SET `is_active` = 0 ,last_update_by=:user_id  WHERE covering_id=:covering_id and merchant_id=:merchant_id";
            $params = array(':covering_id' => $covering_id, ':user_id' => $user_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E297]Error while delete Covering note Error: for bulk id [' . $covering_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateCoveringNote($covering_id, $template_name, $body, $subject, $invoice_label, $pdf_enable, $user_id) {
        try {

            $sql = "UPDATE `covering_note` SET `template_name` = :template_name,`body` = :body, `subject` = :subject,`invoice_label` = :invoice_label,`pdf_enable` = :pdf_enable,
`last_update_by` = :user_id WHERE `covering_id` = :covering_id";
            $params = array(':covering_id' => $covering_id, ':template_name' => $template_name, ':body' => $body, ':subject' => $subject, ':invoice_label' => $invoice_label,
                ':pdf_enable' => $pdf_enable, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E298]Error while update covering note Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function isExistTemplate($templatename, $merchant_id, $covering_id = null) {
        try {
            if ($covering_id != null) {
                $sql = "select covering_id from covering_note WHERE template_name=:templatename and merchant_id=:merchant_id and is_active=1 and covering_id<>" . $covering_id;
            } else {
                $sql = "select covering_id from covering_note WHERE template_name=:templatename and merchant_id=:merchant_id and is_active=1";
            }
            $params = array(':templatename' => $templatename, ':merchant_id' => $merchant_id);
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

}

?>
