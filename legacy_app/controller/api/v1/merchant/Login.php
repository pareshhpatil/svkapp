<?php

/**
 * Invoice controller class to handle create and update invoice for patron
 */
class Login extends Api {

    Private $errorlist = NULL;

    function __construct() {
        parent::__construct('Login');
        $this->version = 'v1';
    }

    public function check() {
        if (isset($_POST['data'])) {
            # Validate Json format
            $jsonarray = json_decode($_POST['data'], 1);
            if (!$this->validate_json($_POST['data'])) {
                SwipezLogger::debug(__CLASS__, json_encode($jsonarray));
                $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01001');
            }
            $array1 = $this->getValidateLoginCheckJson();
            $this->jsonArray = json_decode($_POST['data'], 1);
            $this->compairJsonArray($array1, $this->jsonArray);
            $req_time = date("Y-m-d H:i:s");
            require_once MODEL . 'LoginModel.php';
            $ticketModel = new LoginModel();
            $success = array();
            $data = $ticketModel->queryLoginInfo($this->jsonArray['username'], $this->jsonArray['password']);

            if ($data['isValid'] == 0) {
                SwipezLogger::info("Login", $this->jsonArray['username'] . " login failed login attempt " . $data['loginattempt']);
                if ($data['loginattempt'] == 10) {
                    $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02039');
                } else if ($data['status'] == 18) {
                    $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02039');
                } else {
                    if ($data['status'] == 1 || $data['status'] == 11 || $data['status'] == 19) {
                        $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02040');
                    } else {
                        $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02041');
                    }
                }
            } else {
                $success['is_valid'] = 1;
                $success['company_name'] = $data['company_name'];
                $success['user_id'] = $data['user_id'];
                $success['name'] = $data['name'];
                $success['email_id'] = $data['email_id'];
                $success['merchant_id'] = $data['merchant_id'];
                $success['cable_enable'] = $data['cable_enable'];
                $success['customer_group'] = $data['customer_group'];
                $success['view_roles'] = $data['view_roles'];
                $success['update_roles'] = $data['update_roles'];
            }


            $this->printJsonResponse($req_time, $error_code, $success, $this->errorlist);
        } else {
            $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01006');
        }
    }

    public function validate() {
        if (isset($_POST['data'])) {
            # Validate Json format
            $jsonarray = json_decode($_POST['data'], 1);
            if (!$this->validate_json($_POST['data'])) {
                SwipezLogger::debug(__CLASS__, json_encode($jsonarray));
                $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01001');
            }
            $array1 = $this->getValidateLoginJson();
            $this->jsonArray = json_decode($_POST['data'], 1);
            $this->compairJsonArray($array1, $this->jsonArray);
            $req_time = date("Y-m-d H:i:s");
            require_once MODEL . 'merchant/TicketModel.php';
            $ticketModel = new TicketModel();
            $success = array();
            $result = $ticketModel->existExistCookie($this->jsonArray['user_id'], $this->jsonArray['merchant_id']);
            if ($result == FALSE) {
                $this->errorlist = array('user_id' => 'Invalid User id Or Merchant id');
                $error_code = 'ER02030';
            } else {
                $date_now = new DateTime();
                $date2 = new DateTime($result['cookie_expire']);
                if ($date2 > $date_now) {
                    $success['status'] = '1';
                    $success['message'] = 'Logged in user';
                } else {
                    $success['status'] = '0';
                    $success['message'] = 'Session expired';
                }
                $details = $this->common->getSingleValue('user', 'user_id', $this->jsonArray['user_id']);
                $success['user_id'] = $details['user_id'];
                $success['merchant_id'] = $this->jsonArray['merchant_id'];
                $success['user_name'] = $details['first_name'] . ' ' . $details['last_name'];
                $success['email'] = $details['email_id'];
                $success['user_type'] = $details['user_group_type'];
            }
            $this->printJsonResponse($req_time, $error_code, $success, $this->errorlist);
        } else {
            $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01006');
        }
    }

    function createErrorlist($code, $key_name, $key_path) {
        $message = $this->ApiErrors->fetch($code);
        $this->errorlist[] = array('code' => $code, 'msg' => $message, 'keyname' => $key_name, 'keypath' => $key_path);
    }

}
