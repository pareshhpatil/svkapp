<?php

use  App\Http\Controllers\InvoiceController;
use Swipez\ShortUrl\ShortUrl;

/**
 * Secure controller class to handle  payment gateways response 
 */
class Notification
{

    public $subject = null;
    private $SMSHelper = null;
    private $common = null;
    private $smarty = null;
    private $encrypt = null;
    private $emailWrapper = null;
    private $SMSMessage = null;
    public $franchise_email = '';
    public $reminder = false;
    public $reminder_subject = '';
    public $reminder_sms = '';
    public $app_url = '';
    public $request_type = 'web';


    function __construct($app_url = null, $request_type = 'web')
    {
        if ($app_url == null) {
            $this->app_url = config('app.url');
        }
        $this->SMSHelper = new SMSSender();
        $this->smarty = new Smarty();
        $this->smarty->setCompileDir(SMARTY_FOLDER);
        $this->common = new CommonModel();
        $this->encrypt = new Encryption();
        $this->emailWrapper = new EmailWrapper();
        $this->SMSMessage = new SMSMessage();
        $this->request_type = $request_type;
    }

    public function sendMailReceipt($payment_transaction_id, $merchant_notify = 1, $file_name = null, $download = 0)
    {
        try {
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
            } elseif ($receipt_info['payment_request_type'] == 5) {
                $pg_type = 'booking';
            } else {
                $pg_type = 'request';
            }

            $coupon_id = 0;
            $patron_email = $receipt_info['patron_email'];
            $patron_name = $receipt_info['patron_name'];
            $merchant_email = $receipt_info['merchant_email'];
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
            $attach_invoice = 0;
            if ($receipt_info['estimate_req_id'] != '') {
                $attach_invoice = 1;
                $payment_request_id = $receipt_info['payment_request_id'];
            }

            if ($receipt_info['xway_type'] == 2) {
                $form_detail = $this->common->getSingleValue('form_builder_transaction', 'transaction_id', $payment_transaction_id);
                if (!empty($form_detail)) {
                    $receipt_info['TransactionID'] = $payment_transaction_id;
                    $receipt_info['MerchantRefNo'] = $receipt_info['ref_no'];
                    if ($form_detail['invoice_enable'] == 1) {
                        if ($form_detail['payment_request_id'] != '') {
                            $attach_invoice = 1;
                            $payment_request_id = $form_detail['payment_request_id'];
                        }
                    }
                    $array = json_decode($form_detail['json'], 1);
                    if ($array[0]['standard_receipt'] == 1) {
                        $this->subject = "Amazon APOB registration next steps and payment receipt";
                        $rec_page = 'seller_receipt';
                    }
                }
            }

            if ($attach_invoice == 1) {
                $reqlink = $this->encrypt->encode($payment_request_id);
                require_once CONTROLLER . 'patron/Paymentrequest.php';
                $Paymentrequest = new Paymentrequest();
                $file_name = $Paymentrequest->download($reqlink, 1);
            }

            $this->smarty->assign("current_year", date('Y'));
            if ($receipt_info['image'] != '') {
                $logo = $this->app_url . "/uploads/images/logos/" . $receipt_info['image'];
            } elseif ($receipt_info['merchant_logo'] != '') {
                $logo = $this->app_url . "/uploads/images/landing/" . $receipt_info['merchant_logo'];
            }
            $booking_email = array();
            if ($pg_type == 'event') {
                $info = $this->common->getPaymentRequestDetails($receipt_info['payment_request_id'], NULL, 2);
                $attendee_details = $this->common->getAttendeeDetails($payment_transaction_id);
                $customer_details = $this->common->getCustomerValueDetail($receipt_info['customer_id']);
                $this->smarty->assign("payee_capture", json_decode($info['payee_capture'], 1));
                $this->smarty->assign("attendees_capture", json_decode($info['attendees_capture'], 1));
                $this->smarty->assign("customer_details", $customer_details);
                $this->smarty->assign("info", $info);
                $this->smarty->assign("host", $this->app_url);
                $this->smarty->assign("event_link", $this->app_url . '/patron/event/view/' . $this->encrypt->encode($receipt_info['payment_request_id']));
                $this->smarty->assign("receipt_link", $this->app_url . '/patron/transaction/receipt/' . $this->encrypt->encode($payment_transaction_id));

                foreach ($attendee_details as $det) {
                    if ($det['coupon_code'] != '') {
                        $coupon_id = $det['coupon_code'];
                    }
                }
                if ($coupon_id > 0) {
                    $coupon_details = $this->common->getSingleValue('coupon', 'coupon_id', $coupon_id);
                    $copoun_code = $coupon_details['coupon_code'];
                }
            } elseif ($pg_type == 'booking') {
                $this->smarty->assign("info", $receipt_info);
                $booking_details = $this->common->getBookingDetails($payment_transaction_id);
                $cal_detail = $this->common->getSingleValue('booking_calendars', 'calendar_id', $booking_details[0]['calendar_id']);
                if ($cal_detail['confirmation_message'] != '') {
                    $receipt_info['confirmation_message'] = $cal_detail['confirmation_message'];
                }
                if ($cal_detail['notification_email'] != '') {
                    $booking_email = explode(',', $cal_detail['notification_email']);
                }
                if ($cal_detail['cancellation_policy'] != '') {
                    $receipt_info['cancellation_policy'] = $cal_detail['cancellation_policy'];
                }
                $receipt_info['cancellation_link'] = $this->app_url . '/patron/booking/cancellation/' . $this->encrypt->encode($payment_transaction_id);
            } elseif ($pg_type == 'request') {
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
                            $receipt_info['custom_fields'] = $this->common->setReceiptFields($receipt_info, $plugin_value['receipt_fields'], $type, $this->request_type);
                        }
                    }
                }
            }
            $this->smarty->assign("response", $receipt_info);
            $this->smarty->assign("pg_type", $pg_type);
            $this->smarty->assign("logo", $logo);
            $this->smarty->assign("coupon_code", $copoun_code);
            $this->smarty->assign("attendee_details", $attendee_details);
            $this->smarty->assign("booking_details", $booking_details);
            if ($receipt_info['customer_default_column'] != null) {
                $this->smarty->assign("customer_default_column", json_decode($receipt_info['customer_default_column'], 1));
            }

            if ($pg_type == 'event' || $pg_type == 'booking') {
                $message = $this->smarty->fetch(VIEW . 'mailer/' . $pg_type . '_receipt.tpl');
                $qr = new \chillerlan\QRCode\QRCode();
                $enc_trans_id = $this->encrypt->encode($payment_transaction_id);
                $qrcodeimg = $qr->render($enc_trans_id);
                $this->smarty->assign("qrcode", $qrcodeimg);
                $qrcode_template = $this->smarty->fetch(VIEW . 'mailer/' . $pg_type . '_qrcode_receipt.tpl');
                $mpdf = new \Mpdf\Mpdf();
                $mpdf->WriteHTML($qrcode_template);
                $mpdf->SetDisplayMode('fullpage');
                $name = 'Receipt' . random_int(0, 99999);
                $file_name = TMP_FOLDER . $name . '.pdf';
                if ($download == 1) {
                    $mpdf->Output($name . '.pdf', 'D');
                    exit();
                } else {
                    $mpdf->Output($file_name, 'F');
                }
            } else {
                $message = $this->smarty->fetch(VIEW . 'mailer/' . $rec_page . '.tpl');
            }


            if ($file_name != null) {
                $this->emailWrapper->attachment = $file_name;
            }


            if ($payment_towards != NULL) {
                $this->emailWrapper->from_name_ = $payment_towards;
            }

            $this->emailWrapper->merchant_email_ = $receipt_info['merchant_email'];
            if ($pg_type == 'event' || $pg_type == 'booking') {
                $subject = 'Your booking is confirmed!';
            } else {
                $subject = 'Payment receipt from ' . $payment_towards;
            }
            if ($this->subject != null) {
                $subject = $this->subject;
            }
            if ($patron_email != '') {
                $this->emailWrapper->sendMail($patron_email, "", $subject, $message);
            }
            $subject = 'Payment receipt from ' . $patron_name;
            $message = str_replace('Your', 'Customer', $message);
            $message = str_replace('your', 'customer', $message);
            $message = str_replace('" id="impinfo"', ' display:none;" id="impinfo"', $message);
            $this->emailWrapper->from_name_ = 'Swipez';
            $this->emailWrapper->from_email_ = 'support@swipez.in';
            $this->emailWrapper->merchant_email_ = $patron_email;
            if ($merchant_notify == 1) {
                $result = $this->common->getRowValue('send_email', 'preferences', 'user_id', $receipt_info['merchant_user_id']);
                //$this->emailWrapper->attachment = NULL;
                if ($result != 0) {
                    if ($merchant_email != '') {
                        $this->emailWrapper->sendMail($merchant_email, "", $subject, $message);
                    } else {
                        SwipezLogger::error(__METHOD__, 'Merchant email empty for receipt. Transaction id: ' . $payment_transaction_id);
                    }
                    if (!empty($booking_email)) {
                        foreach ($booking_email as $merchant_email) {
                            $this->emailWrapper->sendMail($merchant_email, "", $subject, $message);
                        }
                    }
                }
                if ($this->franchise_email != '') {
                    $this->emailWrapper->sendMail($this->franchise_email, "", $subject, $message);
                }
            }
            if (isset($file_name)) {
                unlink($file_name);
            }
            return $receipt_info;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E096]Error while payment response Error: ' . $e->getMessage());
        }
    }

    function sendSMSReceiptMerchant($result, $mobile_no, $sg_id, $return_sms = 0)
    {
        $sg_details = NULL;
        if ($sg_id > 1) {
            $sg_details = $this->common->getSingleValue('sms_gateway', 'sg_id', $sg_id);
        }
        $result['BillingName'] = $this->common->getShortName($result['BillingName'], 20);
        if ($result['patron_name'] != '') {
            $result['BillingName'] = $this->common->getShortName($result['patron_name'], 20);
        }
        if ($result['type'] == 'event') {
            $event_name = $this->common->getShortName($result['event_name'], 37);
            $message = $this->SMSMessage->fetch('m10');
            $message = str_replace('<event title>', $event_name, $message);
            $message = str_replace('<COUNT>', $result['quantity'], $message);
            $message = str_replace('<UNIT>', $result['unit_type'], $message);
            $message = str_replace('<Patron name>', $result['BillingName'] . ' (' . $result['customer_code'] . ')', $message);
            $message = str_replace('<URL>', $result['transaction_short_url'], $message);
        } else {
            $message = $this->SMSMessage->fetch('m7');
            $customer_code = ($result['customer_code'] != '') ? ' (' . $result['customer_code'] . ')' : '';
            $message = str_replace('<Patron Name>', $result['BillingName'] . $customer_code, $message);
            $message = str_replace('xxxx', $result['Amount'], $message);
        }
        if ($return_sms == 1) {
            $array['sms'] = $message;
            $array['sms_details'] = $sg_details;
            return $array;
        }
        $response = $this->sendSMS($result['merchant_user_id'], $message, $mobile_no, $result['merchant_id'], $result['sms_gateway_type'], $sg_details);
        SwipezLogger::info(__CLASS__, 'SMS Sending to Merchant Mobile: ' . $mobile_no . ' Response:' . $response);
    }

    function sendSMSReceiptCustomer($result, $patronMobile, $sg_id, $return_sms = 0)
    {
        $sg_details = NULL;
        if ($sg_id > 1) {
            $sg_details = $this->common->getSingleValue('sms_gateway', 'sg_id', $sg_id);
        }
        if ($result['pg_type'] == 'event') {
            $event_name = $this->common->getShortName($result['event_name'], 37);
            if ($result['Amount'] > 0) {
                $message = $this->SMSMessage->fetch('p10');
                $message = str_replace('<AMOUNT>', $result['Amount'], $message);
            } else {
                $message = $this->SMSMessage->fetch('p11');
            }
            $message = str_replace('<event title>', $event_name, $message);
            $message = str_replace('<COUNT>', $result['quantity'], $message);
            $message = str_replace('<UNIT>', $result['unit_type'], $message);
            $message = str_replace('<URL>', $result['transaction_short_url'], $message);
            $message = str_replace('<Transaction id>', $result['transaction_id'], $message);
        } else {
            $message = $this->SMSMessage->fetch('p3');
            $message = str_replace('<Merchant Name>', $this->common->getShortName($result['sms_name'], 20), $message);
            $message = str_replace('xxxx', $result['Amount'], $message);
            $message = str_replace('<URL>', $result['transaction_short_url'], $message);
            $message = str_replace('<Transaction id>', $result['transaction_id'], $message);
        }
        if ($return_sms == 1) {
            $array['sms'] = $message;
            $array['sms_details'] = $sg_details;
            return $array;
        }
        $response = $this->sendSMS(null, $message, $patronMobile, $result['merchant_id'], $result['sms_gateway_type'], $sg_details);
        SwipezLogger::info(__CLASS__, 'SMS Sending to Patron Mobile: ' . $patronMobile . ' Response:' . $response);
    }

    function sendInvoiceSMS($info, $long_url)
    {
        if (strlen($info['customer_mobile']) > 9) {
            $plugin = json_decode($info['plugin_value'], 1);
            $sg_details = NULL;
            if ($info['short_url'] == '') {
                $info['short_url'] = $this->saveShortUrl($long_url);
                $this->common->updateShortURL($info['payment_request_id'], $info['short_url']);
            }
            if ($info['sms_gateway'] > 1) {
                $sg_details = $this->common->getSingleValue('sms_gateway', 'sg_id', $info['sms_gateway']);
            }
            if ($plugin['custom_sms'] != '') {
                $info['company_name'] = $this->common->getShortName($info['sms_name'], 20);
                $message = $this->getDynamicString($info, $plugin['custom_sms']);
            } else {
                $message = $this->SMSMessage->fetch('p2');
                $info['sms_name'] = $this->common->getShortName($info['sms_name'], 20);
                $message = str_replace('<Merchant Name>', $info['sms_name'], $message);
                $message = str_replace('xxxx', number_format($info['absolute_cost'], 2), $message);
                $message = str_replace('<short-url>', $info['short_url'], $message);
            }
            if ($this->reminder_sms != '') {
                $message = $this->reminder_sms;
                $message = $this->getDynamicString($info, $message);
            }
            $response = $this->sendSMS(NULL, $message, $info['customer_mobile'], $info['merchant_id'], $info['sms_gateway_type'], $sg_details);
            SwipezLogger::info(__CLASS__, 'SMS Sent to ' . $info['customer_mobile'] . ' Response:' . $response);
        }
    }

    public function sendSMS($user_id = null, $message, $mobileNo, $merchant_id = '', $sms_gateway_type = 1, $gateway = NULL)
    {
        $result = $this->common->getRowValue('send_sms', 'preferences', 'user_id', $user_id);
        if (!empty($result)) {
            if ($result == 0) {
                return;
            }
        }
        $SMSHelper = new SMSSender();
        $SMSHelper->merchant_id = $merchant_id;
        $SMSHelper->sms_gateway_type = $sms_gateway_type;
        if (!empty($gateway)) {
            $SMSHelper->url = $gateway['req_url'];
            $SMSHelper->val1 = $gateway['sg_val1'];
            $SMSHelper->val2 = $gateway['sg_val2'];
            $SMSHelper->val3 = $gateway['sg_val3'];
            $SMSHelper->val4 = $gateway['sg_val4'];
            $SMSHelper->val5 = $gateway['sg_val5'];
        }
        $responseArr = $SMSHelper->send($message, $mobileNo);
        SwipezLogger::info(__CLASS__, 'SMS Sent to ' . $mobileNo . ' SMS:' . $message);
        return $responseArr;
    }

    public function sendInvoiceNotification($payment_request_id, $revised = 0, $sms = 0, $custom_covering = '')
    {
        try {
            $this->smarty->clearAllAssign();
            $info = $this->common->getPaymentRequestDetails($payment_request_id, 'customer');
            if (empty($info)) {
                throw new Exception('Empty invoice details Invoice id :' . $payment_request_id);
            }
            if ($info['payment_request_type'] == 4) {
                return false;
            }
            $encoded = $this->encrypt->encode($payment_request_id);
            $invoiceviewurl = $this->app_url . '/patron/paymentrequest/view/' . $encoded;
            if ($this->reminder == false) {
                $this->common->genericupdate('payment_request', 'notification_sent', 1, 'payment_request_id', $payment_request_id);
            }
            if ($info['customer_mobile'] != '' || $info['customer_email'] != '') {
                if ($info['customer_email'] != '') {
                    $plugin = json_decode($info['plugin_value'], 1);
                  
                    if ($info['template_type']!='construction' && $plugin['default_covering_note'] > 0) {
                   
                        $this->sendCoveringNote($custom_covering, $plugin['default_covering_note'], $info, '', $plugin['custom_email_subject']);
                   
                    } else {
                        require_once CONTROLLER . 'InvoiceWrapper.php';
                        $invoice = new InvoiceWrapper($this->common);
                        $smarty_array = $invoice->asignSmarty($info, array(), $payment_request_id, 'Invoice', 'patron');
                        foreach ($smarty_array as $key => $value) {
                            $this->smarty->assign($key, $value);
                        }
                        $unsuburl = $this->app_url . '/unsubscribe/select/' . $encoded;
                        $pdf_url = $this->app_url . '/patron/paymentrequest/download/' . $encoded;
                        $this->smarty->assign('server_path', $this->app_url);
                        $this->smarty->assign('url', $invoiceviewurl);
                        $this->smarty->assign('pdf_url', $pdf_url);
                        $this->smarty->assign('unsub_link', $unsuburl);
                        $subject = "Payment request from __COMPANY_NAME__";
                        $subject = str_replace('__COMPANY_NAME__', $info['company_name'], $subject);
                        if ($plugin['has_custom_notification'] == 1) {
                            $subject = $this->getDynamicString($info, $plugin['custom_email_subject']);
                        }
                        if ($revised == 1) {
                            $subject = 'Revised ' . lcfirst($subject);
                        }

                        if ($this->reminder_subject != '') {
                            $subject = $this->reminder_subject;
                            $subject = $this->getDynamicString($info, $subject);
                        }

                        $template_type = ($info['template_type'] != 'isp') ? 'detail' : 'isp';
                        $template_type = ($info['template_type'] == 'franchise') ? 'franchise' : $template_type;
                        $template_type = ($info['template_type'] == 'travel') ? 'travel' : $template_type;
                        $message = $this->smarty->fetch(VIEW . 'mailer/' . 'invoice_' . $template_type . '.tpl');

                        if ($info['document_url'] != '') {
                            $fileInfo = pathinfo($info['document_url']);
                            $s3file = file_get_contents($info['document_url']);
                            $file_name = TMP_FOLDER . $fileInfo['basename'];
                            file_put_contents($file_name, $s3file);
                            $this->emailWrapper->attachment = $file_name;
                        }
                        $this->emailWrapper->from_name_ = $info['company_name'];

                        if ($info['from_email'] != '') {
                            $this->emailWrapper->from_email_ = $info['from_email'];
                        } else {
                            $this->emailWrapper->from_email_ = SENDER_EMAIL;
                        }

                        if ($info['business_email'] != '') {
                            $this->emailWrapper->merchant_email_ = $info['business_email'];
                        } else {
                            $this->emailWrapper->merchant_email_ = '';
                        }
                        $this->emailWrapper->merchant_id = $info['merchant_id'];
                        if (!empty($plugin['cc_email'])) {
                            $this->emailWrapper->cc = $plugin['cc_email'];
                        } else {
                            $this->emailWrapper->cc = null;
                        }

                        if (substr(req_type, 0, 1) == 'R') {
                            $this->emailWrapper->is_log = 'off';
                        }



 if ($info['template_type']=='construction' || $info['design_name'] != '') {
                            if (BATCH_CONFIG == true) {
                                $subject = str_replace(" ", "%20", $subject);
                                $subject = str_replace("&", "%26", $subject);
                                $subject = preg_replace("/\r|\n/", "%0a", $subject);
                                $post_url = SWIPEZ_APP_URL . 'invoice/sendmail/' . $encoded . '/' . $subject;

                                $this->laravelInvoiceMail($post_url);
                            } else {
                                $invoicecontroller = new InvoiceController();
                                $invoicecontroller->sendEmail($encoded, $subject);
                            }
                        } else {
                            $this->emailWrapper->sendMail($info['customer_email'], "", $subject, $message);
                        }
                        if (isset($file_name)) {
                            unlink($file_name);
                        }
                    }
                }

                if ($sms == 1 && $info['customer_mobile'] != '') {
                    $this->sendInvoiceSMS($info, $invoiceviewurl);
                }
                return $invoiceviewurl;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E096+87]Error while sending invoice email Error: ' . $e->getMessage());
        }
    }
    public function laravelInvoiceMail($post_url)
    {
         $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $post_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => '',
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
    }
    public function sendCoveringNote($custom_covering, $covering_id, $info, $add_subject = '', $custom_subject = '')
    {
        try {
            $plugin = json_decode($info['plugin_value'], 1);
            if ($custom_covering != '') {
                $details = json_decode($custom_covering, true);
            } else {
                $details = $this->common->getSingleValue('covering_note', 'covering_id', $covering_id);
            }
            $details['email_id'] = $info['customer_email'];
            $details['company_name'] = $info['company_name'];
            $this->smarty->setCompileDir(SMARTY_FOLDER);
            if (isset($info['payment_request_id'])) {
                $link = $this->encrypt->encode($info['payment_request_id']);
                $this->smarty->assign('invoice_link', $this->app_url . '/patron/paymentrequest/view/' . $link);
                //$this->smarty->assign('pdf_link', $this->view->server_name . '/patron/paymentrequest/download/' . $link);
                $this->smarty->assign('unsub_link', $this->app_url . '/unsubscribe/select/' . $link);
            } else {
                $this->smarty->assign('invoice_link', '#');
                $this->smarty->assign('pdf_link', '#');
                $this->smarty->assign('unsub_link', '#');
            }
            $this->smarty->assign('det', $details);
            $message = $this->smarty->fetch(VIEW . 'mailer/covering_note.tpl');
            $merchantName_ = $info['company_name'];
            if ($info['main_company_name'] != '') {
                $merchantName_ = $info['main_company_name'] . ' (' . $info['company_name'] . ')';
            }
            if ($merchantName_ != NULL) {
                $this->emailWrapper->from_name_ = $merchantName_;
            }
            if ($info['from_email'] != '') {
                $this->emailWrapper->from_email_ = $info['from_email'];
            }

            if (!empty($plugin['cc_email'])) {
                $this->emailWrapper->cc = $plugin['cc_email'];
            } else {
                $this->emailWrapper->cc = null;
            }

            if ($info['business_email'] != '') {
                $this->emailWrapper->merchant_email_ = $info['business_email'];
            }
            if ($details['pdf_enable'] == 1) {
                $file_name = $this->getPDFSmarty($info);
                $this->emailWrapper->attachment = $file_name;
            }
            $details['subject'] = $details['subject'] . $add_subject;
            if ($custom_subject != '') {
                $details['subject'] = $custom_subject;
            }
            $subject = $this->getDynamicString($info, $details['subject']);
            $message = $this->getDynamicString($info, $message);
            $this->emailWrapper->sendMail($details['email_id'], "", $subject, $message);
            if (isset($file_name)) {
                unlink($file_name);
            }
            return $this->app_url . '/patron/paymentrequest/view/' . $link;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E096]Error while payment response Error: ' . $e->getMessage());
        }
    }

    function getDynamicString($info, $message)
    {
        $vars = $this->common->getListValue('dynamic_variable', 'is_active', 1);
        foreach ($vars as $row) {
            if ($row['name'] == '%BILL_MONTH%') {
                $message = str_replace($row['name'], date("M-y", strtotime($info[$row['column_name']])), $message);
            } else {
                $message = str_replace($row['name'], $info[$row['column_name']], $message);
            }
        }
        return $message;
    }

    function getPDFSmarty($info)
    {
        try {
            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $this->smarty->assign('is_merchant', 0);
            $this->smarty->assign('info', $info);
            $smarty_array = $invoice->asignSmarty($info, array(), $info['payment_request_id']);
            foreach ($smarty_array as $key => $value) {
                $this->smarty->assign($key, $value);
            }
            if ($info['template_type'] == 'franchise' || $info['template_type'] == 'isp' || $info['template_type'] == 'travel') {
                $template_type = $info['template_type'];
            } else {
                $template_type = 'detail';
            }
            $html .= $this->smarty->fetch(VIEW . 'pdf/' . 'invoice_' . $template_type . '.tpl');
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($html);
            $mpdf->SetDisplayMode('fullpage');
            $name = 'Invoice' . random_int(0, 99999);
            $file_name = TMP_FOLDER . $name . '.pdf';
            $mpdf->Output($file_name, 'F');
            return $file_name;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E01pdf3]Error while download pdf  Error: for request id [' . $info['payment_request_id'] . '] ' . $e->getMessage());
        }
    }

    function getUnit($unit, $seat)
    {
        switch ($unit) {
            case 'Quantity':
                if ($seat > 1) {
                    return 'Quantities';
                } else {
                    return 'Quantity';
                }
                break;
            case 'Seat':
                if ($seat > 1) {
                    return 'Seat(s)';
                } else {
                    return 'Seat';
                }
                break;
        }
    }

    function saveShortUrl($url)
    {
        $shorUrl = new ShortUrl();
        return $shorUrl->SaveUrl($url);
    }
}
