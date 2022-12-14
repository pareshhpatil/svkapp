<?php

/**
 * Controls transaction listing for patrons
 * 
 */
class Transaction extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->view->selectedMenu = array(116);
        $this->view->disable_talk = 1;
    }

    /**
     * Listing of transaction relevant to the current logged in patron
     * 
     */
    public function viewlist()
    {
        try {
            $this->validateSession('patron');
            $user_id = $this->session->get('userid');
            $this->view->merchantType = $this->session->get('merchant_type');
            $last_date = $this->getLast_date();
            $current_date = date('d M Y');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $merchant_name = $_POST['merchant_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $merchant_name = "";
            }


            $merchantselected = isset($_POST['merchant_name']) ? $_POST['merchant_name'] : '';
            $merchantnamelist = $this->model->getPatronSpecificMerchantList($this->session->get('email_id'));

            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date) );
            $this->smarty->assign("to_date",  $this->generic->formatDateString($from_date) );
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $this->smarty->assign("merchantselected", $merchantselected);
            $this->smarty->assign("merchant_list", $merchantnamelist);

            $status_selected = isset($_POST['status']) ? $_POST['status'] : '';
            $status_list = $this->model->getPaymentTransactionStatus();
            if (empty($status_list)) {
                SwipezLogger::warn(__CLASS__, 'Fetching empty payment transaction status list for merchant [' . $user_id . '] ');
            } else {
                $this->smarty->assign("status_selected", $status_selected);
                $this->smarty->assign("status_list", $status_list);
            }

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);

            $status = ($_POST['status'] != '') ? $_POST['status'] : -1;
            $transactionlist = $this->model->getPatronSpecificPaymentTransactionList($fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $merchant_name, $status, $this->session->get('email_id'));

            $this->smarty->assign("transactionlist", $transactionlist);

            $this->view->title = 'View Transactions';
            $this->smarty->assign('title', $this->view->title);

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'patron/transaction/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E058]Error while listing patron transaction Error: for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Display payment receipt
     */
    public function receipt($link = null, $success = null)
    {
        try {
            if ($link == null) {
                $this->setInvalidLinkError();
            }
            $payment_transaction_id = $this->encrypt->decode($link);
            if (strlen($payment_transaction_id) != 10) {
                $payment_transaction_id = $this->common->getRowValue('value', 'encrypt_backup', 'encrypt_key', $link, 0);
                if ($payment_transaction_id == FALSE) {
                    $this->setInvalidLinkError();
                }
            }
            if ($this->session->get('logged_in') != TRUE) {
                $this->smarty->assign("isGuest", '1');
            }
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

                    if(isset($plugin_value['has_customized_payment_receipt']) && $plugin_value['has_customized_payment_receipt'] == 1) {
                        $receipt_info['has_customized_payment_receipt'] = 1;
                        if(!empty($plugin_value['receipt_fields'])) {
                            $receipt_info['custom_fields'] = $this->common->setReceiptFields($receipt_info,$plugin_value['receipt_fields'],$type);
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
                $this->view->title = 'Receipt';
                $this->view->render('header/guest');
                $this->smarty->assign("response", $receipt_info);
                if ($receipt_info['customer_default_column'] != null) {
                    $this->smarty->assign("customer_default_column", json_decode($receipt_info['customer_default_column'], 1));
                }
                if ($receipt_info['payment_request_type'] == 2) {
                    $event_details = $this->common->getSingleValue('event_request', 'event_request_id', $receipt_info['payment_request_id']);
                    $attendee_details = $this->common->getAttendeeDetails($payment_transaction_id);
                    $customer_details = $this->common->getCustomerValueDetail($receipt_info['customer_id']);
                    $this->smarty->assign("payee_capture", json_decode($event_details['payee_capture'], 1));
                    $this->smarty->assign("attendees_capture", json_decode($event_details['attendees_capture'], 1));
                    $this->smarty->assign("customer_details", $customer_details);
                    //$custom_capture_detail = $this->common->getEventCaptureDetails($payment_transaction_id);
                    //$this->smarty->assign("c_c_detail", $custom_capture_detail);
                    //$this->dd($attendee_details);
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
                    if ($success == null) {
                        $this->smarty->display(VIEW . 'merchant/transaction/receipt.tpl');
                    } else {
						if($success == 'processing'){
							$this->smarty->display(VIEW . 'patron/paymentrequest/processing.tpl');
						}else{
							$this->smarty->display(VIEW . 'patron/paymentrequest/success.tpl');
						}
                    }
                } else {
                    $this->smarty->display(VIEW . 'merchant/transaction/offlinereceipt.tpl');
                }
                $this->view->render('footer/nonfooter');
            } else {
                SwipezLogger::error(__CLASS__, '[E222]Error while getting payment receipt link: for payment transaction id [' . $payment_transaction_id . ']' . $link);
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E220]Error while getting payment receipt for payment transaction id [' . $payment_transaction_id . ']' . $e->getMessage());
        }
    }

    /**
     * Display invoice 
     */
    public function invoice($link)
    {
        try {
            $this->validateSession('patron');
            $user_id = $this->session->get('userid');
            $payment_request_id = $this->encrypt->decode($link);
            $rows = $this->common->getInvoiceBreakup($payment_request_id);
            $info = $this->common->getPaymentRequestDetails($payment_request_id, 'customer');
            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E008]Error while geting invoice details. for user id [' . $user_id . '] and for payment request id ' . $particular['payment_request_id']);
                $this->setGenericError();
            }
            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $smarty = $invoice->asignSmarty($info, array(), $payment_request_id);
            $smarty['error'] = NULL;
            foreach ($smarty as $key => $value) {
                $this->smarty->assign($key, $value);
            }

            $this->smarty->assign("Url", $link);
            $this->smarty->assign("info", $info);
            $this->view->canonical = 'patron/transaction/invoice';
            $this->view->render('nonlogoheader');
            $template_type = ($info['template_type'] != '') ? $info['template_type'] : 'society';

            if ($template_type == 'simple') {
                $this->smarty->display(VIEW . 'merchant/paymentrequest/invoice_simple_header.tpl');
            } elseif ($template_type == 'isp') {
                $this->smarty->display(VIEW . 'merchant/paymentrequest/invoice_isp.tpl');
            } else {
                $this->smarty->display(VIEW . 'merchant/paymentrequest/invoice_header.tpl');
                $this->smarty->display(VIEW . 'merchant/paymentrequest/particular_' . $template_type . '.tpl');
                $this->smarty->display(VIEW . 'merchant/paymentrequest/invoice_tax.tpl');
            }
            $this->smarty->display(VIEW . 'merchant/paymentrequest/invoice_nopay_footer.tpl');
            $this->view->render('footer/nonfooter');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E009]Error while payment request view initiate Error:for user id [' . $user_id . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function downloadreceipt($link)
    {
        $payment_transaction_id = $this->encrypt->decode($link);
        $type = substr($payment_transaction_id, 0, 1);
        switch ($type) {
            case 'T':
                $type = 'Online';
                $rec_page = 'receipt';
                break;
            case 'H':
                $type = 'Offline';
                $rec_page = 'offline_receipt';
                break;
            default:
                $type = 'Xway';
                $rec_page = 'receipt';
                break;
        }
        $receipt_info = $this->common->getReceipt($payment_transaction_id, $type);
        if ($receipt_info['payment_request_type'] == 2) {
            $pg_type = 'event';
        } else {
            $pg_type = 'request';
        }

        $coupon_id = 0;
        $patron_email = $receipt_info['patron_email'];
        $patron_name = $receipt_info['patron_name'];
        $merchant_email = $receipt_info['merchant_email'];
        $merchant_user_id = $receipt_info['merchant_user_id'];
        $payment_transaction_id = $receipt_info['transaction_id'];
        if ($receipt_info['main_company_name'] != '') {
            $payment_towards = $receipt_info['main_company_name'];
        } else {
            $payment_towards = $receipt_info['company_name'];
        }
        $copoun_code = '';
        $attendee_details = array();
        $booking_details = array();
        $receipt_info['MerchantRefNo'] = $payment_transaction_id;
        $receipt_info['BillingName'] = $patron_name;
        $receipt_info['BillingEmail'] = $patron_email;
        $receipt_info['merchant_name'] = $payment_towards;
        $receipt_info['TransactionID'] = $receipt_info['ref_no'];
        $receipt_info['DateCreated'] = $receipt_info['date'];
        $receipt_info['Amount'] = $receipt_info['amount'];
        $this->smarty->assign("response", $receipt_info);
        $this->smarty->assign("current_year", date('Y'));
        if (getenv('ENV') == "PROD") {
            $host = 'https://www.swipez.in';
        } else {
            $host = 'https://h7sak8am43.swipez.in';
        }
        if ($receipt_info['image'] != '') {
            $logo = $host . "/uploads/images/logos/" . $receipt_info['image'];
        } elseif ($receipt_info['merchant_logo'] != '') {
            $logo = $host . "/uploads/images/landing/" . $receipt_info['merchant_logo'];
        }
        $event_details = $this->common->getSingleValue('event_request', 'event_request_id', $receipt_info['payment_request_id']);
        $attendee_details = $this->common->getAttendeeDetails($payment_transaction_id);
        $customer_details = $this->common->getCustomerValueDetail($receipt_info['customer_id']);
        $this->smarty->assign("payee_capture", json_decode($event_details['payee_capture'], 1));
        $this->smarty->assign("attendees_capture", json_decode($event_details['attendees_capture'], 1));
        $this->smarty->assign("customer_details", $customer_details);
        $info = $this->common->getPaymentRequestDetails($receipt_info['payment_request_id'], NULL, 2);
        $this->smarty->assign("info", $info);
        $this->smarty->assign("host", $host);
        $this->smarty->assign("event_link", $host . '/patron/event/view/' . $this->encrypt->encode($receipt_info['payment_request_id']));
        $this->smarty->assign("receipt_link", $host . '/patron/transaction/receipt/' . $this->encrypt->encode($payment_transaction_id));

        foreach ($attendee_details as $det) {
            if ($det['coupon_code'] != '') {
                $coupon_id = $det['coupon_code'];
            }
        }
        if ($coupon_id > 0) {
            $coupon_details = $this->common->getSingleValue('coupon', 'coupon_id', $coupon_id);
            $copoun_code = $coupon_details['coupon_code'];
        }
        $this->smarty->assign("pg_type", $pg_type);
        $this->smarty->assign("logo", $logo);
        $this->smarty->assign("coupon_code", $copoun_code);
        $this->smarty->assign("attendee_details", $attendee_details);
        $this->smarty->assign("booking_details", $booking_details);
        $message = $this->smarty->fetch(VIEW . 'mailer/event_receipt.tpl');
        $qr = new \chillerlan\QRCode\QRCode();
        $enc_trans_id = $this->encrypt->encode($payment_transaction_id);
        $qrcodeimg = $qr->render($enc_trans_id);
        $this->smarty->assign("qrcode", $qrcodeimg);
        $qrcode_template = $this->smarty->fetch(VIEW . 'mailer/event_qrcode_receipt.tpl');
        $mpdf_path = getenv('MPDF_PATH');
        require_once($mpdf_path . 'mpdf.php');
        $mpdf = new mPDF();
        $mpdf->WriteHTML($qrcode_template);
        $mpdf->SetDisplayMode('fullpage');
        $name = 'Receipt' . random_int(0, 99999);
        $file_name = $name . '.pdf';
        $mpdf->Output($file_name, 'D');
    }

    public function defineTag($event_request_id, $type)
    {
        $req_detail = $this->common->getSingleValue('event_request', 'event_request_id', $event_request_id);
        if ($req_detail['global_tag'] != '') {
            $this->view->global_tag = $req_detail['global_tag'];
            $this->smarty->assign("global_tag", $req_detail['global_tag']);
        }
        if ($type == 1 && $req_detail['landing_tag'] != '') {
            $this->view->landing_tag = $req_detail['landing_tag'];
            $this->smarty->assign("landing_tag", $req_detail['landing_tag']);
        }
        if ($type == 2 && $req_detail['success_tag'] != '') {
            $this->view->success_tag = $req_detail['success_tag'];
            $this->smarty->assign("success_tag", $req_detail['success_tag']);
        }
    }

    public function success()
    {
        try {
            $user_id = $this->session->get('userid');
            $response = $this->session->get('response');
            if (empty($response)) {
                $this->setError('Invalid request', 'Please do not refresh the browser window or hit enter in the browser address bar click <a href="' . $this->view->server_name . '" >here</a> go back to home page.');
                header('location:/error');
            }
            if ($this->session->get('logged_in') != TRUE) {
                $this->view->isGuest = TRUE;
                $this->smarty->assign("isGuest", 1);
                $this->session->destroy();
            }
            $coupon_id = 0;
            if ($response['pg_type'] == 'event') {

                $payment_transaction_id = $response['MerchantRefNo'];
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


                $event_id = $this->common->getRowValue('payment_request_id', 'payment_transaction', 'pay_transaction_id', $response['MerchantRefNo']);
                $this->defineTag($event_id, 2);
                $event_details = $this->common->getSingleValue('event_request', 'event_request_id', $receipt_info['payment_request_id']);
                $attendee_details = $this->common->getAttendeeDetails($payment_transaction_id);
                $customer_details = $this->common->getCustomerValueDetail($receipt_info['customer_id']);
                $this->smarty->assign("payee_capture", json_decode($event_details['payee_capture'], 1));
                $this->smarty->assign("attendees_capture", json_decode($event_details['attendees_capture'], 1));
                $this->smarty->assign("customer_details", $customer_details);
                $custom_capture_detail = $this->common->getEventCaptureDetails($response['MerchantRefNo']);
                $this->smarty->assign("c_c_detail", $custom_capture_detail);
                foreach ($attendee_details as $det) {
                    if ($det['coupon_code'] != '') {
                        $coupon_id = $det['coupon_code'];
                    }
                }
                if ($coupon_id > 0) {
                    $coupon_details = $this->common->getSingleValue('coupon', 'coupon_id', $coupon_id);
                }
            } else {
                $booking_details = $this->common->getBookingDetails($response['MerchantRefNo']);
            }

            $coupon_enabled = $this->common->getRowValue('coupon_enabled', 'merchant_setting', 'merchant_id', $response['merchant_id']);
            if ($response['pg_type'] == 'membership') {
                $category_id = $this->common->getRowValue('category_id', 'customer_membership', 'transaction_id', $response['MerchantRefNo']);
                $this->session->setCookie('login_cat_id', $category_id);
                $display_url = $this->common->getRowValue('display_url', 'merchant', 'merchant_id', $response['merchant_id']);
                $court_link = '/m/' . $display_url . '/selectcourt';
                $this->session->setCookie('court_link', $court_link);
                $this->session->setCookie('membership_transaction_success', 1);
                $this->session->setCookie('membership_transaction_receipt', $this->encrypt->encode($response['MerchantRefNo']));

                if ($coupon_enabled != 1) {
                    header('Location: ' . $court_link);
                    die();
                }
            }

            if ($coupon_enabled == 1) {
                $display_url = $this->common->getRowValue('display_url', 'merchant', 'merchant_id', $response['merchant_id']);
                if (!isset($display_url)) {
                    $display_url = 'swipez';
                }
                $coupon_link = '/m/' . $display_url . '/showcoupon/' . $this->encrypt->encode($response['merchant_id']) . '/' . $this->encrypt->encode($response['MerchantRefNo']);
                header('Location: ' . $coupon_link);
                die();
            }

            $this->smarty->assign("coupon_code", $coupon_details['coupon_code']);
            $this->smarty->assign("attendee_details", $attendee_details);
            $this->smarty->assign("booking_details", $booking_details);
            $this->smarty->assign("response", $response);
            $this->session->remove('response');
            $this->view->title = 'Payment success';
            $this->view->render('header/guest');
            if ($response['payment_request_id'] == $this->gloss_event_id) {
                $this->smarty->display(VIEW . 'patron/event/gloss_success.tpl');
            } else {
                $this->smarty->display(VIEW . 'patron/event/success.tpl');
            }
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E046]Error while payment success initiate Error: for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
