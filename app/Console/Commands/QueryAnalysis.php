<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Model\ParentModel;

class QueryAnalysis extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'query:test {procedure} {merchant_id} {from_date} {to_date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mysql store procedure test';

    public $merchant_id = null;
    public $user_id = null;
    public $from_date = null;
    public $to_date = null;
    public $version = null;
    public $template_id = null;
    public $payment_request_id = null;
    public $model = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $procedure = $this->argument('procedure');
        $procedures = $this->getProcedure($procedure);
        if ($procedures == false) {
            echo 'Invalid procedure name';
            return false;
        }
        $this->merchant_id = $this->argument('merchant_id');
        $this->model = new ParentModel();
        $this->user_id = $this->model->getColumnValue('merchant', 'merchant_id', $this->merchant_id, 'user_id');

        $this->from_date = $this->argument('from_date');
        $this->to_date = $this->argument('to_date');
        $ports = array('3308', '3309', '3305');
        $ports_name = array('3308' => 'Mysql 5.7', '3305' => 'Mysql 5.6', '3306' => 'Mysql 5.5', '3307' => 'Mysql 8', '3309' => 'Maria');
        foreach ($ports as $port) {
            DB::disconnect('mysql');
            $this->version = $ports_name[$port];
            Config::set("database.connections.mysql.port", $port);
            if ($port == '3305') {
                Config::set("database.connections.mysql.password", '');
            } else {
                Config::set("database.connections.mysql.password", 'swipezapp');
            }
            foreach ($procedures as $name) {
                $this->$name();
            }
        }
    }

    public function merchant_register()
    {
        $start = microtime(true);
        $i = 1;
        // for ($i = 1; $i < 2; $i++) {
        $email = 'testload' . $i . '@swipez.in';
        $f_name = '2Test';
        $l_name = 'Last';
        $mobile = '9999999999';
        $company_name = 'Test company' . $i;
        DB::select("call `merchant_register`('" . $email . "','" . $f_name . "','" . $l_name . "','+91','" . $mobile . "','','" . $company_name . "',2,32,0,2)");
        //  }
        $end = microtime(true);
        $time = $end - $start;
        $time = round($time, 3);
        $this->saveAnalysis('merchant_register', $time);
    }

    public function report_invoice_details()
    {
        $start = microtime(true);
        // for ($i = 1; $i < 2; $i++) {
        DB::select("call report_invoice_details('" . $this->merchant_id . "','" . $this->from_date . "','" . $this->to_date . "',1,'',0,null,'bill_date','',0,0,0,'','ORDER BY `invoice_id` DESC','')");
        // }
        $end = microtime(true);
        $time = $end - $start;
        $time = round($time, 3);
        $this->saveAnalysis('report_invoice_details', $time);
    }

    public function template_save()
    {
        $inv_id = $this->model->getColumnValue('merchant_auto_invoice_number', 'merchant_id', $this->merchant_id, 'auto_invoice_id');
        $start = microtime(true);
        $templatename = 'Invoice' . time();
        $template_type = 'isp';
        $main_header_id = '9~10~11~12';
        $main_header_default = 'Profile~Profile~Profile~Profile';
        $customer_column = '1~2~3~4';
        $custom_column = '';
        $header = 'Invoice No.~Billing cycle name~Bill date~Due date';
        $position = 'R~R~R~R';
        $column_type = 'H~H~H~H';
        $sort = 'MCompany name~MMerchant contact~MMerchant email~MMerchant address~CCustomer code~CCustomer name~CEmail ID~CMobile no~HInvoice No.~HBilling cycle name~HBill date~HDue date';
        $column_position = '-1~4~5~6';
        $function_id = '9~-1~5~7';
        $function_param = 'system_generated~~~bill_date';
        $function_val = $inv_id . '~~~5';
        $is_delete = '1~2~2~2';
        $headerdatatype = 'text~text~date~date';
        $headertablename = 'metadata~request~request~request';
        $headermandatory = '2~1~1~1';
        $particularname = 'Particular';
        $pc = '{"sr_no":"#","item":"Description","sac_code":"Sac Code","description":"Time period","gst":"GST","total_amount":"Absolute cost"}';
        $pd = '["Particular"]';
        $td = '';
        $plugin = '';
        $tnc = '';
        $defaultValue = '';
        $particular_total = 'Sub total';
        $tax_total = 'Tax total';
        $ext = '.';
        $maxposition = '6';
        DB::select("call `template_save`('" . $templatename . "','" . $template_type . "','" . $this->merchant_id . "','" . $this->user_id . "','" . $main_header_id . "','" . $main_header_default . "','" . $customer_column . "','" . $custom_column . "','" . $header . "','" . $position . "','" . $column_type . "','" . $sort . "','" . $column_position . "','" . $function_id . "','" . $function_param . "','" . $function_val . "','" . $is_delete . "','" . $headerdatatype . "','" . $headertablename . "','" . $headermandatory . "','" . $tnc . "','" . $defaultValue . "','" . $particular_total . "','" . $tax_total . "','" . $ext . "','" . $maxposition . "','" . $pc . "','" . $pd . "','" . $td . "','" . $plugin . "',0,'" . $this->user_id . "',@message,@template_id)");
        $end = microtime(true);
        $data = DB::select("select @template_id as template_id");
        $this->template_id = $data[0]->template_id;
        $time = $end - $start;
        $time = round($time, 3);
        $this->saveAnalysis('template_save', $time);
    }
    public function insert_invoicevalues()
    {
        $inv_id = $this->model->getColumnValue('merchant_auto_invoice_number', 'merchant_id', $this->merchant_id, 'auto_invoice_id');
        $customer_id = $this->model->getColumnValue('customer', 'merchant_id', $this->merchant_id, 'customer_id');
        if ($this->template_id == null) {
            $this->template_save();
        }
        $start = microtime(true);
        // for ($i = 1; $i < 500; $i++) {
        $res = DB::select("call `insert_invoicevalues`('" . $this->merchant_id . "','" . $this->user_id . "','2','System generated" . $inv_id . "','" . $this->template_id . "','System generated" . $inv_id . "','" . $customer_id . "','" . date('Y-m-d') . "','" . date('Y-m-d') . "','System Bill','','200','36.00','0','0','0','0','0','0','0','0',null,'" . $this->user_id . "',' 1 ','1','0','0','','0','0');");
        // }
        $this->payment_request_id = $res[0]->request_id;
        $end = microtime(true);
        $time = $end - $start;
        $time = round($time, 3);
        $this->saveAnalysis('insert_invoicevalues', $time);
    }
    public function save_invoice_particular()
    {
        if ($this->payment_request_id == null) {
            $this->insert_invoicevalues();
        }
        $start = microtime(true);
        // for ($i = 1; $i < 500; $i++) {
        $res = DB::select("call `save_invoice_particular`('" . $this->payment_request_id . "','0','Particular','','8787','1 Month','','','','18','','','200','','" . $this->user_id . "','" . $this->merchant_id . "' ,'0','0');");
        // }
        $end = microtime(true);
        $time = $end - $start;
        $time = round($time, 3);
        $this->saveAnalysis('save_invoice_particular', $time);
    }
    public function save_invoice_tax()
    {
        if ($this->payment_request_id == null) {
            $this->insert_invoicevalues();
        }
        $tax = DB::table('merchant_tax')
            ->select(DB::raw('tax_id'))
            ->where('merchant_id', $this->merchant_id)
            ->where('tax_type', 1)
            ->where('percentage', 9)
            ->first();
        $cgst = $tax->tax_id;
        $tax = DB::table('merchant_tax')
            ->select(DB::raw('tax_id'))
            ->where('merchant_id', $this->merchant_id)
            ->where('tax_type', 2)
            ->where('percentage', 9)
            ->first();
        $sgst = $tax->tax_id;
        $start = microtime(true);
        // for ($i = 1; $i < 500; $i++) {
        $res = DB::select("call `save_invoice_tax`('" . $this->payment_request_id . "','" . $cgst . "~" . $sgst . "~','9.00~9.00','400~400','36.00~36.00','0~0','" . $this->user_id . "','0','0' );");
        // }
        $end = microtime(true);
        $time = $end - $start;
        $time = round($time, 3);
        $this->saveAnalysis('save_invoice_tax', $time);
    }

    public function getinvoice_details()
    {
        if ($this->payment_request_id == null) {
            $this->insert_invoicevalues();
        }
        $start = microtime(true);
        // for ($i = 1; $i < 500; $i++) {
        $res = DB::select("call getinvoice_details('customer','" . $this->payment_request_id . "');");
        // }
        $end = microtime(true);
        $time = $end - $start;
        $time = round($time, 3);
        $this->saveAnalysis('getinvoice_details', $time);
    }
    public function update_invoicevalues()
    {
        if ($this->payment_request_id == null) {
            $this->insert_invoicevalues();
        }
        $customer_id = $this->model->getColumnValue('customer', 'merchant_id', $this->merchant_id, 'customer_id');

        $start = microtime(true);
        // for ($i = 1; $i < 500; $i++) {
        $res = DB::select("call `update_invoicevalues`('" . $this->payment_request_id . "','" . $this->user_id . "','2','179','179','" . $customer_id . "','" . date('Y-m-d') . "','" . date('Y-m-d') . "','System Bill','','400','72.00','0','0','0','0','0','1','0','0',null,'" . $this->user_id . "','0','','0','0');");
        // }
        $end = microtime(true);
        $time = $end - $start;
        $time = round($time, 3);
        $this->saveAnalysis('update_invoicevalues', $time);
    }

    public function saveAnalysis($name, $time)
    {
        $delay = ($time > 1500) ? 1 : 0;
        DB::table('admin_database_query_analysis')->insertGetId(
            [
                'version' => $this->version,
                'date' => date('Y-m-d'),
                'name' => $name,
                'time' => $time,
                'delay' => $delay,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
    }

    function getProcedure($procedure)
    {
        $array[] = 'merchant_register';
        $array[] = 'report_invoice_details';
        $array[] = 'template_save';
        $array[] = 'insert_invoicevalues';
        $array[] = 'save_invoice_particular';
        $array[] = 'save_invoice_tax';
        $array[] = 'getinvoice_details';
        $array[] = 'update_invoicevalues';

        if ($procedure == 'all') {
            return $array;
        } else {
            if (in_array($procedure, $array)) {
                return array($procedure);
            } else {
                return false;
            }
        }
    }
    function getAllProcedure($procedure)
    {
        $array[] = 'admin_gst_calculation_monthwise';
        $array[] = 'admin_gst_settelement_dump';
        $array[] = 'admin_gst_tally_report';
        $array[] = 'admin_merchant_transaction';
        $array[] = 'admin_monthly_payment_trends';
        $array[] = 'admin_paid_merchant_transaction';
        $array[] = 'admin_save_merchant_package';
        $array[] = 'admin_save_payment_gateway';
        $array[] = 'admin_settelement_gap';
        $array[] = 'admin_tdr_revenue_calc';
        $array[] = 'auto_aprove_customer_details';
        $array[] = 'chart_invoice_status';
        $array[] = 'chart_particular_summary';
        $array[] = 'chart_payment_mode';
        $array[] = 'chart_payment_received';
        $array[] = 'chart_tax_summary';
        $array[] = 'collect_customer_list';
        $array[] = 'convert_estimate_to_invoice';
        $array[] = 'convert_invoice_to_expense';
        $array[] = 'customer_save';
        $array[] = 'customer_staging_save';
        $array[] = 'customer_structure_save';
        $array[] = 'customer_update';
        $array[] = 'Eventrespond';
        $array[] = 'event_save';
        $array[] = 'event_update';
        $array[] = 'forgotPassword';
        $array[] = 'generate_societystatement';
        $array[] = 'geteventinvoice_details';
        $array[] = 'getinvoice_details';
        $array[] = 'getPayment_receipt';
        $array[] = 'getuser_details';
        $array[] = 'get_all_customer_list';
        $array[] = 'get_approval';
        $array[] = 'get_attendees_details';
        $array[] = 'get_calendar_reservation';
        $array[] = 'get_customer_details';
        $array[] = 'get_customer_list';
        $array[] = 'get_customer_pending_invoices';
        $array[] = 'get_customer_register_list';
        $array[] = 'get_customer_service_list';
        $array[] = 'get_customer_unregister_list';
        $array[] = 'get_EventPatronId';
        $array[] = 'get_Eventuser_details';
        $array[] = 'get_event_package_summary';
        $array[] = 'get_failedTransaction';
        $array[] = 'get_invoice_breckup';
        $array[] = 'get_membership_bookings';
        $array[] = 'get_merchant_bill_transaction';
        $array[] = 'get_merchant_cycledetail';
        $array[] = 'get_merchant_viewevent';
        $array[] = 'get_merchant_viewrequest';
        $array[] = 'get_merchant_viewtransaction';
        $array[] = 'get_merchant_xwaytransaction';
        $array[] = 'get_mybills';
        $array[] = 'get_paid_invoice';
        $array[] = 'get_partial_payments';
        $array[] = 'get_patron_viewrequest';
        $array[] = 'get_patron_viewtransaction';
        $array[] = 'get_pending_approval';
        $array[] = 'get_pending_bills';
        $array[] = 'get_pending_notification_invoice';
        $array[] = 'get_settopbox_list';
        $array[] = 'get_staging_customer_list';
        $array[] = 'get_staging_invoice_breckup';
        $array[] = 'get_staging_invoice_details';
        $array[] = 'get_staging_viewrequest';
        $array[] = 'get_subscription_viewrequest';
        $array[] = 'get_sub_userlist';
        $array[] = 'get_transaction_status';
        $array[] = 'GrouploginCheck';
        $array[] = 'insert_invoicevalues';
        $array[] = 'insert_payment_response';
        $array[] = 'insert_statging_invoicevalues';
        $array[] = 'mailer_monthly_payment_summary';
        $array[] = 'mailer_rpt_payment_summary';
        $array[] = 'merchantProfileUpdate';
        $array[] = 'merchant_dummy_data';
        $array[] = 'merchant_info_saved';
        $array[] = 'merchant_mobile_template';
        $array[] = 'merchant_register';
        $array[] = 'merchant_transactions';
        $array[] = 'new_procedure';
        $array[] = 'offlinerespond';
        $array[] = 'patron_register';
        $array[] = 'report_aging_detail';
        $array[] = 'report_aging_summary';
        $array[] = 'report_customer_balance';
        $array[] = 'report_expected_payment';
        $array[] = 'report_invoice_details';
        $array[] = 'report_ledger';
        $array[] = 'report_payment_received';
        $array[] = 'report_payment_settlement_detail';
        $array[] = 'report_payment_transaction_tdr';
        $array[] = 'report_refund_details';
        $array[] = 'report_swipez_ledger';
        $array[] = 'report_taxdetails_metadata';
        $array[] = 'report_tax_details';
        $array[] = 'report_tax_summary';
        $array[] = 'savebulkUpload_invoice';
        $array[] = 'saveSubscription_invoice';
        $array[] = 'save_bulk_expense';
        $array[] = 'save_change_details';
        $array[] = 'save_event_transaction';
        $array[] = 'save_invoice_particular';
        $array[] = 'save_invoice_reminder';
        $array[] = 'save_invoice_tax';
        $array[] = 'save_merchant_package';
        $array[] = 'save_merchant_PG_details';
        $array[] = 'save_new_structure';
        $array[] = 'save_offline_event_transaction';
        $array[] = 'save_outgoing_sms_detail';
        $array[] = 'save_slot_offline_transaction';
        $array[] = 'save_sub_merchant';
        $array[] = 'save_transaction_settlement';
        $array[] = 'save_transaction_tdr';
        $array[] = 'save_travel_particular';
        $array[] = 'save_travel_ticket_detail';
        $array[] = 'save_xwaytransaction';
        $array[] = 'save_xway_payment_response';
        $array[] = 'set_partialypaid_amount';
        $array[] = 'stock_management';
        $array[] = 'supplier_save';
        $array[] = 'supplier_update';
        $array[] = 'swipez_report_month_wise';
        $array[] = 'swipe_settlement_save';
        $array[] = 'template_edit';
        $array[] = 'template_save';
        $array[] = 'temp_gazonDynamic';
        $array[] = 'temp_shengli_cc';
        $array[] = 'temp_subscription_invoice_number';
        $array[] = 'temp_update_inv';
        $array[] = 'update_api_invoice';
        $array[] = 'update_booking_status';
        $array[] = 'update_invoicevalues';
        $array[] = 'update_staging_invoicevalues';
        $array[] = 'update_status';
        $array[] = 'validate_xwayrequest';
        if ($procedure == 'all') {
            return $array;
        } else {
            if (in_array($procedure, $array)) {
                return array($procedure);
            } else {
                return false;
            }
        }
    }
}
