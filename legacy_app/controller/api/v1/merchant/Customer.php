<?php

/**
 * Invoice controller class to handle create and update invoice for patron
 */
class Customer extends Api
{

    private $errorlist = NULL;

    function __construct()
    {
        parent::__construct('Merchant');
        $this->version = 'v1';
    }

    function searchcustomer()
    {
        $array1 = $this->getSearchCustomerJson();
        $this->compairJsonArray($array1, $this->jsonArray);
        $req_time = date("Y-m-d H:i:s");
        require_once MODEL . 'merchant/CustomerModel.php';
        $Customer_model = new CustomerModel();
        $result = $Customer_model->getsearchCustomerList($this->merchant_id, $this->jsonArray['keyword']);
        return $this->printJsonResponse($req_time, $error_code, $result, $this->errorlist);
    }

    function getlist()
    {
        $array1 = $this->getListCustomerJson();
        $this->compairJsonArray($array1, $this->jsonArray);
        $req_time = date("Y-m-d H:i:s");
        require_once MODEL . 'merchant/CustomerModel.php';
        $Customer_model = new CustomerModel();
        $keyword = $this->jsonArray['keyword'];
        $search = $this->jsonArray['searchby'];
        $where = '';
        switch ($search) {
            case 'group':
                $group_id = $this->common->getRowValue('group_id', 'customer_group', 'group_name', $keyword, 1, " and merchant_id='" . $this->merchant_id . "'");
                $where = " where customer_group like ~%" . '{' . $group_id . '}' . '%~';
                break;
            case 'city':
                $where = " where __City like ~%" . $keyword . '%~';
                break;
            case 'state':
                $where = " where __State like ~%" . $keyword . '%~';
                break;
            case 'zipcode':
                $where = " where __Zipcode like ~%" . $keyword . '%~';
                break;
            case 'name':
                $where = " where name like ~%" . $keyword . '%~';
                break;
            case 'customer_code':
                $where = " where customer_code =~" . $keyword . '~';
                break;
            case 'email':
                $where = " where email like =~" . $keyword . '~';
                break;
            case 'mobile':
                $where = " where mobile like =~" . $keyword . '~';
                break;
        }

        $result = $Customer_model->getCustomerList($this->merchant_id, '', 0, $where);
        $customer_array = array();
        foreach ($result as $row) {
            $group = 'All';
            if ($row['customer_group'] != '["{0}"]') {
                $group_array = json_decode($row['customer_group'], 1);
                foreach ($group_array as $grp) {
                    if ($grp != '{0}') {
                        $grp = str_replace('{', '', $grp);
                        $id = str_replace('}', '', $grp);
                        $group_name = $this->common->getRowValue('group_name', 'customer_group', 'group_id', $id, 1, " and merchant_id='" . $this->merchant_id . "'");

                        $group .= ',' . $group_name;
                    }
                }
            }
            $customer['customer_id'] = $row['customer_id'];
            $customer['customer_code'] = $row['customer_code'];
            $customer['name'] = $row['name'];
            $customer['email'] = $row['email'];
            $customer['mobile'] = $row['mobile'];
            $customer['customer_group'] = $group;
            $customer['address'] = $row['__Address'];
            $customer['city'] = $row['__City'];
            $customer['state'] = $row['__State'];
            $customer['zipcode'] = $row['__Zipcode'];
            $customer['created_date'] = $row['created_date'];
            $customer_array[] = $customer;
        }
        return $this->printJsonResponse($req_time, $error_code, $customer_array, $this->errorlist);
    }

