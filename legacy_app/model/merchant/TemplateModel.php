<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class TemplateModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getSystemTemplateList($type) {
        try {
            $converter = new Encryption;
            $sql = "select system_template_id,created_date,template_name,template_description,template_type,type,thumbnail from system_template where type=:type and is_active=:active order by `order`";
            $params = array(':type' => $type, ':active' => 1);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $converter->encode($item['system_template_id']);
                $int++;
            }
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E132]Error while fetching template list list Error: ' . $e->getMessage());
        }
    }

    public function getTemplateList($merchant_id) {
        try {
            $sql = "select template_id,t.template_name,s.template_name as type,t.template_type,t.created_date from invoice_template t inner join system_template s on s.template_type=t.template_type where merchant_id=:merchant_id and s.type<>'event' and t.template_type<>'simple' and t.is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E132]Error while fetching template list list Error: ' . $e->getMessage());
        }
    }

    public function saveTemplate($merchant_id, $user_id, $main_header_id, $main_header_default, $customer_column, $custom_column, $header, $position, $column_type, $sort, $column_position, $function_id, $function_param, $function_val, $isdelete, $headerdatatype, $headertablename, $headermandatory, $tnc, $defaultValue, $templatename, $template_type, $particular_total, $tax_total, $image_file, $pc, $pd, $td, $plugin,$profile_id) {
        try {
            $main_header_id = implode('~', $main_header_id);
            $main_header_default = implode('~', $main_header_default);
            $customer_column = implode('~', $customer_column);
            $custom_column = implode('~', $custom_column);
            $maxposition = max($column_position);
            $header = implode('~', $header);
            $headerdatatype = implode('~', $headerdatatype);
            $headertablename = implode('~', $headertablename);
            $headermandatory = implode('~', $headermandatory);
            $column_position = implode('~', $column_position);
            $function_id = implode('~', $function_id);
            $function_param = implode('~', $function_param);
            $function_val = implode('~', $function_val);
            $position = implode('~', $position);
            $column_type = implode('~', $column_type);
            $sort = implode('~', $sort);
            $isdelete = implode('~', $isdelete);
            $defaultValue = implode('~', $defaultValue);
            $filename = basename($image_file['name']);
            $ext = '.' . substr($filename, strrpos($filename, '.') + 1);

            $pc = json_encode($pc);
            $sql = "call `template_save`(:templatename,:template_type,:merchant_id,:user_id,:main_header_id,:main_header_default,:customer_column,:custom_column,:header,:position,:column_type,:sort,:column_position,:function_id,:function_param,:function_val,:is_delete,:headerdatatype,:headertablename,:headermandatory,:tnc,:defaultValue,:particular_total,:tax_total,:ext,:maxposition,:pc,:pd,:td,:plugin,:profile_id,:created_by,@message,@template_id);";
            $params = array(':templatename' => $templatename, ':template_type' => $template_type, ':merchant_id' => $merchant_id, ':user_id' => $user_id, ':main_header_id' => $main_header_id, ':main_header_default' => $main_header_default, ':customer_column' => $customer_column, ':custom_column' => $custom_column, ':header' => $header, ':position' => $position, ':column_type' => $column_type, ':sort' => $sort, ':column_position' => $column_position, ':function_id' => $function_id, ':function_param' => $function_param, ':function_val' => $function_val, ':is_delete' => $isdelete, ':headerdatatype' => $headerdatatype, ':headertablename' => $headertablename, ':headermandatory' => $headermandatory, ':tnc' => $tnc, ':defaultValue' => $defaultValue, ':particular_total' => $particular_total, ':tax_total' => $tax_total, ':ext' => $ext, ':maxposition' => $maxposition, ':pc' => $pc, ':pd' => $pd, ':td' => $td, ':plugin' => $plugin,':profile_id'=>$profile_id, ':created_by' => $this->system_user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();

            $sql = "select @message,@template_id";
            $params = array();
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['@message'] == 'success') {
                $this->uploadImage($image_file, md5($row['@template_id']), $row['@template_id']);
                return $row;
            } else {
                $sql = "show errors";
                $params = array();
                $this->db->exec($sql, $params);
                $row = $this->db->single();
                return $this->db->error . $row['Message'];
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E133]Error while saving new template Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateTemplate($user_id, $main_header_id, $main_header_default, $exist_header_default, $template_id, $exist_customer_column, $customer_column, $custom_column, $existheader, $headerid, $existheaderdatatype, $exist_function_id, $function_id, $exist_function_param
    , $exist_function_val, $function_param, $function_val, $header, $headerdatatype, $column_type, $position, $sort, $tnc, $templatename, $particular_total, $tax_total, $image_file, $pc, $pd, $td, $plugin,$profile_id) {
        try {
            $main_header_id = implode('~', $main_header_id);
            $main_header_default = implode('~', $main_header_default);
            $exist_header_default = implode('~', $exist_header_default);
            $exist_customer_column = implode('~', $exist_customer_column);
            $customer_column = implode('~', $customer_column);
            $custom_column = implode('~', $custom_column);
            $existheader = implode('~', $existheader);
            $headerid = implode('~', $headerid);
            $existheaderdatatype = implode('~', $existheaderdatatype);
            $exist_function_id = implode('~', $exist_function_id);
            $function_id = implode('~', $function_id);
            $exist_function_param = implode('~', $exist_function_param);
            $exist_function_val = implode('~', $exist_function_val);
            $function_param = implode('~', $function_param);
            $function_val = implode('~', $function_val);
            $headerdatatype = implode('~', $headerdatatype);
            $position = implode('~', $position);
            $column_type = implode('~', $column_type);
            $sort = implode('~', $sort);
            $header = implode('~', $header);
            $filename = basename($image_file['name']);
            $ext = '.' . substr($filename, strrpos($filename, '.') + 1);

            $pc = json_encode($pc);

            $sql = "call `template_edit`(:template_id,:templatename,:main_header_id,:main_header_default,:exist_header_default,:exist_customer_column,:customer_column,:custom_column,:existheader,:headerid,:existheaderdatatype,:exist_function_id,:header,:headerdatatype,:column_type,:position,:sort,:function_id,:exist_function_param,:exist_function_val,:function_param,:function_val,:tnc,:particular_total,:tax_total,:ext,:pc,:pd,:td,:plugin,:profile_id,:update_by);";
            $params = array(':template_id' => $template_id, ':templatename' => $templatename, ':main_header_id' => $main_header_id, ':main_header_default' => $main_header_default, ':exist_header_default' => $exist_header_default, ':exist_customer_column' => $exist_customer_column, ':customer_column' => $customer_column, ':custom_column' => $custom_column, ':existheader' => $existheader, ':headerid' => $headerid, ':existheaderdatatype' => $existheaderdatatype, ':exist_function_id' => $exist_function_id, ':header' => $header, ':headerdatatype' => $headerdatatype, ':column_type' => $column_type, ':position' => $position, ':sort' => $sort, ':function_id' => $function_id, ':exist_function_param' => $exist_function_param, ':exist_function_val' => $exist_function_val, ':function_param' => $function_param, ':function_val' => $function_val, ':tnc' => $tnc, ':particular_total' => $particular_total, ':tax_total' => $tax_total, ':ext' => $ext, ':pc' => $pc, ':pd' => $pd, ':td' => $td, ':plugin' => $plugin,':profile_id'=>$profile_id, ':update_by' => $this->system_user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['message'] == 'success') {
                if (empty($image_file)) {
                    $this->update_logo('', $template_id);
                } else {
                    if ($image_file['name'] != '') {
                        $this->uploadImage($image_file, md5($template_id), $template_id);
                    }
                }
                return $row;
            } else {
                return $row['Message'];
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E134]Error while updating exist template Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getTemplateInfo($template_id) {
        try {
            $sql = 'select * from invoice_template where template_id=:template_id';
            $params = array(':template_id' => $template_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            $this->db->closeStmt();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function uploadImage($image_file, $name, $template_id) {
        try {
            $filename = basename($image_file['name']);

            $ext = substr($filename, strrpos($filename, '.') + 1);
            $newname = 'uploads/images/logos/' . $name . '.' . $ext;
            //Check if the file with the same name is already exists on the server
            while (file_exists($newname)) {
                $name = '1' . $name;
                $newname = 'uploads/images/logos/' . $name . '.' . $ext;
            }
            $name = $name . '.' . $ext;
            //Attempt to move the uploaded file to it's new place
            if ((move_uploaded_file($image_file['tmp_name'], $newname))) {
                $this->update_logo($name, $template_id);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E136]Error while uploading template logo Error: ' . $e->getMessage());
        }
    }

    public function update_logo($logo, $template_id) {
        try {
            $sql = 'UPDATE `invoice_template` SET `image_path`=:logo,last_update_by=`user_id`, last_update_date=CURRENT_TIMESTAMP() WHERE template_id=:template_id';
            $params = array(':template_id' => $template_id, ':logo' => $logo);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E137]Error while deleting existing template Error: for template id[' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function deleteTemplate($template_id, $user_id) {
        try {
            $sql = 'UPDATE `invoice_template` SET `is_active`=0,last_update_by=`user_id`,'
                    . ' last_update_date=CURRENT_TIMESTAMP() WHERE template_id=:template_id and user_id=:user_id';
            $params = array(':template_id' => $template_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E137]Error while deleting existing template Error: for template id[' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getDecreptValue($link) {
        $converter = new Encryption;
        return $converter->decode($link);
    }

    public function isExistTemplate($templatename, $user_id) {
        try {
            $sql = "select template_id from invoice_template WHERE template_name=:templatename and user_id=:user_id and is_active=1 and template_type<>'event'";
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

    public function getSystemTemplateDetails($template_id) {
        try {
            $sql = "SELECT system_column_id as column_id,system_col_name,position,column_position,is_delete_allow,default_column_value,save_table_name,function_id,column_name,column_datatype,is_mandatory,column_type,column_group_id,column_position,
	system_template_id from system_template_column_metadata  where system_template_id=:template_id and is_active=1  order by column_position,system_column_id";
            $params = array(':template_id' => $template_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, 'Error while getting invoice breckup Error: for template id[' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function GSTTaxExist($merchant_id, $percentage, $type) {
        try {
            $sql = "select tax_id from merchant_tax WHERE merchant_id=:merchant_id and tax_type=:tax_type and percentage=:percentage and is_active=1 limit 1";
            $params = array(':merchant_id' => $merchant_id, ':tax_type' => $type, ':percentage' => $percentage);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return $result['tax_id'];
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__ . __METHOD__, 'Error: for data [' . json_encode($params) . ']' . $e->getMessage());
        }
    }

    public function getCloneTemplateMetadata($template_id) {
        try {
            $sql = "select m.*,f.param,f.value as param_value from invoice_column_metadata m inner join column_function_mapping f on m.column_id=f.column_id and m.function_id=f.function_id and f.is_active=1 where m.template_id=:template_id";
            $params = array(':template_id' => $template_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, 'Error while getting invoice breckup Error: for template id[' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveTemplateData($merchant_id, $user_id, $clone_template_id, $template_name, $tax) {
        try {
            $sql = "select generate_sequence('Template_id') as template_id";
            $this->db->exec($sql, array());
            $row = $this->db->single();

            $sql = 'INSERT INTO `invoice_template`(`template_id`,`merchant_id`,`user_id`,`template_name`,`template_type`,`particular_column`,`default_particular`,`particular_values`,`default_tax`,`particular_total`,`tax_total`,`plugin`,`image_path`,`banner_path`,`invoice_title`,`created_by`,`created_date`,`last_update_by`)'
                    . 'select :template_id,:merchant_id,:user_id,:template_name,`template_type`,`particular_column`,`default_particular`,`particular_values`,:tax,`particular_total`,`tax_total`,`plugin`,`image_path`,`banner_path`,`invoice_title`,`created_by`,now(),`last_update_by` from invoice_template where template_id=:clone_template_id';
            $params = array(':template_id' => $row['template_id'], ':template_name' => $template_name, ':merchant_id' => $merchant_id, ':user_id' => $user_id, ':tax' => $tax, ':clone_template_id' => $clone_template_id);
            $this->db->exec($sql, $params);
            return $row['template_id'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__METHOD__, 'Error: for data [' . json_encode($params) . ']' . $e->getMessage());
        }
    }

    public function saveTemplateMetaData($template_id, $clone_template_id) {
        try {
            $sql = 'INSERT INTO `invoice_column_metadata`(`template_id`,`column_datatype`,`column_position`,`sort_order`,`position`,`column_name`,`default_column_value`,`column_type`,`customer_column_id`,
                `is_mandatory`,`is_delete_allow`,`save_table_name`,`is_template_column`,`function_id`,`column_group_id`,`created_by`,`created_date`,`last_update_by`)
                select :template_id,`column_datatype`,`column_position`,`sort_order`,`position`,`column_name`,`default_column_value`,`column_type`,`customer_column_id`,`is_mandatory`,`is_delete_allow`,`save_table_name`,`is_template_column`,`function_id`,`column_group_id`,`created_by`,now(),`last_update_by` from invoice_column_metadata where template_id=:clone_template_id';
            $params = array(':template_id' => $template_id, ':clone_template_id' => $clone_template_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__METHOD__, 'Error: for data [' . json_encode($params) . ']' . $e->getMessage());
        }
    }

    public function saveInvoiceSeq($merchant_id, $prefix) {
        try {
            $sql = 'INSERT INTO `merchant_auto_invoice_number`(`merchant_id`,`prefix`,`val`,`created_by`,`created_date`,`last_update_by`)values('
                    . ':merchant_id,:prefix,0,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id)';
            $params = array(':merchant_id' => $merchant_id, ':prefix' => $prefix);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__METHOD__, 'Error: for data [' . json_encode($params) . ']' . $e->getMessage());
        }
    }

    public function saveColumnFunctionMapping($column_id, $function_id,$param,$value,$created_by) {
        try {
            $sql = 'INSERT INTO `column_function_mapping`(`column_id`,`function_id`,`param`,`value`,`created_by`,`created_date`,`last_update_by`)'
                    . 'VALUES(:column_id,:function_id,:param,:value,:created_by,CURRENT_TIMESTAMP(),:created_by);';
            $params = array(':column_id' => $column_id, ':function_id' => $function_id, ':param' => $param, ':value' => $value, ':created_by' => $created_by);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__METHOD__, 'Error: for data [' . json_encode($params) . ']' . $e->getMessage());
        }
    }

}
