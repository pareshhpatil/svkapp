<?php

/**
 * Invoice controller class to handle create and update invoice for patron
 */
class Payment extends Api {

    Private $errorlist = NULL;

    function __construct() {
        parent::__construct('Merchant');
    }

    public function received() {
        $req_time = date("Y-m-d H:i:s");
        $from_date = $this->jsonArray['from_date'];
        $to_date = $this->jsonArray['to_date'];
        require_once MODEL . 'merchant/TransactionModel.php';
        $transactionModel = new TransactionModel();
        require_once CONTROLLER . 'Paymentvalidator.php';

        $array1 = $this->getPaymentReceivedJson();
        $this->compairJsonArray($array1, $this->jsonArray);

        $_POST['from_date'] = $from_date;
        $_POST['to_date'] = $to_date;
        $_POST['to_cdate'] = $to_date;

        $validator = new Paymentvalidator($transactionModel);
        $validator->validatePaymentReceivedAPI();
        $hasErrors = $validator->fetchErrors();
        if ($hasErrors == FALSE) {
            $datetime1 = new DateTime($from_date);
            $datetime2 = new DateTime($to_date);
            $interval = $datetime1->diff($datetime2);
            $diff = $interval->format('%a');
            if ($diff > 31) {
                $this->printJsonResponse($req_time, 'ER02010');
            } else {
                try {
                    $result = $transactionModel->getPaidInvoiceDetails($this->user_id, $from_date, $to_date);
                } catch (Exception $e) {
Sentry\captureException($e);
                    
SwipezLogger::error(__CLASS__, '[A0010]Error while get payment request details payment request id:[' . $payment_req_id . '] Error:' . $e->getMessage());
                    $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
                }
            }
        } else {
            foreach ($hasErrors as $key => $value) {
                $path = ($path_ != NULL) ? $path_ . "." . $key : $key;
                switch ($key) {
                    case 'from_date':
                        $this->createErrorlist('ER02002', $key, $path);
                        break;
                    case 'to_date':
                        $this->createErrorlist('ER02002', $key, $path);
                        break;
                    case 'to_cdate':
                        $this->createErrorlist('ER02009', $key, $path);
                        break;
                }
            }
        }

        $this->printJsonResponse($req_time, $error_code, $result, $this->errorlist);
    }

    function createErrorlist($code, $key_name, $key_path) {
        $message = $this->ApiErrors->fetch($code);
        $this->errorlist[] = array('code' => $code, 'msg' => $message, 'keyname' => $key_name, 'keypath' => $key_path);
    }

}

