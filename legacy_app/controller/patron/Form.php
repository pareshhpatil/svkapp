<?php

use App\Http\Controllers\PaymentWrapperController;

/**
 * Payment request functionality for viewing payment requests, confirming and invoking payment gateway
 * 
 */
class Form extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * View listing of payment requests assigned to the patron
     * 
     */
    public $redirect_url_array = array();
    public $redirect_url = null;
    public $redirect_method = null;
    public $param1 = null;
    public $param2 = null;

    public function submit($link = null, $state_ = null, $param_ = null)
    {
        try {
            $udf_params = array();
            if (isset($_SERVER['QUERY_STRING'])) {
                $paramlink = $_SERVER['QUERY_STRING'];
                $params = explode("&", $paramlink);
                foreach ($params as $p) {
                    $par = explode("=", $p);
                    $udf_params[$par[0]] = $par[1];
                }
                $link = str_replace('?' . $paramlink, '', $link);
            }
            if ($link == null) {
                $this->setInvalidLinkError();
            }
            header('Cache-Control: no cache'); //no cache
            session_cache_limiter('private_no_expire'); // works
            $id = $this->encrypt->decode($link);
            if (!is_numeric($id)) {
                $id = $this->encryptBackup($link);
                $link = $this->encrypt->encode($id);
            }
            $failed_transaction_id = $this->session->get('failed_transaction_id');
            if (isset($failed_transaction_id)) {
                $details = $this->common->getSingleValue('form_builder_transaction', 'transaction_id', $failed_transaction_id);
                $this->session->remove('failed_transaction_id');
            } else {
                $details = $this->common->getSingleValue('form_builder_request', 'id', $id, 1);
            }
            if (empty($details)) {
                $this->setInvalidLinkError();
            }

            $error_title = $this->session->get('errorTitle');
            if (isset($error_title)) {
                $this->smarty->assign("error_title", $error_title);
                $this->smarty->assign("error_message", $this->session->get('errorMessage'));
                $this->session->remove('errorTitle');
                $this->session->remove('errorMessage');
            }
            $merchant_id = $details['merchant_id'];
            $json = $details['json'];
            $json_array = json_decode($json, 1);

            $this->setHeader($json_array, $merchant_id);
            if ($state_ != NULL) {
                $this->session->set('seller_state_code', $state_);
            }
            if ($param_ != NULL) {
                $this->session->set('seller_param', $state_);
            }

            $result = $this->validateState($json_array, $state_, $param_);
            if ($result == FALSE) {
                $this->setInvalidLinkError();
            } else {
                $json_array = $result;
            }

            foreach ($json_array as $jdata) {
                if ($jdata["type"] == 'grand_total') {
                    $amount  = $jdata["value"];
                }
            }

            $state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
            $details = $this->common->getSingleValue('merchant', 'merchant_id', $merchant_id);
            $pg_details = $this->common->getMerchantPG($merchant_id);
            $this->smarty->assign("enable_tnc", $pg_details[0]['enable_tnc']);
            if (count($pg_details) > 1) {
                $invoice = new PaymentWrapperController();
                $radio = $invoice->getPGRadio($pg_details, $this->encrypt);
                $this->smarty->assign("paypal_id", $radio['paypal_id']);
                $this->smarty->assign("radio", $radio['radio']);

                $this->smarty->assign("post_url", '/payment-gateway');
                $this->smarty->assign("request_post_url", '/patron/form/payment');
                $this->smarty->assign("is_new_pg", true);
            } else {
                $this->smarty->assign("post_url", '/patron/form/payment');
                $this->smarty->assign("request_post_url", '/patron/form/payment');
                $this->smarty->assign("is_new_pg", true);
            }

            $this->session->set('valid_ajax', 'formBuilder');
            $this->view->disable_talk = 1;
            $this->view->form_link = $link;
            $this->smarty->assign("state_code", $state_code);
            $this->smarty->assign("link", $link);
            $merchant_user_id = $this->common->getRowValue('user_id', 'merchant', 'merchant_id', $merchant_id);
            $this->smarty->assign("merchant_id", $merchant_id);
            $this->smarty->assign("merchant_user_id", $merchant_user_id);
            $this->smarty->assign("json", $json_array);
            $this->smarty->assign("amount",  $amount);
            $this->smarty->assign("url", $details['display_url']);
            $this->smarty->assign("udf_params", $udf_params);
            $this->view->v3captcha = true;
            $this->view->title = $this->view->page_title;
            $this->view->js = array('invoice', 'booking', 'register');
            $this->view->render('header/patronform');
            $this->smarty->display(VIEW . 'patron/form/submit.tpl');
            $this->view->render('footer/merchantwebsite');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E039]Error while listing patron payment request Error: for user id [' . $this->session->get('userid') . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function payment($link = null, $fee_id = null)
    {
        try {
            //$this->validatev3Captcha();
            if ($link == 'paypal') {
                $data = file_get_contents('php://input');
                $dataaarray = json_decode($data, 1);
                foreach ($dataaarray as $row) {
                    $_POST[$row['name']] = $row['value'];
                }
                $_POST['payment_mode'] = $fee_id;
            }
            $id = $this->encrypt->decode($_POST['request_id']);
            $details = $this->common->getSingleValue('form_builder_request', 'id', $id, 1);
            $json = $details['json'];
            $json_array = json_decode($json, 1);
            $merchant_id = $details['merchant_id'];
            if (isset($_POST['token'])) {
                $this->session->set('API_TOKEN', $_POST['token']);
            }
            foreach ($json_array as $key => $row) {
                $json_array[$key]['value'] = $_POST[$row['name']];
            }

            $json = json_encode($json_array);
            $enable_invoice = ($json_array[0]['invoice_enable'] == 1) ? 1 : 0;
            $_POST['is_random_id'] = $details['random_id'];
            $_POST['webhook_id'] = $details['webhook_id'];
            $_POST['return_url'] = $this->view->server_name . '/patron/form/success';
            $_POST['name'] = $_POST['customer_name'];
            $_POST['amount'] = $_POST['grand_total'];

            $_POST['customer_code'] = '';
            require CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($this->model);
            $validator->validatePayment();
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $form_transaction_id = $this->model->saveFormbuilderTransaction($id, $enable_invoice, $details['merchant_id'], $_POST['amount'], $json);
                if (!empty($_FILES)) {
                    $deleteArray = array();
                    $zip = new ZipArchive(); // Load zip library 
                    $zip_name = TMP_FOLDER . $this->encrypt->encode($form_transaction_id) . ".zip"; // Zip name
                    if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
                        SwipezLogger::error(__CLASS__, '[E1011+96]Error: ZIP creation failed at this time. Form transaction id: ' . $form_transaction_id);
                    }
                    $json_array = json_decode($json, 1);
                    $folder_path = $details['merchant_id'] . '/' . $form_transaction_id;

                    foreach ($json_array as $key => $row) {
                        if ($row['subtype'] == 'file') {
                            $value = $this->uploadFile($_FILES[$row['name']], $folder_path, $row['name']);
                            $json_array[$key]['value'] = $value;
                            $file_name = TMP_FOLDER . $_FILES[$row['name']]["name"];
                            move_uploaded_file($_FILES[$row['name']]["tmp_name"], $file_name);
                            $zip->addFile($file_name, $_FILES[$row['name']]["name"]);
                            $deleteArray[] = $file_name;
                        } else {
                            $json_array[$key]['value'] = $row['value'];
                        }
                    }

                    $zip->close();
                    $file_size = round(filesize($zip_name) / 1000, 2);
                    $file['tmp_name'] = $zip_name;
                    $file['name'] = $this->encrypt->encode($form_transaction_id) . ".zip";
                    $zip_path = $this->uploadFile($file, $folder_path, $this->encrypt->encode($form_transaction_id));
                    $deleteArray[] = $zip_name;
                    $json = json_encode($json_array);
                    $this->model->updateForm($json, $file_size, $zip_path, $form_transaction_id);
                    foreach ($deleteArray as $file) {
                        unlink($file);
                    }
                }
                require_once MODEL . 'merchant/LandingModel.php';
                $landing_model = new LandingModel();
                require_once CONTROLLER . 'merchant/Landing.php';
                $landing = new Landing();
                $landing->merchant_details = $landing_model->getMerchantLandingDetails($details['merchant_id']);
                $landing->merchant_id = $details['merchant_id'];
                $transaction_id = $landing->directpayment($form_transaction_id);
                if ($transaction_id != '') {
                    $this->freePackage($transaction_id);
                }
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->submit($_POST['request_id']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E1011+6]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function freePackage($transaction_id)
    {
        try {
            $this->common->genericupdate('xway_transaction', 'xway_transaction_status', 1, 'xway_transaction_id', $transaction_id);
            $details = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $transaction_id);
            $detail = $this->common->getMerchantProfile($details['merchant_id']);
            $company_name = $detail['company_name'];
            $business_email = $detail['business_email'];
            $array['transaction_id'] = $details['xway_transaction_id'];
            $array['reference_no'] = $details['xway_transaction_id'];
            $array['bank_ref_no'] = 'NA';
            $array['mode'] = 'Free';
            $array['status'] = 'success';
            $array['amount'] = 0.00;
            $array['date'] = $details['created_date'];
            $array['message'] = 'Transaction Successful';
            $array['merchant_email'] = $business_email;
            $array['company_name'] = $company_name;
            $array['billing_name'] = $details['name'];
            $array['billing_email'] = $details['email'];
            $array['billing_mobile'] = $details['phone'];
            $array['billing_address'] = $details['address'];
            $array['billing_city'] = $details['city'];
            $array['billing_state'] = $details['state'];
            $array['billing_postal_code'] = $details['postal_code'];
            $array['type'] = 'request';
            $this->view->render('secure/loader');
            $this->view->post_url = $details['return_url'];
            $this->view->post = $array;
            $this->view->render('secure/redirect');
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E1011+63]Error while freePackage redirect Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function formsubmit()
    {
        try {
            $id = $this->encrypt->decode($_POST['request_id']);
            $details = $this->common->getSingleValue('form_builder_request', 'id', $id, 1);
            $json = $details['json'];
            $json_array = json_decode($json, 1);
            $merchant_id = $details['merchant_id'];

            foreach ($json_array as $key => $row) {
                $json_array[$key]['value'] = $_POST[$row['name']];
            }
            $json = json_encode($json_array);
            $enable_invoice = ($json_array[0]['invoice_enable'] == 1) ? 1 : 0;
            $form_sms_mobile = (isset($json_array[0]['notify_mobile'])) ? $json_array[0]['notify_mobile'] : 0;
            $form_sms = (isset($json_array[0]['form_sms'])) ? $json_array[0]['form_sms'] : $details['name'] . ' form submitted successfully';
            $notify_customer = (isset($json_array[0]['notify_customer'])) ? $json_array[0]['notify_customer'] : 0;
            $notify_merchant = (isset($json_array[0]['notify_merchant'])) ? $json_array[0]['notify_merchant'] : 0;
            $_POST['is_random_id'] = $details['random_id'];
            $_POST['webhook_id'] = $details['webhook_id'];
            $_POST['name'] = $_POST['customer_name'];
            $_POST['amount'] = $_POST['grand_total'];
            $_POST['customer_code'] = '';

            $form_transaction_id = $this->model->saveFormbuilderTransaction($id, $enable_invoice, $details['merchant_id'], 0, $json);
            $enc_form_id = $this->encrypt->encode($form_transaction_id);
            if (!empty($_FILES)) {
                $deleteArray = array();
                $zip = new ZipArchive(); // Load zip library 
                $zip_name = TMP_FOLDER . $enc_form_id . ".zip"; // Zip name
                if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
                    SwipezLogger::error(__CLASS__, '[E1011+96]Error: ZIP creation failed at this time. Form transaction id: ' . $form_transaction_id);
                }
                $json_array = json_decode($json, 1);
                $folder_path = $details['merchant_id'] . '/' . $form_transaction_id;

                foreach ($json_array as $key => $row) {
                    if ($row['subtype'] == 'file') {
                        $value = $this->uploadFile($_FILES[$row['name']], $folder_path, $row['name']);
                        $json_array[$key]['value'] = $value;
                        $file_name = TMP_FOLDER . $_FILES[$row['name']]["name"];
                        move_uploaded_file($_FILES[$row['name']]["tmp_name"], $file_name);
                        $zip->addFile($file_name, $_FILES[$row['name']]["name"]);
                        $deleteArray[] = $file_name;
                    } else {
                        $json_array[$key]['value'] = $row['value'];
                    }
                }

                $zip->close();
                $file_size = round(filesize($zip_name) / 1000, 2);
                $file['tmp_name'] = $zip_name;
                $file['name'] = $enc_form_id . ".zip";
                $zip_path = $this->uploadFile($file, $folder_path, $enc_form_id);
                $deleteArray[] = $zip_name;
                $json = json_encode($json_array);
                $this->model->updateForm($json, $file_size, $zip_path, $form_transaction_id);
                foreach ($deleteArray as $file) {
                    unlink($file);
                }
            }
            $json_array = json_decode($json, 1);
            $customer_email = '';
            $customer_mobile = '';
            foreach ($json_array as $json) {
                if ($json['display'] == 1 && $json['type'] != 'label') {
                    $array[] = array('label' => $json['label'], 'value' => $json['value']);
                }
                if ($json['name'] == 'email') {
                    $customer_email = $json['value'];
                }
                if ($json['name'] == 'mobile') {
                    $customer_mobile = $json['value'];
                }
            }
            $merchant_email = $this->common->getRowValue('business_email', 'merchant_billing_profile', 'merchant_id', $merchant_id);

            $response['form_details'] = $array;
            $this->smarty->assign("response", $response);
            $message = $this->smarty->fetch(VIEW . 'mailer/form_receipt.tpl');
            $emailWrapper = new EmailWrapper();
            require_once CONTROLLER . 'Notification.php';
            $notification = new Notification();
            $long_url = config('app.url') . '/patron/form/detail/' . $enc_form_id;
            $shortUrl = $notification->saveShortUrl($long_url);
            $sms = $form_sms . ' link ' . $shortUrl;
            if ($notify_merchant == 1) {
                if ($form_sms_mobile != 0) {
                    $notification->sendSMS(null, $sms, $form_sms_mobile, $merchant_id);
                }
                $emailWrapper->sendMail($merchant_email, "", $form_sms, $message);
            }
            if ($notify_customer == 1) {
                if ($customer_mobile != '') {
                    $notification->sendSMS(null, $sms, $customer_mobile, $merchant_id);
                }
                if ($customer_email != '') {
                    $emailWrapper->sendMail($customer_email, "", $form_sms, $message);
                }
            }

            header('Location: /patron/form/detail/' . $enc_form_id . '/success');
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function uploadFile($upload_file, $path, $file_name, $folder = 'uploads')
    {
        $bucket = getenv('FORM_BUILDER_BUCKET');
        require_once UTIL . 'SiteBuilderS3Bucket.php';
        $aws = new SiteBuilderS3Bucket('ap-south-1');
        $file = $upload_file['name'];
        $ext = substr($file, strrpos($file, '.') + 1);
        $keyname = $folder . '/' . $path . '/' . $file_name . '.' . $ext;
        $fileurl = $aws->putFile($bucket, $keyname, $upload_file['tmp_name']);
        return $fileurl;
    }

    public function success()
    {
        try {
            $result = $this->common->getSingleValue('form_builder_transaction', 'transaction_id', $_POST['transaction_id']);
            $merchant_id = $result['merchant_id'];
            $transaction_id = $_POST['transaction_id'];
            $json_array = json_decode($result['json'], 1);
            $this->setHeader($json_array, $merchant_id);
            if ($_POST['status'] == 'success') {
                require_once MODEL . 'merchant/LandingModel.php';
                $landing_model = new LandingModel();
                $merchant_details = $landing_model->getMerchantLandingDetails($merchant_id);
                $logo = $merchant_details['logo'];
                $business_email = $this->common->getMerchantProfile($merchant_id, 0, 'business_email');
                $mer_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
                $user = $this->common->getSingleValue('user', 'user_id', $merchant_details['user_id']);
                $description = $this->common->getRowValue('description', 'xway_transaction', 'xway_transaction_id', $_POST['transaction_id']);
                $receipt_with_data = $this->common->getRowValue('receipt_with_data', 'form_builder_request', 'id', $result['request_id']);
                $response['customer_code'] = $_POST['udf1'];
                $response['BillingName'] = $_POST['billing_name'];
                $response['BillingMobile'] = $_POST['billing_mobile'];
                $response['BillingEmail'] = $_POST['billing_email'];
                $response['merchant_name'] = $_POST['company_name'];
                $response['TransactionID'] = $_POST['transaction_id'];
                $response['merchant_email'] = $business_email;
                $response['merchant_mobile'] = $user['mobile_no'];
                $response['MerchantRefNo'] = $_POST['bank_ref_no'];
                $response['DateCreated'] = $_POST['date'];
                $response['narrative'] = $description;
                $response['Amount'] = $_POST['amount'];
                $response['payment_mode'] = $_POST['mode'];
                $response['image'] = '';
                $response['merchant_logo'] = $logo;

                if ($logo != '') {
                    $response['logo'] = $this->view->server_name . "/uploads/images/landing/" . $logo;
                }
                $file_name = null;
                $invoice_link = null;
                try {
                    if (!empty($result)) {
                        $this->common->genericupdate('form_builder_transaction', 'status', 1, 'id', $result['id'], $merchant_id);
                        if ($result['invoice_enable'] == 1) {
                            try {
                                SwipezLogger::debug(__CLASS__, 'Form builder invoice initiate');
                                require_once CONTROLLER . 'InvoiceWrapper.php';
                                $wrapper = new InvoiceWrapper($this->common);
                                $inv_details = $wrapper->xwayInvoice($result);
                                $file_name = $inv_details['file_name'];
                                $invoice_link = $inv_details['invoice_link'];
                            } catch (Exception $e) {
                                Sentry\captureException($e);

                                SwipezLogger::error(__CLASS__, '[E2145]Error while save form builder invoice Error: ' . $e->getMessage());
                            }
                        }
                    }
                    $response['mailer_content'] = $this->view->mailer_content;
                    if ($json_array[0]['standard_receipt'] == 1) {
                        $subject = 'Amazon APOB registration next steps and payment receipt';
                        $rec_page = 'seller_receipt';
                    } else {
                        $subject = 'Payment receipt from ' . $response['BillingName'];
                        $rec_page = 'receipt';
                    }

                    if ($receipt_with_data == 1) {
                        $response['form_details'] = json_decode($result['json'], 1);
                    }

                    require_once CONTROLLER . '/Secure.php';
                    $secure = new Secure();
                    $secure->sendMailReceipt($response, 'directpay', $file_name, $rec_page, $subject);
                    if (isset($file_name)) {
                        unlink($file_name);
                    }
                    require_once CONTROLLER . 'Notification.php';
                    $notify = new Notification();
                    $transaction_link = $this->encrypt->encode($_POST['transaction_id']);
                    $long_url = $this->view->server_name . '/patron/transaction/receipt/' . $transaction_link;
                    $shortUrl = $notify->saveShortUrl($long_url);
                    $result['transaction_short_url'] = $shortUrl;
                    $result['sms_name'] = $_POST['company_name'];
                    $result['transaction_id'] = $_POST['transaction_id'];
                    $result['Amount'] = $_POST['amount'];
                    $result['sms_gateway_type'] = $mer_setting['sms_gateway_type'];
                    $result['merchant_id'] = $merchant_id;
                    $result['customer_code'] = $response['customer_code'];
                    $result['BillingName'] = $response['BillingName'];
                    $result['merchant_user_id'] = $this->merchant_details['user_id'];

                    $notify->sendSMSReceiptMerchant($result, $user['mobile_no'], $mer_setting['sms_gateway']);
                    $notify->sendSMSReceiptCustomer($result, $_POST['billing_mobile'], $mer_setting['sms_gateway']);
                } catch (Exception $e) {
                    Sentry\captureException($e);

                    SwipezLogger::error(__CLASS__, '[E2145]Error while save form builder invoice Error: ' . $e->getMessage());
                }
                if ($this->redirect_url != null) {
                    require_once CONTROLLER . 'InvoiceWrapper.php';
                    $wrapper = new InvoiceWrapper($this->common);
                    $array = $wrapper->setRedirectPost($transaction_id, 'success', $this->merchant_details['company_name']);
                    $redirect_url = str_replace('__TRANSACTION_ID__', $transaction_id, $this->redirect_url);
                    require_once UTIL . 'CBCEncryption.php';
                    $cbcenc = new CBCEncryption();
                    $cbc_transaction_id = $cbcenc->encode($transaction_id);
                    $cbc_param = $cbcenc->encode($this->param2);
                    $redirect_url = str_replace('__CBC_ENC_TRANSACTION_ID__', $cbc_transaction_id, $redirect_url);
                    $redirect_url = str_replace('__CBC_ENC_PARAM__ID__', $cbc_param, $redirect_url);
                    $this->view->post_url = $redirect_url;
                    if ($this->redirect_method == 'GET') {
                        header('Location:' . $this->view->post_url);
                        die();
                    } else {
                        $this->view->post = $array;
                        $this->view->render('secure/redirect');
                        die();
                    }
                }

                $this->view->disable_talk = 1;
                $this->smarty->assign("invoice_link", $invoice_link);
                $this->smarty->assign("response", $response);
                $this->smarty->assign("merchant_id", $this->merchant_details['user_id']);
                $this->view->selected = 'directpay';
                $this->view->title = 'Payment success';
                $this->view->render('header/guest');
                if ($this->view->standard_receipt == 1) {
                    $this->smarty->display(VIEW . 'patron/form/standard_success.tpl');
                    $this->view->render('footer/successWizard');
                } else {
                    $this->smarty->display(VIEW . 'patron/form/success.tpl');
                    $this->view->render('footer/merchantwebsite');
                }
            } else {
                $this->session->set('failed_transaction_id', $_POST['transaction_id']);
                $state_ = $this->session->get('seller_state_code');
                $param_ = $this->session->get('seller_param');
                $this->session->remove('seller_state_code');
                $this->session->remove('seller_param');
                $repath = $this->view->server_name . "/patron/form/submit/" . $this->encrypt->encode($result['request_id']);
                if (isset($state_)) {
                    $repath .= "/" . $state_;
                }
                if (isset($param_)) {
                    $repath .= "/" . $param_;
                }
                $message = $this->model->fetchMessage('p4');
                $message = str_replace('<Merchant Name>', $_POST['company_name'], $message);
                $message = str_replace('<URL>', $repath, $message);
                $message = str_replace('xxxx', $_POST['amount'] . '/-', $message);
                $response = $this->model->sendSMS($this->session->get('userid'), $message, $_POST['billing_mobile'], $merchant_id, 1, array());
                SwipezLogger::info(__CLASS__, 'SMS Sending to Patron Mobile: ' . $_POST['billing_mobile'] . ' Response:' . $response);
                $this->setError("Payment failed", 'Your payment attempt has failed. Transaction ref no "<b>' . $_POST['transaction_id'] . '</b>". Please re-try this payment');
                header('Location:' . $repath);
                die();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E046]Error while payment success initiate Error: for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function test()
    {
        $response['TransactionID'] = 'THGAH5454';
        $response['step1'] = '<h3 class="block">Your payment is successful</h3><h4 style="line-height: 1.7;">Copy the transaction reference number <span style="background-color: turquoise;"><b><abc1>__TRANSACTION_ID__</abc1></b></span><a href="javascript:;" class="btn btn-xs blue bs_growl_show" data-clipboard-action="copy" data-clipboard-target="abc1"><i class="fa fa-clipboard"></i> Copy</a> and paste this into the Amazon seller central screen.<br> Check <a href="https://www.swipez.in/assets/admin/pages/media/invoice/amazon-flow.png" class="iframe" >screen shot</a> to learn where to paste the transaction reference number.</h4><br><label><input type="checkbox" required="" name="confirm"> I have entered the Payment Ref Number in Amazon seller central</label><div style="color: red;" id="form_payment_error"></div>';
        $response['step2'] = '<h4 class="block">Dear Seller,<br><br>Thank you for making the payment towards your APOB registration. A representative from "__MERCHANT_NAME__"  will reach out to you and will help in registering Amazon warehouses as an APOB in your GSTIN portal.<br><br>Please sit back and relax as they complete your GSTN registration and guide you on uploading REG-14 form in the Amazon Seller central website. (Refer <a href="https://www.swipez.in/assets/admin/pages/media/invoice/amazon-screenshot.png" class="iframe" >screen shot</a> on, where to upload REG-14 link)<br><br>In case of any queries you could reach your chosen accountancy firm on <a>__MERCHANT_EMAIL__</a> & <a>__MERCHANT_MOBILE__</a></h4>';
        $response['mailer'] = '<tr><td><span style="font-size: 13px;width: 100%;"> 1. Copy the transaction reference number highlighted below and paste this into the Amazon seller central screen. Check <a href="https://www.swipez.in/assets/admin/pages/media/invoice/amazon-flow.png" >screen shot</a> to learn where to paste the transaction reference number.</span></td></tr><tr><td> <span style="font-size: 13px;width: 100%;">  2. Thank you for making the payment towards your APOB registration. A representative from "__MERCHANT_NAME__"  will reach out to you and will help in registering Amazon warehouses as an APOB in your GSTIN portal.Please sit back and relax as they complete your GSTN registration and guide you on uploading REG-14 form in the Amazon Seller central website. (Refer <a href="https://www.swipez.in/assets/admin/pages/media/invoice/amazon-screenshot.png">screen shot</a> on, where to upload REG-14 link) <br>In case of any queries you could reach your chosen accountancy firm on <a>__MERCHANT_EMAIL__</a> & <a>__MERCHANT_MOBILE__</a></span></td></tr>';
        $this->smarty->assign("response", $response);
        $this->smarty->assign("step1", '<h3 class="block">Your payment is successful</h3><h4 style="line-height: 1.7;">Copy the transaction reference number <span style="background-color: turquoise;"><b><abc1>__TRANSACTION_ID__</abc1></b></span><a href="javascript:;" class="btn btn-xs blue bs_growl_show" data-clipboard-action="copy" data-clipboard-target="abc1"><i class="fa fa-clipboard"></i> Copy</a> and paste this into the Amazon seller central screen.<br> Check <a href="https://www.swipez.in/assets/admin/pages/media/invoice/amazon-flow.png" class="iframe" >screen shot</a> to learn where to paste the transaction reference number.</h4><br><label><input type="checkbox" required="" name="confirm"> I have entered the Payment Ref Number in Amazon seller central</label><div style="color: red;" id="form_payment_error"></div>');
        $this->smarty->assign("step2", '<h4 class="block">Dear Seller,<br><br>Thank you for making the payment towards your APOB registration. A representative from "__MERCHANT_NAME__"  will reach out to you and will help in registering Amazon warehouses as an APOB in your GSTIN portal.<br><br>Please sit back and relax as they complete your GSTN registration and guide you on uploading REG-14 form in the Amazon Seller central website. (Refer <a href="https://www.swipez.in/assets/admin/pages/media/invoice/amazon-screenshot.png" class="iframe" >screen shot</a> on, where to upload REG-14 link)<br><br>In case of any queries you could reach your chosen accountancy firm on <a>__MERCHANT_EMAIL__</a> & <a>__MERCHANT_MOBILE__</a></h4>');
        $this->view->render('header/guest');
        $this->smarty->display(VIEW . 'patron/form/standard_success.tpl');
        $this->view->render('footer/successWizard');
    }

    function detail($link, $success = null)
    {
        $id = $this->encrypt->decode($link);
        $row = $this->common->getSingleValue('form_builder_transaction', 'id', $id);
        $column = array();
        $array[] = array('label' => 'Transaction Id', 'value' => $row['transaction_id']);
        $array[] = array('label' => 'Date', 'value' => $row['created_date']);
        $status = ($row['status'] == 1) ? 'Success' : 'Failed';
        $array[] = array('label' => 'Payment status', 'value' => $status);
        $column['transaction_id'] = 'Transaction Id';
        $column['date'] = 'Submit Date';
        $column['transaction_status'] = 'Payment status';
        $json_array = json_decode($row['json'], 1);
        foreach ($json_array as $json) {
            if ($json['display'] == 1 && $json['type'] != 'label') {
                $array[] = array('label' => $json['label'], 'value' => $json['value']);
                $column[$json['name']] = $json['label'];
            }

            if ($json['type'] == 'thankyoupage') {
                $this->smarty->assign("thankyoupage", $json);
            }
        }
        $this->smarty->assign("column", $column);
        $this->smarty->assign("success_msg", 0);
        if ($success != null) {
            $this->smarty->assign("success_msg", 1);
        }
        $this->smarty->assign("detail", $array);
        $this->view->render('header/guest');
        $this->smarty->display(VIEW . 'patron/form/detail.tpl');
        $this->view->render('footer/nonfooter');
    }

    function setHeader($json_array, $merchant_id)
    {
        require_once MODEL . 'merchant/LandingModel.php';
        $landing = new LandingModel();
        $this->merchant_details = $landing->getMerchantLandingDetails($merchant_id);
        $url = $this->merchant_details['display_url'];
        $instate = array();
        $outstate = array();
        $custom = array();

        $merchant_form = $this->common->getListValue('form_builder_request', 'merchant_id', $merchant_id, 1, ' and display_menu=1');
        $form_builder = array();
        foreach ($merchant_form as $frm) {
            $form_builder[] = array('name' => $frm['name'], 'key' => $this->encrypt->encode($frm['id']), 'link' => '/patron/form/submit/' . $this->encrypt->encode($frm['id']));
        }
        $this->view->form_builder = $form_builder;
        $instatetotaltax = 0;
        $outstatetotaltax = 0;

        foreach ($json_array as $row) {
            if ($row['type'] == 'setting') {

                $this->view->menu_home = ($url != '') ? $row['menu_home'] : 0;
                $this->view->menu_paybill = ($url != '') ? $row['menu_paybill'] : 0;
                $this->view->menu_package = ($url != '') ? $row['menu_package'] : 0;
                $this->view->menu_booking = ($url != '') ? $row['menu_booking'] : 0;
                $this->view->menu_policies = ($url != '') ? $row['menu_policies'] : 0;
                $this->view->menu_about = ($url != '') ? $row['menu_about'] : 0;
                $this->view->menu_home = ($url != '') ? $row['menu_contact'] : 0;
                $this->view->standard_receipt = (isset($row['standard_receipt'])) ? $row['standard_receipt'] : 0;
                $receipt_background_color = (isset($row['receipt_background_color'])) ? $row['receipt_background_color'] : '';
                $background_image = (isset($row['background_image'])) ? $row['background_image'] : '';
                $disable_payment = (isset($row['disable_payment'])) ? $row['disable_payment'] : 0;
                $this->smarty->assign("disable_payment", $disable_payment);
                $disable_tnc = (isset($row['disable_tnc'])) ? $row['disable_tnc'] : 0;
                $this->smarty->assign("disable_tnc", $disable_tnc);
                $this->view->disable_payment = $disable_payment;
                $this->view->disable_menu = (isset($row['disable_menu'])) ? $row['disable_menu'] : 0;
                $btn_background_color = (isset($row['btn_background_color'])) ? $row['btn_background_color'] : '';
                $coupon_enable = (isset($row['coupon_enable'])) ? $row['coupon_enable'] : 0;
                $onsubmit = (isset($row['onsubmit'])) ? $row['onsubmit'] : '';
                $this->smarty->assign("onsubmit", $onsubmit);
                $this->smarty->assign("receipt_background_color", $receipt_background_color);
                $this->smarty->assign("background_image", $background_image);
                $this->smarty->assign("btn_background_color", $btn_background_color);
                $this->smarty->assign("coupon_enable", $coupon_enable);
            } else if ($row['type'] == 'in_state_tax') {
                $instatetotaltax += $row['value'];
                $instate[] = $row['label'];
            } else if ($row['type'] == 'out_state_tax') {
                $outstatetotaltax += $row['value'];
                $outstate[] = $row['label'];
            } else if ($row['type'] == 'custom_tax') {
                $instatetotaltax += $row['value'];
                $outstatetotaltax += $row['value'];
                $custom[] = $row['label'];
            } else if ($row['type'] == 'receipt_text') {
                $this->smarty->assign("step1_content", $row['step1_content']);
                $this->smarty->assign("step2_content", $row['step2_content']);
                $this->view->mailer_content = $row['mailer_content'];
            } else if ($row['type'] == 'label') {
                $this->view->page_title = $row['label'];
            } else if ($row['type'] == 'footer') {
                $this->smarty->assign("footer", $row['value']);
            } else if ($row['type'] == 'web_redirect') {
                $this->redirect_url_array = $row['redirect_logic'];
                $this->redirect_method = $row['redirect_method'];
                if ($row['value'] != '') {
                    $this->redirect_url = $row['value'];
                }
            }
            if ($row['is_param'] == 1) {
                if ($row['param_number'] == 1) {
                    $this->param1 = $row['value'];
                }
                if ($row['param_number'] == 2) {
                    $this->param2 = $row['value'];
                }
            }
        }

        $instate_label = "";
        $outstate_label = "";
        foreach ($instate as $t) {
            if ($instate_label != '') {
                $instate_label .= " & " . $t;
            } else {
                $instate_label .= "Inclusive " . $t;
            }
        }
        foreach ($outstate as $t) {
            if ($outstate_label != '') {
                $outstate_label .= " & " . $t;
            } else {
                $outstate_label .= "Inclusive " . $t;
            }
        }

        foreach ($custom as $t) {
            if ($instate_label != '') {
                $instate_label .= " & " . $t;
            } else {
                $instate_label .= "Inclusive " . $t;
            }

            if ($outstate_label != '') {
                $outstate_label .= " & " . $t;
            } else {
                $outstate_label .= "Inclusive " . $t;
            }
        }


        $this->smarty->assign("instate_total_tax", $instatetotaltax);
        $this->smarty->assign("outstate_total_tax", $outstatetotaltax);
        $this->smarty->assign("instate_label", $instate_label);
        $this->smarty->assign("outstate_label", $outstate_label);
        $this->smarty->assign("gst_number", $this->merchant_details['gst_number']);
        $this->view->url = '/m/' . $this->merchant_details['display_url'];

        $this->view->logo = $this->merchant_details['logo'];
        $this->view->company_name = $this->merchant_details['company_name'];
    }

    function validateState($json_array, $param1 = null, $param2 = null)
    {
        if ($param1 == null) {
            return $json_array;
        } else {
            foreach ($json_array as $key => $row) {
                if ($row['type'] == 'web_redirect') {
                    if (isset($this->redirect_url_array[$param1])) {
                        $json_array[$key]['value'] = $this->redirect_url_array[$param1];
                    }
                }
                if ($row['is_param'] == 1) {
                    if ($row['param_number'] == 1) {
                        $json_array[$key]['value'] = $param1;
                    }
                    if ($row['param_number'] == 2) {
                        $json_array[$key]['value'] = $param2;
                    }
                }
            }
            return $json_array;
        }
    }

    function gstpassword($transaction_id)
    {
        if (!empty($_POST)) {
            $emailWrapper = new EmailWrapper();
            $subject = "Seller GSTIN password";
            $body_message = "Seller name: " . $_POST['name'] . " <br>Email: " . $_POST['email'] . " <br>Transaction ref number: " . $_POST['transaction_id'] . "<br>" . "GST number: " . $_POST['gst_number'] . "<br>" . "GSTIN passwprd: " . $_POST['gst_password'];
            $emailWrapper->sendMail($_POST['merchant_email'], "", $subject, $body_message, $body_message, $_POST['email']);
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'patron/form/gstsuccess.tpl');
            $this->view->render('footer/nonfooter');
            die();
        }

        $detail = $this->common->getSingleValue('form_builder_transaction', 'transaction_id', $transaction_id);
        $business_email = $this->common->getMerchantProfile($detail['merchant_id'], 0, 'business_email');
        $json = $detail['json'];
        $json_array = json_decode($json, 1);
        foreach ($json_array as $j) {
            $array[$j['name']] = $j['value'];
        }
        $this->smarty->assign("transaction_id", $transaction_id);
        $this->smarty->assign("detail", $array);
        $this->smarty->assign("merchant_email", $business_email);
        $this->view->render('header/guest');
        $this->smarty->display(VIEW . 'patron/form/gstpassword.tpl');
        $this->view->render('footer/nonfooter');
    }

    function tempstatus()
    {
        $data = file_get_contents('php://input');
        SwipezLogger::debug(__CLASS__, 'Response: ' . $data);
    }
}
