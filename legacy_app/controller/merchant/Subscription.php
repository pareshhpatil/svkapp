<?php

/**
 * Subscription controller class to handle subscription requests for patron
 */

use App\Libraries\Helpers;

class Subscription extends Controller
{

    function __construct()
    {
        parent::__construct();
        //TODO : Check if using static function is causing any problems!
        $this->validateSession('merchant');
        $this->view->js = array('template');
    }

    /**
     * Display subscription list
     */
    public function viewlist($link = null)
    {
        try {
            $this->hasRole(1, 5);
            $merchant_id = $this->session->get('merchant_id');
            $cycle_selected = isset($_POST['cycle_name']) ? $_POST['cycle_name'] : '';
            $cycle_list = $this->model->getCycleList($this->session->get('userid'));

            $this->view->selectedMenu = 'mysubscription';
            $this->smarty->assign("cycle_selected", $cycle_selected);
            $this->smarty->assign("cycle_list", $cycle_list);
            $this->smarty->assign("title", "Subscriptions");
            $this->smarty->assign("is_filter", "True");

            $this->smarty->assign('link', $link);
            // if (strlen($link) != 10) {
            //     $this->setInvalidLinkError();
            // }

            // $requestlist = $this->model->getSubscriptionList($merchant_id, $this->session->get('sub_franchise_id'));
            // $int = 0;
            // foreach ($requestlist as $item) {
            //     $requestlist[$int]['paylink'] = $this->encrypt->encode($item['payment_request_id']);
            //     $requestlist[$int]['created_at'] = $this->generic->formatTimeString($item['created_date']);
            //     $int++;
            // }

            $this->view->selectedMenu = array(5, 118);
            $this->view->hide_first_col = true;
            //$this->smarty->assign("requestlist", $requestlist);
            $this->view->title = "Subscriptions";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Sales', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end
            $this->session->set('valid_ajax', 'subscription-details');
            //$this->view->datatablejs = 'table-ellipsis-small';
            $jsarray[] = 'subscription';
            $this->view->js = $jsarray;

            $this->setAjaxDatatableSession();
            $this->view->ajaxpage = 'subscription.php';

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/subscription/list.tpl');
            $this->view->render('footer/request_list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E007]Error while payment request list initiate Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function view($request_id)
    {
        try {
            $this->hasRole(1, 5);
            $user_id = $this->session->get('userid');
            $request_id = $this->encrypt->decode($request_id);
            if (strlen($request_id) != 10) {
                $this->setInvalidLinkError();
            }
            $list = $this->model->getSubscriptionrequestList($request_id, $this->merchant_id);
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['paylink'] = $this->encrypt->encode($item['payment_request_id']);
                $int++;
            }
            $this->view->selectedMenu = 'mysubscription';
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", 'Subscription request');
            $this->view->title = "Subscription view";
            $this->view->datatablejs = 'table-ellipsis-small';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/subscription/subscriptionlist.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E239]Error while payment request list initiate Error: for merchant [' . $user_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Create new subscription request
     */
    function create()
    {
        try {
            $this->hasRole(2, 5);
            $merchant_id = $this->session->get('merchant_id');
            $user_id = $this->session->get('userid');
            $sub_franchise_id = $this->session->get('sub_franchise_id');
            $templatelist = $this->common->getTemplateList($user_id, $sub_franchise_id);
            $templatelist = $this->generic->getEncryptedList($templatelist, 'encrypted_id', 'template_id');
            $this->smarty->assign("request_type", 1);
            $jsarray[] = 'customer';
            $jsarray[] = 'template';
            $jsarray[] = 'coveringnote';
            $jsarray[] = 'subscription';
            $jsarray[] = 'invoiceformat';
            $this->view->js = $jsarray;
            $this->smarty->assign("templatelist", $templatelist);
            if (count($templatelist) == 1) {
                $_POST['selecttemplate'] = $templatelist[0]['template_id'];
            }
            if (isset($_POST['selecttemplate']) && $_POST['selecttemplate'] != '') {
                require_once CONTROLLER . 'InvoiceWrapper.php';
                $invWrap = new InvoiceWrapper($this->common);
                $reInfo = $invWrap->setCreateInvoice($_POST['selecttemplate'], $merchant_id, $user_id, 'subscription');
                foreach ($reInfo['smarty'] as $key => $value) {
                    $this->smarty->assign($key, $value);
                }
                $this->view->function_script = $reInfo['function_script'];
            }
            $this->smarty->assign("title", 'Create subscription&nbsp;');
            $this->view->selectedMenu = array(3, 21);
            $this->view->title = "Create subscription";
            $this->view->header_file = ['create_invoice'];
            $this->view->render('header/app');
            $this->smarty->assign("subscription", 1);
            $this->smarty->display(VIEW . 'merchant/invoice/select.tpl');
            if (isset($_POST['selecttemplate'])) {
                $template_type = $reInfo['template_type'];
                if ($template_type != 'travel') {
                    $template_type = 'particular';
                }
                $this->smarty->display(VIEW . 'merchant/subscription/invoice_header.tpl');
                $this->smarty->display(VIEW . 'merchant/invoice/create_' . $template_type . '.tpl');
                $this->smarty->display(VIEW . 'merchant/invoice/invoice_footer.tpl');
            }
            $this->smarty->display(VIEW . 'merchant/template/supplier.tpl');
            $this->smarty->display(VIEW . 'merchant/invoice/footer.tpl');
            $this->view->render('footer/create_event');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E003]Error while merchant invoice create initiate Error: for merchant [' . $merchant_id . '] and for template [' . $this->view->templateselected . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function delete($link)
    {
        try {
            $this->hasRole(3, 5);
            $payment_request_id = $this->encrypt->decode($link);
            $subscription_id = $this->common->getRowValue('subscription_id', 'subscription', 'payment_request_id', $payment_request_id);
            $auto_subscription_id = $this->common->getRowValue('subscription_id', 'autocollect_subscriptions', 'invoice_subscription_id', $subscription_id);
            if ($auto_subscription_id > 0) {
                Helpers::APIrequest('v1/autocollect/subscription/delete/' . $auto_subscription_id, '', 'GET');
            }
            $this->model->deleteSubscription($payment_request_id, $this->user_id);
            $this->session->set('successMessage', 'Subscription have been deleted.');
            header("Location:" . $_SERVER["HTTP_REFERER"]);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E011-87]Error while delete invoice Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Update exist subscription request
     */
    function update($link)
    {
        try {
            $this->hasRole(2, 5);
            $merchant_id = $this->session->get('merchant_id');
            $user_id = $this->session->get('userid');

            $payment_request_id = $this->encrypt->decode($link);
            $info = $this->common->getPaymentRequestDetails($payment_request_id, $merchant_id);
            if (empty($info)) {
                SwipezLogger::error(__CLASS__, '[E008-1]Error while initiate update invoice Error: invalid status for merchant [' . $merchant_id . '] and for payment request id ' . $payment_request_id);
                $this->setGenericError();
            }

            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $smarty = $invoice->setUpdateInvoiceSmarty($info);
            foreach ($smarty as $key => $value) {
                $this->smarty->assign($key, $value);
            }

            $subscription = $this->model->getSubscription($payment_request_id);
            $this->smarty->assign("subscription", $subscription);
            $date = new DateTime($subscription['start_date']);
            $start_date = $date->format('d M Y');
            $this->smarty->assign("start_date", $start_date);
            $date = new DateTime($subscription['due_date']);
            $due_date = $date->format('d M Y');
            $this->smarty->assign("due_date", $due_date);
            $weeks = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

            $this->view->function_script = $smarty['function_script'];
            $template_type = $smarty['template_type'];

            $this->view->title = 'Update Subscription';
            $this->view->selectedMenu = 'mysubscription';
            $jsarray[] = 'coveringnote';
            $jsarray[] = 'customer';
            $jsarray[] = 'template';
            $jsarray[] = 'invoiceformat';
            $this->view->js = $jsarray;


            if ($template_type != 'travel') {
                $template_type = 'particular';
            }
            $this->view->header_file = ['create_invoice'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/subscription/update_header.tpl');
            $this->smarty->display(VIEW . 'merchant/invoice/update_' . $template_type . '.tpl');
            $this->smarty->display(VIEW . 'merchant/invoice/update_footer.tpl');
            $this->smarty->display(VIEW . 'merchant/template/supplier.tpl');
            $this->smarty->display(VIEW . 'merchant/invoice/footer.tpl');
            $this->view->render('footer/create_event');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E005]Error while sending payment request Error: for merchant [' . $merchant_id . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function success()
    {
        try {
            $this->view->title = 'Subscription created';
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/subscription/success.tpl');
            $this->view->render('footer/profile');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E005]Error while sending payment request Error: for merchant [' . $merchant_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getSubscriptionDetails()
    {
        $valid_ajax = $this->session->get('valid_ajax');
        if ($valid_ajax == 'subscription-details') {
            $request_id = $_POST['subscription_id'];
            $request_id = $this->encrypt->decode($request_id);
            if (strlen($request_id) != 10) {
                $this->setInvalidLinkError();
            }

            $subscriptionPageData = $this->model->getSummaryPageDetails($request_id, $this->merchant_id);
            $subscriptionPageData['currency_icon'] = $this->common->getRowValue('icon', 'currency', 'code', $subscriptionPageData['details']['currency']);
            $this->smarty->assign("subscription_summary", $subscriptionPageData);
            $this->smarty->assign("list", $subscriptionPageData['list']);
            $this->smarty->display(VIEW . 'merchant/subscription/subscription_view_detail.tpl');
            //echo json_encode($list);
        }
    }
}
