<?php

namespace App\Console\Commands;

use App\Imports\CustomerImport;
use App\Imports\CostTypeImport;

use App\Imports\BriqPermissionsImport;
use App\Imports\BriqRolesImport;
use App\Imports\BriqUserRolesImport;

use App\Model\User;

use App\Http\Controllers\InvoiceFormatController;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Console\Command;

class ImportBriqData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importdata:briq {--merchant_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import briq sample data';
    private $user_model=null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->user_model = new User();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $merchant_id = $this->option('merchant_id');
        $merchant_data = $this->user_model->getTableRow('merchant', 'merchant_id', $merchant_id);
        $user_id = $merchant_data->user_id;

        $this->insertData($merchant_id,  $user_id);
        $this->insertMandatoryData($merchant_id,  $user_id);
    }

    function insertData($merchant_id,  $user_id)
    {

        Excel::import(new CustomerImport($merchant_id, $user_id), env('BRIQ_TEST_DATA_CUSTOMER_FILE'));
        Excel::import(new CostTypeImport($merchant_id, $user_id), env('BRIQ_TEST_DATA_COST_TYPE_FILE'));

        echo $merchant_id;
        return true;
    }

    function insertInvoiceTemplate($merchant_id, $user_id)
    {
        $InvoiceFormat = new InvoiceFormatController();

        $merchant_billing_profile = $this->user_model->getTableRow('merchant_billing_profile', 'merchant_id', $merchant_id);
        $request_data  = '{"design_name":null,"design_color":null,"template_name":"G703/702","billing_profile_id":"19554","main_header_id":["5","6","7","8"],"main_header_datatype":["text","number","email","textarea"],"main_header_column_id":["0","0","0","0"],"main_header_name":["Company name","Merchant contact","Merchant email","Merchant address"],"hcheck":["0","1","2","3"],"cust_column_id":["0","0","0","0"],"customer_column_id":["1","2","3","4"],"customer_column_type":["customer","customer","customer","customer"],"customer_column_name":["Customer code","Customer name","Email ID","Mobile no"],"customer_datatype":["primary","text","email","mobile"],"ccheck":["0","1","2","3"],"column_config":["{\"position\":\"R\",\"column_type\":\"H\",\"headertablesave\":\"metadata\",\"headermandatory\":0,\"headercolumnposition\":1,\"function_id\":9,\"function_param\":\"system_generated\",\"function_val\":\"\",\"headerisdelete\":1,\"headerdatatype\":\"text\"}","{\"position\":\"R\",\"column_type\":\"H\",\"headertablesave\":\"request\",\"headermandatory\":1,\"headercolumnposition\":4,\"function_id\":0,\"function_param\":\"\",\"function_val\":\"\",\"headerisdelete\":0,\"headerdatatype\":\"text\"}","{\"position\":\"R\",\"column_type\":\"H\",\"headertablesave\":\"request\",\"headermandatory\":1,\"headercolumnposition\":5,\"function_id\":0,\"function_param\":\"\",\"function_val\":\"\",\"headerisdelete\":0,\"headerdatatype\":\"date\"}","{\"position\":\"R\",\"column_type\":\"H\",\"headertablesave\":\"request\",\"headermandatory\":1,\"headercolumnposition\":6,\"function_id\":0,\"function_param\":\"\",\"function_val\":\"\",\"headerisdelete\":0,\"headerdatatype\":\"date\"}"],"column_id":["0","0","0","0"],"headercolumn":["Invoice Number","Billing cycle name","Bill date","Due date"],"pc_sr_no":"#","particular_col":["item","description","total_amount"],"pc_item":"Item","pc_annual_recurring_charges":"Annual recurring charges","pc_sac_code":"Sac Code","pc_description":"Desc","pc_qty":"Quantity","pc_unit_type":"Unit type","pc_rate":"Rate","pc_gst":"GST","pc_tax_amount":"Tax amount","pc_discount_perc":"Discount %","pc_discount":"Discount","pc_total_amount":"Amount","pc_narrative":"Narrative","tax_total":"Tax total","tnc":null,"upload_file_label":"View document","partial_min_amount":"50","is_covering":"1","default_covering":"0","custom_subject":"Payment request from %COMPANY_NAME%","custom_sms":"You have received a payment request from %COMPANY_NAME% for amount %TOTAL_AMOUNT%. To make an online payment, access your bill via %SHORT_URL%","reminder":["3","1","0"],"reminder_subject":[null,null,null],"reminder_sms":[null,null,null],"has_online_payments":"0","enable_payments":"0","template_type":"construction","template_id":null,"custmized_receipt_fields":null}';
        $request_data = json_decode($request_data, 1);
        $request_data["billing_profile_id"] =  $merchant_billing_profile->id;
        $request_data["billingProfile_id"] =  $merchant_billing_profile->id;

        $request_data["particularname"] = null;
        $request_data["tax_id"] = null;
        $request_data["template_fooer_msg"] = '';

        unset($request_data["particular_col"]);
        $request_data = (object)$request_data;
        $template_id = $InvoiceFormat->saveInvoiceFormat($request_data, null, $merchant_id, $user_id);
        $InvoiceFormat->saveMetadataV2($request_data, $template_id, $merchant_id, $user_id);
        $this->user_model->updateTable('invoice_template', 'template_id', $template_id, 'plugin', '{"has_upload":1,"upload_file_label":"View document","has_signature":1,"has_cc":"1","cc_email":[],"roundoff":"1","has_acknowledgement":"1","is_prepaid":"1","has_autocollect":"1","has_partial":"1","partial_min_amount":"50","has_covering_note":"1","default_covering_note":0,"has_custom_notification":"1","custom_email_subject":"Payment request from %COMPANY_NAME%","custom_sms":" You have received a payment request from %COMPANY_NAME% for amount %TOTAL_AMOUNT%. To make an online payment, access your bill via %SHORT_URL% ","has_online_payments":"1","enable_payments":"1","save_revision_history":"1"}');
        echo  $template_id;
    }


    function insertMandatoryData($merchant_id,  $user_id)
    {
        //Excel::import(new BriqPermissionsImport($merchant_id, $user_id), env('BRIQ_TEST_DATA_PERMISSIONS_FILE'));
        //Excel::import(new BriqRolesImport($merchant_id, $user_id), env('BRIQ_TEST_DATA_ROLES_FILE'));
        $this->insertInvoiceTemplate($merchant_id, $user_id);
        echo $merchant_id;
        return true;
    }
}
