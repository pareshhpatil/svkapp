<?php

use App\Http\Controllers\PaymentWrapperController;
use App\Model\BookingCalendar;

/**
 * Controls transaction listing for merchant
 * 
 */
class Transaction extends Controller
{

    function __construct()
    {
        parent::__construct();

        $this->validateSession('merchant');
        $this->view->selectedMenu = 'transaction';
        $this->errorMessage();
    }

    /**
     * Listing of transaction relevant to the current logged in merchant
     * 
     */
    public function viewlist($page_type = NULL, $bulk_id = null, $export = null)
    {
        try {
            $this->hasRole(1, 9);
            $this->view->selectedMenu = ($page_type == NULL) ? 'transaction' : $page_type;
            $merchant_id = $this->session->get('merchant_id');
            $this->view->merchantType = $this->session->get('merchant_type');
            $last_date = $this->getLast_date();
            $current_date = date('d M Y');

            if (isset($_POST['export'])) {
                $export = 1;
            }

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $cycle_name = $_POST['cycle_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $cycle_name = "";
            }

            $cycle_selected = isset($_POST['cycle_name']) ? $_POST['cycle_name'] : '';
            if ($page_type == 'event') {
                $cycle_list = $this->model->getMerchantEventList($this->session->get('userid'));
                $this->view->datatablejs = 'table-sum-ellipsis-large';
                $this->view->sum_column = 4;
            } else {
                $this->view->datatablejs = 'table-small';
                $cycle_list = $this->model->getMerchantSpecificcycleList($this->session->get('userid'));
            }

            $this->smarty->assign("from_date",  $this->generic->formatDateString($from_date) );
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $this->smarty->assign("cycle_selected", $cycle_selected);
            $this->smarty->assign("cycle_list", $cycle_list);
            $status_selected = isset($_POST['status']) ? $_POST['status'] : '';
            $calender_selected = isset($_POST['calender_id']) ? $_POST['calender_id'] : '';
            $date_range_type = isset($_POST['date_range_type']) ? $_POST['date_range_type'] : 'paid_on';
            $status_list = $this->model->getPaymentTransactionStatus();
            if (empty($status_list)) {
                SwipezLogger::warn(__CLASS__, 'Fetching empty payment transaction status list for merchant [' . $merchant_id . '] ');
            } else {
                $this->smarty->assign("status_selected", $status_selected);
                $this->smarty->assign("status_list", $status_list);
            }
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($bulk_id != null) {
                $bulk_id = $this->encrypt->decode($bulk_id);
            } else {
                $bulk_id = 0;
            }
            $calenders = $this->model->getAllCalendars($this->merchant_id);
            if (count($calenders) == 1) {
                $calender_selected = $calenders[0]["calendar_id"];
            }
            $this->smarty->assign("calenders", $calenders);
            $calender_slot = $this->model->getCalendarSlot($calender_selected);
            $this->smarty->assign("calender_selected", $calender_selected);
            $this->smarty->assign("date_range_type", $date_range_type);
            $this->smarty->assign("calender_slot", $calender_slot);
            $status = ($_POST['status'] != '') ? $_POST['status'] : -1;
            if ($page_type == 'booking') {
                $status = ($_POST['status'] != '') ? $_POST['status'] : '';
                // $this->smarty->assign("booking_type", $_POST['booking_type']);
                // $_POST['booking_type'] = 5;
                // if ($_POST['booking_type'] == 5) {

                $transactionlist = $this->model->getMerchantSlotTransactionsbyCalendarID(
                    $fromdate->format('Y-m-d'),
                    $todate->format('Y-m-d'),
                    $calender_selected,
                    $status,
                    $this->merchant_id,
                    $date_range_type
                );


                $trans_detail = $this->model->getCalendarTransactionDetail($calender_selected);

                $slot_count = [];
                foreach ($trans_detail as &$raw_data) {
                    if (isset($slot_count[$raw_data["slot_title"]][$raw_data["transaction_id"]])) {
                        $slot_count[$raw_data["slot_title"]][$raw_data["transaction_id"]] = $slot_count[$raw_data["slot_title"]][$raw_data["transaction_id"]] + 1;
                        $slot_count[$raw_data["slot_title"] . "_amount"][$raw_data["transaction_id"]] = $slot_count[$raw_data["slot_title"] . "_amount"][$raw_data["transaction_id"]] + $raw_data["slot_price"];
                    } else {
                        $slot_count[$raw_data["slot_title"]][$raw_data["transaction_id"]] = 1;
                        $slot_count[$raw_data["slot_title"] . "_amount"][$raw_data["transaction_id"]] = $raw_data["slot_price"];
                    }
                }

                $int = 0;
                foreach ($transactionlist as &$item) {
                    $transactionlist[$int]['created_at'] = $this->generic->formatTimeString($item['created_date']);
                    $transactionlist[$int]['display_amount'] = $this->generic->moneyFormatIndia($item['absolute_amount']);
                    $transactionlist[$int]['transaction_id'] = $this->encrypt->encode($transactionlist[$int]['pay_transaction_id']);

                    foreach ($calender_slot as $slot_name => $row) {
                        $transactionlist[$int][$row["slot_title"]] = $slot_count[$row["slot_title"]][$item["pay_transaction_id"]];
                        $transactionlist[$int][$row["slot_title"] . "_amount"] = $slot_count[$row["slot_title"] . "_amount"][$item["pay_transaction_id"]];
                        // $transactionlist[$int][$row["slot_title"] . "_Amount"] = $row["slot_price"] * $transactionlist[$int][$row["slot_title"]];
                    }
                    $int++;
                }

                // } else if ($_POST['booking_type'] == 6) {
                //     $transactionlist = $this->model->getMerchantSpecificPaymentTransactionList($fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $cycle_name, $status, $this->merchant_id, 'membership', $this->session->get('sub_franchise_id'), $bulk_id);
                // } else {
                //     $transactionlist1 = $this->model->getMerchantSpecificPaymentTransactionList($fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $cycle_name, $status, $this->merchant_id, $page_type, $this->session->get('sub_franchise_id'), $bulk_id);
                //     $transactionlist2 = $this->model->getMerchantSpecificPaymentTransactionList($fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $cycle_name, $status, $this->merchant_id, 'membership', $this->session->get('sub_franchise_id'), $bulk_id);
                //     $transactionlist = array_merge($transactionlist1, $transactionlist2);
                // }
            } else {
                $transactionlist = $this->model->getMerchantSpecificPaymentTransactionList($fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $cycle_name, $status, $this->merchant_id, $page_type, $this->session->get('sub_franchise_id'), $bulk_id);
                $int = 0;
                foreach ($transactionlist as &$item) {
                    $transactionlist[$int]['created_at'] = $this->generic->formatTimeString($item['date']);
                    $transactionlist[$int]['display_amount'] = number_format($item['absolute_cost']);
                    $int++;
                }
            }

            $this->smarty->assign("transactionlist", $transactionlist);

            if ($export != NULL) {
                foreach ($transactionlist as &$key_data) {
                    $amount = $key_data["absolute_amount"];
                    unset($key_data["absolute_amount"]);
                    unset($key_data["transaction_id"]);
                    unset($key_data["pay_transaction_id"]);
                    unset($key_data["calendar_date"]);
                    unset($key_data["calendar_title"]);
                    unset($key_data["created_date"]);
                    unset($key_data["created_at"]);
                    unset($key_data["display_amount"]);
                    unset($key_data["payment_mode"]);
                    unset($key_data["status_name"]);
                    $key_data["advance"] =  $amount;
                }

                $this->common->excelexport('Transaction List', $transactionlist);
            }
            if (isset($_POST['paymentresponse_id']) && $_POST['paymentresponse_id'] != '') {
                $banklist = $this->common->querylist("select config_key, config_value from config where config_type='Bank_name'");
                $responseDetails = $this->model->getofflinerespond_detail($_POST['paymentresponse_id']);
                $responddate = new DateTime($this->view->responseDetails['settlement_date']);
                $responddate = $responddate->format('d M Y');
                foreach ($banklist as $value) {
                    $bank_ids[] = $value['config_key'];
                    $bank_values[] = $value['config_value'];
                }

                $this->smarty->assign("bank_id", $bank_ids);
                $this->smarty->assign("bank_value", $bank_values);
                $this->smarty->assign("is_update", 1);
                $this->smarty->assign("res_type", $responseDetails['offline_response_type']);
                $this->smarty->assign("res_date", $responddate);
                $this->smarty->assign("res_details", $responseDetails);
            }
            switch ($page_type) {
                case 'bulk':
                    $title = "Bulk upload transactions";
                    break;
                case 'event':
                    $title = "Event transactions";
                    //Breadcumbs array start
                    $breadcumbs_array = array(
                        array('title' => 'Events', 'url' => ''),
                        array('title' => $title, 'url' => '')
                    );
                    $this->smarty->assign("links", $breadcumbs_array);
                    //Breadcumbs array end
                    $this->view->selectedMenu = array(8, 37);
                    break;
                case 'booking':
                    $title = "Booking transactions";
                    //Breadcumbs array start
                    $breadcumbs_array = array(
                        array('title' => 'Booking Calendar', 'url' => ''),
                        array('title' => $title, 'url' => '')
                    );
                    $this->smarty->assign("links", $breadcumbs_array);
                    //Breadcumbs array end
                    $this->view->selectedMenu = array(9, 41);
                    break;
                case 'subscription':
                    $title = "Subscription transactions";
                    break;
                default:
                    $title = "Invoice transactions";
                    //Breadcumbs array start
                    $breadcumbs_array = array(
                        array('title' => 'Transactions', 'url' => ''),
                        array('title' => $title, 'url' => '')
                    );
                    $this->smarty->assign("links", $breadcumbs_array);
                    //Breadcumbs array end
                    $this->view->selectedMenu = array(6, 31);
                    break;
            }
            $this->view->hide_first_col = true;
            $this->smarty->assign("title", $title);
            $this->smarty->assign("type", $page_type);
            $this->view->title = $title;
            $this->view->js = array('transaction');
            $this->view->header_file = ['list'];
            $this->view->render('header/app');

            $pg_vendor_id = $this->common->getRowValue('vendor_id', 'merchant_fee_detail', 'merchant_id', $this->merchant_id, 1);
            $this->smarty->assign("pg_vendor_id", $pg_vendor_id);

            if ($page_type == 'event') {
                $this->smarty->display(VIEW . 'merchant/transaction/event_list.tpl');
            } else if ($page_type == 'booking') {
                $this->smarty->display(VIEW . 'merchant/transaction/booking_list.tpl');
            } else {
                $this->smarty->display(VIEW . 'merchant/transaction/list.tpl');
            }

            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E035]Error while listing merchant transaction Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function generateinvoice($link, $type = null)
    {
        try {
            $invoice_enable = 0;
            $transaction_id = $this->encrypt->decode($link);
            $result = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $transaction_id);
            if ($type == 'plan') {
                $mer_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);
                if ($result['type'] == 3 && $result['plan_id'] > 0 && $mer_setting['plan_invoice_create'] == 1) {
                    $invoice_enable = 1;
                }
            } elseif ($type == 'form') {
                $form_transaction = $this->common->getSingleValue('form_builder_transaction', 'transaction_id', $transaction_id);
                $invoice_enable = $form_transaction['invoice_enable'];
            }
            if ($result['payment_request_id'] == '') {
                if ($invoice_enable == 1) {
                    require_once CONTROLLER . 'InvoiceWrapper.php';
                    $wrapper = new InvoiceWrapper($this->common);
                    $wrapper->bill_date = substr($result['created_date'], 0, 10);
                    $wrapper->paid_date = substr($result['created_date'], 0, 10);
                    if ($type == 'form') {
                        $wrapper->xwayInvoice($form_transaction);
                    } elseif ($type == 'plan') {
                        $details = $wrapper->getPlanInvoiceDetails($result);
                        $wrapper->xwayInvoice($details);
                    }
                    $this->session->set('successMessage', 'Invoice created successfully.');
                }
            } else {
                $this->session->set('successMessage', 'Invoice created successfully.');
            }
            header('Location:/merchant/transaction/xway/' . $type);
            die();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E035+23]Error while create xway invoice ' . $e->getMessage());
        }
    }

    public function xway($xwaytype = null)
    {
        try {
            $this->hasRole(1, 9);
            $this->view->selectedMenu = array(6, 32);
            $merchant_id = $this->session->get('merchant_id');
            $this->view->merchantType = $this->session->get('merchant_type');
            $last_date = $this->getLast_date();
            $current_date = date('d M Y');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $cycle_name = $_POST['cycle_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $cycle_name = "";
            }

            $this->smarty->assign("from_date", $from_date);
            $this->smarty->assign("to_date", $to_date);
            $status_selected = isset($_POST['status']) ? $_POST['status'] : '';
            $status_list = $this->model->getPaymentTransactionStatus();
            if (empty($status_list)) {
                SwipezLogger::warn(__CLASS__, 'Fetching empty payment transaction status list for merchant [' . $merchant_id . '] ');
            } else {
                $this->smarty->assign("status_selected", $status_selected);
                $this->smarty->assign("status_list", $status_list);
            }
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $type = 1;
            $this->view->title = 'Website transactions';
            $this->smarty->assign("title", "Website transactions");

            if ($xwaytype == 'form') {
                $this->view->selectedMenu = 'form_transaction';
                $this->view->selectedMenu = array(6, 33);
                $type = 2;
                $this->view->title = 'Form builder transactions';
                $this->smarty->assign("title", "Form builder transactions");
            }
            if ($xwaytype == 'plan') {
                $this->view->selectedMenu = 'plan_transaction';
                $this->view->selectedMenu = array(6, 34);
                $type = 3;
                $this->view->title = 'Plan based transactions';
                $this->smarty->assign("title", "Plan based transaction");
            }
            if ($xwaytype == 'directpay') {
                $this->view->selectedMenu = array(6, 123);
                $type = 4;
                $this->view->title = 'Payment link transactions';
                $this->smarty->assign("title", "Payment link transactions");
            }
            $status = ($_POST['status'] != '') ? $_POST['status'] : -1;
            $transactionlist = $this->model->getMerchantXwayPaymentTransactionList($fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $status, $merchant_id, $this->session->get('sub_franchise_id'), $type);

            $int = 0;
            foreach ($transactionlist as $item) {
                $transactionlist[$int]['created_at'] = $this->generic->formatTimeString($item['date']);
                $transactionlist[$int]['display_amount'] = $this->generic->moneyFormatIndia($item['amount']);
                $int++;
            }

            $plan_invoice_create = $this->common->getRowValue('plan_invoice_create', 'merchant_setting', 'merchant_id', $this->merchant_id);
            $this->smarty->assign("plan_invoice_create", $plan_invoice_create);
            $this->smarty->assign("has_franchise", $this->session->get('has_franchise'));
            $this->smarty->assign("transactionlist", $transactionlist);
            $pg_vendor_id = $this->common->getRowValue('vendor_id', 'merchant_fee_detail', 'merchant_id', $this->merchant_id, 1);
            $this->smarty->assign("pg_vendor_id", $pg_vendor_id);

            $this->view->hide_first_col = true;
            $this->view->datatablejs = 'table-small';


            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Transactions', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            if ($xwaytype == 'form') {
                $this->smarty->display(VIEW . 'merchant/transaction/xwayformlist.tpl');
            } else if ($xwaytype == 'plan') {
                $this->smarty->display(VIEW . 'merchant/transaction/xwayplanlist.tpl');
            } else {
                $this->smarty->display(VIEW . 'merchant/transaction/xwaylist.tpl');
            }

            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E035+23]Error while listing merchant xway transaction Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    /**
     * Respond as an offline request
     */
    public function updaterespond($page_type = NULL)
    {
        try {
            $this->hasRole(2, 9);
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location:/merchant/transaction/viewlist');
            }
            require CONTROLLER . 'Paymentvalidator.php';

            require_once MODEL . 'merchant/PaymentrequestModel.php';
            $requestModel = new PaymentRequestModel();

            $validator = new Paymentvalidator($this->model);
            $validator->validateRespond();
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $date = new DateTime($_POST['date']);
                if ($_POST['cheque_status'] == 'Bounced') {
                    $this->model->deletetransaction($_POST['offline_response_id']);
                }
                if ($page_type == 'staging') {
                    $bulk_id = $this->common->getRowValue('bulk_id', 'staging_offline_response', 'id', $_POST['offline_response_id']);
                    $result = $requestModel->respondStagingUpdate($_POST['amount'], $_POST['bank_name'], $_POST['offline_response_id'], $date->format('Y-m-d'), $_POST['response_type'], $_POST['bank_transaction_no'], $_POST['cheque_no'], $_POST['cash_paid_to'], $this->session->get('userid'));
                    $this->session->set('successMessage', 'Offline transaction updated successfully.');
                    header('Location: /merchant/transaction/bulklist/' . $this->encrypt->encode($bulk_id));
                    die();
                }
                $result = $requestModel->respondUpdate($_POST['amount'], $_POST['bank_name'], $_POST['offline_response_id'], $date->format('Y-m-d'), $_POST['response_type'], $_POST['bank_transaction_no'], $_POST['cheque_no'], $_POST['cheque_status'], $_POST['cash_paid_to'], $this->session->get('userid'), $_POST['cod_status']);
                $payment_request_id = $this->common->getRowValue('payment_request_id', 'offline_response', 'offline_response_id', $_POST['offline_response_id']);
                $this->common->queryexecute("call set_partialypaid_amount('" . $payment_request_id . "');");
                $this->smarty->assign("success", 'Offline transaction update successfully.');
                $this->viewlist($page_type);
            } else {
                $this->view->setError($hasErrors);
                $this->viewlist();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E036]Error while updating respond Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function sendreceipt($type, $link)
    {
        $payment_transaction_id = $this->encrypt->decode($link);
        require_once CONTROLLER . 'Notification.php';
        $download = 0;
        if (substr($type, 0, 8) == 'download') {
            $download = 1;
            $type = substr($type, 8);
        }
        $noti = new Notification();
        $noti->sendMailReceipt($payment_transaction_id, 0, null, $download);
        $this->session->set('successMessage', 'Transaction Receipt has been sent.');
        switch ($type) {
            case 'plan':
                $page = 'xway/plan';
                break;
            case 'form':
                $page = 'xway/form';
                break;
            case 'xway':
                $page = 'xway';
                break;
            case 'list':
                $page = 'viewlist';
                break;
            default:
                $page = 'viewlist/' . $type;
                break;
        }
        header('Location: /merchant/transaction/' . $page);
        exit();
    }

    function bulklist($bulk_id = NULL)
    {
        try {
            $bulk_id = $this->encrypt->decode($bulk_id);
            $list = $this->common->getListValue('staging_offline_response', 'bulk_id', $bulk_id, 1);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['encrypted_id'] = $this->encrypt->encode($item['id']);
                $list[$int]['encrypted_req_id'] = $this->encrypt->encode($item['payment_request_id']);
                $int++;
            }

            if (isset($_POST['paymentresponse_id']) && $_POST['paymentresponse_id'] != '') {
                $banklist = $this->common->querylist("select config_key, config_value from config where config_type='Bank_name'");
                $responseDetails = $this->common->getSingleValue('staging_offline_response', 'id', $_POST['paymentresponse_id']);
                $responddate = new DateTime($responseDetails['settlement_date']);
                $responddate = $responddate->format('d M Y');

                foreach ($banklist as $value) {
                    $bank_ids[] = $value['config_key'];
                    $bank_values[] = $value['config_value'];
                }

                $this->smarty->assign("bank_id", $bank_ids);
                $this->smarty->assign("bank_value", $bank_values);
                $this->smarty->assign("is_update", 1);
                $this->smarty->assign("res_type", $responseDetails['offline_response_type']);
                $this->smarty->assign("res_date", $responddate);
                $this->smarty->assign("res_details", $responseDetails);
            }


            $this->view->selectedMenu = 'bulk_transaction';
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Bulk transaction list");
            $this->view->js = array('transaction');
            $this->view->title = "Transaction list";
            $this->view->canonical = 'merchant/vendor/viewlist';
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/transaction/bulklist.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    /**
     * Display Invoice receipt of succes payment
     */
    public function receipt($link)
    {
        try {
            $this->hasRole(1, 9);
            $merchant_id = $this->session->get('merchant_id');
            $payment_transaction_id = $this->encrypt->decode($link);
            $type = substr($payment_transaction_id, 0, 1);
            switch ($type) {
                case 'T':
                    $type = 'Online';
                    break;
                case 'H':
                    $type = 'Offline';
                    break;
                default:
                    $type = 'Xway';
                    break;
            }
            $receipt_info = $this->common->getReceipt($payment_transaction_id, $type);

            if (!empty($receipt_info)) {

                //get payment receipt feilds if plugin is on 
                //get template details for showing custom configure fields
                $template_info = $this->common->getSingleValue('payment_request', 'payment_request_id', $receipt_info['payment_request_id']);
                $receipt_info['has_customized_payment_receipt'] = 0;

                if (isset($template_info['plugin_value']) && !empty($template_info['plugin_value'])) {
                    //check if custom configure receipt fields plugin is on or not
                    $plugin_value = json_decode($template_info['plugin_value'], 1);

                    if (isset($plugin_value['has_customized_payment_receipt']) && $plugin_value['has_customized_payment_receipt'] == 1) {
                        $receipt_info['has_customized_payment_receipt'] = 1;
                        if (!empty($plugin_value['receipt_fields'])) {
                            $receipt_info['custom_fields'] = $this->common->setReceiptFields($receipt_info, $plugin_value['receipt_fields'], $type);
                        }
                    }
                }

                $logo = '';
                if ($receipt_info['image'] == '') {
                    if ($receipt_info['merchant_logo'] != '') {
                        $logo = '/uploads/images/landing/' . $receipt_info['merchant_logo'];
                    }
                } else {
                    $logo = '/uploads/images/logos/' . $receipt_info['image'];
                }
                $this->smarty->assign("logo", $logo);

                $this->view->paymentdetail = $receipt_info;
                $this->view->render('header/guest');
                $this->smarty->assign("response", $receipt_info);
                if ($receipt_info['payment_request_type'] == 2) {
                    $event_details = $this->common->getSingleValue('event_request', 'event_request_id', $receipt_info['payment_request_id']);
                    $attendee_details = $this->common->getAttendeeDetails($payment_transaction_id);
                    $customer_details = $this->common->getCustomerValueDetail($receipt_info['customer_id']);
                    $this->smarty->assign("payee_capture", json_decode($event_details['payee_capture'], 1));
                    $this->smarty->assign("attendees_capture", json_decode($event_details['attendees_capture'], 1));
                    $this->smarty->assign("customer_details", $customer_details);
                    foreach ($attendee_details as $det) {
                        if ($det['coupon_code'] != '') {
                            $coupon_id = $det['coupon_code'];
                        }
                    }
                    $coupon_details = $this->common->getSingleValue('coupon', 'coupon_id', $coupon_id);
                    $this->smarty->assign("coupon_code", $coupon_details['coupon_code']);
                    $this->smarty->assign("attendee_details", $attendee_details);
                } else if ($receipt_info['payment_request_type'] == 5) {
                    $booking_details = $this->common->getBookingDetails($payment_transaction_id);
                    $this->smarty->assign("booking_details", $booking_details);
                }

                $this->view->render('header/guest');
                if ($type == 'Online') {
                    $this->smarty->display(VIEW . 'merchant/transaction/receipt.tpl');
                } else {
                    $this->smarty->display(VIEW . 'merchant/transaction/offlinereceipt.tpl');
                }
                $this->view->render('footer/nonfooter');
            } else {
                SwipezLogger::error(__CLASS__, '[E221]Error for merchant [' . $merchant_id . '] and while getting payment receipt link: ' . $link);
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E220]Error while getting payment receipt Error: for merchant [' . $merchant_id . '] and for payment transaction id [' . $payment_transaction_id . ']' . $e->getMessage());
        }
    }

    /**
     * Display invoice 
     */
    public function invoice($link)
    {
        try {
            $this->hasRole(1, 9);
            $merchant_id = $this->session->get('merchant_id');
            $user_id = $this->session->get('userid');
            $payment_request_id = $this->encrypt->decode($link);
            $info = $this->common->getPaymentRequestDetails($payment_request_id, 'customer');
            $is_statement = ($info['statement_enable'] == 1) ? 1 : 0;
            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E008]Error while geting invoice details. for merchant [' . $merchant_id . '] and payment request id ' . $payment_request_id);
                $this->setGenericError();
            }
            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $smarty = $invoice->asignSmarty($info, array(), $payment_request_id);
            $smarty['error'] = NULL;
            foreach ($smarty as $key => $value) {
                $this->smarty->assign($key, $value);
            }
            $this->view->canonical = 'merchant/transaction/invoice';
            $this->view->render('nonlogoheader');
            $template_type = ($info['template_type'] != 'isp') ? 'detail' : 'isp';
            $template_type = ($info['template_type'] == 'franchise') ? 'franchise' : $template_type;
            $template_type = ($info['template_type'] == 'travel') ? 'travel' : $template_type;
            $this->smarty->display(VIEW . 'merchant/paymentrequest/invoice_' . $template_type . '.tpl');
            $this->smarty->display(VIEW . 'merchant/paymentrequest/invoice_nopay_footer.tpl');
            $this->view->render('footer/nonfooter');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E009]Error while payment request view initiate Error: for merchant [' . $merchant_id . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function refund($link)
    {
        try {
            $this->hasRole(1, 9);
            $merchant_id = $this->session->get('merchant_id');
            $user_id = $this->session->get('userid');
            $transaction_id = $this->encrypt->decode($link);
            if (substr($transaction_id, 0, 1) == 'T') {
                $info = $this->common->getSingleValue('payment_transaction', 'pay_transaction_id', $transaction_id, 0, " and merchant_id='" . $this->merchant_id . "'");
                $info['status'] = $info['payment_transaction_status'];
            } else {
                $info = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $transaction_id, 0, " and merchant_id='" . $this->merchant_id . "'");
                $info['status'] = $info['xway_transaction_status'];
            }
            $info['transaction_id'] = $transaction_id;
            $this->smarty->assign("info", $info);
            $this->view->canonical = 'merchant/transaction/refund';
            $this->view->render('nonlogoheader');
            $this->smarty->display(VIEW . 'merchant/transaction/refund.tpl');
            $this->view->render('footer/nonfooter');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E009]Error while payment request view initiate Error: for merchant [' . $merchant_id . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saverefund()
    {
        $this->hasRole(1, 9);
        $transaction_id = $_POST['transaction_id'];
        $exist = $this->common->getRowValue('id', 'refund_request', 'transaction_id', $transaction_id);
        if ($exist > 0) {
        } else {
            $payment = new PaymentWrapperControllerr();
            if (substr($transaction_id, 0, 1) == 'T') {
                $transaction_type = 'T';
                $info = $this->common->getSingleValue('payment_transaction', 'pay_transaction_id', $transaction_id, 0, " and merchant_id='" . $this->merchant_id . "'");
                $info['status'] = $info['payment_transaction_status'];
            } else {
                $transaction_type = 'X';
                $info = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $transaction_id, 0, " and merchant_id='" . $this->merchant_id . "'");
                $info['status'] = $info['xway_transaction_status'];
                $info['pg_ref_no'] = $info['pg_ref_no1'];
            }
            $pg_detail = $this->common->getSingleValue('payment_gateway', 'pg_id', $info['pg_id']);
            $pg_type = $pg_detail['pg_type'];
            if ($pg_type == 7 || $pg_type == 4) {
                $settlement_type = 1;
            } else {
                $settlement_type = 2;
            }
            $refund_id = $this->model->refundSave($transaction_id, $this->merchant_id, $info['amount'], $_POST['amount'], $info['pg_id'], $settlement_type, $_POST['reason'], $this->user_id);
            if ($refund_id > 0) {
                switch ($pg_type) {
                    case 3:
                        $result = $payment->payuRefund($pg_detail['pg_val7'], $info['pg_ref_no'], $_POST['amount']);
                        $reference_id = $result['refund_id'];
                        break;
                    case 4:

                        break;
                    case 7:
                        $result = $payment->cashfreeRefund($pg_detail['pg_val1'], $pg_detail['pg_val2'], $transaction_id, $info['pg_ref_no'], $_POST['amount'], $_POST['reason']);
                        $reference_id = $result['refund_id'];
                        break;
                    case 2:
                        $result = $payment->paytmRefund($pg_detail['pg_val1'], $pg_detail['pg_val2'], $transaction_id, $refund_id, $_POST['amount'], $info['pg_ref_no']);
                        $reference_id = $result['refund_id'];
                        break;
                    case 13:
                        $result = $payment->setuRefund($pg_detail, $transaction_id, $refund_id, $_POST['amount'], $info['pg_ref_no']);
                        $reference_id = $result['refund_id'];
                        break;
                }
                if ($reference_id != FALSE) {
                    $this->common->genericupdate('refund_request', 'refund_id', $reference_id, 'id', $refund_id);
                    if ($transaction_type == 'T') {
                    } else {
                        $this->common->genericupdate('refund_request', 'refund_id', $reference_id, 'id', $refund_id);
                    }
                }
            }
        }

        $this->view->render('nonlogoheader');
        $this->smarty->display(VIEW . 'merchant/transaction/refund.tpl');
        $this->view->render('footer/nonfooter');
    }

    /**
     * Delete transaction
     */
    function delete($link, $type = null)
    {
        try {
            $this->hasRole(3, 9);
            $transaction_id = $this->encrypt->decode($link);
            if ($type == 'staging') {
                $bulk_id = $this->common->getRowValue('bulk_id', 'staging_offline_response', 'id', $transaction_id);
                $this->common->genericupdate('staging_offline_response', 'is_active', 0, 'id', $transaction_id, $this->user_id);
                header('Location:/merchant/transaction/bulklist/' . $this->encrypt->encode($bulk_id));
                die();
            }
            $result = $this->model->deletetransaction($transaction_id);
            if ($result != '') {
                switch ($result) {
                    case '3':
                        $page_type = '/bulk';
                        break;
                    case '2':
                        $page_type = '/event';
                        break;
                    case '4':
                        $page_type = '/subscription';
                        break;
                    case '5':
                        $page_type = '/booking';
                        break;
                    default:
                        $page_type = "";
                        break;
                }
                $this->session->set('successMessage', 'Offline transaction deleted successfully.');
                $payment_request_id = $this->common->getRowValue('payment_request_id', 'offline_response', 'offline_response_id', $transaction_id);
                $this->common->queryexecute("call set_partialypaid_amount('" . $payment_request_id . "');");
                header('Location:/merchant/transaction/viewlist' . $page_type);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E033]Error while deleting template Error: for merchant [' . $merchant_id . '] and for template [' . $template_id . ']' . $e->getMessage());
        }
    }

    function used($link, $type = 'event')
    {
        $status = 0;
        $this->availedstatus($link, $status, $type);
    }

    function availed($link, $type = 'event')
    {
        $status = 1;
        $this->availedstatus($link, $status, $type);
    }

    function availedstatus($link, $status, $type)
    {
        try {
            $transaction_id = $this->encrypt->decode($link);
            $this->model->changeAvailedStatus($transaction_id, $status);

            $this->session->set('successMessage', 'Transaction status changed successfully.');

            header('Location:/merchant/transaction/viewlist/' . $type);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E033]Error while deleting template Error: for merchant [' . $merchant_id . '] and for template [' . $template_id . ']' . $e->getMessage());
        }
    }

    /**
     * Bulk upload offline payment
     */ function bulkupload()
    {
        try {
            require_once MODEL . 'merchant/VendorModel.php';
            $vendor = new VendorModel();

            $list = $vendor->getBulkuploadList($this->merchant_id, 5);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['bulk_id'] = $this->encrypt->encode($item['bulk_upload_id']);
                $list[$int]['created_date'] = $this->generic->formatTimeString($item['created_date']);
                $int++;
            }
            $this->view->hide_first_col = true;
            $this->smarty->assign("bulklist", $list);
            $this->view->datatablejs = 'table-small-no-export';
            $this->view->selectedMenu = array(5, 30);
            $this->view->title = 'Bulk upload transactions';
            $this->smarty->assign('title', $this->view->title);

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Sales', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['bulkupload'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/transaction/settlementupload.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC001]Error while vendor bulkupload Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    function download()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $link = $this->encrypt->encode($merchant_id);
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("swipez")
                ->setLastModifiedBy("swipez")
                ->setTitle("swipez offline payment upload")
                ->setSubject($link)
                ->setDescription("swipez offline payment upload");
            #create array of excel column
            $first = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
            $column = array();
            foreach ($first as $s) {
                $column[] = $s . '1';
            }
            foreach ($first as $f) {
                foreach ($first as $s) {
                    $column[] = $f . $s . '1';
                }
            }

            $int = 0;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Bill Date');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Customer Code');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Customer Name');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Invoice Amount');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Invoice ID');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Paid date');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Paid Amount');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Mode (Cash/Cheque/NEFT/Online payment)');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Bank name');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Bank ref no');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Cheque no');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Cash paid to');
            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Notify (Yes/No)');
            $int++;
            $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')
                ->setSize(10);
            $objPHPExcel->getActiveSheet()->setTitle('Vendor structure');
            $objPHPExcel->getActiveSheet()->getStyle('A1:' . $column[$int - 1])->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'AAAADD')
                    )
                )
            );
            $int++;
            $values = $this->model->getPendingSettlementInvoice($this->merchant_id);
            $rint = 2;
            foreach ($values as $val) {
                $vint = 0;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $rint, $val['bill_date']);
                $vint++;
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $rint, $val['customer_code']);
                $vint++;
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rint, $val['first_name'] . ' ' . $val['last_name']);
                $vint++;
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $rint, $val['grand_total']);
                $vint++;
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rint, $val['payment_request_id']);
                $rint++;
            }


            $autosize = 0;
            while ($autosize < $int) {
                $objPHPExcel->getActiveSheet()->getColumnDimension(substr($column[$autosize], 0, -1))->setAutoSize(true);
                $autosize++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="offline_payment_structure' . time() . '.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);
            exit;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E235]Error while export excel Error: for merchant [' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function upload()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (isset($_FILES["fileupload"])) {
                require_once CONTROLLER . 'merchant/Uploadvalidator.php';
                $validator = new Uploadvalidator($this->model);
                $validator->validateExcelUpload();
                $hasErrors = $validator->fetchErrors();
                if ($hasErrors == false) {
                    if (isset($_POST['bulk_id'])) {
                        require_once(MODEL . 'merchant/BulkuploadModel.php');
                        $bulkupload = new BulkuploadModel();
                        $merchant_id = $this->session->get('merchant_id');
                        $bulk_id = $this->encrypt->decode($_POST['bulk_id']);
                        $detail = $bulkupload->getBulkuploaddetail($merchant_id, $bulk_id);
                        $bulkupload->updateBulkUploadStatus($bulk_id, 7);
                        $folder = ($detail['status'] == 2 || $detail['status'] == 3) ? 'staging' : 'error';
                        $bulkupload->moveExcelFile($merchant_id, $folder, $detail['system_filename']);
                    }
                    $this->transactionbulk_upload($_FILES["fileupload"]);
                } else {
                    $this->smarty->assign("hasErrors", $hasErrors);
                    $this->bulkupload();
                }
            } else {
                header('Location: /merchant/vendor/bulkupload/' . $_POST['type']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E270]Error while bulk upload submit Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function transactionbulk_upload($inputFile, $bulk_upload_id = NULL)
    {
        try {
            if ($bulk_upload_id == NULL) {
                $File = $inputFile['tmp_name'];
            } else {
                $File = $inputFile;
            }
            $merchant_id = $this->merchant_id;
            $inputFileType = PHPExcel_IOFactory::identify($File);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($File);
            $subject = $objPHPExcel->getProperties()->getSubject();
            $link = $this->encrypt->decode($subject);
            $merchant_id_ = substr($link, 0, 10);
            $worksheet = $objPHPExcel->getSheet(0);
            //$worksheetTitle = $worksheet->getTitle();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;
            $errors = array();
            $is_upload = TRUE;

            require_once(MODEL . 'merchant/BulkuploadModel.php');
            $bulkupload = new BulkuploadModel();

            if ($merchant_id_ == $merchant_id) {
            } else {
                $errors[0][0] = 'Invalid excel sheet';
                $errors[0][1] = 'Download again excel Vendor bulkupload and re-up load with transaction data.';
                $is_upload = FALSE;
            }



            $getcolumnvalue = array();
            if (empty($errors)) {
                for ($rowno = 2; $rowno <= $highestRow; ++$rowno) {
                    $cell = $worksheet->getCellByColumnAndRow(0, $rowno);
                    $val = $cell->getFormattedValue();
                    if ((string) $val != '') {
                        for ($col = 0; $col < $highestColumnIndex; ++$col) {
                            $cell = $worksheet->getCellByColumnAndRow($col, $rowno);
                            $val = $cell->getFormattedValue();
                            $getcolumnvalue[$rowno][] = $val;
                            $getrawvalue[$rowno][] = $cell->getValue();
                        }
                        $post_row = array();
                        $int = 4;

                        $post_row['invoice_id'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        try {
                            if (is_numeric($getrawvalue[$rowno][$int])) {
                                $value = PHPExcel_Style_NumberFormat::toFormattedString($getrawvalue[$rowno][$int], 'Y-m-d');
                                $date = new DateTime($value);
                                $value = $date->format('Y-m-d');
                            } else {
                                $value = 'NA';
                            }
                        } catch (Exception $e) {
                            $value = 'NA';
                        }
                        $post_row['date'] = $value;
                        $int++;
                        $post_row['amount'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $payment_mode = (string) $getcolumnvalue[$rowno][$int];
                        $payment_mode = trim($payment_mode);
                        switch (strtolower($payment_mode)) {
                            case 'neft':
                                $mode = 1;
                                break;
                            case 'cheque':
                                $mode = 2;
                                break;
                            case 'cash':
                                $mode = 3;
                                break;
                            case 'online payment':
                                $mode = 5;
                                break;
                            default:
                                $mode = 50;
                                break;
                        }
                        $post_row['mode'] = $mode;
                        $int++;
                        $post_row['bank_name'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['bank_transaction_no'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['cheque_no'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['cash_paid_to'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['notify'] = ucfirst((string) $getcolumnvalue[$rowno][$int]);
                        $int++;
                        $_POSTarray[] = $post_row;
                    }
                }
            }
            $rows_count = count($_POSTarray);
            $errorrow = 2;
            try {
                if (empty($_POSTarray) && empty($errors)) {
                    $is_upload = FALSE;
                    $errors[0][0] = 'Empty excel';
                    $errors[0][1] = 'Uploaded excel does not contain any transaction.';
                    SwipezLogger::error(__CLASS__, '[E286]Error while validating excel Error: empty excel');
                }
                if (empty($errors)) {
                    foreach ($_POSTarray as $_POST) {
                        $result = $this->validateuploadpayment();
                        if (!empty($result)) {
                            $result['row'] = $errorrow;
                            $errors[] = $result;
                        } else {
                        }
                        $errorrow++;
                    }
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                SwipezLogger::error(__CLASS__, '[E275]Error while validating excel Error: for merchant [' . $merchant_id . '] and ' . $e->getMessage());
            }


            if (empty($errors) && $bulk_upload_id == NULL) {

                $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), FALSE, $rows_count, 5);

                $this->session->set('successMessage', '  Excel sheet uploaded.');
                header('Location: /merchant/transaction/bulkupload');
                die();
            } else {
                $user_id = $this->user_id;
                if (strlen($this->system_user_id) > 9) {
                    $user_id = $this->system_user_id;
                }
                if ($is_upload == TRUE && $bulk_upload_id == NULL) {
                    $bulk_id = $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), TRUE, 0, 5);
                    $this->common->genericupdate('bulk_upload', 'error_json', json_encode($errors), 'bulk_upload_id', $bulk_id, $user_id);
                    $bulk_id = $this->encrypt->encode($bulk_id);
                }
                if ($bulk_upload_id != NULL) {
                    $this->smarty->assign("bulk_id", $bulk_upload_id);
                    $this->smarty->assign("errors", $errors);
                    $this->view->render('nonlogoheader');
                    $this->smarty->display(VIEW . 'merchant/bulkupload/allerrors.tpl');
                    $this->view->render('nonfooter');
                } else {
                    $this->smarty->assign("haserrors", $errors);
                    if ($bulk_id != '') {
                        $this->reupload('vendor', $bulk_id);
                    } else {
                        $this->bulkupload('vendor');
                    }
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E271]Error while uploading excel Error: for merchant [' . $merchant_id . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function validateuploadpayment()
    {
        try {
            $request = $this->common->getSingleValue('payment_request', 'payment_request_id', $_POST['invoice_id'], 0, " and merchant_id='" . $this->merchant_id . "'");
            if (empty($request)) {
                $hasErrors[0][0] = 'Invoice ID';
                $hasErrors[0][1] = 'Invalid invoice id';
                return $hasErrors;
            } else {
                if ($request['payment_request_status'] == 1 || $request['payment_request_status'] == 2) {
                    $hasErrors[0][0] = 'Invoice ID';
                    $hasErrors[0][1] = 'Invoice has been paid already';
                    return $hasErrors;
                }
            }
            require_once CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($this->model);
            $validator->validateRespond();
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
            } else {
                return $hasErrors;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E246]Error while sending payment request Error:' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function reupload($bulk_id = '')
    {
        try {
            if ($bulk_id != '') {
                $this->smarty->assign("bulk_id", $bulk_id);
                $this->view->title = 'Re-upload vendor';
                $this->view->selectedMenu = 'bulk_transaction';
                $this->view->canonical = 'merchant/bulkupload/error/';
                $this->view->header_file = ['bulkupload'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/transaction/reupload.tpl');
                $this->view->render('footer/bulkupload');
            } else {
                header('Location: /merchant/transaction/bulkupload');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E269]Error while re-upload Error: bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function reconcile($link)
    {
        try {
            $transaction_id = $this->encrypt->decode($link);
            $det = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $this->merchant_id);
            if (empty($det)) {
                require_once MODEL . 'merchant/ProfileModel.php';
                $profile = new ProfileModel();
                $key_id = $profile->saveKeyDetail($this->merchant_id, $this->user_id);
                $det = $this->common->getSingleValue('merchant_security_key', 'key_id', $key_id);
            }
            $array['access_key_id'] = $det['access_key_id'];
            $array['secret_access_key'] = $det['secret_access_key'];
            $array['transaction_id'] = $transaction_id;
            $_POST['data'] = json_encode($array);
            require_once CONTROLLER . 'api/Api.php';
            require_once CONTROLLER . 'api/v1/merchant/Payment.php';
            $apiinv = new Payment(true);
            $apiinv->webrequest = false;
            $apiinv->reconcile();
            $response = $apiinv->response;
            $array = json_decode($response, 1);
            $this->session->set('successMessage', '  Transaction has been reconciled');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E269]Error while reconcile Error: transaction id [' . $transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    private function errorMessage()
    {
        $errorMessage = $this->session->get('errorMessage');
        if ($errorMessage != false) {
            $this->smarty->assign("error", ' ' . $errorMessage);
            $this->session->remove('errorMessage');
        }
    }

    public function refundtransaction()
    {
        try {
            $det = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $this->merchant_id);
            if (empty($det)) {
                require_once MODEL . 'merchant/ProfileModel.php';
                $profile = new ProfileModel();
                $key_id = $profile->saveKeyDetail($this->merchant_id, $this->user_id);
                $det = $this->common->getSingleValue('merchant_security_key', 'key_id', $key_id);
            }
            if (isset($_POST['type'])) {
                if ($_POST['type'] == 'booking_calendar') {
                    $bookingCalendarModel = new BookingCalendar();
                    $slot_details = $bookingCalendarModel->getSlotDetails($this->encrypt->decode($_POST['transaction_id']));
                    foreach ($slot_details as $cancel_data) {
                        $bookingCalendarModel->updateSlotTransactionDetails($payment_request_id, $cancel_data->qty, $cancel_data->slot_id);
                        $bookingCalendarModel->updateSlotDetails($cancel_data->qty, $cancel_data->slot_id);
                    }
                }
            }
            $array['access_key_id'] = $det['access_key_id'];
            $array['secret_access_key'] = $det['secret_access_key'];
            $array['transaction_id'] = $this->encrypt->decode($_POST['transaction_id']);
            $array['amount'] = $_POST['amount'];
            $array['reason'] = $_POST['reason'];
            $_POST['data'] = json_encode($array);
            require_once CONTROLLER . 'api/Api.php';
            require_once CONTROLLER . 'api/v1/merchant/Payment.php';
            $apiinv = new Payment(true);
            $apiinv->webrequest = false;
            $apiinv->refund();
            $response = $apiinv->response;
            $array = json_decode($response, 1);
            if ($array['errmsg'] == '') {
                $this->session->set('successMessage', '  Refund has been initiated. It would take 7-10 working days for credit into the bank account ');
            } else {
                $this->session->set('errorMessage', '  Refund failed. Error: ' . $array['srvrsp']);
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E269]Error while reconcile Error: transaction id [' . $transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
