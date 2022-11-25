<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class CustomerModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function saveCustomerStructure($user_id, $merchant_id, $prefix, $is_autogenerate, $position, $column_name, $datatype, $exist_col_id, $exist_datatype, $exist_col_name)
    {
        try {
            $position = implode('~', $position);
            $column_name = implode('~', $column_name);
            $datatype = implode('~', $datatype);
            $exist_col_id = implode('~', $exist_col_id);
            $exist_datatype = implode('~', $exist_datatype);
            $exist_col_name = implode('~', $exist_col_name);

            $sql = "call `customer_structure_save`(:user_id,:merchant_id,:prefix,:auto_generate,:position,:column_name,:datatype,:col_id,:exist_col_name,:exist_datatype);";
            $params = array(
                ':user_id' => $user_id, ':merchant_id' => $merchant_id, ':prefix' => $prefix, ':auto_generate' => $is_autogenerate, ':position' => $position,
                ':column_name' => $column_name, ':datatype' => $datatype, ':col_id' => $exist_col_id, ':exist_col_name' => $exist_col_name, ':exist_datatype' => $exist_datatype
            );
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['message'] == 'success') {
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

    public function saveCustomer($user_id, $merchant_id, $customer_code, $first_name, $last_name, $email, $mobile, $address, $address2, $city, $state, $zipcode, $column_id, $column_value, $bulk_id = 0, $password = '', $gst = '', $company_name = '', $country = 'India')
    {
        try {
            $first_name = (isset($first_name)) ? trim($first_name) : '';
            $last_name = (isset($last_name)) ? trim($last_name) : '';
            $email = (isset($email)) ? trim($email) : '';
            $mobile = (isset($mobile)) ? trim($mobile) : '';
            $city = (isset($city)) ? $city : '';
            $state = (isset($state)) ? $state : '';
            $zipcode = ($zipcode > 0) ? trim($zipcode) : '';
            $column_id = implode('~', $column_id);
            $column_value = implode('~', $column_value);
            $address = ($address != '0') ? $address : '';
            $address2 = ($address2 != '0') ? $address2 : '';
            if (strtolower($email) == 'emailunavailable@swipez.in') {
                $email = '';
            }
            if ($mobile == '9999999999') {
                $mobile = '';
            }
            $sql = "call `customer_save`(:user_id,:merchant_id,:customer_code,:first_name,:last_name,:email,:mobile,:address,:address2,:city,:state,:zipcode,:column_id,:column_value,:password,:gst,:bulk_id,:company_name,:country);";
            $params = array(
                ':user_id' => $user_id, ':merchant_id' => $merchant_id, ':customer_code' => $customer_code, ':first_name' => $first_name, ':last_name' => $last_name,
                ':email' => $email, ':mobile' => $mobile, ':address' => trim($address), ':address2' => $address2, ':city' => $city, ':state' => $state, ':zipcode' => $zipcode, ':column_id' => $column_id, ':column_value' => $column_value, ':password' => $password, ':gst' => $gst, ':bulk_id' => $bulk_id, 
                ':company_name' => $company_name, ':country' => $country
            );
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['message'] == 'success') {
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

    public function updateCustomer($user_id, $customer_id, $customer_code, $first_name, $last_name, $email, $mobile, $address, $address2, $city, $state, $zipcode, $column_id, $column_value, $excolumn_id, $excolumn_value, $password = '', $gst = '', $company_name = '', $country ='India')
    {
        try {
            $column_id = implode('~', $column_id);
            $column_value = implode('~', $column_value);
            $excolumn_id = implode('~', $excolumn_id);
            $excolumn_value = implode('~', $excolumn_value);
            $address = ($address != '0') ? $address : '';
            $address2 = ($address2 != '0') ? $address2 : '';
            $zipcode = ($zipcode > 0) ? trim($zipcode) : '';
            if (strtolower($email) == 'emailunavailable@swipez.in') {
                $email = '';
            }
            if ($mobile == '9999999999') {
                $mobile = '';
            }
            $sql = "call `customer_update`(:user_id,:customer_id,:customer_code,:first_name,:last_name,:email,:mobile,:address,:address2,:city,:state,:zipcode,
                :column_id,:column_value,:excolumn_id,:excolumn_value,:password,:gst,:company_name,:country);";
            $params = array(
                ':user_id' => $user_id, ':customer_id' => $customer_id, ':customer_code' => $customer_code, ':first_name' => trim($first_name), ':last_name' => trim($last_name),
                ':email' => trim($email), ':mobile' => trim($mobile), ':address' => trim($address), ':address2' => $address2, ':city' => trim($city), ':state' => trim($state), ':zipcode' => trim($zipcode),
                ':column_id' => $column_id, ':column_value' => $column_value, ':excolumn_id' => $excolumn_id, ':excolumn_value' => $excolumn_value, ':password' => $password, ':gst' => $gst, ':company_name' => $company_name,':country' => $country
            );
            $this->db->exec($sql, $params);

            $row = $this->db->single();
            if ($row['message'] == 'success') {
                return $row;
            } else {
                $sql = "show errors";
                $params = array();
                $this->db->exec($sql, $params);
                $row = $this->db->single();

                SwipezLogger::error(__CLASS__, '[E133-1]Error while updating customer Error: for user id[' . $user_id . ']' . $row['Message']);
                return $this->db->error . $row['Message'];
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E133]Error while updating customer Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveCustomerSettopBox($boxs, $customer_id, $merchant_id, $user_id)
    {
        foreach ($boxs as $name) {
            if ($name != '') {
                $res = $this->isExistService($customer_id, $name);
                if ($res == 0) {
                    $sql = "INSERT INTO `customer_service`(`customer_id`,`merchant_id`,`service_type`,`name`,`narrative`,`status`,`created_by`,`created_date`,`updated_by`)"
                        . "VALUES(:customer_id,:merchant_id,1,:name,'30 Days',1,:user_id,CURRENT_TIMESTAMP(),:user_id);";
                    $params = array(':customer_id' => $customer_id, ':merchant_id' => $merchant_id, ':name' => $name, ':user_id' => $user_id);
                    $this->db->exec($sql, $params);
                }
            }
        }
    }

    public function isExistService($customer_id, $name)
    {
        try {
            $sql = 'select id from customer_service WHERE customer_id=:customer_id and name=:name and is_active=1';
            $params = array(':name' => $name, ':customer_id' => $customer_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return $result['id'];
            } else {
                return 0;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1010]Error while checking service exist Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getStates()
    {
        try{
            $sql = 'select config_value from config WHERE config_type=:getstatecode';
            $params = array(':getstatecode'=> 'gst_state_code');
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            $states = [];

            if (!empty($result)) {
                foreach($result as $item)
                {
                    $states[] = strtolower($item['config_value']);
                }
                return $states;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1010]Error while checking service exist Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }   
        
    }

    public function getCountries() {
        try{
            $sql = 'select config_value from config WHERE config_type=:country_code';
            $params = array(':country_code'=> 'country_name');
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            $countries = [];

            if (!empty($result)) {
                foreach($result as $item)
                {
                    $countries[] = strtolower($item['config_value']);
                }
                return $countries;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1010]Error while checking country exist Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }   
    }

    public function getSMSCount($merchant_id)
    {
        try {
            $sql = "SELECT sum(license_available) as smscount from merchant_addon where is_active=1 and package_id=7 and license_available>0 and start_date<=NOW() and end_date>=NOW() and merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (isset($row['smscount'])) {
                return $row['smscount'];
            } else {
                return 0;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E133]Error while get SMS Count ' . $e->getMessage());
        }
    }

    public function getCustomerBreakup($merchant_id)
    {
        try {
            $sql = "select * from customer_column_metadata where is_active=1 and merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E135]Error while getting invoice breckup Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function getcustomervalueid($column_id, $customer_id)
    {
        try {
            $sql = "select id from customer_column_values where is_active=1 and column_id=:column_id and customer_id=:customer_id";
            $params = array(':column_id' => $column_id, ':customer_id' => $customer_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['id'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E135]Error while getting invoice breckup Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function getCustomerDeatils($customer_id, $merchant_id)
    {
        try {
            $sql = "select *,concat(first_name,' ',last_name) as name,balance from customer where customer_id=:customer_id and merchant_id=:merchant_id;";
            $params = array(':customer_id' => $customer_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E135]Error while getting customer details Error: for Customer id[' . $customer_id . ']' . $e->getMessage());
        }
    }

    public function getCustomerCustomValues($customer_id, $merchant_id)
    {
        try {
            $sql = "select v.id,c.column_id,v.value,c.column_datatype,c.position,c.column_name from customer_column_values v inner join customer_column_metadata c on v.column_id=c.column_id
                 where v.customer_id=:customer_id and c.is_active=1 and c.merchant_id=:merchant_id";
            $params = array(':customer_id' => $customer_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E135]Error while getting invoice breckup Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function getBulkuploadList($merchant_id)
    {
        try {
            $sql = "select bulk_upload_id,merchant_id,total_rows,merchant_filename,system_filename,status,bulk_upload.created_date,config_value from bulk_upload inner join config on bulk_upload.status = config.config_key where merchant_id=:merchant_id and config.config_type='bulk_upload_status' and status not in (6,7)  and type=2 order by bulk_upload_id desc";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function getCustomerCode($merchant_id)
    {
        try {
            $is_exist = TRUE;
            while ($is_exist == TRUE) {
                $sql = 'select generate_customer_sequence(:merchant_id) as customer_code';
                $params = array(':merchant_id' => $merchant_id);
                $this->db->exec($sql, $params);
                $row = $this->db->single();
                $customer_code = $row['customer_code'];
                $is_exist = $this->isExistCustomerCode($merchant_id, $customer_code);
            }
            return $customer_code;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function isExistCustomerCode($merchant_id, $data, $customer_id = NULL)
    {
        try {
            $sql = 'select distinct customer_id from customer where merchant_id=:merchant_id and customer_code=:customer_code and is_active=1';
            if ($customer_id != NULL) {
                $sql .= " and customer_id<>" . $customer_id . " ;";
            }
            $params = array(':merchant_id' => $merchant_id, ':customer_code' => $data);
            $this->db->exec($sql, $params);
            $data = $this->db->single();
            if (!empty($data)) {
                return $data['customer_id'];
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function isExistContactdetails($merchant_id, $email, $mobile)
    {
        try {
            $sql = "select customer_id from customer where merchant_id=:merchant_id and ((email=:email and email<>'') OR (mobile=:mobile and mobile<>'')) and is_active=1";
            $params = array(':merchant_id' => $merchant_id, ':email' => $email, ':mobile' => $mobile);
            $this->db->exec($sql, $params);
            $data = $this->db->resultset();
            if (!empty($data)) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function getcustomerId($merchant_id, $first_name, $email)
    {
        try {
            $sql = 'select * from customer where merchant_id=:merchant_id and first_name=:first_name and email=:email limit 1';
            $params = array(':merchant_id' => $merchant_id, ':first_name' => $first_name, ':email' => $email);
            $this->db->exec($sql, $params);
            $data = $this->db->single();
            if (!empty($data)) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function getexistcustomerId($merchant_id, $first_name, $last_name, $email, $mobile)
    {
        try {
            $sql = 'select customer_id from customer where merchant_id=:merchant_id and first_name=:first_name and last_name=:last_name and email=:email and mobile=:mobile and is_active=1';
            $params = array(':merchant_id' => $merchant_id, ':first_name' => trim($first_name), ':last_name' => trim($last_name), ':email' => $email, ':mobile' => $mobile);
            $this->db->exec($sql, $params);
            $data = $this->db->resultset();
            if (!empty($data)) {
                foreach ($data as $a) {
                    $ids[] = $a['customer_id'];
                }
                return $ids;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function existCustomerDetail($merchant_id, $first_name, $state, $email, $mobile, $gst_number)
    {
        try {
            $sql = 'select customer_id,customer_code from customer where merchant_id=:merchant_id and first_name=:first_name and email=:email and mobile=:mobile and is_active=1 ';
            if ($state != '') {
                $sql .= " and state='" . $state . "'";
            }
            if ($gst_number != '') {
                $sql .= " and gst_number='" . $gst_number . "'";
            }
            $params = array(':merchant_id' => $merchant_id, ':first_name' => trim($first_name), ':email' => $email, ':mobile' => $mobile);
            $this->db->exec($sql, $params);
            $data = $this->db->single();
            if (!empty($data)) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function isExistCustomerEmail($merchant_id, $email, $type = 1)
    {
        try {
            if ($type == 1) {
                $sql = 'select customer_id from customer where merchant_id=:merchant_id 
                and email=:email limit 1';
            } else {
                $sql = 'select customer_id from customer where merchant_id=:merchant_id 
                and mobile=:email limit 1';
            }
            $params = array(':merchant_id' => $merchant_id, ':email' => $email);
            $this->db->exec($sql, $params);
            $data = $this->db->single();
            return $data;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138-12]Error while checking template exist Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function getCustomerallList($merchant_id, $where = '')
    {
        try {
            $sql = "select customer_id,customer_code,concat(first_name,' ',last_name) as name from "
                . "customer where merchant_id=:merchant_id and is_active=1" . $where;
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $data = $this->db->resultset();
            return $data;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function getCustomerList($merchant_id, $column_name, $bulk_id, $where = '')
    {
        try {
            $column_name = implode('~', $column_name);
            $sql = "call get_customer_list(:merchant_id,:column_name,:where,'','',:bulk_id);";
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

    public function getbulkCustomerList($bulk_id, $merchant_id, $column_name)
    {
        try {
            $column_name = implode('~', $column_name);
            $sql = "call get_staging_customer_list(:bulk_id,:merchant_id,:column_name);";
            $params = array(':bulk_id' => $bulk_id, ':merchant_id' => $merchant_id, ':column_name' => $column_name);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E149]Error while getting customer list Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getCustomerTemplateColumn($template_id, $customer_id)
    {
        try {
            $sql = "SELECT distinct m.column_name,m.column_id,v.value,m.customer_column_id,m.save_table_name,m.column_datatype FROM invoice_column_metadata m left outer join customer_column_values v
            on m.customer_column_id=v.column_id and v.customer_id=:customer_id  where template_id=:template_id and m.is_active=1 and column_type='C'";
            $params = array(':customer_id' => $customer_id, ':template_id' => $template_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E149]Error while getting customer list Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function deleteCustomer($merchant_id, $customer_id, $user_id)
    {
        try {
            $sql = "update customer set is_active=0,last_update_by=:user_id where merchant_id=:merchant_id and customer_id=:customer_id;";
            $params = array(':merchant_id' => $merchant_id, ':customer_id' => $customer_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E149]Error while delete customer Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function deleteGroup($merchant_id, $group_id, $user_id)
    {
        try {
            $sql = "update customer_group set is_active=0,last_update_by=:user_id where merchant_id=:merchant_id and group_id=:group_id;";
            $params = array(':merchant_id' => $merchant_id, ':group_id' => $group_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E149]Error while delete customer group Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function getcustomercoldetails($customer_id, $template_id, $merchant_id)
    {
        try {
            $customer = $this->getCustomerDeatils($customer_id, $merchant_id);
            $list = $this->getCustomerTemplateColumn($template_id, $customer_id);
            foreach ($list as $val) {
                if ($val['save_table_name'] == 'customer') {
                    switch ($val['customer_column_id']) {
                        case 1:
                            $value = $customer['customer_code'];
                            break;
                        case 2:
                            $value = $customer['first_name'] . ' ' . $customer['last_name'];
                            break;
                        case 3:
                            $value = $customer['email'];
                            break;
                        case 4:
                            $value = $customer['mobile'];
                            break;
                        case 5:
                            $value = $customer['address'];
                            break;
                        case 6:
                            $value = $customer['city'];
                            break;
                        case 7:
                            $value = $customer['state'];
                            break;
                        case 8:
                            $value = $customer['zipcode'];
                            break;
                    }
                    $column[] = array('column_name' => $val['column_name'], 'id' => $val['column_id'], 'value' => $value, 'datatype' => $val['column_datatype']);
                } else {
                    $column[] = array('column_name' => $val['column_name'], 'id' => $val['column_id'], 'value' => $val['value'], 'datatype' => $val['column_datatype']);
                }
            }
            return $column;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E272]Error while bulk upload error Error:for merchant [' . $customer_id . '] and  bulk id [' . $bulk_id . ']' . $e->getMessage());
        }
    }

    public function isExistTemplate($name, $merchant_id)
    {
        try {
            $sql = 'select group_id from customer_group WHERE group_name=:name and merchant_id=:merchant_id and is_active=1';
            $params = array(':name' => $name, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return TRUE;
            }
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1010]Error while checking group exist Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function createGroup($category_name, $merchant_id, $user_id)
    {
        try {

            $sql = "INSERT INTO `customer_group`(`merchant_id`,`group_name`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
                VALUES(:merchant_id,:name,:user_id,CURRENT_TIMESTAMP(),:user_id,CURRENT_TIMESTAMP())";
            $params = array(':merchant_id' => $merchant_id, ':name' => $category_name, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $id = $this->db->lastInsertId();
            return $id;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1004]Error while creating group Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getsearchCustomerList($merchant_id, $keyword)
    {
        try {
            if (strlen($keyword) > 2) {
                $sql = "select customer_id,customer_code,first_name,last_name from customer where merchant_id='" . $merchant_id . "' and (customer_code like '%" . $keyword . "%' OR first_name like '%" . $keyword . "%' OR last_name like '%" . $keyword . "%')";
                $params = array();
                $this->db->exec($sql, $params);
                $list = $this->db->resultset();
                return $list;
            } else {
                return array();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E149]Error while getting customer list Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateFranchise($customer_id, $franchise_code, $name, $email_id, $mobile, $address, $user_id)
    {
        try {
            $sql = "update franchise set franchise_code=:franchise_code,contact_person_name=:name,email_id=:email_id,mobile=:mobile,address=:address,last_update_by=:user_id where customer_id=:customer_id;";
            $params = array(':franchise_code' => $franchise_code, ':name' => $name, ':email_id' => $email_id, ':mobile' => $mobile, ':address' => $address, ':customer_id' => $customer_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E149]Error while delete customer Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
