<?php

use App\Jobs\SupportTeamNotification;
use Illuminate\Support\Facades\Redis;

include_once SWIPEZ_UTIL_PATH . '/src/shorturl/SwipezShortURLWrapper.php';

/**
 * Dashboard controller class to handle dashboard requests for merchant
 */
class Event extends Controller
{


    function __construct()
    {
        parent::__construct();

        //TODO : Check if using static function is causing any problems!
        $this->validateSession('merchant', 4);
        $this->view->js = array('event');
        $this->view->selectedMenu = 'event';
    }

    public function viewlist()
    {
        try {
            $this->hasRole(1, 7);
            $this->view->selectedMenu = array(8, 36);
            $merchant_id = $this->session->get('merchant_id');
            $last_date = $this->getLast_date();
            $current_date = date('d M Y');
            $status = 1;
            if (isset($_POST['status'])) {
                $status = $_POST['status'];
            }
            $this->smarty->assign("title", "Events list");
            $this->smarty->assign("is_filter", "True");
            $this->smarty->assign("status", $status);
            $this->smarty->assign("company_name", $this->session->get('company_name'));
            $this->smarty->assign("has_franchise", $this->session->get('has_franchise'));
            $sub_franchise_id = $this->session->get('sub_franchise_id');
            $requestlist = $this->model->getEventList($this->user_id, $status, $sub_franchise_id);
            $requestlist = $this->generic->getEncryptedList($requestlist, 'encrypted_id', 'event_request_id');
            $requestlist = $this->generic->getDateFormatList($requestlist, 'created_at', 'created_date');
            $this->smarty->assign("requestlist", $requestlist);
            $this->view->title = 'Events list';

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Events', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->hide_first_col = true;
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/event/list.tpl');
            $this->view->datatablejs = 'table-small';
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E007]Error while payment request list initiate Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function create()
    {
        $this->hasRole(2, 7);
        $this->validatePackage();
        $user_id = $this->session->get('userid');
        require MODEL . 'merchant/CouponModel.php';
        $couponmodel = new CouponModel();
        $pgdetails = $this->common->getPGDetails($user_id);
        $coupons = $couponmodel->getActiveCoupon($this->merchant_id);
        $this->smarty->assign("coupons", $coupons);
        $this->smarty->assign("pg", $pgdetails);
        $this->smarty->assign("event_type", 'event');
        if ($this->session->get('has_franchise') == 1) {
            $franchise_list = $this->common->getListValue('franchise', 'merchant_id', $this->merchant_id, 1, 'and status=1');
            $this->smarty->assign("franchise_list", $franchise_list);
        }
        if ($this->session->get('vendor_enable') == 1) {
            $vendor_list = $this->common->getListValue('vendor', 'merchant_id', $this->merchant_id, 1, 'and status=1');
            $this->smarty->assign("vendor_list", $vendor_list);
        }
        $structure_column = $this->getCaptureColumn();
        $this->smarty->assign("structure_column", $structure_column);
        $this->smarty->assign("structure_column_json", json_encode($structure_column));

        $this->smarty->assign("type", 1);
        $this->smarty->assign("currency", $this->session->get('currency'));
        $this->view->title = "Create an event";
        $this->smarty->assign("title", $this->view->title);
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Events', 'url' => ''),
            array('title' => $this->view->title, 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->selectedMenu = array(8, 35);
        $this->view->js = array('event', 'booking');
        $this->view->header_file = ['create_event'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/event/create_event.tpl');
        $this->smarty->display(VIEW . 'merchant/event/footer.tpl');
        if (env('ENV') != 'LOCAL') {
            //menu list
            $mn1 = Redis::get('merchantMenuList' . $this->merchant_id);
            $item_list = json_decode($mn1, 1);
            $row_array['name'] = 'Event Page';
            $row_array['link'] = '/merchant/event/create';
            $item_list[] = $row_array;
            Redis::set('merchantMenuList' . $this->merchant_id, json_encode($item_list));
        }


        $this->view->render('footer/create_event');
    }

    function getCaptureColumn()
    {
        $customer_column_list = $this->common->getListValue('customer_column_metadata', 'merchant_id', $this->merchant_id, 1);
        $structure_column[] = array('column_name' => 'Name', 'datatype' => 'text', 'type' => 'system', 'name' => 'name', 'selected' => 1, 'mandatory' => 1, 'position' => 'L', 'disable' => 1);
        $structure_column[] = array('column_name' => 'Email', 'datatype' => 'email', 'type' => 'system', 'name' => 'email', 'selected' => 1, 'mandatory' => 1, 'position' => 'L', 'disable' => 1);
        $structure_column[] = array('column_name' => 'Mobile', 'datatype' => 'mobile', 'type' => 'system', 'name' => 'mobile', 'selected' => 1, 'mandatory' => 1, 'position' => 'L', 'disable' => 1);
        $structure_column[] = array('column_name' => 'Address', 'datatype' => 'textarea', 'type' => 'system', 'name' => 'address', 'selected' => 1, 'mandatory' => 0, 'position' => 'R');
        $structure_column[] = array('column_name' => 'City', 'datatype' => 'text', 'type' => 'system', 'name' => 'city', 'selected' => 1, 'mandatory' => 0, 'position' => 'R');
        $structure_column[] = array('column_name' => 'State', 'datatype' => 'text', 'type' => 'system', 'name' => 'state', 'selected' => 0, 'mandatory' => 0, 'position' => 'R');
        $structure_column[] = array('column_name' => 'Zipcode', 'datatype' => 'text', 'type' => 'system', 'name' => 'zipcode', 'selected' => 1, 'mandatory' => 0, 'position' => 'R');
        foreach ($customer_column_list as $key => $row) {
            $structure_column[] = array('column_name' => $row['column_name'], 'datatype' => $row['column_datatype'], 'type' => 'custom', 'name' => $row['column_id'], 'selected' => 0, 'mandatory' => 0);
        }
        return $structure_column;
    }

    function update($link)
    {
        try {
            $this->hasRole(2, 7);
            if (!isset($link)) {
                $this->setInvalidLinkError();
            }
            $user_id = $this->session->get('userid');
            $pgdetails = $this->common->getPGDetails($user_id);
            $payment_request_id = $this->encrypt->decode($link);
            $info = $this->common->getPaymentRequestDetails($payment_request_id, NULL, 2);
            if ($info['merchant_id'] != $this->merchant_id) {
                $this->setInvalidLinkError();
            }

            if ($this->session->get('has_franchise') == 1) {
                $franchise_list = $this->common->getListValue('franchise', 'merchant_id', $this->merchant_id, 1, 'and status=1');
                $this->smarty->assign("franchise_list", $franchise_list);
            }
            if ($this->session->get('vendor_enable') == 1) {
                $vendor_list = $this->common->getListValue('vendor', 'merchant_id', $this->merchant_id, 1, 'and status=1');
                $this->smarty->assign("vendor_list", $vendor_list);
            }
            $rows = $this->model->getInvoiceBreakup($payment_request_id);
            $package = $this->common->getListValue('event_package', 'event_request_id', $payment_request_id, 1);
            $occurence = $this->common->getListValue('event_occurence', 'event_request_id', $payment_request_id, 1);
            $comma = '';
            $occurence_text = '';
            foreach ($occurence as $occ) {
                if ($occ['start_time'] != '') {
                    $text = $occ['start_date'] . ' ' . substr($occ['start_time'], 0, 5);
                } else {
                    $text = $occ['start_date'];
                }
                $total_occurence[] = $text;
            }

            foreach ($package as $key => $pkg) {
                if (!in_array($pkg['category_name'], $total_cat) && $pkg['category_name'] != '') {
                    $total_cat[] = $pkg['category_name'];
                }
                $package[$key]['occurence_array'] = explode(',', $pkg['occurence']);
                $package[$key]['currency_price'] = json_decode($pkg['currency_price'], 1);
            }
            $payee_capture = json_decode($info['payee_capture'], 1);
            $attendees_capture = json_decode($info['attendees_capture'], 1);
            //$this->dd($payee_capture);
            $structure_column = $this->getCaptureColumn();
            $this->smarty->assign("payee_capture", $payee_capture);
            $this->smarty->assign("attendees_capture", $attendees_capture);
            $this->smarty->assign("structure_column", $structure_column);
            $this->smarty->assign("structure_column_json", json_encode($structure_column));
            require MODEL . 'merchant/CouponModel.php';
            $couponmodel = new CouponModel();
            $coupons = $couponmodel->getActiveCoupon($this->merchant_id);
            $this->smarty->assign("total_cat", $total_cat);
            $this->smarty->assign("total_occurence", $total_occurence);
            $this->smarty->assign("occurence_text", $occurence_text);
            $this->smarty->assign("coupons", $coupons);
            $this->smarty->assign("pg", $pgdetails);
            $this->smarty->assign("eventlist", $rows);
            $this->smarty->assign("info", $info);
            $this->smarty->assign("package", $package);
            $this->smarty->assign("occurence", $occurence);
            $this->smarty->assign("link", $link);
            $this->smarty->assign("payment_request_id", $link);
            $this->smarty->assign("stop_booking_time", explode(':', $info['stop_booking_time']));
            $this->smarty->assign("currency", $this->session->get('currency'));
            $this->smarty->assign("event_currency", json_decode($info['currency'], 1));

            if ($info['event_type'] == 2) {
                $this->smarty->assign("display_type", 'Campaign');
            } else {
                $this->smarty->assign("display_type", 'Event');
            }
            $title = 'Event';
            $display_booking = 'Seat';
            $this->view->title = 'Update ' . $title;
            $this->smarty->assign("title", $this->view->title);
            $this->smarty->assign("display_booking", $display_booking);

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Events', 'url' => ''),
                array('title' => 'Events list', 'url' => '/merchant/event/viewlist'),
                array('title' => 'Update event', 'url' => '/merchant/event/update/' . $link),
                array('title' => $info['event_name'], 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end
            $this->view->selectedMenu = array(8, 35);
            $this->view->js = array('event', 'booking');
            $this->view->header_file = ['create_event', 'profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/event/update_event.tpl');
            $this->smarty->display(VIEW . 'merchant/event/footer.tpl');
            $this->view->render('footer/create_event');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E005]Error while sending payment request Error: for merchant [' . $merchant_id . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function convertJsonArray($row)
    {
        $string = '{';
        $row = str_replace('{', '', $row);
        $row = str_replace('}', '', $row);
        $array = explode(',', $row);
        foreach ($array as $val) {
            $array2 = explode(':', $val);
            $string .= '"' . $array2[0] . '":"' . $array2[1] . '",';
        }
        $json = substr($string, 0, -1) . '}';
        return json_decode($json, 1);
    }

    function setCapturedetails()
    {
        $payee_details = array();
        foreach ($_POST['cpdl'] as $row) {
            $array = $this->convertJsonArray($row);
            unset($array['selected']);
            $array['position'] = 'L';
            $payee_details[] = $array;
        }
        foreach ($_POST['cpdr'] as $row) {
            $array = $this->convertJsonArray($row);
            unset($array['selected']);
            $array['position'] = 'R';
            $payee_details[] = $array;
        }

        $attendees_details = array();
        foreach ($_POST['cadl'] as $row) {
            $array = $this->convertJsonArray($row);
            unset($array['selected']);
            $array['position'] = 'L';
            $attendees_details[] = $array;
        }
        foreach ($_POST['cadr'] as $row) {
            $array = $this->convertJsonArray($row);
            unset($array['selected']);
            $array['position'] = 'R';
            $attendees_details[] = $array;
        }
        $_POST['capture_payee_details'] = json_encode($payee_details);
        $_POST['capture_attendeed_details'] = json_encode($attendees_details);
    }

    function saved()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location:/merchant/event/create');
            }
            $this->setCapturedetails();
            ini_set('display_errors', 1);
            # simple event
            if ($_POST['occurence'] == 0) {
                $_POST['from_date'][] = date('Y-m-d');
                $_POST['to_date'][] = date('Y-m-d');
            }
            $this->generic->setEmptyArray(array('column', 'columnvalue', 'position', 'is_mandatory', 'datatype'));

            require CONTROLLER . 'merchant/Templatevalidator.php';
            $validator = new Templatevalidator($this->model);
            $validator->validateEventSave($this->session->get('userid'));
            $error = $validator->fetchErrors();
            if ($error != FALSE) {
                $hasErrors[] = $error;
            }

            $stop_booking_string = $_POST['stop_booking_day'] . ':' . $_POST['stop_booking_hour'] . ':' . $_POST['stop_booking_min'];
            $int = 0;
            foreach ($_POST['package_name'] as $package) {
                $_POST['_package_name'] = $package;
                $_POST['_unitcost'] = $_POST['unitcost'][$int];
                $_POST['_tax'] = $_POST['tax'][$int];
                $_POST['tax'][$int] = ($_POST['tax'][$int] > 0) ? $_POST['tax'][$int] : 0;
                $pkg_int = $_POST['package_int'][$int];
                $date_array = array();
                if ($_POST['package_type' . $pkg_int][0] == 2) {
                    $_POST['package_type'][] = 2;
                } else {
                    foreach ($_POST['multi_occurence' . $pkg_int] as $date) {
                        $sdate = new DateTime($date);
                        $date_array[] = $sdate->format('Y-m-d H:i');
                    }
                    $_POST['package_type'][] = 1;
                }
                if ($_POST['occurence'] == 0) {
                    $_POST['package_occurence'][$int] = date('Y-m-d');
                } else {
                    $_POST['package_occurence'][$int] = implode(',', $date_array);
                }

                $validator->validateEventPackage();
                $error = $validator->fetchErrors();
                if ($error != FALSE) {
                    $hasErrors[] = $error;
                }
                $int++;
            }
            if ($hasErrors == false) {
                $start_date = array();
                foreach ($_POST['from_date'] as $date) {
                    $sdate = new DateTime($date);
                    $start_date[] = $sdate->format('Y-m-d');
                }
                $end_date = array();
                foreach ($_POST['to_date'] as $date) {
                    $sdate = new DateTime($date);
                    $end_date[] = $sdate->format('Y-m-d');
                }
                $start_time = array();
                foreach ($_POST['from_time'] as $date) {
                    $sdate = new DateTime($date);
                    $start_time[] = $sdate->format('H:i');
                }
                $end_time = array();
                foreach ($_POST['to_time'] as $date) {
                    $sdate = new DateTime($date);
                    $end_time[] = $sdate->format('H:i');
                }
                if (empty($_POST['currency'])) {
                    $_POST['currency'] = array('INR');
                }
                $currency_count = count($_POST['currency']);
                $intp = 0;
                foreach ($_POST['package_int'] as $key => $int) {
                    $cost_array = array();
                    for ($i = 0; $i < $currency_count; $i++) {
                        if ($i == 0) {
                            $unitcost[] = $_POST['unitcost'][$intp];
                        }
                        $cost_array[$_POST['package_currency'][$intp]]['price'] = $_POST['unitcost'][$intp];
                        $cost_array[$_POST['package_currency'][$intp]]['min_price'] = $_POST['min_price'][$intp];
                        $cost_array[$_POST['package_currency'][$intp]]['max_price'] = $_POST['max_price'][$intp];
                        $intp++;
                    }
                    $currency_amount[] = json_encode($cost_array);
                }

                $_POST['short_description'] = substr($_POST['short_description'], 0, 300);
                $this->generic->setZeroValue(array('is_capture', 'mobile_capture', 'age_capture', 'coupon_code', 'franchise_id', 'vendor_id'));
                $result = $this->model->saveEvent($this->user_id,  $this->merchant_id, $_POST['event_name'], $_POST['title'], $_POST['short_description'], $_POST['venue'], $_POST['description'], $_POST['duration'], $_POST['occurence'], $_POST['column'], $_POST['columnvalue'], $_POST['is_mandatory'], $_POST['datatype'], $_POST['position'], $_FILES["banner"], $start_date, $end_date, $start_time, $end_time, $_POST['package_name'], $_POST['package_description'], $_POST['unitavailable'], $_POST['min_seat'], $_POST['max_seat'], $_POST['min_price'], $_POST['max_price'], $unitcost, $_POST['tax_text'], $_POST['tax'], $_POST['package_coupon'], $_POST['is_flexible'], $_POST['capture_payee_details'], $_POST['capture_attendeed_details'], $_POST['coupon_code'], $_POST['franchise_id'], $_POST['vendor_id'], $_POST['event_type'], $_POST['booking_unit'], $_POST['artist'], $_POST['artist_label'], $_POST['category_name'], $_POST['event_tnc'], $_POST['cancellation_policy'], $_POST['package_type'], $_POST['package_occurence'], $stop_booking_string, json_encode($_POST['currency']), $currency_amount);
                if ($result['message'] == 'success') {
                    $this->view->selectedMenu = array(8, 36);
                    $this->view->title = 'Event created / modified';
                    $link = $this->encrypt->encode($result['request_id']);
                    $eventlink[] = $this->view->server_name . '/patron/event/view/' . $link;
                    $shortUrlWrap = new SwipezShortURLWrapper();
                    $shortUrls = $shortUrlWrap->SaveUrl($eventlink);

                    $this->notifySupport($shortUrls[0], 'created');
                    $this->model->updateShortURL($result['request_id'], $shortUrls[0]);
                    $text = 'Check out the latest event ' . $_POST['event_name'] . ' by ' . $this->session->get('company_name') . '. Click here to view event details and book your seats - ' . $shortUrls[0];
                    $this->smarty->assign("whatsapptext", $text);
                    $this->smarty->assign("is_success", 'True');
                    $this->smarty->assign("link", $link);
                    $this->view->header_file = ['profile'];
                    $this->view->render('header/app');
                    $this->smarty->display(VIEW . 'merchant/event/success.tpl');
                    $this->view->render('footer/invoice');
                } else {
                    SwipezLogger::error(__CLASS__, '[E031]Error while save event. error: ' . $result);
                    $this->setGenericError();
                }
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->create();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E032]Error while creating new template Error:for merchant [' . $merchant_id . '] and for template [' . $template_id . '] ' . $e->getMessage());
        }
    }

    public function notifySupport($eventlink, $type)
    {
        $subject = "Event " . $type;
        $body_message = "Merchant id: " . $this->merchant_id . " <br>Event link: " . $eventlink;
        SupportTeamNotification::dispatch($subject, $body_message, 'SUPPORT')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
    }

    public function updatesaved()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location:/merchant/event/viewlist');
            }
            $this->setCapturedetails();
            # simple event
            if ($_POST['occurence'] == 0) {
                $_POST['from_date'][] = date('Y-m-d');
                $_POST['to_date'][] = date('Y-m-d');
            }

            $payment_request_id = $this->encrypt->decode($_POST['event_request_id']);

            $this->generic->setEmptyArray('column', 'columnvalue', 'existvalue', 'invoice_id');
            $_POST['unitavailable'] = ($_POST['unitavailable'] == '') ? 0 : $_POST['unitavailable'];

            $capture_details = ($_POST['is_capture'] != 1) ? 0 : 1;
            $franchise_id = ($_POST['franchise_id'] > 0) ? $_POST['franchise_id'] : 0;
            $vendor_id = ($_POST['vendor_id'] > 0) ? $_POST['vendor_id'] : 0;

            if ($is_flexible == 1) {
                $unitcost = 0;
                $grandtotal = 0;
                $min_price = $_POST['min_price'];
                $max_price = $_POST['max_price'];
                $_POST['unitcost'] = $max_price;
                $_POST['grandtotal'] = $max_price;
            } else {
                $unitcost = $_POST['unitcost'];
                $grandtotal = $_POST['grandtotal'];
                $min_price = 0;
                $max_price = 0;
            }

            if ($_POST['event_type'] == 2) {
                $_POST['venue'] = '';
                $_POST['unitavailable'] = 0;
            }

            require CONTROLLER . 'merchant/Templatevalidator.php';
            $validator = new Templatevalidator($this->model);
            $validator->validateEventSave($this->session->get('userid'));
            $hasErrors = $validator->fetchErrors();

            $int = 0;
            $error = FALSE;
            foreach ($_POST['epackage_name'] as $package) {
                $_POST['_package_name'] = $package;
                $_POST['_unitcost'] = $_POST['eunitcost'][$int];
                $_POST['_tax'] = $_POST['etax'][$int];
                $validator->validateEventPackage();
                $hasErrors = $validator->fetchErrors();
                $pkg_int = $_POST['epackage_int'][$int];
                $date_array = array();
                if ($_POST['epackage_type' . $pkg_int][0] == 2) {
                    $_POST['epackage_type'][] = 2;
                } else {
                    foreach ($_POST['emulti_occurence' . $pkg_int] as $date) {
                        $sdate = new DateTime($date);
                        $date_array[] = $sdate->format('Y-m-d H:i');
                    }
                    $_POST['epackage_type'][] = 1;
                }
                $_POST['epackage_occurence'][$int] = implode(',', $date_array);

                $int++;
            }
            $error = FALSE;

            $int = 0;
            foreach ($_POST['package_name'] as $package) {
                $_POST['_package_name'] = $package;
                $_POST['_unitcost'] = $_POST['unitcost'][$int];
                $_POST['_tax'] = $_POST['tax'][$int];
                $validator->validateEventPackage();
                $hasErrors = $validator->fetchErrors();
                $pkg_int = $_POST['package_int'][$int];
                $date_array = array();
                if ($_POST['package_type' . $pkg_int][0] == 2) {
                    $_POST['package_type'][] = 2;
                } else {
                    foreach ($_POST['multi_occurence' . $pkg_int] as $date) {
                        $sdate = new DateTime($date);
                        $date_array[] = $sdate->format('Y-m-d H:i');
                    }
                    $_POST['package_type'][] = 1;
                }
                if ($_POST['occurence'] == 0) {
                    $_POST['package_occurence'][$int] = date('Y-m-d');
                } else {
                    $_POST['package_occurence'][$int] = implode(',', $date_array);
                }
                $int++;
            }

            if ($hasErrors == false) {

                $start_date = array();
                foreach ($_POST['from_date'] as $date) {
                    $sdate = new DateTime($date);
                    $start_date[] = $sdate->format('Y-m-d');
                }
                $end_date = array();
                foreach ($_POST['to_date'] as $date) {
                    $sdate = new DateTime($date);
                    $end_date[] = $sdate->format('Y-m-d');
                }
                $start_time = array();
                foreach ($_POST['from_time'] as $date) {
                    $sdate = new DateTime($date);
                    $start_time[] = $sdate->format('H:i');
                }
                $end_time = array();
                foreach ($_POST['to_time'] as $date) {
                    $sdate = new DateTime($date);
                    $end_time[] = $sdate->format('H:i');
                }

                if (empty($_POST['currency'])) {
                    $_POST['currency'] = array('INR');
                }
                $currency_count = count($_POST['currency']);
                $intp = 0;
                foreach ($_POST['package_int'] as $key => $int) {
                    $cost_array = array();
                    for ($i = 0; $i < $currency_count; $i++) {
                        if ($i == 0) {
                            $unitcost[] = $_POST['unitcost'][$intp];
                        }
                        $cost_array[$_POST['package_currency'][$intp]] = $_POST['unitcost'][$intp];
                        $intp++;
                    }
                    $currency_amount[] = json_encode($cost_array);
                }
                $intp = 0;
                foreach ($_POST['epackage_int'] as $key => $int) {
                    $cost_array = array();
                    for ($i = 0; $i < $currency_count; $i++) {
                        if ($i == 0) {
                            $unitcost[] = $_POST['eunitcost'][$intp];
                        }
                        $cost_array[$_POST['epackage_currency'][$intp]] = $_POST['eunitcost'][$intp];
                        $intp++;
                    }
                    $ecurrency_amount[] = json_encode($cost_array);
                }


                $mobile_capture = ($_POST['mobile_capture'] != 1) ? 0 : 1;
                $age_capture = ($_POST['age_capture'] != 1) ? 0 : 1;
                $coupon_code = ($_POST['coupon_code'] > 0) ? $_POST['coupon_code'] : 0;
                $stop_booking_string = $_POST['stop_booking_day'] . ':' . $_POST['stop_booking_hour'] . ':' . $_POST['stop_booking_min'];
                $result = $this->model->updateEvent($payment_request_id, $_POST['event_name'], $_POST['title'], $_POST['short_description'], $_POST['venue'], $_POST['description'], $_POST['duration'], $_POST['occurence'], $_POST['column'], $_POST['columnvalue'], $_POST['is_mandatory'], $_POST['datatype'], $_POST['position'], $_POST['existvalue'], $_POST['invoice_id'], $_FILES["banner"], $start_date, $end_date, $start_time, $end_time, $_POST['epackage_id'], $_POST['epackage_name'], $_POST['epackage_description'], $_POST['eunitavailable'], $_POST['esold_out'], $_POST['emin_seat'], $_POST['emax_seat'], $_POST['emin_price'], $_POST['emax_price'], $_POST['eunitcost'], $_POST['epackage_coupon'], $_POST['ecategory_name'], $_POST['category_name'], $_POST['epackage_type'], $_POST['eis_flexible'], $_POST['epackage_occurence'], $_POST['etax_text'], $_POST['etax'], $_POST['package_name'], $_POST['package_description'], $_POST['unitavailable'], $_POST['min_seat'], $_POST['max_seat'], $_POST['min_price'], $_POST['max_price'], $_POST['unitcost'], $_POST['tax_text'], $_POST['tax'], $_POST['package_coupon'], $franchise_id, $vendor_id, $_POST['is_flexible'], $_POST['capture_payee_details'], $_POST['capture_attendeed_details'], $coupon_code, $_POST['event_type'], $_POST['package_type'], $_POST['package_occurence'], $_POST['event_tnc'], $_POST['cancellation_policy'], $_POST['booking_unit'], $_POST['artist'], $_POST['artist_label'], $stop_booking_string, json_encode($_POST['currency']), $currency_amount, $ecurrency_amount);

                $shortUrl = $this->common->getRowValue('short_url', 'event_request', 'event_request_id', $payment_request_id);
                if ($result['message'] == 'success') {
                    $this->view->selectedMenu = array(8, 36);
                    $text = 'Check out the latest event ' . $_POST['event_name'] . ' by ' . $this->session->get('company_name') . '. Click here to view event details and book your seats - ' . $shortUrl;
                    $this->smarty->assign("whatsapptext", $text);
                    $this->notifySupport($shortUrl, 'modified');
                    $this->view->title = 'Event created / modified';
                    $this->smarty->assign("link", $_POST['event_request_id']);
                    $this->view->header_file = ['profile'];
                    $this->view->render('header/app');
                    $this->smarty->display(VIEW . 'merchant/event/success.tpl');
                    $this->view->render('footer/invoice');
                } else {
                    SwipezLogger::error(__CLASS__, '[E031-3]Error while update event. error: ' . $result);
                    $this->setGenericError();
                }
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->update($_POST['event_request_id']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E032]Error while creating new template Error:for merchant [' . $merchant_id . '] and for template [' . $template_id . '] ' . $e->getMessage());
        }
    }

    public function view($link)
    {
        try {
            $this->hasRole(1, 7);
            if (!isset($link)) {
                $this->setInvalidLinkError();
            }
            $merchant_id = $this->session->get('merchant_id');
            $payment_request_id = $this->encrypt->decode($link);
            $info = $this->common->getPaymentRequestDetails($payment_request_id, NULL, 2);
            if ($info['merchant_id'] != $this->merchant_id) {
                $this->setInvalidLinkError();
            }
            $rows = $this->common->getInvoiceBreakup($payment_request_id, 'Event');
            $currency = json_decode($info['currency'], 1);
            if (count($currency) > 1) {
                if (isset($_POST['currency_id'])) {
                    $currency_id = $_POST['currency_id'];
                } else {
                    $currency_id = $currency[0];
                }
            } else {
                $currency_id = $currency[0];
            }
            $occurence = $this->common->getListValue('event_occurence', 'event_request_id', $payment_request_id, 1);
            if (isset($_POST['occurence_id'])) {
                $occurence_id = $_POST['occurence_id'];
            } else {
                $occurence_id = $occurence[0]['occurence_id'];
            }
            $int = 0;
            if ($info['coupon_code'] != '') {
                $couponlist[$int]['package_id'] = 0;
                $couponlist[$int]['package_name'] = 'all package';
                $couponlist[$int]['coupon_id'] = $info['coupon_id'];
                $couponlist[$int]['coupon_code'] = $info['coupon_code'];
                $couponlist[$int]['descreption'] = $info['c_descreption'];
                $couponlist[$int]['type'] = $info['c_type'];
                $couponlist[$int]['percent'] = $info['c_percent'];
                $couponlist[$int]['fixed_amount'] = $info['c_fixed_amount'];
                $couponlist[$int]['start_date'] = $info['c_start_date'];
                $couponlist[$int]['end_date'] = $info['c_end_date'];
                $couponlist[$int]['available'] = $info['c_available'];
                $couponlist[$int]['limit'] = $info['c_limit'];
                $int++;
            }

            $count = 0;
            if ($occurence_id > 0) {
                $occurence_date = $this->common->getRowValue('start_date', 'event_occurence', 'occurence_id', $occurence_id);
                $type_query = "and (package_type=2 OR occurence like '%" . $occurence_date . "%')";
            } else {
                $type_query = "and package_type=2";
            }
            $package = $this->common->getListValue('event_package', 'event_request_id', $payment_request_id, 1, $type_query);
            foreach ($package as $p) {

                $package_id = $package[$count]['package_id'];

                if ($p['package_type'] == 2) {
                    $package[$count]['occurence_id'] = $occurence[0]['occurence_id'];
                } else {
                    $package[$count]['occurence_id'] = $occurence_id;
                }

                if ($package[$count]['seats_available'] != 0) {
                    $available = $this->common->getPackageBookCount($package_id, $package[$count]['occurence_id'], $payment_request_id);
                    $package[$count]['available'] = $package[$count]['seats_available'] - $available;
                    if ($package[$count]['available'] < 1 || $package[$count]['sold_out'] == 1) {
                        $package[$count]['sold_out'] = 1;
                    }
                }

                if (count($currency) > 1) {
                    $currency_array = json_decode($p['currency_price'], 1);
                    $package[$count]['price'] = $currency_array[$currency_id]['price'];
                    $package[$count]['min_price'] = $currency_array[$currency_id]['min_price'];
                    $package[$count]['max_price'] = $currency_array[$currency_id]['max_price'];
                }



                if ($p['coupon_code'] > 0) {
                    $coupondetails = $this->common->getCouponDetails($p['coupon_code']);
                    if (!empty($coupondetails)) {
                        $couponlist[$int] = $coupondetails;
                        $couponlist[$int]['package_name'] = $p['package_name'];
                        $couponlist[$int]['package_id'] = $p['package_id'];
                        $int++;
                    }
                }
                $count++;
            }

            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E008]Error while geting invoice details. for merchant [' . $merchant_id . '] and for payment request id [' . $particular['payment_request_id'] . ']');
                $this->setGenericError();
            }

            switch ($info['event_type']) {
                case '1':
                    $event_type = 'event';
                    $display_booking = 'Seat';
                    break;
                case '3':
                    $event_type = 'simple_event';
                    $display_booking = 'Seat';
                    break;
            }

            $this->smarty->assign("url", $link);
            $link = $this->app_url . '/patron/event/view/' . $link;
            $this->smarty->assign("payment_request_id", $payment_request_id);
            $post_link = ($info['short_url'] != '') ? $info['short_url'] : $link;
            $this->smarty->assign("link", $post_link);
            $this->smarty->assign("occurence_id", $occurence_id);
            $this->smarty->assign("header", $rows);
            $this->smarty->assign("couponlist", $couponlist);
            $this->smarty->assign("info", $info);
            $this->smarty->assign("package", $package);
            $this->smarty->assign("occurence", $occurence);
            $this->smarty->assign("is_valid", "YES");

            $this->smarty->assign("currency", $currency);
            $currency_icon = $this->common->getRowValue('icon', 'currency', 'code', $currency_id);

            $this->smarty->assign("currency_icon", $currency_icon);
            $this->smarty->assign("currency_id", $currency_id);
            //TODO 
            // if ($info['seats_available'] > 0 && $availableunits < 1) {
            //     $this->smarty->assign("is_valid", "NO");
            //      $this->smarty->assign("invalid_message", "Currently there are no seats available for this event.");
            //  }


            $todate = new DateTime($info['event_to_date']);
            $todate = $todate->format('Y-m-d');
            if ($todate < date('Y-m-d') && $info['occurence'] > 0) {
                $this->smarty->assign("is_valid", "NO");
                $this->smarty->assign("invalid_message", "Event link has expired.");
            }

            if ($info['is_active'] != 1) {
                $this->smarty->assign("is_valid", "NO");
                $this->smarty->assign("invalid_message", "This event has been deactivated by the merchant.");
            }

            $merchant_page = '#';
            if ($info['display_url'] != '') {
                $merchant_page = $this->app_url . '/m/' . $info['display_url'];
            }
            $this->smarty->assign("merchant_page", $merchant_page);
            $this->view->js = array('event', 'template');
            // $this->asignSmarty($rows, $info, $banklist, $payment_request_id);
            $this->view->selectedMenu = 'myevent';
            $this->view->title = 'View Event';
            $this->smarty->assign('title', $this->view->title);
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Events', 'url' => ''),
                array('title' => 'Events list', 'url' => '/merchant/event/viewlist'),
                array('title' => $info['event_name'], 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->smarty->assign("display_booking", $display_booking);
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/event/view_' . $event_type . '.tpl');
            $this->view->render('footer/invoice');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E009]Error while payment request view initiate Error:for merchant [' . $merchant_id . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function seatbooking($link)
    {
        try {
            $this->hasRole(1, 7);
            $this->validatePackage();
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header('Location:/merchant/event/view/' . $link);
            }

            require MODEL . 'merchant/CustomerModel.php';
            $Customermodel = new CustomerModel();
            $customer_list = $Customermodel->getCustomerallList($merchant_id);
            $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
            $customer_column = $Customermodel->getCustomerBreakup($merchant_id);
            $this->smarty->assign("column", $customer_column);
            $this->smarty->assign("merchant_setting", $merchant_setting);
            $this->smarty->assign("customer_list", $customer_list);
            $this->smarty->assign("current_date", date('d M Y'));

            $payment_request_id = $this->encrypt->decode($link);
            $info = $this->common->getPaymentRequestDetails($payment_request_id, NULL, 2);
            $package = $this->common->getListValue('event_package', 'event_request_id', $payment_request_id, 1);
            $int = 0;
            $absolute_cost = $_POST['grand_total'];
            $seat = 0;
            foreach ($_POST['package_qty'] as $key => $value) {
                if ($value == 0) {
                    unset($_POST['package_id'][$key]);
                }
            }
            if (!empty($_POST['package_id'])) {
                $extra = ' and package_id in (' . implode(',', $_POST['package_id']) . ')';
                $package = $this->common->getListValue('event_package', 'event_request_id', $payment_request_id, 1, $extra);
                foreach ($package as $row) {
                    $package_array[$row['package_id']] = $row;
                }
            } else {
                $package_array = [];
                $package = [];
            }
            $int = 0;
            $absolute_cost = 0;
            $seat = 0;
            $currency_id = $_POST['currency'];
            foreach ($_POST['package_id'] as $key => $value) {
                $package[$int] = $package_array[$value];
                $package[$int]['seat'] = $_POST['package_qty'][$key];
                $package[$int]['occurence_id'] = $_POST['occurence_id'][$key];
                $occ_row = $this->common->getSingleValue('event_occurence', 'occurence_id', $_POST['occurence_id'][$key]);
                $package[$int]['date'] = $occ_row;
                $event_package[] = $package[$int];
                $seat = $seat + $_POST['package_qty'][$key];

                $currency_array = json_decode($package[$int]['currency_price'], 1);
                if ($currency_array[$currency_id] > 0) {
                    $package[$int]['price'] = $currency_array[$currency_id]['price'];
                    $package[$int]['min_price'] = $currency_array[$currency_id]['min_price'];
                    $package[$int]['max_price'] = $currency_array[$currency_id]['max_price'];
                }

                $int++;
            }
            if (!isset($_POST['is_flexible'])) {
            } else {
                $this->smarty->assign("is_flexible", 1);
            }
            $this->view->js = array('event', 'template');
            $absolute_cost = $_POST['grand_total'];
            $this->smarty->assign("package", $package);
            $this->smarty->assign("coupon_id", $_POST['coupon_id']);
            $this->smarty->assign("attendees_capture", json_decode($info['attendees_capture'], 1));
            $this->smarty->assign("absolute_cost", $absolute_cost);
            $this->smarty->assign("seat", $seat);
            $this->smarty->assign("occurence_id", $_POST['start_date']);
            $this->smarty->assign("tax_amount", $_POST['service_tax']);
            $this->smarty->assign("discount_amount", $_POST['coupon_discount']);
            $currency_icon = $this->common->getRowValue('icon', 'currency', 'code', $_POST['currency']);
            $this->smarty->assign("currency_id", $_POST['currency']);
            $this->smarty->assign("currency_icon", $currency_icon);
            $banklist = $this->common->querylist("select config_key, config_value from config where config_type='Bank_name'");
            if (empty($banklist)) {
                SwipezLogger::warn(__CLASS__, 'Fetching empty bank list');
            }
            foreach ($banklist as $value) {
                $bank_ids[] = $value['config_key'];
                $bank_values[] = $value['config_value'];
            }
            $this->smarty->assign("bank_id", $bank_ids);
            $this->smarty->assign("bank_value", $bank_values);
            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E207]Error while patron geting invoice details. payment request id ' . $payment_request_id);
                $this->setGenericError();
            } else {
                $post_link = $this->app_url . '/patron/event/payment/' . $link;
                $this->smarty->assign("post_link", $post_link);
                $this->view->title = 'Event Seat booking';
                $this->smarty->assign("info", $info);
                $this->smarty->assign("payment_request_id", $link);
                $this->smarty->assign("title", $info['template_name']);
                $this->view->selectedMenu = 'myevent';
                $this->view->header_file = ['profile'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/event/confirm.tpl');
                $this->view->render('footer/invoice');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E042]Error while payment request pay initiate Error:for user id [' . $this->session->get('userid') . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function asignSmarty($rows, $info, $banklist, $payment_request_id)
    {
        $headerinc = 0;
        $header = array();
        foreach ($rows as $row) {
            if ($row['column_type'] == 'H') {
                $header[$headerinc]['column_name'] = $row['column_name'];
                $header[$headerinc]['value'] = $row['value'];
                $header[$headerinc]['position'] = $row['position'];
                $header[$headerinc]['column_position'] = $row['column_position'];
                $header_position[$row['column_position']]['column_name'] = $row['column_name'];
                $header_position[$row['column_position']]['value'] = $row['value'];
                $headerinc++;
            }
        }
        $this->smarty->assign("header", $header);
        $this->smarty->assign("payment_request_id", $payment_request_id);
        $encode = $this->encrypt->encode($payment_request_id);
        $link = $this->app_url . '/patron/event/view/' . $encode;
        $this->smarty->assign("link", $link);
        $availableunits = $info['seats_available'] - $info['count_transaction'];
        $this->smarty->assign("available_units", $availableunits);
        $this->smarty->assign("unit_available", $info['unit_available']);
        $this->smarty->assign("is_valid", "YES");
        if ($info['seats_available'] > 0 && $availableunits < 1) {
            $this->smarty->assign("is_valid", "NO");
        }

        $todate = new DateTime($info['event_to_date']);
        $todate = $todate->format('Y-m-d');
        if ($info['event_from_date'] != $info['event_to_date'] && $todate < date('Y-m-d')) {
            $this->smarty->assign("is_valid", "NO");
        }

        $this->smarty->assign("image_path", $info['image_path']);
        $this->smarty->assign("banner_path", $info['banner_path']);


        $this->smarty->assign("dropdownvalidate", $this->view->HTMLValidatorPrinter->fetch("htmlval.dropdown"));
        $this->smarty->assign("bankrefvalidate", $this->view->HTMLValidatorPrinter->fetch("htmlval.bankref"));
        $this->smarty->assign("chequevalidate", $this->view->HTMLValidatorPrinter->fetch("htmlval.cheque"));
        $this->smarty->assign("narrativevalidate", $this->view->HTMLValidatorPrinter->fetch("htmlval.narrative"));
        $this->smarty->assign("amountvalidate", $this->view->HTMLValidatorPrinter->fetch("htmlval.amount"));
        $this->smarty->assign("currentdate", date("d M Y"));
    }

    function getAttendeesCustomerId($info, $int, $update = 0)
    {
        require_once MODEL . 'merchant/CustomerModel.php';
        $customer = new CustomerModel($this->model);
        if (isset($_POST['attendee_name'][$int])) {
            $name = $_POST['attendee_name'][$int];
            $name = trim($name);
        } else {
            $name = 'NA';
        }
        $email = isset($_POST['attendee_email'][$int]) ? $_POST['attendee_email'][$int] : '';
        $mobile = isset($_POST['attendee_mobile'][$int]) ? $_POST['attendee_mobile'][$int] : '';
        $address = isset($_POST['attendee_address'][$int]) ? $_POST['attendee_address'][$int] : '';
        $city = isset($_POST['attendee_city'][$int]) ? $_POST['attendee_city'][$int] : '';
        $state = isset($_POST['attendee_state'][$int]) ? $_POST['attendee_state'][$int] : '';
        $zipcode = isset($_POST['attendee_zipcode'][$int]) ? $_POST['attendee_zipcode'][$int] : '';
        $space_position = strpos($name, ' ');
        if ($space_position > 0) {
            $first_name = substr($name, 0, $space_position);
            $last_name = substr($name, $space_position);
        } else {
            $first_name = $name;
            $last_name = '';
        }
        $capture_array = json_decode($info['attendees_capture'], 1);
        $customer_ids = $customer->getexistcustomerId($info['merchant_id'], $first_name, $last_name, $email, $mobile);
        $column_ids = array();
        $column_values = array();
        foreach ($capture_array as $row) {
            if ($row['type'] != 'system') {
                $column_id = $row['name'];
                $value = $_POST['attendee_' . $column_id][$int];
                $column_ids[] = $column_id;
                $column_values[] = $value;
                if (!empty($customer_ids)) {
                    $rows = $this->common->getListValue('customer_column_values', 'column_id', $column_id, 1, " and value='" . $value . "' and customer_id in (" . implode(',', $customer_ids) . ")");
                    $customer_ids = array();
                    foreach ($rows as $r) {
                        $customer_ids[] = $r['customer_id'];
                    }
                }
            }
        }
        if ($update != 0) {
            $customer_code = $this->common->getRowValue('customer_code', 'customer', 'customer_id', $update);
            $customer->updateCustomer($update, $update, $customer_code, $first_name, $last_name, $email, $mobile, $address, '', $city, $state, $zipcode, $column_ids, $column_values, array(), array());
            return $update;
        } else {
            if (empty($customer_ids)) {
                $customer_code = $customer->getCustomerCode($info['merchant_id']);
                $patron = $customer->saveCustomer($info['merchant_id'], $info['merchant_id'], $customer_code, $first_name, $last_name, $email, $mobile, $address, '', $city, $state, $zipcode, $column_ids, $column_values);
                return $patron['customer_id'];
            } else {
                return $customer_ids[0];
            }
        }
    }

    public function respond()
    {
        try {
            $this->hasRole(1, 7);
            if (empty($_POST)) {
                header('Location:/error');
            }
            $merchant_id = $this->session->get('userid');
            require CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($this->model);
            $validator->validateEventMerchantRespond();
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                if (!isset($_POST['attendeename'])) {
                    $_POST['attendeename'] = array();
                }
                if (!isset($_POST['attendeemobile'])) {
                    $_POST['attendeemobile'] = array();
                }
                if (!isset($_POST['attendeeage'])) {
                    $_POST['attendeeage'] = array();
                }

                $date = new DateTime($_POST['date']);

                $payment_request_id = $this->encrypt->decode($_POST['payment_request_id']);

                if ($_POST['amount'] > 0) {
                    $amount = $_POST['amount'];
                } else {
                    $amount = 0;
                }

                if ($_POST['coupon_id'] > 0) {
                    $coupon_id = $_POST['coupon_id'];
                } else {
                    $coupon_id = 0;
                }
                $info = $this->common->getPaymentRequestDetails($payment_request_id, NULL, 2);

                $attendee_array = json_decode($info['attendees_capture'], 1);
                $_POST['attendee_customer_id'] = array();
                foreach ($_POST['package_id'] as $key => $val) {
                    if (empty($attendee_array)) {
                        $_POST['attendee_customer_id'][] = 0;
                    } else {
                        $update = 0;
                        if ($key == 1 && $_POST['same_aspayee'] == 1) {
                            $update = $customer_id;
                        }
                        $_POST['attendee_customer_id'][] = $this->getAttendeesCustomerId($info, $key, $update);
                    }
                }

                $flexible_amount = 0;
                if (isset($_POST['flexible_amount'])) {
                    foreach ($_POST['flexible_amount'] as $f) {
                        $flexible_amount = $flexible_amount + $f;
                    }
                }

                $amount = $amount + $flexible_amount;

                $transaction_id = $this->model->save_offline_event_transaction($payment_request_id, $_POST['customer_id'], $merchant_id, $amount, $_POST['tax'], $_POST['discount'], $_POST['bank_name'], $date->format('Y-m-d'), $_POST['bank_transaction_no'], $_POST['cheque_no'], $_POST['cash_paid_to'], $_POST['response_type'], $_POST['seat'], $_POST['occurence_id'], $_POST['price'], $_POST['package_id'], $_POST['attendee_customer_id'], $_POST['attendeemobile'], $_POST['attendeeage'], $coupon_id, $_POST['cheque_status'], $_POST['currency_id']);
                if ($transaction_id != '') {
                    require_once CONTROLLER . '/Notification.php';
                    $secure = new Notification();
                    $receipt_info = $secure->sendMailReceipt($transaction_id, 0);
                    $receipt_info['BillingEmail'] = $receipt_info['patron_email'];
                    $receipt_info['MerchantRefNo'] = $receipt_info['transaction_id'];
                    $receipt_info['BillingName'] = $receipt_info['patron_name'];
                    $receipt_info['company_name'] = $this->session->get('company_name');
                    $receipt_info['is_offline'] = 1;
                    $logo = '';
                    if ($receipt_info['image'] == '') {
                        if ($receipt_info['merchant_logo'] != '') {
                            $logo = '/uploads/images/landing/' . $receipt_info['merchant_logo'];
                        }
                    } else {
                        $logo = '/uploads/images/logos/' . $receipt_info['image'];
                    }
                    $this->smarty->assign("logo", $logo);
                    $attendee_details = $this->common->getAttendeeDetails($transaction_id);
                    $coupon_details = $this->common->getSingleValue('coupon', 'coupon_id', $attendee_details['coupon_id']);
                    $this->smarty->assign("coupon_code", $coupon_details['coupon_code']);
                    $this->smarty->assign("response", $receipt_info);
                    $this->smarty->assign("attendee_details", $attendee_details);
                    $this->view->header_file = ['profile'];
                    $this->view->render('header/app');
                    $this->smarty->display(VIEW . 'merchant/transaction/offlinereceipt.tpl');
                    $this->view->render('footer/profile');
                } else {
                    SwipezLogger::error(__CLASS__, '[E223]Error while patron respond payment request Error: ' . $result['message']);
                    $this->setGenericError();
                }
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->view($_POST['payment_request_id']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E045]Error while respond payment Error: for user id [' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Event deactive for merchant
     */
    function delete($link)
    {
        try {
            $this->hasRole(3, 7);
            $event_id = $this->encrypt->decode($link);
            $this->model->deleteevent($event_id);
            $this->session->set('successMessage', 'Event deactivate successfully.');
            header('Location:/merchant/event/viewlist');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E033]Error while deleting template Error: for merchant [' . $merchant_id . '] and for template [' . $template_id . ']' . $e->getMessage());
        }
    }

    function attendees($link, $export = NULL)
    {
        try {
            $this->hasRole(1, 7);
            $this->view->selectedMenu = 'myevent';
            $event_id = $this->encrypt->decode($link);
            $package_id = 0;
            $occurence_id = 0;
            $from_date = NULL;
            $to_date = NULL;
            $from_date_select = $this->getLast_date();
            $to_date_select = date('d M Y');
            if (isset($_POST['from_date'])) {
                $from_date_select = $_POST['from_date'];
                $to_date_select = $_POST['to_date'];
                if ($_POST['from_date'] != '' && $_POST['to_date'] != '') {
                    $fromdate = new DateTime($_POST['from_date']);
                    $from_date = $fromdate->format('Y-m-d');
                    $to_date = new DateTime($_POST['to_date']);
                    $to_date = $to_date->format('Y-m-d');
                }
                $package_id = ($_POST['package_id'] > 0) ? $_POST['package_id'] : 0;
                $occurence_id = ($_POST['occurence_id'] > 0) ? $_POST['occurence_id'] : 0;
            }
            $event_det = $this->common->getSingleValue('event_request', 'event_request_id', $event_id);
            $event_name = $event_det['event_name'];
            $occurence = $event_det['occurence'];
            $package_list = $this->common->getListValue('event_package', 'event_request_id', $event_id, 1);
            $occurence_list = $this->common->getListValue('event_occurence', 'event_request_id', $event_id, 1);
            $requestlist = $this->model->getAttendees($event_id, $from_date, $to_date, $package_id, $occurence_id);

            $attendee_capture = json_decode($event_det['attendees_capture'], 1);
            foreach ($requestlist as $key => $val) {
                if ($val['attendee_customer_id'] > 0) {
                    $det = $this->common->getCustomerValueDetail($val['attendee_customer_id']);
                    foreach ($attendee_capture as $ac) {
                        if ($ac['type'] == 'system') {
                            $column_name = str_replace(' ', '_', $ac['column_name']);
                            $requestlist[$key]['attendee_' . $column_name] = $det['cust_det'][$ac['name']];
                        } else {
                            $column_name = str_replace(' ', '_', $ac['column_name']);
                            $requestlist[$key]['attendee_' . $column_name] = $det['cust_value_det'][$ac['name']];
                        }
                    }
                }
            }
            if ($export != NULL) {
                $this->common->excelexport('AttendeesDetails', $requestlist);
            }

            $requestlist = $this->generic->getEncryptedList($requestlist, 'link', 'transaction_id');
            $this->smarty->assign("attendee_capture", $attendee_capture);
            $this->smarty->assign("occurence", $occurence);
            $this->smarty->assign("title", 'Attendees of ' . $event_name);

            $this->smarty->assign("package_id", $package_id);
            $this->smarty->assign("occurence_id", $occurence_id);
            $this->smarty->assign("from_date", $from_date_select);
            $this->smarty->assign("to_date", $to_date_select);

            $this->smarty->assign("requestlist", $requestlist);
            $this->smarty->assign("package_list", $package_list);
            $this->smarty->assign("occurence_list", $occurence_list);
            $this->smarty->assign("link", $link);
            $this->view->title = 'View event attendees';

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Events', 'url' => ''),
                array('title' => 'Events list', 'url' => '/merchant/event/viewlist'),
                array('title' => 'Attendees of ' . $event_name, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/event/attendeelist.tpl');
            $this->view->datatablejs = 'table-small';
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E033]Error while deleting template Error: for merchant [' . $merchant_id . '] and for event_id [' . $event_id . ']' . $e->getMessage());
        }
    }

    function packagesummary($link)
    {
        try {
            $this->hasRole(1, 7);
            $this->view->selectedMenu = 'myevent';
            $event_id = $this->encrypt->decode($link);
            $requestlist = $this->model->getPackageSummary($event_id);
            $event_det = $this->common->getSingleValue('event_request', 'event_request_id', $event_id);
            $event_name = $event_det['event_name'];
            $occurence = $event_det['occurence'];
            $this->smarty->assign("occurence", $occurence);
            $this->smarty->assign("requestlist", $requestlist);
            $this->view->title = 'Package summary';
            $this->smarty->assign("title", $this->view->title);
            $this->smarty->assign("link", $link);

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Events', 'url' => ''),
                array('title' => 'Events list', 'url' => '/merchant/event/viewlist'),
                array('title' => 'Package summary', 'url' => ''),
                array('title' => $event_name, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/event/packagesummary.tpl');
            $this->view->datatablejs = 'table-simple-scroll';
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E033]Error while deleting template Error: for merchant [' . $merchant_id . '] and for event_id [' . $event_id . ']' . $e->getMessage());
        }
    }
}
