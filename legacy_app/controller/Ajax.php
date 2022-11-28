<?php

class Ajax extends Controller
{

    private $user_name = null;
    private $password = null;
    private $api_url = null;

    function __construct()
    {
        parent::__construct();
        $env = getenv('ENV');
        if ($env == 'PROD') {
            $this->api_url = "https://intapi.swipez.in";
            $this->user_name = 'tech@swipez.in';
            $this->password = '1kingSwan@53';
        } else {
            $this->api_url = "https://h7sak-api.swipez.in";
            $this->user_name = 'pareshhpatil@gmail.com';
            $this->password = '123456';
        }
    }

    function getCalendarJson($val)
    {
        try {
            $valid_ajax = $this->session->get('valid_ajax');
            if ($valid_ajax == 'calendarJson') {
                require_once MODEL . 'merchant/BookingsModel.php';
                $bookingModel = new BookingsModel();
                $val_array = explode(',', $val);
                $merchant_id = $val_array[0];
                $category_id = $val_array[1];
                $calendar_id = $val_array[2];
                if ($calendar_id == 0) {
                    $calendar_id = $bookingModel->getDefaultCalendar($merchant_id, $category_id);
                }
                $slots = $bookingModel->getCalendarSlots($merchant_id, $category_id, $calendar_id);
                if (!empty($slots)) {
                    foreach ($slots as $slot) {
                        $slotarray[] = array('start' => $slot['slot_date'], 'day' => $slot['slot_day'], 'month' => $slot['slot_month'], 'year' => $slot['slot_year'], 'title' => $slot['count_slot'] . ' Slots Available', 'calendar_id' => $slot['calendar_id']);
                    }
                } else {
                    $slotarray = array();
                }
                echo json_encode($slotarray);
            } else {
                echo 'Invalid';
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E003]Error while promotion august' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function getCalendar($cat_id, $merchant_id)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'calendarJson') {
            require_once MODEL . 'merchant/BookingsModel.php';
            $bookingModel = new BookingsModel();
            $cal_array = $bookingModel->getCategoryCalendar($merchant_id, $cat_id);
            echo json_encode($cal_array);
        }
    }

    function getcouponcode($id)
    {
        require_once UTIL . 'CouponDuniaAPI.php';
        $coupon = new CouponDuniaAPI();
        $response = $coupon->getCode($id);
        $re = json_decode($response, 1);
        if ($re['code'] == 400) {
            echo 'No Coupon Code Required!';
        } else {
            echo $re['code'];
        }
    }

