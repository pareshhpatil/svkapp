<?php
/**
 * Report controller class to handle reports for merchant
 */
class Report extends Controller
{

    private $has_error = false;

    function __construct()
    {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->userName = $this->session->get('display_name');
        $this->view->lastsortenable = 1;
        // $this->validateDateDuration();
        $this->view->selectedMenu = array(13);
        //find company column name from customer metadata
        if ($this->session->has('customer_company_label')) {
            $this->get_custom_company_col_name = $this->session->get('customer_company_label');
            $this->smarty->assign("company_column_name", $this->session->get('customer_company_label'));
        } else {
            $this->get_custom_company_col_name = $this->common->getRowValue('column_name', 'customer_column_metadata', 'column_datatype', 'company_name', 1, " and merchant_id='" . $this->merchant_id . "'");
            $this->get_custom_company_col_name =  ($this->get_custom_company_col_name != false) ? $this->get_custom_company_col_name : 'Company name';
            $this->smarty->assign("company_column_name", $this->get_custom_company_col_name);
            $this->session->set('customer_company_label', $this->get_custom_company_col_name);
        }
    }

    function index()
    {
        $this->view->title = 'Reports';
        $this->smarty->assign("service_id", $this->session->get('service_id'));
        $this->view->header_file = ['profile'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/report/landing.tpl');
        $this->view->render('footer/profile');
    }

    function validateDateDuration()
    {
        $_SESSION['has_error'] = false;
        if ($_POST['from_date']) {
            $date1 = date_create($_POST['from_date']);
            $date2 = date_create($_POST['to_date']);
            $diff = date_diff($date1, $date2);
            $days = $diff->format("%a");
            if ($days > 92) {
                $this->smarty->assign("haserrors", 'Please change your search criteria. Maximum date range allowed is 3 months.');
                $this->has_error = true;
                $_SESSION['has_error'] = true;
            }
        }
    }

    /**
     * Display merchant dashboard
     */
    function customerbalance()
    {
        try {
            $user_id = $this->session->get('userid');
            $customer_selected = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
            $customer_list = $this->model->getMerchantCustomer($this->merchant_id);
            $this->smarty->assign("customer_selected", $customer_selected);
            $this->smarty->assign("customer_list", $customer_list);
            $current_date = date("d M Y");

            $last_date = $this->getLast_date();

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $customer_name = $_POST['customer_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $customer_name = '';
            }
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                $reportlist = $this->model->get_ReportCustomerBalance($user_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $customer_name);
            }
            $this->smarty->assign("reportlist", $reportlist);
            $this->smarty->assign("title", "Customer balance report");
            $this->view->title = "Report Customer balance";
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/customer_balance.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function agingsummary()
    {
        try {
            $user_id = $this->session->get('userid');

            $redis_items = $this->getSearchParamRedis('aging_summary_report');
            
            $aging_selected = isset($_POST['aging']) ? $_POST['aging'] : 4;
            $aging_by_selected = isset($_POST['aging_by']) ? $_POST['aging_by'] : 'bill_date';
            $interval = isset($_POST['interval']) ? $_POST['interval'] : 15;

            $customer_selected = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
            $customer_list = $this->model->getMerchantCustomer($this->merchant_id);
            $this->smarty->assign("customer_selected", $customer_selected);
            $this->smarty->assign("customer_list", $customer_list);

            $current_date = date("d M Y");

            $last_date = $this->getLast_date();

            $column = array();
            $column_display = array();
            $column[] = 'customer_code';
            $column[] = 'customer_name';
            $column[] = 'company_name';
            $column[] = 'current';
            $column_display[] = 'Customer code';
            $column_display[] = 'Contact person name';
            $column_display[] = ($this->get_custom_company_col_name != false) ? $this->get_custom_company_col_name : 'Company name';
            $column_display[] = 'Today';
            $int = 1;
            $start = 1;
            $max = 0;
            while ($int < $aging_selected) {
                $start_interval = $start + $max;
                $max = $max + $interval;
                $column[] = $start_interval . '_to_' . $max;
                $column_display[] = $start_interval . ' to ' . $max;
                $int++;
            }
            $column[] = 'above_' . $max;
            $column[] = 'total';
            $column_display[] = '> ' . $max;
            $column_display[] = 'Total';
            $columns = $column;
            $display_columns = $column_display;
            $this->smarty->assign("columns", $columns);
            $this->smarty->assign("display_columns", $display_columns);

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $aging_by = $_POST['aging_by'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $customer_name = '';
                $aging_by = 'bill_date';
            }

            //find last search criteria into Redis 
            if(isset($redis_items['aging_summary_report']['search_param']) && $redis_items['aging_summary_report']['search_param']!=null) {
                $from_date = $redis_items['aging_summary_report']['search_param']['payment_status'];
                $to_date = $redis_items['aging_summary_report']['search_param']['column_name'];
                $interval = $redis_items['aging_summary_report']['search_param']['interval'];
                $aging_by_selected = $redis_items['aging_summary_report']['search_param']['aging_by'];
                $aging_selected = $redis_items['aging_summary_report']['search_param']['aging'];
            }

            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                $reportlist = $this->model->get_ReportAgingSummary($user_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $aging_selected, $interval, $aging_by);
            }

            $this->smarty->assign("interval", $interval);
            $this->smarty->assign("aging_by_selected", $aging_by_selected);
            $this->smarty->assign("aging_selected", $aging_selected);
            $this->smarty->assign("reportlist", $reportlist);
            $this->smarty->assign("title", "Aging summary");
            $this->view->datatablejs = 'table-sum-ellipsis-small-statesave';  //old value table-sum-ellipsis-small
            $this->view->title = "Aging summary";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Invoicing', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->sum_column = $aging_selected + 3;
            $this->view->header_file = ['list'];
            $this->view->list_name = 'aging_summary_report';
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/aging_summary.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function agingdetails()
    {
        try {
            $redis_items = $this->getSearchParamRedis('agingdetails_report');

            $user_id = $this->session->get('userid');
            $aging_by_selected = isset($_POST['aging_by']) ? $_POST['aging_by'] : 'bill_date';
            $customer_selected = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
            $customer_list = $this->model->getMerchantCustomer($this->merchant_id);
            $current_date = date("d M Y");

            $last_date = $this->getLast_date();

            $merchant_created_date = $this->session->get('created_date');
            $merchant_start_date = date('d M Y', strtotime($merchant_created_date));

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $aging_by = $_POST['aging_by'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $aging_by = 'bill_date';
            }

             //find last search criteria into Redis 
             if(isset($redis_items['agingdetails_report']['search_param']) && $redis_items['agingdetails_report']['search_param']!=null) {
                $from_date = $redis_items['agingdetails_report']['search_param']['from_date'];
                $to_date = $redis_items['agingdetails_report']['search_param']['to_date'];
                $customer_selected = $redis_items['agingdetails_report']['search_param']['customer_name'];
                $aging_by_selected = $redis_items['agingdetails_report']['search_param']['aging_by'];
            }

            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $this->smarty->assign("customer_selected", $customer_selected);
            $this->smarty->assign("customer_list", $customer_list);
            $this->smarty->assign("aging_by_selected", $aging_by_selected);
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                $reportlist = $this->model->get_ReportAgingDetail($user_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $customer_selected, $aging_by);
                $reportlist = $this->generic->getEncryptedList($reportlist, 'link', 'payment_request_id');
            }
            
            $this->smarty->assign("reportlist", $reportlist);
            if ($this->session->get('configure_invoice_statues')) {
                $invoice_statues = $this->session->get('configure_invoice_statues');
            }
            $this->smarty->assign("custom_invoice_status", json_decode($invoice_statues,true));
            $this->smarty->assign("title", "Unpaid invoices");
            $this->view->title = "Unpaid invoices";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Invoicing', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-ellipsis-small-statesave'; //old value table-ellipsis-small
            $this->view->sum_column = $reportlist[0]['display_invoice_no'] + 9;
            $this->view->startDate = $merchant_start_date;
            $this->view->show_all_records = 1;
            $this->view->header_file = ['list'];
            $this->view->list_name = 'agingdetails_report';
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/aging_detail.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function invoicedetails($type = null)
    {
        try {
            $user_id = $this->session->get('userid');
            $redis_items = $this->getSearchParamRedis('invoicedetails_report'.$type);
            
            
            #SwipezLogger::info(__CLASS__, "Invoice details invoked by $user_id");
            $aging_by_selected = isset($_POST['aging_by']) ? $_POST['aging_by'] : 'bill_date';
            $cycle_selected = isset($_POST['billing_cycle_name']) ? $_POST['billing_cycle_name'] : '';
            $customer_selected = isset($_POST['customer_id']) ? $_POST['customer_id'] : '';
            $invoice_type = ($type == 'estimate') ? 2 : 1;

            $column_list = $this->model->getColumnList($user_id);
            $customer_list = $this->model->getMerchantCustomer($this->merchant_id);
            $addcolumn[] = array('column_name' => 'Email');
            $addcolumn[] = array('column_name' => 'Mobile');
            $addcolumn[] = array('column_name' => 'Address');
            $addcolumn[] = array('column_name' => 'City');
            $addcolumn[] = array('column_name' => 'State');
            $addcolumn[] = array('column_name' => 'Zipcode');
            $addcolumn[] = array('column_name' => 'Type');
            $addcolumn[] = array('column_name' => 'Created by');
            $addcolumn[] = array('column_name' => 'Settled by');
            $addcolumn[] = array('column_name' => 'Franchise name');
            $addcolumn[] = array('column_name' => 'Vendor name');
            $column_list = array_merge($addcolumn, $column_list);
            
            $statuslist = $this->model->getStatusList();
            if (empty($statuslist)) {
                SwipezLogger::warn(__CLASS__, '[E006]Fetching empty payment request status list for merchant [' . $user_id . '] ');
            } else {
                $updated_status_list = $this->setStatusList($statuslist);
                $this->smarty->assign("statuslist", $updated_status_list);
            }

            $current_date = date("d M Y");
            $franchise_id = 0;
            $vendor_id = 0;
            
            $last_date = $this->getLast_date();
            $this->view->checkedlist = '';
            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $customer_name = $_POST['customer_id'];
                $aging_by = $_POST['aging_by'];
                
                $status = implode(',', $_POST['status']);
                $column_select = $_POST['column_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $customer_name = '';
                $aging_by = 'last_update_date';
                $status = '';
                $column_select = array();
            }
            //find last search criteria into Redis 
            if(isset($redis_items['invoicedetails_report'.$type]['search_param']) && $redis_items['invoicedetails_report'.$type]['search_param']!=null) {
                $from_date = $redis_items['invoicedetails_report'.$type]['search_param']['from_date'];
                $to_date = $redis_items['invoicedetails_report'.$type]['search_param']['to_date'];
                $cycle_selected = $redis_items['invoicedetails_report'.$type]['search_param']['billing_cycle_name'];
                $customer_selected = $redis_items['invoicedetails_report'.$type]['search_param']['customer_id'];
                $aging_by_selected =  $redis_items['invoicedetails_report'.$type]['search_param']['aging_by'];
                $_POST['status'] = $redis_items['invoicedetails_report'.$type]['search_param']['status'];
                $status = implode(',', $_POST['status']);
                $group = $_POST['group'] = $redis_items['invoicedetails_report'.$type]['search_param']['group'];
                $column_select = $redis_items['invoicedetails_report'.$type]['search_param']['column_name'];
                $franchise_id = $_POST['franchise_id'] = isset($redis_items['invoicedetails_report'.$type]['search_param']['franchise_id']) ? $redis_items['invoicedetails_report'.$type]['search_param']['franchise_id'] : 0;
                $vendor_id = $_POST['vendor_id'] = isset($redis_items['invoicedetails_report'.$type]['search_param']['vendor_id']) ? $redis_items['invoicedetails_report'.$type]['search_param']['vendor_id'] : 0;
            }
            
            $this->view->showLastRememberSearchCriteria = true;
            
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);

            $cycle_list = $this->model->getCycleList($user_id);

            $this->smarty->assign("cycle_selected", $cycle_selected);
            $this->smarty->assign("cycle_list", $cycle_list);
            $this->smarty->assign("column_list", $column_list);
            $this->smarty->assign("customer_list", $customer_list);
            $this->smarty->assign("customer_selected", $customer_selected);
            $this->smarty->assign("aging_by_selected", $aging_by_selected);
            $this->smarty->assign("checkedlist", $_POST['status']);
            if (count($column_select) > 5) {
                $column_select = array();
                $this->smarty->assign("haserrors", "Column name selected more than 5 values");
            }
            $this->smarty->assign("column_select", $column_select);

            $_SESSION['display_column'] = $column_select;

            $is_settle = 0;
            foreach ($column_select as $key => $value) {
                if ($value == 'Setteled by') {
                    $is_settle = 1;
                }
                if ($value == 'Email' || $value == 'Mobile' || $value == 'Type' || $value == 'Address' || $value == 'City' || $value == 'State' || $value == 'Zipcode' || $value == 'Created by' || $value == 'Setteled by' || $value == 'Franchise name' || $value == 'Vendor name') {
                    unset($column_select[$key]);
                }
            }
            $_SESSION['column_select'] = $column_select;
            
            $this->smarty->assign("invoice_type", $invoice_type);
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $group = isset($_POST['group']) ? $_POST['group'] : '';
            $franchise_id = isset($_POST['franchise_id']) ? $_POST['franchise_id'] : $franchise_id;
            $vendor_id = isset($_POST['vendor_id']) ? $_POST['vendor_id'] : $vendor_id;

            if ($this->view->franchise == 1) {
                $sub_franchise_id = $this->session->get('sub_franchise_id');
                if ($sub_franchise_id > 0) {
                    $franchise_id = $sub_franchise_id;
                    $this->smarty->assign("franchise_id", $sub_franchise_id);
                    $franchise = $this->common->getSingleValue('franchise', 'franchise_id', $sub_franchise_id, 1);
                    if (!empty($franchise)) {
                        $franchise_list[] = $franchise;
                    }
                } else {
                    $this->smarty->assign("franchise_id", $franchise_id);
                    $franchise_list = $this->common->getListValue('franchise', 'merchant_id', $this->merchant_id, 1, 'and status=1');
                }
                $this->smarty->assign("franchise_list", $franchise_list);
            }
            if ($this->view->vendor_enable == 1) {
                $this->smarty->assign("vendor_id", $vendor_id);
                $vendor_list = $this->common->getListValue('vendor', 'merchant_id', $this->merchant_id, 1, 'and status=1');
                $this->smarty->assign("vendor_list", $vendor_list);
            }

            $billing_profile_id = ($_POST['billing_profile_id'] > 0) ? $_POST['billing_profile_id'] : 0;
            $billing_profile_list = $this->common->getListValue('merchant_billing_profile', 'merchant_id', $this->merchant_id, 1);
            $this->smarty->assign("billing_profile_list", $billing_profile_list);
            $this->smarty->assign("billing_profile_id", $billing_profile_id);


            if ($group != '') {
                $where = " where customer_group like ~%" . '{' . $group . '}' . '%~';
            } else {
                $where = '';
            }

            if ($billing_profile_id > 0) {
                if ($where != '') {
                    $where .= ' and billing_profile_id =' . $billing_profile_id;
                } else {
                    $where = ' where billing_profile_id =' . $billing_profile_id;
                }
            }

            $_SESSION['_from_date'] = $fromdate->format('Y-m-d');
            $_SESSION['_to_date'] = $todate->format('Y-m-d');
            $_SESSION['_cycle_name'] = $cycle_selected;
            $_SESSION['_customer_id'] = $customer_selected;
            $_SESSION['_status'] = $status;
            $_SESSION['_aging_by'] = $aging_by;
            $_SESSION['_is_settle'] = $is_settle;
            $_SESSION['_franchise_id'] = $franchise_id;
            $_SESSION['_vendor_id'] = $vendor_id;
            $_SESSION['_group'] = $group;
            $_SESSION['_invoice_type'] = $invoice_type;
            $_SESSION['_billing_profile_id'] = $billing_profile_id;
            if ($this->session->get('configure_invoice_statues')) {
                $invoice_statues = $this->session->get('configure_invoice_statues');
                $_SESSION['_custom_invoice_status'] = json_decode($invoice_statues,true);
            } else {
                $_SESSION['_custom_invoice_status'] = '';
            }
            if ($this->has_error == false) {
                if (isset($_POST['exportExcel'])) {
                    $reportlist = $this->model->getReportInvoiceDetail($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $invoice_type, $cycle_selected, $customer_selected, $status, $aging_by, $column_select, $is_settle, $franchise_id, $vendor_id, $where);
                    $this->common->excelexport('InvoiceDetails', $reportlist);
                } else if (isset($_POST['exportPDF'])) {
                    if (!empty($_POST['pdf_req'])) {
                        $this->PDFExport("$from_date - $to_date", $user_id, $_POST['pdf_req']);
                    }
                }
            }

            $this->smarty->assign("group", $group);

            $where = '';
            $login_cust_group = $this->session->get('login_customer_group');
            if (isset($login_cust_group)) {
                $where = " and group_id in (" . implode(",", $login_cust_group) . ')';
            }
            $customer_group = $this->common->getListValue('customer_group', 'merchant_id', $this->merchant_id, 1, $where);

            $this->smarty->assign("customer_group", $customer_group);
            $this->view->sum_column = 10;
            $this->setAjaxDatatableSession();
            $this->view->ajaxpage = 'invoicedetail.php';
            if ($type == null) {
                $this->smarty->assign("title", "Invoice details");
                $this->view->title = "Invoice details";
            } else {
                $this->smarty->assign("title", "Estimate details");
                $this->view->title = "Estimate details";
            }

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Invoicing', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['list'];
            $this->view->list_name= 'invoicedetails_report'.$type;
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/invoice_detail.tpl');
            $this->view->render('footer/request_list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function taxdetails()
    {
        try {
            ini_set('max_execution_time', 120);
            $user_id = $this->session->get('userid');
            $current_date = date("d M Y");
            $last_date = $this->getLast_date();
            $this->view->checkedlist = '';

            $redis_items = $this->getSearchParamRedis('tax_details_report');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $column_select = $_POST['column_name'];
                $template_id = $_POST['template_id'];
                if (count($column_select) > 5) {
                    //$this->smarty->assign("haserrors", 'Columns selected more than 5 values.');
                    // $column_select = array();
                }
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $customer_name = '';
                $aging_by = 'last_update_date';
                $column_select = array();
                $template_id = '';
            }

            //find last search criteria into Redis 
            if(isset($redis_items['tax_details_report']['search_param']) && $redis_items['tax_details_report']['search_param']!=null) {
                $from_date = $redis_items['tax_details_report']['search_param']['from_date'];
                $to_date = $redis_items['tax_details_report']['search_param']['to_date'];
                $template_id = $redis_items['tax_details_report']['search_param']['template_id'];
                $column_select = $redis_items['tax_details_report']['search_param']['column_name'];
                $_POST['status'] = $redis_items['tax_details_report']['search_param']['status'];
            }

            $status_selected = isset($_POST['status']) ? $_POST['status'] : '';
            $status_list = $this->common->getListValue('config', 'config_type', 'payment_request_status');
            if (empty($status_list)) {
                SwipezLogger::warn(__CLASS__, 'Fetching empty payment transaction status list for merchant [' . $this->merchant_id . '] ');
            } else {
                $updated_status_list = $this->setStatusList($status_list);
                
                $this->smarty->assign("status_selected", $status_selected);
                $this->smarty->assign("status_list", $updated_status_list);
            }

            $sub_franchise_id = $this->session->get('sub_franchise_id');
            $templatelist = $this->common->getTemplateList($user_id, $sub_franchise_id);
            if (count($templatelist) == 1) {
                $template_id = $templatelist[0]['template_id'];
            }
            $custom_columns = array();
            $template_type = $this->common->getRowValue('template_type', 'invoice_template', 'template_id', $template_id);
            $column_list = $this->model->getColumnList($template_id);
            foreach ($column_list as $col) {
                $custom_columns[$col['column_name']] = $col['column_value'];
            }
            $cust_col_list = $this->model->getCustomerColumnList($template_id);

            foreach ($cust_col_list as $col) {
                if ($this->get_custom_company_col_name != $col['column_name']) {
                    $custom_columns['_C_' . $col['customer_column_id']] = $col['column_name'];
                }
            }

            $billing_profile_id = ($_POST['billing_profile_id'] > 0) ? $_POST['billing_profile_id'] : 0;
            $billing_profile_list = $this->common->getListValue('merchant_billing_profile', 'merchant_id', $this->merchant_id, 1);
            $this->smarty->assign("billing_profile_list", $billing_profile_list);
            $this->smarty->assign("billing_profile_id", $billing_profile_id);


            $ignore_col = array('Email', 'Mobile', 'Address', 'Gst Number', 'City', 'Transaction amount', 'State', 'Zipcode', 'Transaction id', 'Transaction ref no', 'Settlement ref no', 'TDR', 'TDR GST', 'Settled Amount', 'Settlement date', '_P_Particulars', '_T_Taxes', 'Advance Received');
            $addcolumn[] = array('column_name' => 'Email');
            $addcolumn[] = array('column_name' => 'Mobile');
            $addcolumn[] = array('column_name' => 'Address');
            $addcolumn[] = array('column_name' => 'Gst Number');
            $addcolumn[] = array('column_name' => 'City');
            $addcolumn[] = array('column_name' => 'State');
            $addcolumn[] = array('column_name' => 'Zipcode');
            foreach ($cust_col_list as $aa) {
                if ($this->get_custom_company_col_name != $aa['column_name']) {
                    $addcolumn[] = array('column_name' => '_C_' . $aa['customer_column_id'], 'column_value' => $aa['column_name']);
                }
            }
            $addcolumn[] = array('column_name' => '_P_' . 'Particulars', 'column_value' => 'Particulars');
            $addcolumn[] = array('column_name' => '_T_' . 'Taxes', 'column_value' => 'Taxes');
            $addcolumn[] = array('column_name' => 'Transaction id');
            $addcolumn[] = array('column_name' => 'Transaction ref no');
            $addcolumn[] = array('column_name' => 'Transaction amount');
            $addcolumn[] = array('column_name' => 'TDR');
            $addcolumn[] = array('column_name' => 'TDR GST');
            $addcolumn[] = array('column_name' => 'Settled Amount');
            $addcolumn[] = array('column_name' => 'Settlement ref no');
            $addcolumn[] = array('column_name' => 'Settlement date');
            $addcolumn[] = array('column_name' => 'Advance Received');
            $column_list = array_merge($addcolumn, $column_list);
            $this->smarty->assign("column_list", $column_list);
            $statuslist = $this->model->getStatusList();
            if (empty($statuslist)) {
                SwipezLogger::warn(__CLASS__, '[E006]Fetching empty payment request status list for merchant [' . $user_id . '] ');
            } else {
                $this->smarty->assign("statuslist", $statuslist);
            }
            $customer_column = array();
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $this->smarty->assign("template_id", $template_id);
            $this->smarty->assign("templatelist", $templatelist);

            $colselect = $column_select;
            $colselect_all = $column_select;
            $is_settle = 0;
            foreach ($column_select as $key => $value) {
                if (substr($value, 0, 3) == '_C_') {
                    unset($column_select[$key]);
                    $customer_column[] = substr($value, 3);
                    $colselect_all[] = $custom_columns[$value];
                    unset($colselect_all[$key]);
                }

                if (is_numeric($value)) {
                    $colselect_all[] = $custom_columns[$value];
                    unset($colselect_all[$key]);
                }
                if (in_array($value, $ignore_col)) {
                    unset($column_select[$key]);
                }
            }
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                $reportlist = $this->model->getReportTaxDetail($this->merchant_id, $template_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $status_selected, $column_select, $customer_column, $billing_profile_id);
            }
            $custom_particular = array();
            $particular_meta = array();
            $tax_meta = array();

            $particular_json = $this->common->getRowValue('particular_column', 'invoice_template', 'template_id', $template_id);
            $particular_column = json_decode($particular_json, 1);
            unset($particular_column['sr_no']);
            if ($template_type == 'franchise') {
                $colselect_all[] = 'Commision fee percent';
                $colselect_all[] = 'Commision waiver percent';
                $colselect_all[] = 'Commision net percent';
                $colselect_all[] = 'Gross sale';
                $colselect_all[] = 'Net sale';
                $colselect_all[] = 'Gross fee';
                $colselect_all[] = 'Waiver fee';
                $colselect_all[] = 'Net fee';
                $colselect_all[] = 'Penalty';
                $colselect_all[] = 'Bill period';
            }
            foreach ($reportlist as $key => $rp) {
                foreach ($colselect as $colkk => $col) {
                    if ($col == '_P_Particulars') {
                        $particularrows = $this->model->getParticularRows($rp['invoice_id']);
                        if (!empty($particularrows)) {
                            // SwipezLogger::debug('Count', count($particularrows));
                            $int = 1;
                            foreach ($particularrows as $row) {
                                $extra = ($int > 1) ? $int : '';
                                foreach ($particular_column as $pk => $pv) {
                                    $reportlist[$key][str_replace(' ', '_', '__' . $pv . $extra)] = $row[$pk];
                                    if (!in_array($pv . $extra, $colselect_all)) {
                                        $colselect_all[] = $pv . $extra;
                                    }
                                }
                                $int++;
                            }
                        }
                    }

                    if ($col == '_T_Taxes') {
                        $tax_rows = $this->model->getTaxRows($rp['invoice_id']);
                        foreach ($tax_rows as $row) {
                            $reportlist[$key][str_replace(' ', '_', '__' . $row['tax_name'])] = $row['tax_amount'];
                            if (!in_array($row['tax_name'], $colselect_all)) {
                                $colselect_all[] = $row['tax_name'];
                            }
                        }
                    }

                    if (substr($col, 0, 3) == '_C_') {
                        $tcol = 'CUST__' . substr($col, 3);
                        $ccol = str_replace(' ', '_', $custom_columns[$col]);
                        $ccol = '__' . str_replace('.', '', $ccol);
                        $reportlist[$key][$ccol] = $rp[$tcol];
                        unset($reportlist[$key][$tcol]);
                    }

                    if (is_numeric($col)) {
                        $tcol = 'META__' . $col;
                        $ccol = str_replace(' ', '_', $custom_columns[$col]);
                        $ccol = '__' . str_replace('.', '', $ccol);
                        $reportlist[$key][$ccol] = $rp[$tcol];
                        unset($reportlist[$key][$tcol]);
                    }
                }

                if ($template_type == 'franchise') {
                    $summary = $this->common->getSingleValue('invoice_food_franchise_summary', 'payment_request_id', $rp['invoice_id'], 1);
                    $reportlist[$key]['__Commision_fee_percent'] = $summary['commision_fee_percent'];
                    $reportlist[$key]['__Commision_waiver_percent'] = $summary['commision_waiver_percent'];
                    $reportlist[$key]['__Commision_net_percent'] = $summary['commision_net_percent'];
                    $reportlist[$key]['__Gross_sale'] = $summary['gross_sale'];
                    $reportlist[$key]['__Net_sale'] = $summary['net_sale'];
                    $reportlist[$key]['__Gross_fee'] = $summary['gross_fee'];
                    $reportlist[$key]['__Waiver_fee'] = $summary['waiver_fee'];
                    $reportlist[$key]['__Net_fee'] = $summary['net_fee'];
                    $reportlist[$key]['__Penalty'] = $summary['penalty'];
                    $reportlist[$key]['__Bill_period'] = $summary['bill_period'];
                }
            }
            $this->smarty->assign("column_select", $colselect_all);
            if (isset($_POST['exportExcel'])) {

                foreach ($reportlist as $key => $row) {
                    $export = array();
                    $export['Request_id'] = $row['invoice_id'];
                    $export['Invoice_number'] = $row['invoice_number'];
                    $export['Bill_date'] = $row['bill_date'];
                    $export['Customer_code'] = $row['customer_code'];
                    $export['Customer_name'] = $row['customer_name'];
                    $export[$this->get_custom_company_col_name] = $row['company_name'];
                    $export['Due_date'] = $row['due_date'];
                    $export['Status'] = $row['status'];
                    $export['Base_amount'] = $row['basic_amount'];
                    $export['Invoice_total'] = $row['invoice_amount'];

                    foreach ($colselect_all as $col) {
                        if ($col != '_P_Particulars' && $col != '_T_Taxes') {
                            $col_name = str_replace('_C_', '', $col);
                            $col_name = str_replace('.', '', $col_name);
                            $col_val = '__' . str_replace(' ', '_', $col_name);
                            $export[$col_name] = $row[$col_val];
                        }
                    }
                    $exportlist[] = $export;
                }
                $this->common->excelexport('TaxDetails', $exportlist);
            }
            $this->view->list_name = 'tax_details_report';
            $this->view->datatablejs = 'table-small-statesave';  //table-small old value
            $this->smarty->assign("reportlist", $reportlist);
            $this->view->sum_column = 9;
            $this->setAjaxDatatableSession();
            //$this->view->ajaxpage = 'invoicedetail.php';
            $this->smarty->assign("title", "Tax details");
            $this->view->title = "Tax Details";
            if ($this->session->get('configure_invoice_statues')) {
                $invoice_statues = $this->session->get('configure_invoice_statues');
            }
            $this->smarty->assign("custom_invoice_status", json_decode($invoice_statues,true));
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Invoicing', 'url' => ''),
                array('title' => 'Tax details', 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/tax_detail.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function ledger()
    {
        try {
            $user_id = $this->user_id;
            $merchant_id = $this->merchant_id;
            $type = isset($_POST['type']) ? $_POST['type'] : 1;

            $customer_list = $this->common->getListValue('customer', 'merchant_id', $this->merchant_id, 1);
            $this->smarty->assign("type", $type);
            $this->smarty->assign("customer_list", $customer_list);

            $current_date = date("d M Y");
            $last_date = $this->getLast_date();
            $this->view->checkedlist = '';

            //check last search criteria into Redis is exist or not 
            $redis_items = $this->getSearchParamRedis('ledger_report');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $customer_name = $_POST['customer_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $customer_name = '';
                $status = '';
            }
            //find last search criteria into Redis  
            if(isset($redis_items['ledger_report']['search_param']) && $redis_items['ledger_report']['search_param']!=null) {
                $_POST['customer_name'] = $redis_items['ledger_report']['search_param']['customer_name'];
                $status = $redis_items['ledger_report']['search_param']['status'];
                $from_date = $redis_items['ledger_report']['search_param']['from_date'];
                $to_date = $redis_items['ledger_report']['search_param']['to_date'];
            }

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $column_list[] = array('column_name' => 'Customer code');
            $column_list[] = array('column_name' => 'Narrative');
            $column_list[] = array('column_name' => 'Settlement value');
            $column_list[] = array('column_name' => 'Bill date');
            $this->smarty->assign("column_list", $column_list);
            $this->smarty->assign("column_select", $_POST['column_name']);
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                if ($type == 2) {
                    $reportlist = $this->model->get_ReportLedger($merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), '', $this->session->get('sub_franchise_id'));
                    $reportlist[0]['date'] = 'Opening Balance';
                    $reportlist[0]['credit'] = '';
                } else {
                    $this->view->datatablejs = 'table-ellipsis-small';
                    $reportlist = $this->model->get_ReportSwipezLedger($merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), '', $this->session->get('sub_franchise_id'));
                }
            }

            $int = 0;
            $sum_credit = 0;
            $sum_debit = 0;
            $sum_tdr = 0;
            $sum_st = 0;
            foreach ($reportlist as $row) {
                $row['credit'] = $row['credit'] - $row['tdr'] - $row['st'];
                $reportlist[$int]['credit'] = $row['credit'];

                if ($row['credit'] > 0) {
                    $sum_credit = $sum_credit + $row['credit'];
                    $sum_tdr = $sum_tdr + $row['tdr'];
                    $sum_st = $sum_st + $row['st'];
                }

                if ($row['debit'] > 0) {
                    $sum_debit = $sum_debit + $row['debit'];
                }
                $int++;
            }

            if ($type == 2) {
                $reportlist[$int]['date'] = 'Total';
                $reportlist[$int]['customer_detail'] = '';
                $reportlist[$int]['cr_dr'] = '';
                $reportlist[$int]['invoice_number'] = '';
                $reportlist[$int]['type'] = '';
                $reportlist[$int]['debit'] = $sum_debit;
                $reportlist[$int]['credit'] = $sum_credit;
                $reportlist[$int]['tdr'] = $sum_tdr;
                $reportlist[$int]['st'] = $sum_st;
                $int++;
                $reportlist[$int]['date'] = 'Closing Balance';
                $reportlist[$int]['customer_detail'] = '';
                $reportlist[$int]['cr_dr'] = '';
                $reportlist[$int]['invoice_number'] = '';
                $reportlist[$int]['type'] = '';
                $reportlist[$int]['debit'] = $sum_debit - $sum_credit - $sum_tdr - $sum_st;
            }

            if (isset($_POST['customer_name']) && $_POST['customer_name'] != '') {
                if ($type == 1) {
                    foreach ($reportlist as $key => $row) {
                        if ($row['__Customer_code'] != $_POST['customer_name']) {
                            unset($reportlist[$key]);
                        }
                    }
                } else {
                    foreach ($reportlist as $key => $row) {
                        if ($row['customer_code'] != $_POST['customer_name']) {
                            unset($reportlist[$key]);
                        }
                    }
                }
            }

            if (isset($_POST['export'])) {
                $this->common->excelexport('LedgerReport', $reportlist);
            }
            $this->smarty->assign("customer_selected", $_POST['customer_name']);
            $this->smarty->assign("reportlist", $reportlist);
            $this->smarty->assign("title", "Ledger report");
            $this->view->title = "Ledger report";
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Collections', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small-nosort-statesave';  //table-small-nosort old value
            $this->view->hide_first_col = true;
            $this->view->list_name = 'ledger_report';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/ledger' . $type . '.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function expense($type = null)
    {
        try {
            $user_id = $this->user_id;
            $current_date = date("d M Y");
            $last_date = $this->getLast_date();
            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $payment_status = $_POST['payment_status'];
                $category_id = $_POST['category_id'];
                $department_id = $_POST['department_id'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $payment_status = '';
                $category_id = 0;
                $department_id = 0;
            }
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $column_list[] = array('column_name' => 'email_id', 'display_name' => 'Email');
            $column_list[] = array('column_name' => 'mobile', 'display_name' => 'Mobile');
            $column_list[] = array('column_name' => 'state', 'display_name' => 'State');
            $column_list[] = array('column_name' => 'gst_number', 'display_name' => 'GST number');
            $column_list[] = array('column_name' => 'gst', 'display_name' => 'GST');
            $column_list[] = array('column_name' => 'tds', 'display_name' => 'TDS');
            $this->smarty->assign("column_list", $column_list);
            $this->smarty->assign("column_select", $_POST['column_name']);
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $this->view->datatablejs = 'table-ellipsis-small';
            $where = '';
            if ($_POST['payment_status'] != '') {
                $where = ' and e.payment_status=' . $_POST['payment_status'];
            }
            if ($_POST['category_id'] > 0) {
                $where .= ' and e.category_id=' . $_POST['category_id'];
            }
            if ($_POST['department_id'] > 0) {
                $where .= ' and e.department_id=' . $_POST['department_id'];
            }
            if ($type == null) {
                $expense_type = 1;
                $file = 'expense';
            } else {
                $expense_type = 2;
                $file = 'po';
            }
            if ($this->has_error == false) {
                $reportlist = $this->model->getExpenseReport($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $expense_type, $where);

                if (isset($_POST['export'])) {
                    $this->common->excelexport('ExpenseReport', $reportlist, array('type', 'category_id', 'department_id', 'vendor_id', 'created_by', 'is_active', 'last_update_by'));
                }
            }
            $category = $this->common->getListValue('expense_category', 'merchant_id', $this->merchant_id, 1);
            $department = $this->common->getListValue('expense_department', 'merchant_id', $this->merchant_id, 1);
            $this->smarty->assign("department", $department);
            $this->smarty->assign("category", $category);
            $this->smarty->assign("expense_type", $expense_type);

            $this->smarty->assign("payment_status", $payment_status);
            $this->smarty->assign("department_id", $department_id);
            $this->smarty->assign("category_id", $category_id);
            $this->smarty->assign("reportlist", $reportlist);
            $this->smarty->assign("title", "Expense report");
            $this->view->datatablejs = 'table-small';
            $this->view->hide_first_col = true;
            $this->view->title = "Expense details";

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/' . $file . '.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function collections()
    {
        try {
            $current_date = date("d M Y");
            $last_date = $this->getLast_date();
            $this->view->checkedlist = '';

            //check last search criteria into Redis is exist or not 
            $redis_items = $this->getSearchParamRedis('collection_report');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $mode = $_POST['mode'];
                $source = $_POST['source'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $mode = array();
                $source = array();
                $column_select = array();
                $aging_by = 'last_update_date';
                $status = '';
            }

            //find last search criteria into Redis  
            if(isset($redis_items['collection_report']['search_param']) && $redis_items['collection_report']['search_param']!=null) {
                $mode = $redis_items['collection_report']['search_param']['mode'];
                $source = $redis_items['collection_report']['search_param']['source'];
                $from_date = $redis_items['collection_report']['search_param']['from_date'];
                $to_date = $redis_items['collection_report']['search_param']['to_date'];
            }
            $this->view->showLastRememberSearchCriteria = true;
            
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);

            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $where = '';
            if (!empty($mode)) {
                if ($where == '') {
                    $where = " where mode in (" . "~" . implode("~,~", $mode) . "~" . ")";
                } else {
                    $where .= " and mode in (" . "~" . implode("~,~", $mode) . "~" . ")";
                }
            }
            if (!empty($source)) {
                if ($where == '') {
                    $where = " where source in (" . "~" . implode("~,~", $source) . "~" . ")";
                } else {
                    $where .= " and source in (" . "~" . implode("~,~", $source) . "~" . ")";
                }
            }


            if ($this->has_error == false) {
                if (isset($_POST['export'])) {
                    $reportlist = $this->model->getReportCollections($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $where);
                    $this->common->excelexport('Collections', $reportlist, array('offline_response_type', 'source_type', 'xway_source_type'));
                }
            }

            $_SESSION['_from_date'] = $fromdate->format('Y-m-d');
            $_SESSION['_to_date'] = $todate->format('Y-m-d');
            $_SESSION['_mode'] = $mode;
            //dd($column_select);
            $_SESSION['_source'] = $source;
            $_SESSION['_where'] = $where;

            $modes = array('Online payment', 'NEFT/RTGS', 'Cash', 'Cheque');
            $sources = array('Invoice', 'Payment link', 'Form builder', 'Event', 'Booking', 'Website', 'Prepaid plan');

            $this->smarty->assign("modes",  $modes);
            $this->smarty->assign("mode",  $mode);
            $this->smarty->assign("sources", $sources);
            $this->smarty->assign("source", $source);
            $this->smarty->assign("title", "Collections");
            $this->view->sum_column = 6;
            $this->setAjaxDatatableSession();
            $this->view->ajaxpage = 'collections.php';
            $this->view->list_name= 'collection_report';
            $this->view->title = "Collections";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Collections', 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/collections.tpl');
            $this->view->render('footer/request_list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function paymentsreceived()
    {
        try {
            $user_id = $this->session->get('userid');
            $customer_selected = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';

            $customer_list = $this->model->getMerchantCustomer($this->merchant_id);
            $column_list = $this->model->getColumnList($user_id);
            
            $this->smarty->assign("customer_list", $customer_list);

            $addcolumn[] = array('column_name' => 'Email');
            $addcolumn[] = array('column_name' => 'Mobile');
            $addcolumn[] = array('column_name' => 'Narrative');
            $addcolumn[] = array('column_name' => 'Franchise name');
            $addcolumn[] = array('column_name' => 'Vendor name');
            $column_list = array_merge($addcolumn, $column_list);
            $this->smarty->assign("column_list", $column_list);

            $current_date = date("d M Y");

            $last_date = $this->getLast_date();
            $this->view->checkedlist = '';
            
            $redis_items = $this->getSearchParamRedis('payment_received_report');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $customer_name = $_POST['customer_name'];
                $column_select = $_POST['column_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $customer_name = '';
                $column_select = array();
                $aging_by = 'last_update_date';
                $status = '';
            }

            //find last search criteria into Redis  
            if(isset($redis_items['payment_received_report']['search_param']) && $redis_items['payment_received_report']['search_param']!=null) {
                $column_select = $redis_items['payment_received_report']['search_param']['column_name'];
                $from_date = $redis_items['payment_received_report']['search_param']['from_date'];
                $to_date = $redis_items['payment_received_report']['search_param']['to_date'];
                $customer_selected = $redis_items['payment_received_report']['search_param']['customer_name'];
                $franchise_id = $redis_items['payment_received_report']['search_param']['franchise_id'];
                $vendor_id = $redis_items['payment_received_report']['search_param']['vendor_id'];
            }
            $this->view->showLastRememberSearchCriteria = true;

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);

            $cycle_list = $this->model->getCycleList($user_id);

            $this->smarty->assign("cycle_list", $cycle_list);


            if (count($column_select) > 5) {
                $column_select = array();
                $this->smarty->assign("haserrors", "Column name selected more than 5 values");
            }
            $this->smarty->assign("column_select", $column_select);
            $_SESSION['_column_select'] = $column_select;
            foreach ($column_select as $key => $value) {
                if ($value == 'Email' || $value == 'Mobile' || $value == 'Narrative' || $value == 'Franchise name' || $value == 'Vendor name') {
                    unset($column_select[$key]);
                }
            }


            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $group = isset($_POST['group']) ? $_POST['group'] : '';

            $franchise_id = isset($_POST['franchise_id']) ? $_POST['franchise_id'] : 0;
            if ($this->view->franchise == 1) {
                $sub_franchise_id = $this->session->get('sub_franchise_id');
                if ($sub_franchise_id > 0) {
                    $franchise_id = $sub_franchise_id;
                    $this->smarty->assign("franchise_id", $sub_franchise_id);
                    $franchise = $this->common->getSingleValue('franchise', 'franchise_id', $sub_franchise_id, 1);
                    if (!empty($franchise)) {
                        $franchise_list[] = $franchise;
                    }
                } else {
                    $this->smarty->assign("franchise_id", $franchise_id);
                    $franchise_list = $this->common->getListValue('franchise', 'merchant_id', $this->merchant_id, 1, 'and status=1');
                }
                $this->smarty->assign("franchise_list", $franchise_list);
            }
            
            $vendor_id = isset($_POST['vendor_id']) ? $_POST['vendor_id'] : 0;
            if ($this->view->vendor_enable == 1) {
                $this->smarty->assign("vendor_id", $vendor_id);
                $vendor_list = $this->common->getListValue('vendor', 'merchant_id', $this->merchant_id, 1, 'and status=1');
                $this->smarty->assign("vendor_list", $vendor_list);
            }
            if ($group != '') {
                $where = " where customer_group like ~%" . '{' . $group . '}' . '%~';
            } else {
                $where = '';
                $grptext = '';
                if ($this->session->get('login_customer_group')) {
                    foreach ($this->session->get('login_customer_group') as $grpname) {
                        if ($grptext == '') {
                            $grptext = " where (customer_group like ~%" . '{' . $grpname . '}' . '%~';
                        } else {
                            $grptext .= " or customer_group like ~%" . '{' . $grpname . '}' . '%~';
                        }
                    }
                    $grptext .= ')';

                    if ($where == '') {
                        $where = $grptext;
                    } else {
                        $where .= " AND " . $grptext;
                    }
                }
            }
            if ($franchise_id > 0) {
                if ($where == '') {
                    $where = ' where franchise_id=' . $franchise_id;
                } else {
                    $where .= ' and franchise_id=' . $franchise_id;
                }
            }
            if ($vendor_id > 0) {
                if ($where == '') {
                    $where = ' where vendor_id=' . $vendor_id;
                } else {
                    $where .= ' and vendor_id=' . $vendor_id;
                }
            }
            if ($this->has_error == false) {
                if (isset($_POST['export'])) {
                    $reportlist = $this->model->get_ReportPaymentReceived($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $customer_selected, $column_select, $where);
                    $this->common->excelexport('PaymentReceived', $reportlist);
                }
            }

            $_SESSION['_from_date'] = $fromdate->format('Y-m-d');
            $_SESSION['_to_date'] = $todate->format('Y-m-d');
            $_SESSION['_customer_id'] = $customer_selected;
            //dd($column_select);
            $_SESSION['_column'] = $column_select;
            $_SESSION['_where'] = $where;
            $this->smarty->assign("group", $group);
            $where = '';
            $login_cust_group = $this->session->get('login_customer_group');
            if (isset($login_cust_group)) {
                $where = " and group_id in (" . implode(",", $login_cust_group) . ')';
            }
            $customer_group = $this->common->getListValue('customer_group', 'merchant_id', $this->merchant_id, 1, $where);
            $this->smarty->assign("customer_group", $customer_group);
            $this->smarty->assign("customer_selected", $customer_selected);
            $this->smarty->assign("reportlist", $reportlist);
            $this->view->sum_column = 11;
            $this->smarty->assign("title", "Invoice payments");
            // $this->view->sum_column = 10;
            $this->setAjaxDatatableSession();
            $this->view->ajaxpage = 'paymentreceived.php';
            $this->view->title = "Payment received";
            $this->view->list_name = 'payment_received_report';
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Collections', 'url' => ''),
                array('title' => 'Invoice payments', 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/payment_received.tpl');
            $this->view->render('footer/request_list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function websitepaymentsreceived($xwaytype = null)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $this->view->merchantType = $this->session->get('merchant_type');
            $last_date = $this->getLast_date();
            $current_date = date('d M Y');
            
            //check last search criteria into Redis is exist or not 
            $redis_items = $this->getSearchParamRedis('web_payment_received_report'.$xwaytype);

            $franchise_id = 0;

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $cycle_name = $_POST['cycle_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $cycle_name = "";
            }
             //find last search criteria into Redis  
             if(isset($redis_items['web_payment_received_report'.$xwaytype]['search_param']) && $redis_items['web_payment_received_report'.$xwaytype]['search_param']!=null) {
                $from_date = $redis_items['web_payment_received_report'.$xwaytype]['search_param']['from_date'];
                $to_date = $redis_items['web_payment_received_report'.$xwaytype]['search_param']['to_date'];

                if($xwaytype==null) {
                    $franchise_id = isset($redis_items['web_payment_received_report'.$xwaytype]['search_param']['franchise_id']) ? $redis_items['web_payment_received_report'.$xwaytype]['search_param']['franchise_id'] : 0;
                } else {
                    $franchise_id = 0;
                }
            }
            $this->view->showLastRememberSearchCriteria = true;

            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $franchise_id = isset($_POST['franchise_id']) ? $_POST['franchise_id'] : $franchise_id;   //chnaged by darshana - old value is 0;

            if ($this->view->franchise == 1) {
                $sub_franchise_id = $this->session->get('sub_franchise_id');
                if ($sub_franchise_id > 0) {
                    $franchise_id = $sub_franchise_id;
                    $this->smarty->assign("franchise_id", $sub_franchise_id);
                    $franchise = $this->common->getSingleValue('franchise', 'franchise_id', $sub_franchise_id, 1);
                    if (!empty($franchise)) {
                        $franchise_list[] = $franchise;
                    }
                } else {
                    $this->smarty->assign("franchise_id", $franchise_id);
                    $franchise_list = $this->common->getListValue('franchise', 'merchant_id', $this->merchant_id, 1, 'and status=1');
                }
                $this->smarty->assign("franchise_list", $franchise_list);
            }
            $type = 1;
            $sumcol = 6;
            $colspan = 12;

            $this->view->title = 'Website payments';
            
            if ($xwaytype == 'form') {
                $type = 2;
                $sumcol = 6;
                $colspan = 7;
                $this->view->title = 'Form builder transactions';
            } elseif ($xwaytype == 'directpay') {
                $type = 4;
                $sumcol = 6;
                $colspan = 7;
                $this->view->title = 'Payment link transactions';
            } else if ($xwaytype == 'plan') {
                $type = 3;
                $sumcol = 6;
                $colspan = 9;
                $this->view->title = 'Plan payments';
            }

            if ($this->session->get('has_franchise') == 1) {
                $sumcol = $sumcol + 1;
                $colspan = $colspan + 1;
            }
            $this->view->sum_column = $sumcol;
            $this->smarty->assign("col_span", $colspan);

            $status = ($_POST['status'] != '') ? $_POST['status'] : -1;
            if (isset($_POST['export'])) {
                if ($this->has_error == false) {
                    require_once MODEL . 'merchant/TransactionModel.php';
                    $transaction = new TransactionModel();
                    $transactionlist = $transaction->getMerchantXwayPaymentTransactionList($fromdate->format('Y-m-d'), $todate->format('Y-m-d'), 1, $merchant_id, $franchise_id, $type);
                    if (count($transactionlist) > 5000) {
                        $this->smarty->assign("warning", array('title' => 'Reduce date range', 'text' => 'Your export request contains ' . count($transactionlist) . ' records. Currently we support an export of 5,000 records at a time, please reduce the date range and try again.'));
                    } else {
                        $this->common->excelexport('WebsitePaymentReceived', $transactionlist);
                    }
                }
            } else {
                $_SESSION['_from_date'] = $fromdate->format('Y-m-d');
                $_SESSION['_to_date'] = $todate->format('Y-m-d');
                $_SESSION['_franchise_id'] = $franchise_id;
                $_SESSION['_type'] = $type;
                $_SESSION['_has_franchise'] = $this->view->franchise;
            }
            $this->setAjaxDatatableSession();
            $this->view->ajaxpage = 'websitepayment.php';
            $this->smarty->assign("has_franchise", $this->session->get('has_franchise'));
            $this->smarty->assign("type", $type);
            $this->smarty->assign("transactionlist", $transactionlist);
            $this->view->list_name = 'web_payment_received_report'.$xwaytype;
            $this->view->datatablejs = 'table-sum-ellipsis-large';
            $this->smarty->assign("title", $this->view->title);
            
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Collections', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/xwaypayment_received.tpl');
            $this->view->render('footer/request_list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E035+23]Error while listing merchant xway transaction Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function payment_tdr()
    {
        try {
            $user_id = $this->session->get('userid');
            $column_list = $this->model->getColumnList($user_id);
            $this->smarty->assign("column_list", $column_list);

            $current_date = date("d M Y");
            $last_date = $this->getLast_date();
            $this->view->checkedlist = '';

            $redis_items = $this->getSearchParamRedis('payment_tdr_report');
           
            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $column_select = $_POST['column_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $column_select = array();
                $aging_by = 'last_update_date';
                $status = '';
            }

            //find last search criteria into Redis 
            if(isset($redis_items['payment_tdr_report']['search_param']) && $redis_items['payment_tdr_report']['search_param']!=null) {
                $from_date = $redis_items['payment_tdr_report']['search_param']['from_date'];
                $to_date = $redis_items['payment_tdr_report']['search_param']['to_date'];
            }
            $this->view->showLastRememberSearchCriteria = true;

            if (count($column_select) > 5) {
                $column_select = array();
                $this->smarty->assign("haserrors", "Column name selected more than 5 values");
            }
            $this->smarty->assign("column_select", $column_select);
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                if (isset($_POST['export'])) {
                    $reportlist = $this->model->get_ReportPaymenttdr($user_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $column_select, $this->session->get('sub_franchise_id'));
                    $this->common->excelexport('PaymentTDR', $reportlist);
                }
            }
            $where = '';
            if ($this->session->get('sub_franchise_id') > 0) {
                $where = ' where franchise_id=' . $this->session->get('sub_franchise_id');
            }
            $_SESSION['_column_select'] = $column_select;
            $_SESSION['_from_date'] = $fromdate->format('Y-m-d');
            $_SESSION['_to_date'] = $todate->format('Y-m-d');
            $_SESSION['_where'] = $where;
            $this->smarty->assign("reportlist", $reportlist);
            $this->smarty->assign("title", "TDR charges");
            $this->view->title = "TDR charges";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Collections', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end
            $this->view->list_name = 'payment_tdr_report';
            $this->setAjaxDatatableSession();
            $this->view->ajaxpage = 'paymenttdr.php';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/transaction_tdr.tpl');
            $this->view->render('footer/request_list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function payment_settlement_summary()
    {
        try {
            $user_id = $this->session->get('userid');
            $current_date = date("d M Y");
            $last_date = $this->getLast_date();
            $this->view->checkedlist = '';

            $redis_items = $this->getSearchParamRedis('payment_settlement_summary_report');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $column_select = $_POST['column_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $column_select = array();
                $aging_by = 'last_update_date';
                $status = '';
            }

            //find last search criteria into Redis 
            if(isset($redis_items['payment_settlement_summary_report']['search_param']) && $redis_items['payment_settlement_summary_report']['search_param']!=null) {
                $from_date = $redis_items['payment_settlement_summary_report']['search_param']['from_date'];
                $to_date = $redis_items['payment_settlement_summary_report']['search_param']['to_date'];
                $_POST['franchise_id'] = $redis_items['payment_settlement_summary_report']['search_param']['franchise_id'];
            }

            $franchise_id = isset($_POST['franchise_id']) ? $_POST['franchise_id'] : 0;
            if ($this->view->franchise == 1) {
                $sub_franchise_id = $this->session->get('sub_franchise_id');
                if ($sub_franchise_id > 0) {
                    $franchise_id = $sub_franchise_id;
                    $this->smarty->assign("franchise_id", $sub_franchise_id);
                    $franchise = $this->common->getSingleValue('franchise', 'franchise_id', $sub_franchise_id, 1);
                    if (!empty($franchise)) {
                        $franchise_list[] = $franchise;
                    }
                } else {
                    $this->smarty->assign("franchise_id", $franchise_id);
                    $franchise_list = $this->common->getListValue('franchise', 'merchant_id', $this->merchant_id, 1, 'and status=1');
                }
                $this->smarty->assign("franchise_list", $franchise_list);
            }

            $this->smarty->assign("has_franchise", $this->session->get('has_franchise'));
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $this->smarty->assign("column_select", $column_select);
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                $reportlist = $this->model->get_ReportPaymentsettlementSummary($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $franchise_id);
            }

            $show_narrative = 0;
            foreach ($reportlist as $row) {
                if ($row['narrative'] != '') {
                    $show_narrative = 1;
                }
            }
            if (isset($_POST['export'])) {
                $this->common->excelexport('SettlementSummary', $reportlist);
            }
            $reportlist = $this->generic->getEncryptedList($reportlist, 'link', 'id');
            $reportlist = $this->generic->getDateFormatList($reportlist, 'settlement_at', 'settlement_date');
            $this->smarty->assign("show_narrative", $show_narrative);
            $this->smarty->assign("reportlist", $reportlist);
            $this->smarty->assign("title", "Settlement summary");
            $this->view->title = "Settlement Summary";
            $has_franchise = $this->session->get('has_franchise');
            $this->view->hide_first_col = 1;
            $this->view->sum_column = 7;
            if ($has_franchise == 1) {
                $this->view->sum_column = 8;
            }
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Settlements', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-sum-ellipsis-small-statesave';  //table-sum-ellipsis-small
            //$this->view->datatablejs = 'table-small';
            $this->view->list_name = 'payment_settlement_summary_report';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/transaction_settlement_summary.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function payment_settlement_details($id = null)
    {
        try {
            $user_id = $this->session->get('userid');
            $current_date = date("d M Y");
            if ($id != null) {
                $settlement_id = $this->encrypt->decode($id);
            } else {
                $settlement_id = 0;
            }
            $last_date = $this->getLast_date();
            $this->view->checkedlist = '';

            $redis_items = $this->getSearchParamRedis('payment_settlement_details_report');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $column_select = $_POST['column_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $column_select = array();
                $aging_by = 'last_update_date';
                $status = '';
            }

            //find last search criteria into Redis 
            if(isset($redis_items['payment_settlement_details_report']['search_param']) && $redis_items['payment_settlement_details_report']['search_param']!=null) {
                $from_date = $redis_items['payment_settlement_details_report']['search_param']['from_date'];
                $to_date = $redis_items['payment_settlement_details_report']['search_param']['to_date'];
            }

            $this->smarty->assign("has_franchise", $this->session->get('has_franchise'));
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $this->smarty->assign("column_select", $column_select);
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                $reportlist = $this->model->get_ReportPaymentsettlement($user_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $this->session->get('sub_franchise_id'), $settlement_id);
                if (isset($_POST['export'])) {
                    $this->common->excelexport('PaymentSettlement', $reportlist);
                }
            }
            $reportlist = $this->generic->getDateFormatList($reportlist, 'settlement_at', 'settlement_date');
            $this->smarty->assign("reportlist", $reportlist);
            $this->smarty->assign("title", "Settlement details");
            $this->view->title = "Settlement details";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Settlements', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end
            $this->view->list_name = 'payment_settlement_details_report';
            $this->view->table_default_length = true;
            $this->view->datatablejs = 'table-small-statesave';  //table-small
            //$this->view->hide_first_col = 1;
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/transaction_settlement.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }


    function vendorcommission()
    {
        try {
            $current_date = date("d M Y");
            $last_date = $this->getLast_date();

            $redis_items = $this->getSearchParamRedis('vendorcommission_report');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $status = $_POST['status'];

            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $status = '';
            }

            //find last search criteria into Redis 
            if(isset($redis_items['vendorcommission_report']['search_param']) && $redis_items['vendorcommission_report']['search_param']!=null) {
                $from_date = $redis_items['vendorcommission_report']['search_param']['from_date'];
                $to_date = $redis_items['vendorcommission_report']['search_param']['to_date'];
                $status = $redis_items['vendorcommission_report']['search_param']['status'];
            }

            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $this->smarty->assign("status", $status);
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $reportlist = $this->model->getReportVendorCommission($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $status);

            foreach ($reportlist as $k => $row) {
                $reportlist[$k]['id'] = $k + 1;
            }

            if (isset($_POST['export'])) {
                $this->common->excelexport('VendorCommission', $reportlist);
            }
            $reportlist = $this->generic->getDateFormatList($reportlist, 'date', 'created_date');
            $this->smarty->assign("reportlist", $reportlist);
            $this->smarty->assign("title", "Vendor commission");
            $this->view->title = "Vendor commission";
            $this->view->hide_first_col = 1;
            $this->view->sum_column = 7;
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-sum-ellipsis-small-statesave';  //table-sum-ellipsis-small old value
            $this->view->list_name = 'vendorcommission_report';
            //$this->view->datatablejs = 'table-small';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/vendor_commission.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $this->merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function paymentexpected()
    {
        try {
            $user_id = $this->session->get('userid');
            $aging_selected = isset($_POST['aging']) ? $_POST['aging'] : 4;
            $aging_by_selected = isset($_POST['aging_by']) ? $_POST['aging_by'] : 'bill_date';
            $interval = isset($_POST['interval']) ? $_POST['interval'] : 15;
            $this->smarty->assign("aging_by_selected", $aging_by_selected);
            $this->smarty->assign("aging_selected", $aging_selected);
            $this->smarty->assign("interval", $interval);

            $last_date = date("d M Y");
            $current_date = date('d M Y');
            $date = strtotime($current_date . ' 2 month');
            $current_date = date('d M Y', $date);
            $this->view->sum_column = $aging_selected + 3;
            $agingcount = $aging_selected;
            $inetrval_of = $interval;
            $column = array();
            $column_display = array();
            $column[] = 'customer_code';
            $column[] = 'customer_name';
            $column[] = 'company_name';
            $column[] = 'current';
            $column_display[] = 'Customer code';
            $column_display[] = 'Customer name';
            $column_display[] = ($this->get_custom_company_col_name != false) ? $this->get_custom_company_col_name : 'Company name';
            $column_display[] = 'Today';
            $int = 1;
            $start = 1;
            $max = 0;
            while ($int < $agingcount) {
                $start_interval = $start + $max;
                $max = $max + $inetrval_of;
                $column[] = $start_interval . '_to_' . $max;
                $column_display[] = $start_interval . ' to ' . $max;
                $int++;
            }
            $column[] = 'above_' . $max;
            $column[] = 'total';
            $column_display[] = '> ' . $max;
            $column_display[] = 'Total';
            $this->smarty->assign("column", $column);
            $this->smarty->assign("display_columns", $column_display);

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $aging = $_POST['aging'];
                $interval = $_POST['interval'];
                $aging_by = $_POST['aging_by'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $aging = 4;
                $interval = 15;
                $aging_by = 'bill_date';
            }
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                $reportlist = $this->model->get_ReportExpectedPayment($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $aging, $interval, $aging_by);
            }
            $this->smarty->assign("reportlist", $reportlist);
            $this->view->datatablejs = 'table-sum-ellipsis-large';
            $this->smarty->assign("title", "Payment expected");
            $this->view->title = "Payment expected";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Invoicing', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/payment_expected.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function taxsummary()
    {
        try {

            $current_date = date("d M Y");
            $last_date = $this->getLast_date();

            $redis_items = $this->getSearchParamRedis('taxsummary_report');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
            }

            //find last search criteria into Redis 
            if(isset($redis_items['taxsummary_report']['search_param']) && $redis_items['taxsummary_report']['search_param']!=null) {
                $from_date = $redis_items['taxsummary_report']['search_param']['from_date'];
                $to_date = $redis_items['taxsummary_report']['search_param']['to_date'];
            }

            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');

            $billing_profile_id = ($_POST['billing_profile_id'] > 0) ? $_POST['billing_profile_id'] : 0;
            $billing_profile_list = $this->common->getListValue('merchant_billing_profile', 'merchant_id', $this->merchant_id, 1);
            $this->smarty->assign("billing_profile_list", $billing_profile_list);
            $this->smarty->assign("billing_profile_id", $billing_profile_id);

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                $reportlist = $this->model->getReportTaxSummary($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $billing_profile_id);
            }
            $this->smarty->assign("reportlist", $reportlist);
            $this->smarty->assign("title", "Tax summary");
            $this->view->title = "Tax summary";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Invoicing', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small-statesave';  //old value - table-small
            $this->view->list_name = 'taxsummary_report';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/tax_summary.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $this->merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function refunddetails()
    {
        try {

            $current_date = date("d M Y");
            $last_date = $this->getLast_date();

            $redis_items = $this->getSearchParamRedis('refunddetails_report');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
            }
            //find last search criteria into Redis 
            if(isset($redis_items['refunddetails_report']['search_param']) && $redis_items['refunddetails_report']['search_param']!=null) {
                $from_date = $redis_items['refunddetails_report']['search_param']['from_date'];
                $to_date = $redis_items['refunddetails_report']['search_param']['to_date'];
            }

            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                $reportlist = $this->model->get_ReportRefundDetails($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
            }
            $reportlist = $this->generic->getDateFormatList($reportlist, 'refund_at', 'refund_date');
            $reportlist = $this->generic->getDateFormatList($reportlist, 'transaction_at', 'transaction_date');
            $this->smarty->assign("reportlist", $reportlist);
            $this->view->hide_first_col = true;
            $this->smarty->assign("title", "Refund details");
            $this->view->title = "Refund details";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Settlements', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->list_name = 'refunddetails_report';
            $this->view->datatablejs = 'table-small-statesave';  //table-small old value
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/refund_details.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function disputedetails()
    {
        try {

            $current_date = date("d M Y");
            $last_date = $this->getLast_date();

            $redis_items = $this->getSearchParamRedis('disputedetails_report');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];

            } else {
                $from_date = $last_date;
                $to_date = $current_date;
            }

            //find last search criteria into Redis 
            if(isset($redis_items['disputedetails_report']['search_param']) && $redis_items['disputedetails_report']['search_param']!=null) {
                $from_date = $redis_items['disputedetails_report']['search_param']['from_date'];
                $to_date = $redis_items['disputedetails_report']['search_param']['to_date'];
            }

            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                $reportlist = $this->model->getReportDisputeDetails($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
            }
            $reportlist = $this->generic->getDateFormatList($reportlist, 'dispute_date', 'dispute_date');
            $reportlist = $this->generic->getDateFormatList($reportlist, 'created_date', 'created_date');
            $this->smarty->assign("reportlist", $reportlist);
            $this->view->hide_first_col = true;
            $this->smarty->assign("title", "Dispute details");
            $this->view->title = "Dispute details";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                array('title' => 'Settlements', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end
            $this->view->list_name = 'disputedetails';
            $this->view->datatablejs = 'table-small-statesave';  //table-small
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/dispute_details.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function formbuilder()
    {
        $formlist = $this->common->getListValue('form_builder_request', 'merchant_id', $this->merchant_id, 0);
        $where = '';
        $current_date = date("d M Y");
        $last_date = $this->getLast_date();

        $redis_items = $this->getSearchParamRedis('formbuilder_report');

        if (isset($_POST['from_date'])) {
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];
            if ($_POST['form_id'] > 0) {
                $where .= " and request_id=" . $_POST['form_id'];
            }
            if ($_POST['status'] != '') {
                $where .= " and status=" . $_POST['status'];
            }
           
        } else {
            $from_date = $last_date;
            $to_date = $current_date;
        }

        //find last search criteria into Redis 
        if(isset($redis_items['formbuilder_report']['search_param']) && $redis_items['formbuilder_report']['search_param']!=null) {
            $from_date = $redis_items['formbuilder_report']['search_param']['from_date'];
            $to_date = $redis_items['formbuilder_report']['search_param']['to_date'];
            $_POST['form_id'] = $redis_items['formbuilder_report']['search_param']['form_id'];
            $_POST['status'] = $redis_items['formbuilder_report']['search_param']['status'];
        }

        $this->smarty->assign("from_date", $from_date);
        $this->smarty->assign("to_date", $to_date);

        $fromdate = new DateTime($from_date);
        $todate = new DateTime($to_date);

        $where .= " and created_date >='" . $fromdate->format('Y-m-d') . " 00:00:00'";
        $where .= " and created_date <='" . $todate->format('Y-m-d') . " 23:59:59'";
        if ($this->has_error == false) {
            $reportlist = $this->common->getListValue('form_builder_transaction', 'merchant_id', $this->merchant_id, 0, $where);
        }
        $list = array();
        $column = array();
        foreach ($reportlist as $row) {
            $date = new DateTime($row['created_date']);
            $array['id'] = array('label' => 'Form #', 'value' => $row['id']);
            $array['date'] = array('label' => 'Date', 'value' => $date->format('d/M/y h:m A'));
            $array['transaction_id'] = array('label' => 'Transaction Id', 'value' => $row['transaction_id']);
            $status = ($row['status'] == 1) ? 'Success' : 'Failed';
            $array['transaction_status'] = array('label' => 'Payment status', 'value' => $status);
            $column['id'] = 'Form #';
            $column['date'] = 'Submit Date';
            $column['transaction_id'] = 'Transaction Id';
            $column['transaction_status'] = 'Payment status';
            $json_array = json_decode($row['json'], 1);
            foreach ($json_array as $json) {
                if ($json['display'] == 1 && $json['type'] != 'label') {
                    $array[$json['name']] = array('subtype' => $json['subtype'], 'label' => $json['label'], 'value' => $json['value']);
                    $column[$json['name']] = $json['label'];
                }
            }
            $list[] = $array;
        }

        if (isset($_POST['export'])) {
            foreach ($list as $l) {
                foreach ($column as $key => $c) {
                    $exportarray[$c] = $l[$key]['value'];
                }
                $exportList[] = $exportarray;
            }
            $this->common->excelexport('Formbuilder', $exportList);
        }
        $total_kb = 0;
        if (isset($_POST['downloaddoc']) && count($_POST['form_check']) > 0) {
            foreach ($_POST['form_check'] as $l) {
                $kb = $this->common->getRowValue('zip_size', 'form_builder_transaction', 'id', $l);
                $total_kb = $total_kb + $kb;
            }
            if ($total_kb > 100000) {
                $this->smarty->assign("haserrors", "Document size should not be greater than 100MB");
            } else {
                $this->model->saveFormDownloadRequest($this->merchant_id, implode(',', $_POST['form_check']), $total_kb, $this->user_id);
                $this->smarty->assign("successmessage", "Download request has been saved you will get email notification");
            }
        }

        $this->smarty->assign("post", $_POST);
        $this->smarty->assign("formlist", $formlist);
        $this->smarty->assign("column", $column);
        $this->smarty->assign("reportlist", $list);
        $this->smarty->assign("title", "Form builder data");
        $this->view->title = "Form builder data";

        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Reports', 'url' => '/merchant/report'),
            array('title' => 'Form builder', 'url' => ''),
            array('title' => $this->view->title, 'url' => ''),
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->list_name = 'formbuilder_report';
        $this->view->datatablejs = 'table-small-statesave';  //old value - table-small
        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/report/formtransaction.tpl');
        $this->view->render('footer/list');
    }

    public function PDFExport($name, $user_id, $rows)
    {
        try {
            SwipezLogger::info(__CLASS__, "PDF Export invoked");
            require_once CONTROLLER . 'InvoiceWrapper.php';
            #require_once('/opt/app/lib/MPDF60/mpdf.php');
            #require_once('C:\Root\Work\Code\swipez\lib\MPDF60\mpdf.php');
            $mpdf = new \Mpdf\Mpdf();
            $invoice = new InvoiceWrapper($this->common);
            foreach ($rows as $payment_request_id) {
                $html = "";
                $info = $this->common->getPaymentRequestDetails($payment_request_id, $this->merchant_id);
                $this->smarty->assign('info', $info);
                $this->smarty->assign('is_merchant', 1);
                $smarty = $invoice->asignSmarty($info, array(), $payment_request_id);
                foreach ($smarty as $key => $value) {
                    $this->smarty->assign($key, $value);
                }
                #require_once('/opt/app/lib/MPDF60/mpdf.php');
                $template_type = 'detail';
                if ($info['template_type'] == 'isp') {
                    $template_type = 'isp';
                }
                $html .= $this->smarty->fetch(VIEW . 'pdf/invoice_' . $template_type . '.tpl');
                $mpdf->AddPage();
                $mpdf->WriteHTML($html);
                $mpdf->SetDisplayMode('fullpage');

                //$mpdf->Output();
            }
            $mpdf->Output('Invoice_details_report_' . date('Y-M-d H:m:s') . '.pdf', 'D');
            unset($mpdf);
            unset($invoice);
            SwipezLogger::info(__CLASS__, "PDF Export complete");
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, "Exception occured while writing PDF for userid: $user_id, from/to date : $name" . $e->getMessage());
        }
    }

    function couponanalytics()
    {
        try {
            $user_id = $this->session->get('userid');
            $current_date = date("d M Y");
            $last_date = $this->getLast_date();
            $this->view->checkedlist = '';
            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $column_select = $_POST['column_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $column_select = array();
                $aging_by = 'last_update_date';
                $status = '';
            }
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $this->view->hide_first_col = true;
            $reportlist = $this->model->getCouponAnalytics($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $this->smarty->assign("reportlist", $reportlist);
            $this->smarty->assign("title", "Coupon Analytics");
            $this->view->title = "Coupon Analytics";
            $this->view->datatablejs = 'table-small';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/coupon_analytics.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function productsalesreport()
    {
        try {
            $current_date = date("d M Y");
            $last_date = $this->getLast_date();

            $redis_items = $this->getSearchParamRedis('productsalesreport');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
            }

            //find last search criteria into Redis 
            if(isset($redis_items['productsalesreport']['search_param']) && $redis_items['productsalesreport']['search_param']!=null) {
                $from_date = $redis_items['productsalesreport']['search_param']['from_date'];
                $to_date = $redis_items['productsalesreport']['search_param']['to_date'];
            }

            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            if ($this->has_error == false) {
                $reportlist = $this->model->getProductWiseSalesReport($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
                if (isset($_POST['exportExcel'])) {
                    $this->common->excelexport('ProductWiseSalesReport', $reportlist);
                }
            }

            $reportlist = $this->generic->getDateFormatList($reportlist, 'created_date', 'created_date');

            $this->smarty->assign("reportlist", $reportlist);
            $this->view->hide_first_col = true;
            $this->smarty->assign("title", "Product wise sales report");
            $this->view->title = "Product wise sales report";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Reports', 'url' => '/merchant/report'),
                //array('title' => 'Settlements', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end
            $this->view->list_name = 'productsalesreport';
            $this->view->datatablejs = 'table-small-statesave';  //old value - table-small
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/report/product_wise_sales_report.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant product wise sales report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