    function save()
    {
        try {
            $req_time = date("Y-m-d H:i:s");

            require_once MODEL . 'merchant/CustomerModel.php';
            $Customer_model = new CustomerModel();

            require_once CONTROLLER . 'merchant/Customervalidator.php';
            $validator = new Customervalidator($Customer_model);


            $array1 = $this->getcustomerSaveJson($this->version, $this->merchant_id);
            $this->compairJsonArray($array1, $this->jsonArray);
            $srNo = 0;
            $setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);
            $system_generated = $setting['customer_auto_generate'];
            $cable_enable = $setting['cable_enable'];
            foreach ($this->jsonArray['customer'] as $customer) {
                $_POST = $customer;
                $int = 0;
                foreach ($array1['customer'][0]['custom_fields'] as $struct) {
                    if ($struct['id'] != $customer['custom_fields'][$int]['id']) {
                        $this->createErrorlist('ER02001', 'id', "customer[$srNo].custom_fields[$int].id");
                    } else {
                        if($customer['custom_fields'][$int]['type']!='company_name') {
                            $_POST['column_id'][] = $customer['custom_fields'][$int]['id'];
                            $_POST['column_value'][] = $customer['custom_fields'][$int]['value'];
                        } else {
                            $_POST['company_name'] = $customer['custom_fields'][$int]['value'];
                        }
                        if ($cable_enable == 1) {
                            $datatype = $this->common->getRowValue('column_datatype', 'customer_column_metadata', 'column_id', $customer['custom_fields'][$int]['id']);
                            if ($datatype == 'stb') {
                                $_POST['settop_box'] = explode(',', $customer['custom_fields'][$int]['value']);
                            }
                        }
                    }
                    $int++;
                }
                $_POST['customer_code'] = ($system_generated == 1) ? 'NULL' : $_POST['customer_code'];
                $validator->validateCustomerSave($this->merchant_id);
                $hasErrors = $validator->fetchErrors();
                if ($hasErrors == false) {
                    $POST_array[] = $_POST;
                } else {
                    $this->handleValidationError($hasErrors, "customer[$srNo]");
                }
                $srNo++;
            }

            if ($this->errorlist == NULL) {
                $result = $this->save_Customer($POST_array, $Customer_model);
                if ($result == FALSE) {
                    $error_code = 'ER02030';
                } else {
                    $error_code = 0;
                    $success[] = $result;
                }
            } else {
                $error_code = 'ER02030';
            }
            return $this->printJsonResponse($req_time, $error_code, $success, $this->errorlist);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while saving invoice Error: for user [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    function update()
    {
        try {
            $req_time = date("Y-m-d H:i:s");

            require_once MODEL . 'merchant/CustomerModel.php';
            $Customer_model = new CustomerModel();

            require_once CONTROLLER . 'merchant/Customervalidator.php';
            $validator = new Customervalidator($Customer_model);


            $array1 = $this->getcustomerUpdateJson($this->version, $this->merchant_id);
            $this->compairJsonArray($array1, $this->jsonArray);
            $srNo = 0;
            foreach ($this->jsonArray['customer'] as $customer) {
                $_POST = $customer;
                $int = 0;
                foreach ($array1['customer'][0]['custom_fields'] as $struct) {
                    if ($struct['id'] != $customer['custom_fields'][$int]['id']) {
                        $this->createErrorlist('ER02001', 'id', "customer[$srNo].custom_fields[$int].id");
                    } else {
                        if($customer['custom_fields'][$int]['type']!='company_name') {
                            $_POST['column_id'][] = $customer['custom_fields'][$int]['id'];
                            $_POST['column_value'][] = $customer['custom_fields'][$int]['value'];
                        } else {
                            $_POST['company_name'] = $customer['custom_fields'][$int]['value'];
                        }
                        
                    }
                    $int++;
                }
                $cust_merchant_id = $this->common->getRowValue('merchant_id', 'customer', 'customer_id', $_POST['customer_id'], 1);
                if ($cust_merchant_id != $this->merchant_id) {
                    $hasErrors['customer_id'] = array('Customer id', 'Invalid customer id.');
                } else {
                    $validator->validateCustomerUpdate($this->merchant_id, $_POST['customer_id']);
                    $hasErrors = $validator->fetchErrors();
                }
                if ($hasErrors == false) {
                    $POST_array[] = $_POST;
                } else {
                    $this->handleValidationError($hasErrors, "customer[$srNo]");
                }
                $srNo++;
            }

            if ($this->errorlist == NULL) {
                $result = $this->update_Customer($POST_array, $Customer_model);
                if ($result == FALSE) {
                    $error_code = 'ER02030';
                } else {
                    $error_code = 0;
                    $success[] = $result;
                }
            } else {
                $error_code = 'ER02030';
            }
            return $this->printJsonResponse($req_time, $error_code, $success, $this->errorlist);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while saving invoice Error: for user [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    function save_Customer($POST_array, $Customer_model)
    {
        $system_generated = $this->common->getRowValue('customer_auto_generate', 'merchant_setting', 'merchant_id', $this->merchant_id);
        foreach ($POST_array as $_POST) {
            if ($system_generated == 1) {
                $customer_code = $Customer_model->getCustomerCode($this->merchant_id);
                $_POST['customer_code'] = 'NULL';
            } else {
                $customer_code = $_POST['customer_code'];
            }

            $space_position = strpos($_POST['customer_name'], ' ');
            if ($space_position > 0) {
                $_POST['first_name'] = substr($_POST['customer_name'], 0, $space_position);
                $_POST['last_name'] = substr($_POST['customer_name'], $space_position);
            } else {
                $_POST['first_name'] = $_POST['customer_name'];
                $_POST['last_name'] = '';
            }
            $addressfull = $_POST['address'];
            $_POST['address'] = substr($addressfull, 0, 250);
            $_POST['address2'] = substr($addressfull, 250);
            $_POST['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : NULL;
            $_POST['country'] = isset($_POST['country']) ? $_POST['country'] : 'India';
            $column_id = (empty($_POST['column_id'])) ? array() : $_POST['column_id'];
            $column_value = (empty($_POST['column_value'])) ? array() : $_POST['column_value'];

            $row = $Customer_model->saveCustomer($this->user_id, $this->merchant_id, $customer_code, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zipcode'], $column_id, $column_value,0,'','',$_POST['company_name'],$_POST['country']);
            if ($row['message'] == 'success') {
                unset($row['message']);
                $result[] = $row;
                if (isset($_POST['settop_box'])) {
                    $Customer_model->saveCustomerSettopBox($_POST['settop_box'], $row['customer_id'], $this->merchant_id, $this->user_id);
                }
            } else {
                Sentry\captureMessage('[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while saving customer Error: for user [' . $this->user_id . '] and for error [' . json_encode($row) . ']');
                SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while saving customer Error: for user [' . $this->user_id . '] and for error [' . json_encode($row) . ']');
                return FALSE;
            }
        }
        return $result;
    }

    function update_Customer($POST_array, $Customer_model)
    {
        foreach ($POST_array as $_POST) {

            $customer_code = $_POST['customer_code'];
            $space_position = strpos($_POST['customer_name'], ' ');
            if ($space_position > 0) {
                $_POST['first_name'] = substr($_POST['customer_name'], 0, $space_position);
                $_POST['last_name'] = substr($_POST['customer_name'], $space_position);
            } else {
                $_POST['first_name'] = $_POST['customer_name'];
                $_POST['last_name'] = '';
            }
            $addressfull = $_POST['address'];
            $_POST['address'] = substr($addressfull, 0, 250);
            $_POST['address2'] = substr($addressfull, 250);
            $_POST['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : NULL;
            $_POST['country'] = isset($_POST['country']) ? $_POST['country'] : 'India';
            $column_id = (empty($_POST['column_id'])) ? array() : $_POST['column_id'];
            $column_value = (empty($_POST['column_value'])) ? array() : $_POST['column_value'];
            $invoice_id = array();
            $invoice_value = array();
            $inv_id = array();
            foreach ($column_id as $key => $val) {
                $row = $this->common->getSingleValue('customer_column_values', 'column_id', $val, 0, ' and customer_id=' . $_POST['customer_id']);
                if (!empty($row)) {
                    $invoice_id[] = $row['id'];
                    $invoice_value[] = $column_value[$key];
                    $inv_id[] = $key;
                    unset($column_id[$key]);
                    unset($column_value[$key]);
                }
            }
            $row = $Customer_model->updateCustomer($this->user_id, $_POST['customer_id'], $customer_code, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zipcode'], $column_id, $column_value, $invoice_id, $invoice_value, '', '', $_POST['company_name'],$_POST['country']);
            if ($row['message'] == 'success') {
                unset($row['message']);
                $row['customer_id'] = $_POST['customer_id'];
                $row['customer_name'] = $_POST['first_name'] . ' ' . $_POST['last_name'];
                $row['email'] = $_POST['email'];
                $result[] = $row;
            } else {
                SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while saving customer Error: for user [' . $this->user_id . '] and for error [' . json_encode($row) . ']');
                return FALSE;
            }
        }
        return $result;
    }

    function detail()
    {
        $array1 = $this->getCustomerDetailJson();
        $this->compairJsonArray($array1, $this->jsonArray);
        $req_time = date("Y-m-d H:i:s");
        $row = $this->common->getSingleValue('customer', 'customer_code', $this->jsonArray['customer_code'], 1, "and merchant_id='" . $this->merchant_id . "'");
        if (empty($row)) {
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02031');
        }
        require_once MODEL . 'merchant/CustomerModel.php';
        $Customer_model = new CustomerModel();
        $result = $Customer_model->getCustomerDeatils($row['customer_id'], $this->merchant_id);
        return $this->printJsonResponse($req_time, $error_code, $result, $this->errorlist);
    }

    function handleValidationError($errors, $path_ = NULL)
    {
        foreach ($errors as $key => $value) {
            $path = ($path_ != NULL) ? $path_ . "." . $key : $key;
            switch ($key) {
                case 'customer_id':
                    $this->createErrorlist('ER02034', $key, $path);
                    break;
                case 'customer_code':
                    $this->createErrorlist('ER02031', $key, $path);
                    break;
                case 'email':
                    $this->createErrorlist('ER02004', $key, $path);
                    break;
                case 'mobile':
                    $this->createErrorlist('ER02003', $key, $path);
                    break;
                case 'customer_name':
                    $this->createErrorlist('ER02005', $key, $path);
                    break;
                case 'address':
                    $this->createErrorlist('ER02008', $key, $path);
                    break;
                case 'state':
                    $this->createErrorlist('ER02008', $key, $path);
                    break;
                case 'city':
                    $this->createErrorlist('ER02008', $key, $path);
                    break;
                case 'zipcode':
                    $this->createErrorlist('ER02007', $key, $path);
                    break;
                default:
                    $this->createErrorlist('ER02015', $key, $path);
                    break;
            }
        }
    }

    function createErrorlist($code, $key_name, $key_path)
    {
        $message = $this->ApiErrors->fetch($code);
        $this->errorlist[] = array('code' => $code, 'msg' => $message, 'keyname' => $key_name, 'keypath' => $key_path);
    }
}