    function getmembershipcost($id)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'bookingMembership') {
            $val = $this->common->getRowValue('amount', 'booking_membership', 'membership_id', $id);
            echo $val;
        }
    }

    function getCustomerDetail()
    {
        $_POST['customer_code'] = trim($_POST['customer_code']);
        $extra = '';
        if ($_POST['password_validation'] == 1) {
            if ($_POST['password'] == '') {
                echo 'false';
                die();
            }
            $_POST['password'] = trim($_POST['password']);
            $extra = " and password='" . $_POST['password'] . "'";
        }
        if ($this->session->get('last_customer_code') == $_POST['customer_code']) {
            echo 'false';
            die();
        }

        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'package_payment' && isset($_POST['customer_code'])) {
            $customer_id = $this->common->getRowValue('customer_id', 'customer', 'customer_code', $_POST['customer_code'], 1, " and merchant_id='" . $_POST['merchant_id'] . "'" . $extra);
            if (isset($customer_id)) {
                require_once MODEL . 'merchant/CustomerModel.php';
                $customerModel = new CustomerModel();
                $details = $customerModel->getCustomerDeatils($customer_id, $_POST['merchant_id']);
                echo json_encode($details);
            } else {
                $this->session->set('last_customer_code', $_POST['customer_code']);
                echo 'false';
            }
        }
    }

    function getmCategoryDates($cat_id, $merchant_id)
    {
        require_once MODEL . 'merchant/BookingsModel.php';
        $bookingModel = new BookingsModel();
        $cal_array = $bookingModel->getMerchantCategoryDates($merchant_id, $cat_id);
        $js_array = '[' . '"' . implode('","', $cal_array) . '"' . ']';
        echo $js_array;
    }

    function getCategoryDates($cat_id, $merchant_id)
    {
        require_once MODEL . 'merchant/BookingsModel.php';
        $bookingModel = new BookingsModel();
        $cal_array = $bookingModel->getCategoryDates($merchant_id, $cat_id);
        $js_array = '[' . implode(",", $cal_array) . ']';
        echo $js_array;
    }

    function getEncryptedAmount($amount, $id)
    {
        $array = $this->session->get('chapter_history');
        $key = array_search($id, $array);
        if (false !== $key) {
            unset($array[$key]);
        }
        $this->session->set('chapter_history', $array);
        $this->session->set('chapter_amount', $amount);
        $enc = $this->encrypt->encode($amount);
        echo $enc;
    }

    function savesequence()
    {
        $prefix = $_POST['prefix'];
        $number = $_POST['last_no'];
        $prefix = str_replace('~', '/', $prefix);
        require_once MODEL . 'merchant/ProfileModel.php';
        $profileModel = new ProfileModel();
        $res = $profileModel->existPrefix($this->merchant_id, $prefix);
        if ($res == FALSE) {
            $id = $profileModel->saveInvoiceNumber($this->user_id, $this->merchant_id, $prefix, $number);
            $response['name'] = $prefix . $number;
            $response['id'] = $id;
            $response['prefix'] = $prefix;
            $response['number'] = $number;
            $response['merchant_id'] = $this->merchant_id;
            $response['status'] = 1;
        } else {
            $response['error'] = 'Invoice prefix alredy exist';
            $response['status'] = 0;
        }
        echo json_encode($response);
    }

    function saveExpensesequence($type = 3)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'expense') {
            $id = 0;
            if ($type == 3) {
                $this->common->genericupdate('merchant_setting', 'expense_auto_generate', $_POST['auto_generate'], 'merchant_id', $this->merchant_id);
            } else if ($type == 4) {
                $this->common->genericupdate('merchant_setting', 'po_auto_generate', $_POST['auto_generate'], 'merchant_id', $this->merchant_id);
            } else if ($type == 5) {
                $this->common->genericupdate('merchant_setting', 'credit_note_auto_generate', $_POST['auto_generate'], 'merchant_id', $this->merchant_id);
            } else if ($type == 6) {
                $this->common->genericupdate('merchant_setting', 'debit_note_auto_generate', $_POST['auto_generate'], 'merchant_id', $this->merchant_id);
            } else if ($type == 7) {
                $this->common->genericupdate('merchant_setting', 'vendor_code_auto_generate', $_POST['auto_generate'], 'merchant_id', $this->merchant_id);
            }
            if ($_POST['auto_generate'] == 1) {
                require_once MODEL . 'merchant/ProfileModel.php';
                $profileModel = new ProfileModel();
                $prefix = $_POST['prefix'];
                $number = ($_POST['prefix_val'] > 0) ? $_POST['prefix_val'] : 0;
                if ($_POST['seq_id'] > 0) {
                    $id = $_POST['seq_id'];
                    $profileModel->updateInvoiceNumber($this->user_id, $this->merchant_id, $prefix, $number, $_POST['seq_id']);
                } else {
                    $id = $profileModel->saveInvoiceNumber($this->user_id, $this->merchant_id, $prefix, $number, $type);
                }
                $response['name'] = $prefix . $number;
                $response['prefix'] = $prefix;
                $response['number'] = $number;
            }
            $response['status'] = 1;
            $response['id'] = $id;
            $response['auto_generate'] = $_POST['auto_generate'];
            echo json_encode($response);
        }
    }

    function saveExpenseMaster($type)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'expense') {
            if (!empty($_POST['name'])) {
                require_once MODEL . 'merchant/ProfileModel.php';
                $profileModel = new ProfileModel();
                $id = $profileModel->saveExpenseMaster('expense_' . $type, $_POST['name'], $this->merchant_id, $this->user_id);
                $response['name'] = $_POST['name'];
                $response['id'] = $id;
                $response['status'] = 1;
                echo json_encode($response);
            } else {
                $haserror['status'] = 0;
                $haserror['error'] = 'The name field is required.';
                echo json_encode($haserror);
            }
        }
    }

    function getVendorGSTSTate($vendor_id, $table = 'vendor')
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'expense') {
            $type = 'inter';
            $detail = $this->common->getMerchantProfile($this->merchant_id);
            $merchant_gst_number = $detail['gst_number'];
            $merchant_state = $detail['state'];
            if ($table == 'vendor') {
                $vendor = $this->common->getSingleValue('vendor', 'vendor_id', $vendor_id);
            } else {
                $vendor = $this->common->getSingleValue('customer', 'customer_id', $vendor_id);
            }
            if ($merchant_gst_number != '' && $vendor['gst_number'] != '') {
                if (substr($merchant_gst_number, 0, 2) == substr($vendor['gst_number'], 0, 2)) {
                    $type = 'intra';
                }
            } else {
                if ($merchant_state == $vendor['state']) {
                    $type = 'intra';
                }
            }
            echo $type;
        }
    }

    function getCustomerInvoice($customer_id)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'expense') {
            $type = 'inter';
            $detail = $this->common->getMerchantProfile($this->merchant_id);
            $merchant_gst_number = $detail['gst_number'];
            $merchant_state = $detail['state'];
            $vendor = $this->common->getSingleValue('customer', 'customer_id', $customer_id);
            $invoices = $this->common->getListValue('payment_request', 'customer_id', $customer_id, 1, " and merchant_id='" . $this->merchant_id . "' and invoice_number<>'' and invoice_number is not null order by bill_date desc");
            if ($merchant_gst_number != '' && $vendor['gst_number'] != '') {
                if (substr($merchant_gst_number, 0, 2) == substr($vendor['gst_number'], 0, 2)) {
                    $type = 'intra';
                }
            } else {
                if ($merchant_state == $vendor['state']) {
                    $type = 'intra';
                }
            }

            foreach ($invoices as $row) {
                if ($row['invoice_number'] != '') {
                    $k = $row['invoice_number'];
                    $inv[$k]['id'] = $row['payment_request_id'];
                    $inv[$k]['invoice_number'] = $row['invoice_number'];
                    $inv[$k]['total'] = $row['grand_total'];
                    $date = new DateTime($row['bill_date']);
                    $bill_date = $date->format('d M Y');
                    $inv[$k]['bill_date'] = $bill_date;
                    $date = new DateTime($row['due_date']);
                    $bill_date = $date->format('d M Y');
                    $inv[$k]['due_date'] = $bill_date;
                }
            }

            $array['invoice'] = $inv;
            $array['type'] = $type;
            echo json_encode($array);
        }
    }

    function getFranchiseInvoiceDetail($id)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'expense') {
            if (strlen($id) == 10) {
                $summary = $this->common->getSingleValue('invoice_food_franchise_summary', 'payment_request_id', $id);
                $previous_due = $this->common->getRowValue('previous_due', 'payment_request', 'payment_request_id', $id);
                $sales = $this->common->getListValue('invoice_food_franchise_sales', 'payment_request_id', $id, 1);
                foreach ($sales as $k => $v) {
                    $sales[$k]['sale_date'] = $this->generic->htmlDate($v['date']);
                }
                $summary['previous_due'] = $previous_due;
                $array['summary'] = $summary;
                $array['sales'] = $sales;
                echo json_encode($array);
            }
        }
    }


    function getcgiCode($id)
    {
        $desc = $this->common->getRowValue('description', 'csi_code', 'code', $id, 0, " and merchant_id='" . $this->merchant_id . "'");
        echo $desc;
    }

    function templatetype($id)
    {
        $type = $this->common->getRowValue('template_type', 'invoice_template', 'template_id', $id, 0, " and merchant_id='" . $this->merchant_id . "'");
        echo $type;
    }

    function getSlotdetails($slot_id)
    {
        $details = $this->common->getSingleValue('booking_slots', 'slot_id', $slot_id);
        $package_details = $this->common->getSingleValue('booking_packages', 'package_id', $details["package_id"]);
        $details['package_name'] =  $package_details["package_name"];
        $details['slot_time_from'] = date('h:i A', strtotime($details['slot_time_from']));
        $details['slot_time_to'] = date('h:i A', strtotime($details['slot_time_to']));
        $details['slot_date'] = date('d M Y', strtotime($details['slot_date']));
        echo json_encode($details);
    }

    function getVendordetails($vendor_id)
    {
        $details = $this->common->getSingleValue('vendor', 'vendor_id', $vendor_id);
        $array['commision_type'] = $details['commision_type'];
        if ($details['commision_type'] == 1) {
            $array['commision_value'] = $details['commission_percentage'];
        } else {
            $array['commision_value'] = $details['commision_amount'];
        }
        echo json_encode($array);
    }

    function getCategoryCalendar()
    {
        $date = $_POST['date'];
        $category_id = $_POST['category_id'];
        if ($date != '') {
            $valid_ajax = $this->session->get('valid_ajax');
            if ($valid_ajax == 'calendarJson') {
                $date = str_replace('Space', '-', $date);
                $date = new DateTime($date);
                require_once MODEL . 'merchant/BookingsModel.php';
                $bookingModel = new BookingsModel();
                $cal_array = $bookingModel->getCategoryCourt($date->format('Y-m-d'), $category_id);
                echo json_encode($cal_array);
            }
        }
    }

    function getPackagesCalendar()
    {
        $date = $_POST['date'];
        $category_id = $_POST['category_id'];
        $calendar_id = $_POST['calendar_id'];
        if ($date != '') {
            $valid_ajax = $this->session->get('valid_ajax');
            if ($valid_ajax == 'calendarJson') {
                $date = str_replace('Space', '-', $date);
                $date = new DateTime($date);
                require_once MODEL . 'merchant/BookingsModel.php';
                $bookingModel = new BookingsModel();
                $packages = $bookingModel->getAllPackages($date->format('Y-m-d'), $calendar_id);
                echo json_encode($packages);
            }
        }
    }

    function getSlots($date, $calendar_id)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'calendarJson') {
            $date = str_replace('Space', '-', $date);
            require_once MODEL . 'merchant/BookingsModel.php';
            $bookingModel = new BookingsModel();
            $slots_array = $bookingModel->getSlots($date, $calendar_id);
            $int = 1;
            foreach ($slots_array as $slot) {
                $val .= '<label for="closeButton' . $int . '">
                        <input id="closeButton' . $int . '" onchange="calculateSlot();" title="' . $slot['slot_price'] . '" class="checker" type="checkbox" name="booking_slots[]"  value="' . $slot['slot_id'] . '">
                         From ' . $slot['time_from'] . ' To ' . $slot['time_to'] . '  Price:  ' . $slot['slot_price'] . '</label>';
                $int++;
            }
            $val .= '<input type="hidden" title="0" class="checker" name="booking_slots[]"  value="0">';
            $cal_array['date'] = $date;
            $cal_array['slots'] = $val;
            echo json_encode($cal_array);
        }
    }

    function getCategorySlots($date, $category_id)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'calendarJson') {
            $date = str_replace('Space', '-', $date);
            $date = new DateTime($date);
            require_once MODEL . 'merchant/BookingsModel.php';
            $bookingModel = new BookingsModel();
            $slots_array = $bookingModel->getCategorySlots($date->format('Y-m-d'), $category_id);
            foreach ($slots_array as $slot) {
                $array[] = $slot['time_from'] . ' To ' . $slot['time_to'];
            }
            echo json_encode($array);
        }
    }

    function validatedriverdetails()
    {

        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'formBuilder') {
            if ($this->env == 'PROD') {
            } else {
                $url = "https://cityconnect.azurewebsites.net/api/VerifyDriverForPayment";
            }

            $array['firstname'] = $_POST['customer_name'];
            $array['email'] = $_POST['email'];
            $array['phonenumber'] = $_POST['mobile'];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($array),
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            echo $response;
        }
    }

    function getCoveringDetail($id)
    {
        $array = $this->common->getSingleValue('covering_note', 'covering_id', $id, 1, "and merchant_id='" . $this->merchant_id . "'");
        $array['template_name'] = 'Random' . time();
        echo json_encode($array);
    }

    function getShareDetail($id)
    {
        $array = $this->common->getSingleValue('swipez_products', 'product_id', $id);
        echo '<div class="col-md-3 center"><a href="mailto:?Subject=' . $array['title'] . '&amp;Body=' . $array['description'] . ' More Info: ' . $array['share_link'] . '"><img src="/assets/admin/layout/img/email.png" alt="Email" /></a></div><div class="col-md-3 center"><a href="http://www.facebook.com/sharer.php?u=' . $array['share_link'] . '" target="_blank"><img src="/assets/admin/layout/img/facebook.png" alt="Facebook" /></a></div><div class="col-md-3 center"><a href="https://plus.google.com/share?url=' . $array['share_link'] . '" target="_blank"><img src="/assets/admin/layout/img/google.png" alt="Google" /></a></div><div class="col-md-3 center"><a href="https://api.whatsapp.com/send?text=' . $array['title'] . '.  ' . $array['description'] . ' More info: ' . $array['share_link'] . '"  target="_blank"><img style="max-height: 64px;" src="/assets/admin/layout/img/whatsapp.jpg" alt="Yummly" /></a></div>';
    }

    function getTransferDetail($id)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'expense') {
            $array = $this->common->getSingleValue('vendor_transfer', 'transfer_id', $id, 0, " and merchant_id='" . $this->session->get('merchant_id') . "'");
            if (!empty($array)) {
                $array['transfer_date'] = date('d M Y', strtotime($array['transfer_date']));
                echo json_encode($array);
            }
        }
    }

    function updatepassword($id, $password)
    {
        try {
            $valid_ajax = $this->session->get('valid_ajax');
            if ($valid_ajax == 'custlogins') {
                $user_id = $this->encrypt->decode($id);
                if (strlen($user_id) == 10 && substr($user_id, 0, 1) == 'U') {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $this->common->genericupdate('user', 'password', $password_hash, 'user_id', $user_id);
                    $this->common->genericupdate('customer', 'password', $password, 'user_id', $user_id);
                    echo 'success';
                    die();
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E003]Error while update customer password' . $e->getMessage());
        }
    }

    function getPostJson()
    {
        echo json_encode($_POST);
    }

    function testrequest()
    {
        SwipezLogger::debug(__CLASS__, 'Cashfree Response ' . json_encode($_POST));
    }

    public function checkstatus()
    {
        $status = $this->session->get('gloss_status');
        if ($status == 0) {
            $this->session->set('gloss_status', 1);
        }
        echo $status;
    }

    public function shorturl($long_url)
    {
        if (preg_match('#^https?://#i', $long_url) != 1) {
            require_once CONTROLLER . 'Notification.php';
            $notification = new Notification();
            $shortUrl = $notification->saveShortUrl($long_url);
            echo $shortUrl;
        } else {
            echo 'invalid';
        }
    }

    public function updateexpiry()
    {
        try {
            $expiry_date = $_POST['expiry_date'];
            $expiry_date = $this->generic->sqlDate($expiry_date);
            $service_id = $this->encrypt->decode($_POST['service_id']);
            if ($service_id > 0) {
                $this->common->genericupdate('customer_service', 'expiry_date', $expiry_date, 'id', $service_id);
                echo 'success';
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E003+6]Error while update set top box expiry' . $e->getMessage());
        }
    }

    function validateURL($long_url)
    {
        $is_valid = 1;
        $long_url = filter_var($long_url, FILTER_SANITIZE_URL);
        if (filter_var($long_url, FILTER_VALIDATE_URL) !== false) {
            if (strpos($long_url, 'go.swipez.in') !== false) {
                $result['error'] = 'That is already a short link';
                $is_valid = 0;
            }
            if (strpos($long_url, 'shr.swipez.in') !== false) {
                $result['error'] = 'That is already a short link';
                $is_valid = 0;
            }
        } else {
            $result['error'] = 'Invalid Link';
            $is_valid = 0;
        }
        if ($is_valid == 0) {
            $result['status'] = 0;
            echo json_encode($result);
            die();
        }
    }

    public function getshorturl($is_mobile = 0)
    {
        try {
            $valid_ajax = $this->session->get('valid_ajax');
            if ($valid_ajax == 'short_url') {
                $long_url = $_POST['long_url'];
                $this->validateURL($long_url);
                $token = $this->session->getCookie('short_url_token');
                if ($token == false) {
                    $token = $this->generateToken();
                    $this->session->setCookie('short_url_token', $token, 1);
                }
                $post_string = 'long_url=' . $long_url;
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->api_url . '/api/getshorturl',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $post_string,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer " . $token
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if ($err != '') {
                    Sentry\captureMessage('Short URL Api error ' . $err);
                    SwipezLogger::error(__CLASS__, 'Short URL Api error ' . $err);
                } else {
                    //SwipezLogger::debug(__CLASS__, 'Short URL Response ' . $response);
                }
                $array = json_decode($response, 1);
                if ($array['status'] == 0) {
                    $result['status'] = 0;
                    $result['error'] = $array['error'];
                    echo json_encode($result);
                    die();
                }
                $div = $this->getShortUrlData($long_url, $array['short_url'], $is_mobile);
                $result['status'] = 1;
                $result['short_url'] = $array['short_url'];
                $result['long_url'] = $long_url;
                $result['div'] = $div;
                echo json_encode($result);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E003+6]Error while get Short URL' . $e->getMessage());
        }
    }

    function getShortUrlData($long_url, $short_url, $is_mobile)
    {
        $this->session->set('short_url_mobile', $is_mobile);
        $url_list = $this->session->getArrayCookie('short_url_list');
        $ind = 1;
        if ($long_url != '') {
            $new_array[0] = array('long_url' => $long_url, 'short_url' => $short_url);
            $ind = 0;
        }
        if (empty($url_list)) {
        } else {
            if ($long_url == '') {
                if (isset($url_list[0])) {
                    $new_array[0] = $url_list[0];
                }
            }
            if (isset($url_list[$ind])) {
                $new_array[1] = $url_list[$ind];
            }
            if (isset($url_list[$ind + 1])) {
                $new_array[2] = $url_list[$ind + 1];
            }
        }
        if (!empty($new_array) && $long_url != '') {
            $this->session->setArrayCookie('short_url_list', $new_array);
        }
        $total = count($new_array);
        $int = 1;
        $div = '';
        if ($is_mobile == 1) {
            $max = 30;
        } else {
            $max = 60;
        }
        foreach ($new_array as $arr) {
            if (strlen($arr['long_url']) > $max) {
                $_url = substr($arr['long_url'], 0, $max) . '...';
            } else {
                $_url = $arr['long_url'];
            }
            $div .= '<div class="row no-margin no-padding"><div class="col-md-7 no-padding"><label class="pull-left"><h4 style="margin-top: 25px; ">' . $_url . '</h4></label></div><div class="col-md-3"><h4 style="margin-top: 25px; "><a><short' . $int . '>' . $arr['short_url'] . '</short' . $int . '></a></h4></div><div class="col-md-2"><button style="margin-top: 20px; " class="btn default pull-right bs_growl_show" data-clipboard-action="copy" data-clipboard-target="short' . $int . '">Copy</button></div></div>';
            if ($int != $total) {
                $div .= '<hr style="margin-top: 10px; margin-bottom: 0px;">';
            }
            $int++;
        }
        return $div;
    }

    public function generateToken()
    {
        try {
            $post_data['email'] = $this->user_name;
            $post_data['password'] = $this->password;
            foreach ($post_data as $key => $value) {
                $post_items[] = $key . '=' . $value;
            }
            $post_string = implode('&', $post_items);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->api_url . '/api/login',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_string,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err != '') {
                Sentry\captureMessage('Generate Token error ' . $err);
                SwipezLogger::error(__CLASS__, 'Generate Token error ' . $err);
            } else {
                //SwipezLogger::debug(__CLASS__, 'API Response ' . $response);
            }
            $array = json_decode($response, 1);
            return $array['success']['token'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E003+6]Error while update set top box expiry' . $e->getMessage());
        }
    }

    public function getbeneficiaryList($type)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'beneficiary') {
            $data = array();
            switch ($type) {
                case 'Customer':
                    $data = $this->common->querylist("select customer_id as id, concat(customer_code,' | ',first_name,' ',last_name) as name from customer where is_active=1 and merchant_id='" . $this->merchant_id . "'");
                    break;
                case 'Vendor':
                    $data = $this->common->querylist("select vendor_id as id, vendor_name as name from vendor where is_active=1 and merchant_id='" . $this->merchant_id . "'");
                    break;
                case 'Franchise':
                    $data = $this->common->querylist("select franchise_id as id, franchise_name as name from franchise where is_active=1 and merchant_id='" . $this->merchant_id . "'");
                    break;
            }
            if (!empty($data)) {
                echo json_encode($data);
            }
        }
    }

    public function getbeneficiaryDetail($type, $id)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'beneficiary') {
            $data = array();
            switch ($type) {
                case 'Customer':
                    $data = $this->common->querylist("select email,mobile,address,city,state,zipcode, concat(first_name,' ',last_name) as name,customer_code as code from customer where customer_id=" . $id . " and merchant_id='" . $this->merchant_id . "'");
                    break;
                case 'Vendor':
                    $data = $this->common->querylist("select email_id as email,mobile,address,city,state,zipcode, vendor_name as name,vendor_code as code from vendor where vendor_id=" . $id . " and merchant_id='" . $this->merchant_id . "'");
                    break;
                case 'Franchise':
                    $data = $this->common->querylist("select email_id as email,mobile,address, franchise_name as name,franchise_code as code from franchise where franchise_id=" . $id . " and merchant_id='" . $this->merchant_id . "'");
                    break;
            }
            echo json_encode($data);
        }
    }

    public function saveautoplan()
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'auto_collect') {
            $post_string = json_encode($_POST);
            $result = $this->generic->APIrequest('v1/autocollect/plan/save', $post_string);
            $array = json_decode($result, 1);
            if ($array['status'] == 0) {
                if (is_array($array['error'])) {
                    $errors = $array['error'];
                } else {
                    $errors[] = $array['error'];
                }
                foreach ($errors as $er) {
                    $error_msg .= $er . '<br>';
                }
                $data['status'] = 0;
                $data['errors'] = $error_msg;
            } else {
                $data['status'] = 1;
                $data['plan_id'] = $array['plan_id'];
                $data['plan_name'] = $_POST['name'] . ' | ' . $_POST['amount'];
            }
            echo json_encode($data);
        }
    }

    public function gstinfo($gst_number)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'profile') {
            if (preg_match('/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([A-Za-z]){2}?$/', $gst_number)) {
                $iris_data = $this->common->getListValue('config', 'config_type', 'IRIS_GST_DATA');
                require_once UTIL . 'IRISAPI.php';
                $iris = new IRISAPI($iris_data);
                $GSTInfo = $iris->getGSTInfo($gst_number);
                $state_code = substr($gst_number, 0, 2);
                if (substr($state_code, 0, 1) == '0') {
                    $state_code = substr($state_code, 1, 1);
                }
                $state = $this->common->getRowValue('config_value', 'config', 'config_type', 'gst_state_code', 0, ' and config_key=' . $state_code);
                $info = $GSTInfo['response'];
                if ($info['status_code'] == 1) {
                    $array['status'] = 1;
                    $array['company_name'] = ($info['tradename'] != '') ? $info['tradename'] : $info['name'];
                    $name = explode(" ", $info['name']);
                    $array['first_name'] = $name[0];
                    $array['last_name'] = $name[count($name) - 1];
                    $array['address'] = $info['pradr']['bno'] . ',' . $info['pradr']['st'] . ',' . $info['pradr']['loc'];
                    $array['company_type'] = $info['constitution'];
                    $array['city'] = $info['pradr']['loc'];
                    $array['state'] = $state;
                    $array['zipcode'] = $info['pradr']['pncd'];
                    $array['pan'] = substr($gst_number, 2, 10);
                    if ($info['constitution'] == 'Private Limited Company') {
                        $array['entity_type'] = 2;
                    } else if ($info['constitution'] == 'Proprietorship') {
                        $array['entity_type'] = 4;
                    } else if ($info['constitution'] == 'Limited Liability Partnership') {
                        $array['entity_type'] = 3;
                    } else if (strpos($info['constitution'], 'Partnership') !== false) {
                        $array['entity_type'] = 3;
                    }
                } else if ($info['gstin'] != $gst_number) {
                    $array['status'] = 2;
                    $toEmail_ = array('paresh@swipez.in', 'shuhaid.lambe@swipez.in');
                    $emailWrapper = new EmailWrapper();
                    $body_message = "GST lookup failed for Merchant id: " . $this->merchant_id;
                    foreach ($toEmail_ as $email) {
                        $emailWrapper->sendMail($email, "", "GST lookup failed", $body_message, $body_message);
                    }
                } else {
                    $array['status'] = 0;
                }
                echo json_encode($array);
            } else {
                $array['status'] = 0;
                echo json_encode($array);
            }
        }
    }

    public function getgstinvoiceseq($id)
    {
        $array['id'] = '';
        $array['state'] = '';
        $merchant_id = $this->session->get('merchant_id');
        if ($merchant_id == true) {
            $row = $this->common->getSingleValue('merchant_billing_profile', 'id', $id);
            $seq = $row['invoice_seq_id'];
            if ($seq > 0) {
                $prefix = $this->common->getRowValue('prefix', 'merchant_auto_invoice_number', 'auto_invoice_id', $seq);
                $array['id'] = $prefix;
            }
            $array['state'] = $row['state'];
        }
        echo json_encode($array);
    }

    public function getbillingprofilecurrency($id)
    {
        $array['id'] = '';
        $array['state'] = '';
        $merchant_id = $this->session->get('merchant_id');
        if ($merchant_id == true) {
            $row = $this->common->getSingleValue('merchant_billing_profile', 'id', $id);
            if ($row['currency'] != '') {
                echo $row['currency'];
            } else {
                $currency = $this->session->get('currency');
                echo json_encode($currency);
            }
        }
    }

    public function getprofileinfo($id)
    {
        $valid_ajax = $this->session->get('valid_ajax');
        $array = array();
        if ($valid_ajax == 'template') {
            $merchant = $this->common->getMerchantProfile($this->merchant_id, $id);
            $merchant_website = $this->common->getRowValue('merchant_website', 'merchant', 'merchant_id', $this->merchant_id);
            $array[9] = $merchant['company_name'];
            $array[10] = $merchant['business_contact'];
            $array[11] = $merchant['business_email'];
            $array[12] = $merchant['address'];
            $array[13] = $merchant_website;
            $array[14] = $merchant['pan'];
            $array[15] = $merchant['gst_number'];
            $array[16] = $merchant['tan'];
            $array[61] = $merchant['cin_no'];
            $array['seq'] = $merchant['invoice_seq_id'];
        }
        echo json_encode($array);
    }

    public function getTaxCalculatedOn($tax_id)
    {
        $array['tax_calculated_on'] = '';
        $array['tax_type'] = '';
        $merchant_id = $this->session->get('merchant_id');
        if ($merchant_id == true) {
            $row = $this->common->getSingleValue('merchant_tax', 'tax_id', $tax_id);
            $array['tax_calculated_on'] = $row['tax_calculated_on'];
            $array['tax_type'] = $row['tax_type'];
        }
        echo json_encode($array);
    }

    public function getCountryCode()
    {
        $response = array();
        if (isset($_POST['country_name']) && !empty($_POST['country_name'])) {
            $country_name = $_POST['country_name'];
            $country = $this->common->getRowValue('description', 'config', 'config_type', 'country_name', 0, " and config_value='" . $country_name . "'");
            if ($country != '') {
                $response['country_code'] = $country;
                $response['status'] = '1';
            } else {
                $response['status'] = '0';
            }
        } else {
            $response['status'] = '0';
        }
        echo json_encode($response);
    }
}
