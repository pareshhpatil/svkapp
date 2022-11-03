<?php

include_once('../config.php');
require_once LIB.'Controller.php';
require_once LIB.'Generic.php';
require_once CONTROLLER.'Notification.php';
require_once LIB.'Model.php';
require_once LIB.'SessionLegacy.php';
require_once UTIL.'Encrypt.php';
require_once UTIL.'Secretkey.php';
require_once LIB.'View.php';
require_once UTIL.'Helpdesk/HelpdeskMessage.php';
require_once UTIL.'Email/EmailMessage.php';
require_once MODEL. 'CommonModel.php';
require_once LIB.'Validator.php';


class XwayInvoice
{

    public $logger = NULL;
    function __construct()
    {
        $this->db = new DBWrapper();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->save_invoice();
    }

    function save_invoice()
    {
        try {
            require_once CONTROLLER . 'api/Api.php';
            //require_once(MODEL . 'merchant/InvoiceModel.php');
            require_once CONTROLLER . 'api/v3/merchant/Invoice.php';
            
            $getautoRequestList = $this->getAutoInvoiceRequestList();
            
            foreach($getautoRequestList as $request) {
                
                $_POST['data'] = $request['api_request_json'];

                $apiinv = new Invoice();
                $apiinv->webrequest = false;
                $apiinv->save();
                $response = $apiinv->response;
                $response_array = json_decode($response, 1);
                $invoice_id = $response_array['srvrsp'][0]['invoice_id'];
                if ($invoice_id != '') {
                    //update status and payment_request_id in auto_invoice_request_table
                    $this->updateAutoInvoiceRequestStatus($request['id'],$invoice_id,1,null);
                } else {
                    $this->updateAutoInvoiceRequestStatus($request['id'],null,2,$response);
                }
            }
            
            
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[SE102]Error while get sending subscription Error: ' . $e->getMessage());
        }
    }

    public function updateAutoInvoiceRequestStatus($auto_request_table_id,$invoice_id=null,$status=0,$errros=null) {
        try {
            $sql = "update auto_invoice_api_request set `status`= :status, `payment_request_id`= :invoice_id, `errors` =:errors  where id=:auto_request_table_id;";
            $params = array(':auto_request_table_id' => $auto_request_table_id, ':status' => $status, ':invoice_id' => $invoice_id, ':errors' => $errros);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[NO103]Error while update_notificationsent_Status  Error: ' . $e->getMessage());
        }
    }
    
    public function getAutoInvoiceRequestList()
    {
        try {
            $sql = "select id,api_request_json from auto_invoice_api_request where status=0";
            $this->db->exec($sql, array());
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);
            $this->logger->error(__CLASS__, '[E103]Error while get auto invoice api request list Error: ' . $e->getMessage());
        }
    }
}

new XwayInvoice();
