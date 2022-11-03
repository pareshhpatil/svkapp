<?php

/**
 * Report controller class to handle reports for merchant
 */
class Chart extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->validateSession('merchant');
        header('Location: /404');
        die();
    }

    function billingstatus($is_iframe = 0)
    {
        try {
            $current_date = $this->date_add(0);
            $last_date = $this->date_add(-30);

            if (isset($_POST['from_date'])) {
                $this->view->from_date = $_POST['from_date'];
                $this->view->to_date = $_POST['to_date'];
                $customer_name = $_POST['customer_name'];
            } else {
                $this->view->from_date = $last_date;
                $this->view->to_date = $current_date;
                $customer_name = '';
            }
            $fromdate = new DateTime($this->view->from_date);
            $todate = new DateTime($this->view->to_date);
            $this->view->reportlist = $this->model->get_ChartInvoiceStatus($this->user_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
            $this->view->chartJS = 'https://www.amcharts.com/lib/3/pie.js';
            $title = "Billing status";

            $this->smarty->assign("from_date", $this->view->from_date);
            $this->smarty->assign("to_date", $this->view->to_date);
            $this->smarty->assign("list", $this->view->reportlist);
            $this->view->title = "Chart - " . $title;
            $this->smarty->assign("title", $title);
            if ($is_iframe == 1) {
                $this->view->render('merchant/chart/iframe_header');
                $this->view->render('merchant/chart/iframe_pie');
            } else {
                $this->view->header_file = ['chart'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/chart/pie.tpl');
                $this->view->render('footer/chart');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $this->user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function paymentmode($is_iframe = 0)
    {
        try {

            $current_date = $this->date_add(0);
            $last_date = $this->date_add(-30);

            if (isset($_POST['from_date'])) {
                $this->view->from_date = $_POST['from_date'];
                $this->view->to_date = $_POST['to_date'];
                $customer_name = $_POST['customer_name'];
            } else {
                $this->view->from_date = $last_date;
                $this->view->to_date = $current_date;
                $customer_name = '';
            }
            $fromdate = new DateTime($this->view->from_date);
            $todate = new DateTime($this->view->to_date);
            $this->view->reportlist = $this->model->get_ChartPaymentMode($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
            $this->view->chartJS = 'https://www.amcharts.com/lib/3/pie.js';
            $title = "Payment mode";
            $this->view->title = "Chart - " . $title;
            $this->smarty->assign("from_date", $this->view->from_date);
            $this->smarty->assign("to_date", $this->view->to_date);
            $this->smarty->assign("list", $this->view->reportlist);

            $this->smarty->assign("title", $title);
            if ($is_iframe == 1) {
                $this->view->render('merchant/chart/iframe_header');
                $this->view->render('merchant/chart/iframe_pie');
            } else {
                $this->view->header_file = ['chart'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/chart/pie.tpl');
                $this->view->render('footer/chart');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $this->user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function taxsummary($is_iframe = 0)
    {
        try {

            $current_date = $this->date_add(0);
            $last_date = $this->date_add(-30);

            if (isset($_POST['from_date'])) {
                $this->view->from_date = $_POST['from_date'];
                $this->view->to_date = $_POST['to_date'];
                $customer_name = $_POST['customer_name'];
            } else {
                $this->view->from_date = $last_date;
                $this->view->to_date = $current_date;
                $customer_name = '';
            }
            $fromdate = new DateTime($this->view->from_date);
            $todate = new DateTime($this->view->to_date);
            $this->view->reportlist = $this->model->get_ChartTax($this->user_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
            $this->view->chartJS = 'https://www.amcharts.com/lib/3/pie.js';
            $title = "Tax summary";
            $this->view->title = "Chart - " . $title;
            $this->smarty->assign("from_date", $this->view->from_date);
            $this->smarty->assign("to_date", $this->view->to_date);
            $this->smarty->assign("list", $this->view->reportlist);
            $this->smarty->assign("title", $title);
            if ($is_iframe == 1) {
                $this->view->render('merchant/chart/iframe_header');
                $this->view->render('merchant/chart/iframe_pie');
            } else {
                $this->view->header_file = ['chart'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/chart/pie.tpl');
                $this->view->render('footer/chart');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $this->user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function particularsummary($is_iframe = 0)
    {
        try {

            $current_date = $this->date_add(0);
            $last_date = $this->date_add(-30);

            if (isset($_POST['from_date'])) {
                $this->view->from_date = $_POST['from_date'];
                $this->view->to_date = $_POST['to_date'];
                $customer_name = $_POST['customer_name'];
            } else {
                $this->view->from_date = $last_date;
                $this->view->to_date = $current_date;
                $customer_name = '';
            }
            $fromdate = new DateTime($this->view->from_date);
            $todate = new DateTime($this->view->to_date);
            $this->view->reportlist = $this->model->get_ChartParticular($this->user_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
            $this->view->chartJS = 'https://www.amcharts.com/lib/3/pie.js';
            $title = "Particular summary";
            $this->view->title = "Chart - " . $title;
            $this->smarty->assign("from_date", $this->view->from_date);
            $this->smarty->assign("to_date", $this->view->to_date);
            $this->smarty->assign("list", $this->view->reportlist);
            $this->smarty->assign("title", $title);
            if ($is_iframe == 1) {
                $this->view->render('merchant/chart/iframe_header');
                $this->view->render('merchant/chart/iframe_pie');
            } else {
                $this->view->header_file = ['chart'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/chart/pie.tpl');
                $this->view->render('footer/chart');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $this->user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function paymentreceived($is_iframe = 0)
    {
        try {

            $current_date = $this->date_add(0);
            $last_date = $this->date_add(-15);

            if (isset($_POST['from_date'])) {
                $this->view->from_date = $_POST['from_date'];
                $this->view->to_date = $_POST['to_date'];
                $customer_name = $_POST['customer_name'];
            } else {
                $this->view->from_date = $last_date;
                $this->view->to_date = $current_date;
                $customer_name = '';
            }


            $fromdate = new DateTime($this->view->from_date);
            $todate = new DateTime($this->view->to_date);
            $fromdate = $fromdate->format('Y-m-d');
            $todate = $todate->format('Y-m-d');
            $date = array();
            $start_date = $fromdate;
            while ($start_date <= $todate) {
                $date['date'][] = $start_date;
                $date['value'][] = 0;

                $start_date = strtotime($start_date . ' 1 days');
                $start_date = date('Y-m-d', $start_date);
            }

            $this->view->reportlist = $this->model->get_ChartPaymentReceived($this->merchant_id, $fromdate, $todate);

            foreach ($this->view->reportlist as $value) {
                $key = array_search($value['name'], $date['date']);
                $date['value'][$key] = $value['value'];
            }
            $this->smarty->assign("from_date", $this->view->from_date);
            $this->smarty->assign("to_date", $this->view->to_date);
            $this->view->list = $date;
            $this->smarty->assign("list", $date);

            $this->view->chartJS = 'https://www.amcharts.com/lib/3/serial.js';
            $title = "Payment received";
            $this->view->title = "Chart - " . $title;
            $this->smarty->assign("title", $title);
            if ($is_iframe == 1) {
                $this->view->render('merchant/chart/iframe_header');
                $this->view->render('merchant/chart/iframe_serial');
            } else {
                $this->view->header_file = ['chart'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/chart/serial.tpl');
                $this->view->render('footer/chart');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $this->user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
