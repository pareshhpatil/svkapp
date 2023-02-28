<?php

/**
 * Dashboard controller class to handle dashboard requests for merchant
 */

use App\Jobs\MerchantServiceAddToCRM;
use App\Jobs\SupportTeamNotification;
class Dashboard extends Controller
{

    function __construct()
    {
        parent::__construct();

        //TODO : Check if using static function is causing any problems!
        $this->validateSession('merchant');
        $this->view->selectedMenu = array(1);
    }

    /**
     * Display merchant dashboard
     */
    function home()
    {
        try {
            $services = $this->model->getMerchantActiveServices($this->user_id);
            $is_legal_complete = $this->common->getRowValue('is_legal_complete', 'merchant', 'merchant_id', $this->merchant_id);
            foreach ($services as $row) {
                $active_services[] = $row['service_id'];
            }
            $activate_all_services = array();
            $all_services = array();
            foreach ($services as $key => $val) {
                if ($is_legal_complete == 0 && $val['status'] == 2) {
                    $services[$key]['status'] = 2;
                } else {
                    $services[$key]['status'] = $val['status'];
                }
                $services[$key]['encrypted_id'] = $this->encrypt->encode($val['service_id']);
                $activate_all_services[] = $services[$key];
            }
            $services2 = $this->model->getMerchantInActiveServices(implode(',', $active_services));
            foreach ($services2 as $key => $val) {
                $services2[$key]['status'] = 0;
                $services2[$key]['encrypted_id'] = $this->encrypt->encode($val['service_id']);
                $all_services[] = $services2[$key];
            }
            $services = array_merge($activate_all_services, $all_services);
            $campaign_id = $this->session->getCookie('registration_campaign_id');
            if ($campaign_id == 11) {
                $this->view->software_suggest = 1;
                $this->session->removeCookie('registration_campaign_id');
            }
            $this->view->js = array('dashboard');
            $this->view->hide_menu = 1;
            $this->view->title = 'Swipez Dashboard';
            if ($this->view->has_partner == 1) {
                $this->smarty->assign("support_email", config('app.partner.support_email'));
                $this->smarty->assign("support_contact", config('app.partner.support_contact'));
            }
            $this->smarty->assign("company_name", $this->session->get('company_name'));
            $this->smarty->assign("services", $services);
            $this->smarty->assign("helpdesk_contact", env('SWIPEZ_HELPDESK_CONTACT'));
            $this->view->header_file = ['availableapps'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/dashboard/newdashboard.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001]Error while merchant new dashboard initiate Error: for merchant [' . $this->merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function setServiceInfo($service_id)
    {
        if ($service_id != null) {
            $popup = '';
            $this->session->set('show_help_hero', 0);
            $this->session->remove('menus');
            $this->session->setCookie('last_service_id', $service_id);
            $service_id = $this->encrypt->decode($service_id);
            $this->session->set('service_id', $service_id);
            $created_date = $this->session->get('created_date');
            if ($service_id == 2 && $this->view->has_partner == 0) {
                if ($created_date > '2019-12-31') {
                    $this->session->set('show_help_hero', 1);
                    $this->session->set('created_date', $created_date);
                    #Show help hero popup for if merchant Not created an invoice and No transactions or any sort xway or normal
                    $res = $this->common->getRowValue('payment_request_id', 'payment_request', 'merchant_id', $this->merchant_id);
                    if ($res == false) {
                        $res2 = $this->common->getRowValue('pay_transaction_id', 'payment_transaction', 'merchant_id', $this->merchant_id);
                        if ($res2 == false) {
                            $res3 = $this->common->getRowValue('xway_transaction_id', 'xway_transaction', 'merchant_id', $this->merchant_id);
                            if ($res3 == false) {
                                $is_mobile = $this->mobileBrowser();
                                if ($is_mobile == false) {
                                    $this->session->set('help_hero_popup', 1);
                                }
                            }
                        }
                    }
                }
            }

            if ($service_id == 8) {
                if (substr($created_date, 0, 10) == date('Y-m-d')) {
                    if ($this->session->get('disable_cable_popup') != 1) {
                        $this->session->set('show_cable_popup', 1);
                        $popup = '#cable';
                    }
                }
            }
            //$this->setHotjar($created_date);
            $this->session->set('service_id', $service_id);
            if ($service_id == 9) {
                header('Location: /cable/settopbox', 301);
                die();
            }
            header('Location: /merchant/dashboard' . $popup, 301);
            die();
        } else {
            $service_id = $this->session->get('service_id');
            if ($service_id == null) {
                $last_service_id = $this->session->getCookie('last_service_id');
                if ($last_service_id == false) {
                    $service_id = 2;
                    $this->session->setCookie('last_service_id', 2);
                } else {
                    $service_id = $this->encrypt->decode($last_service_id);
                }
                if ($service_id != null) {
                    $service_id = $this->session->get('service_id');
                }
                $this->session->set('service_id', $service_id);
                header('Location: /merchant/dashboard/index/' . $this->encrypt->encode($service_id), 301);
                die();
            }
            if ($service_id == 1) {
                header('Location: /patron/dashboard', 301);
                die();
            }
        }
    }

    function index($service_id = null)
    {
        try {
            $this->setServiceInfo($service_id);
            $user_id = $this->session->get('userid');
            $merchant_id = $this->session->get('merchant_id');
            $profile_step = $this->session->get('profile_step');
            $this->view->profile_step = $profile_step;
            if ($profile_step > 0 && $profile_step < 4) {
                header('Location: /merchant/getting-started', 301);
                die();
            }
            
            $report_days = $this->session->getCookie('report_days');
            if ($report_days == false) {
                $report_days = 30;
            }
            $this->view->currency = $this->session->get('currency');
            $report_currency = $this->session->getCookie('report_currency');
            if ($report_currency == false) {
                $report_currency = $this->view->currency[0];
            }
            //find merchant display url
            $this->view->currency_icon = $this->common->getRowValue('icon', 'currency', 'code', $report_currency);
            $display_url = $this->session->get('display_url');
            $this->view->display_url = $display_url;
            $this->view->total_customer = $this->model->getCustomerCount($merchant_id);
            $total_invoice_sum = $this->model->getTotalInvoiceSum($merchant_id, $report_days, $report_currency);
            $total_paid_sum = $this->model->getPaidInvoiceSum($merchant_id, $report_days, $report_currency);
            if($this->view->currency_icon == '$'){
                $this->view->total_invoice_sum = number_format($total_invoice_sum);
                $this->view->total_paid_sum = number_format($total_paid_sum,2);
                $this->view->total_customer_display = number_format($this->view->total_customer);
            }else{
                $this->view->total_invoice_sum = $this->moneyFormatIndia(number_format($total_invoice_sum, 0));
                $this->view->total_paid_sum = $this->moneyFormatIndia(number_format($total_paid_sum, 0));
                $this->view->total_customer_display = $this->moneyFormatIndia($this->view->total_customer);
            }
            $this->view->paid_per = round($total_paid_sum * 100 / $total_invoice_sum);
            
            if ($total_invoice_sum == 0) {
                $this->view->has_invoice = $this->model->getInvoiceCount($merchant_id);
            } else {
                $this->view->has_invoice = 1;
            }

            // $this->view->total_transactions = $this->model->getTotalTransaction($merchant_id);
            // $this->session->set('total_transactions', $this->view->total_transactions);

            $this->view->profile_completed = $this->session->get('profile_completed');
            $this->view->merchant_plan = $this->session->get('merchant_plan');
            $this->view->is_payment_active = $this->session->get('is_payment_active');
            $this->view->is_xway_active = $this->session->get('is_xway_active');
            // $this->view->total_transactions = $this->session->get('total_transactions');
            $this->view->entity_type = $this->session->get('entity_type');
            $this->view->bank_verified = $this->session->get('bank_verified');
            $this->view->reg_city = $this->session->get('reg_city');
            $this->view->is_legal = $this->session->get('is_legal');

            //check edit company page is updated or not
            //$isCompletedCompayPage = $this->common->getRowValue('is_complete_company_page', 'merchant_landing', 'merchant_id', $merchant_id);
            $this->view->isCompletedCompayPage = $this->session->get('isCompletedCompayPage');

            $completion_percentage = 0;
            $circular_percentage = 0;
            if ($this->view->is_payment_active == false && $this->view->is_xway_active == true) {       // condition for paid merchant with PG only Xway
                if ($this->view->is_legal == 1) {
                    $completion_percentage += 66.67;
                } else {
                    if ($this->view->entity_type == 1) {
                        $completion_percentage += 16.67;
                    }
                    if ($this->view->reg_city == 1) {
                        $completion_percentage += 16.67;
                    }
                    if ($this->view->bank_verified == 1) {
                        $completion_percentage += 16.67;
                    }
                }
                if ($this->view->isCompletedCompayPage == 1) {
                    $completion_percentage += 33.33;
                }
            } else {
                if ($this->view->is_legal == 1) {
                    $completion_percentage += 66.67;
                } else {
                    if ($this->view->entity_type == 1) {
                        $completion_percentage += 16.67;
                    }
                    if ($this->view->reg_city == 1) {
                        $completion_percentage += 16.67;
                    }
                    if ($this->view->bank_verified == 1) {
                        $completion_percentage += 16.67;
                    }
                }
                if ($this->view->total_invoice > 0) {
                    $completion_percentage += 16.67;
                }
                if ($this->view->isCompletedCompayPage == 1) {
                    $completion_percentage += 16.67;
                }
            }
            if ($completion_percentage > 14 && $completion_percentage < 19) {
                $completion_percentage = 16.67;
                $circular_percentage = 17;
            }
            if ($completion_percentage > 30 && $completion_percentage < 35) {
                $completion_percentage = 33.33;
                $circular_percentage = 33;
            }
            if ($completion_percentage > 48 && $completion_percentage < 52) {
                $completion_percentage = 50;
                $circular_percentage = 50;
            }
            if ($completion_percentage > 64 && $completion_percentage < 68) {
                $completion_percentage = 66.67;
                $circular_percentage = 67;
            }
            if ($completion_percentage > 82 && $completion_percentage < 86) {
                $completion_percentage = 83.33;
                $circular_percentage = 83;
            }
            if ($completion_percentage > 95) {
                $completion_percentage = 100;
                $circular_percentage = 100;
            }

            $this->view->completion_percentage = $completion_percentage;
            $this->view->circular_percentage = $circular_percentage;

            $on_tr = $this->model->getMonthOnlineTransaction($user_id, $report_days, $report_currency);
            $ex_tr = $this->model->getMonthXwayTransaction($merchant_id, $report_days, $report_currency);
            //$settlementrow = $this->model->getMonthSettlement($merchant_id, $report_days);

            $settlementrow = $this->model->getMonthSettlement($user_id, $report_days, $report_currency);

            // if($settlementrow!=false)
            // {
            // 	$settlement=$settlementrow['sumamount'];
            //     $totalcapture = $settlement + $settlementrow['totaltdr'] + $settlementrow['totaltax'];
            // }else
            // {
            // 	$settlement=0;
            // 	$totalcapture=0;
            // }

            if ($settlementrow != false) {
                $settlement = $settlementrow['total'];
            } else {
                $settlement = 0;
            }

            $online_trans = $on_tr['unsettled'] + $ex_tr['unsettled'] + $on_tr['settled'] + $ex_tr['settled'];
            //$pending_settlement = $online_trans - $totalcapture;
            $pending_settlement = $on_tr['unsettled'] + $ex_tr['unsettled'];

            if ($pending_settlement < 0) {
                $pending_settlement = 0;
            }
            if($this->view->currency_icon == '$'){
                $this->view->pending_settlement = number_format($pending_settlement,2);
                $this->view->transaction = number_format($online_trans,2);
                $this->view->settlement = number_format($settlement,2);
            }else{
                $this->view->pending_settlement = $this->moneyFormatIndia(number_format($pending_settlement, 0));
                $this->view->transaction = $this->moneyFormatIndia(number_format($online_trans, 0));
                $this->view->settlement = $this->moneyFormatIndia(number_format($settlement, 0));
            }
            $this->view->settlement_per = round($settlement * 100 / $online_trans);
            if ($this->view->settlement_per > 100) {
                $this->view->settlement_per = 100;
            }
            //$notification = $this->model->getNotification($user_id);
            if ($this->session->has('merchant_notification')) {
                $merchant_notification = $this->session->get('merchant_notification');
            } else {
                $merchant_notification = $this->common->getListValue('merchant_notification', 'merchant_id', $merchant_id, 1, ' and notification_sent > DATE_SUB(now(), INTERVAL 6 MONTH) order by id desc limit 20');
                $this->session->set('merchant_notification', $merchant_notification);
            }
            $merchant_notification = $this->setNotificationTime($merchant_notification);
            $this->view->merchant_notification = $merchant_notification;
            $this->view->document_upload = $this->session->get('document_upload');

            $this->view->report_days = $report_days;
            $this->view->report_currency = $report_currency;

            $this->view->selectedMenu = array(1);
            $this->view->merchantType = $this->session->get('merchant_type');
            $this->view->title = $this->session->get('company_name') . ' Dashboard';
            $this->view->request_demo = $this->session->get('request_demo');
            $this->view->notification = array();
            $this->view->help_hero_popup = $this->session->get('help_hero_popup');
            $this->view->show_cable_popup = $this->session->get('show_cable_popup');
            $this->view->new_merchant = $this->session->get('new_merchant');

            $this->view->from_date = new DateTime(date('Y-m-d'));
            $this->view->to_date = new DateTime(date('Y-m-d'));

            if ($this->view->help_hero_popup == 0) {
                $this->session->remove('new_merchant');
            }
            $this->view->user_type = 'merchant';

            $this->view->js = array('dashboard');
            if ($this->view->is_legal == 0 || $this->view->total_customer < 2 || $this->view->total_invoice == 0) {
                $disable_get_started = $this->session->get('disable_get_started');
                $this->view->get_started = 1;
                if ($disable_get_started == false) {
                    $this->view->show_get_started = 1;
                }
            }
            $campaign_id = $this->session->getCookie('registration_campaign_id');
            if ($campaign_id == 11) {
                $this->view->software_suggest = 1;
                $this->session->removeCookie('registration_campaign_id');
            }

            $this->view->render('header/app');
            $this->view->render('merchant/dashboard/dashboard');
            $this->view->render('footer/mDashboard');
        } catch (Exception $e) {
            dd($e);
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001]Error while merchant dashboard initiate Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function setHotjar($date)
    {
        $OldDate = new DateTime($date);
        $now = new DateTime(Date('Y-m-d'));
        $array = $OldDate->diff($now);
        if ($array->days < 31) {
            $this->session->set('show_hotjar', 1);
        }
    }

    function reportdays($days, $type = 'report_days')
    {
        $this->session->setCookie($type, $days);
        header('Location: /merchant/dashboard', 301);
        die();
    }

    function enableupload()
    {
        $this->session->set('document_upload', true);
        header('Location: /merchant/dashboard', 301);
        die();
    }

    function update($type)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $this->model->seenNotification($this->session->get('userid'), $type);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E002]Error while update merchant notification Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function remindmelater($type = null)
    {
        try {
            if ($type == 'helphero') {
                $this->session->set('help_hero_popup', 0);
                $this->session->remove('document_upload');
            } elseif ($type == 'cable') {
                $this->session->set('show_cable_popup', 0);
                $this->session->set('disable_cable_popup', 1);
            } elseif ($type == 'getstarted') {
                $this->session->set('disable_get_started', 1);
            } else {
                $this->session->set('document_upload', false);
                $this->model->remindMeLater($this->merchant_id);
            }

            header('Location: /merchant/dashboard', 301);
            die();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E002]Error while update merchant notification Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function changelanguage($lang = 'english')
    {
        $this->session->remove('menus');
        $this->session->set('language', $lang);
        $service_id = $this->session->get('service_id');
        if ($service_id > 0) {
            $this->setMenu(1);
            if (isset($_SERVER['HTTP_REFERER'])) {
                header('Location: ' . $_SERVER['HTTP_REFERER'], 301);
            } else {
                header('Location: /merchant/dashboard', 301);
            }
        } else {
            header('Location: /merchant/dashboard/home', 301);
        }
        die();
    }

    function notifications()
    {
        $merchant_notification = $this->common->getListValue('merchant_notification', 'merchant_id', $this->merchant_id, 1, ' order by id desc');
        $merchant_notification = $this->setNotificationTime($merchant_notification);
        $this->view->merchant_notification = $merchant_notification;
        $this->view->render('header/app');
        $this->view->render('merchant/dashboard/notifications');
        $this->view->render('footer/mDashboard');
    }

    function setNotificationTime($merchant_notification)
    {
        $int = 0;
        foreach ($merchant_notification as $row) {
            $datetime1 = new DateTime($row['created_date']);
            $datetime2 = new DateTime();
            $interval = $datetime1->diff($datetime2);
            $min = $interval->format('%i');
            $hour = $interval->format('%h');
            $day = $interval->format('%d');
            $month = $interval->format('%m');
            $year = $interval->format('%y');
            if ($year > 0) {
                $merchant_notification[$int]['time'] = $year . ' Year';
            } else {
                if ($month > 0) {
                    $merchant_notification[$int]['time'] = $month . ' Month';
                } else {
                    if ($day > 0) {
                        $merchant_notification[$int]['time'] = $day . ' Day';
                    } else {
                        if ($hour > 0) {
                            $merchant_notification[$int]['time'] = $hour . ' Hour';
                        } else {
                            if ($min > 0) {
                                $merchant_notification[$int]['time'] = $min . ' Minutes';
                            } else {
                                $merchant_notification[$int]['time'] = 'Just now';
                            }
                        }
                    }
                }
            }

            $int++;
        }
        return $merchant_notification;
    }

    function requestdemo()
    {
        $date = isset($_POST['date_range']) ? $_POST['date_range'] : 'Today';
        $merchant_id = isset($_POST['merchantid']) ? $_POST['merchantid'] : 'New Merchant';
        $time = isset($_POST['time']) ? $_POST['time'] : 'Now';

        $this->model->updateRequestDemo($merchant_id);

        $title  = $this->session->get('company_name') . " requested for demo";
        $mobile  = $this->session->get('mobile');
        $description = "Date : " . $date . "\n  Time : " . $time . "\n  Merchant ID : " . $merchant_id . "\n  Contact Number : " . $mobile;
        $due_date = strtotime($date . " 17:00:00 GMT+0530");
        MerchantServiceAddToCRM::dispatch('REQUEST_A_DEMO', $merchant_id, $this->session->get('company_name'), $title, $description, 'web', $due_date)->onQueue(env('SQS_MERCHANT_REGISTRATION_UPDATE_CRM_QUEUE'));
        SupportTeamNotification::dispatch($title, $description, 'SUPPORT')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
    }
}
