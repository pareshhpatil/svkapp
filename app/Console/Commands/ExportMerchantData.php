<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;
use Illuminate\Support\Facades\Storage;

class ExportMerchantData extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:merchant {merchant_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export merchant data';

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
        $array = [];
        $model = new MigrateModel();
        $merchant_id = $this->argument('merchant_id');
        $merchant = $model->getTableRow('merchant', 'merchant_id', $merchant_id);
        $user_id = $merchant->user_id;
        $data = $model->getTableRows('customer', 'merchant_id', $merchant_id);
        $array['customer'] = $this->setPrimaryKey($data, 'customer_id');

        $data = $model->getTableRows('merchant_auto_invoice_number', 'merchant_id', $merchant_id);
        $array['merchant_auto_invoice_number'] = $this->setPrimaryKey($data, 'auto_invoice_id');

        $data = $model->getTableRows('project', 'merchant_id', $merchant_id);
        $array['project'] = $this->setPrimaryKey($data, 'id');

        $data = $model->getTableRows('csi_code', 'merchant_id', $merchant_id);
        $array['csi_code'] = $this->setPrimaryKey($data, 'id');

        $data = $model->getTableRows('cost_types', 'merchant_id', $merchant_id);
        $array['cost_types'] = $this->setPrimaryKey($data, 'id');

        $data = $model->getTableRows('invoice_template', 'merchant_id', $merchant_id);
        $array['invoice_template'] = $this->setPrimaryKey($data, 'template_id');

        $data = $model->getTableRows('invoice_column_metadata', 'created_by', $user_id);
        $array['invoice_column_metadata'] = $this->setPrimaryKey($data, 'column_id');

        $data = $model->getTableRows('column_function_mapping', 'created_by', $user_id);
        $array['column_function_mapping'] = $this->setPrimaryKey($data, 'id');

        $data = $model->getTableRows('contract', 'merchant_id', $merchant_id);
        $array['contract'] = $this->setPrimaryKey($data, 'contract_id');

        $data = $model->getTableRows('order', 'merchant_id', $merchant_id);
        $array['order'] = $this->setPrimaryKey($data, 'order_id');

        $data = $model->getTableRows('billing_cycle_detail', 'user_id', $user_id);
        $array['billing_cycle_detail'] = $this->setPrimaryKey($data, 'billing_cycle_id');

        $data = $model->getTableRows('payment_request', 'merchant_id', $merchant_id);
        $array['payment_request'] = $this->setPrimaryKey($data, 'payment_request_id');

        $data = $model->getTableRows('invoice_attatchments', 'created_by', $user_id);
        $array['invoice_attatchments'] = $this->setPrimaryKey($data, 'id');

        $data = $model->getTableRows('invoice_column_values', 'created_by', $user_id);
        $array['invoice_column_values'] = $this->setPrimaryKey($data, 'invoice_id');


        $data = $model->getTableRows('invoice_construction_particular', 'created_by', $user_id);
        $array['invoice_construction_particular'] = $this->setPrimaryKey($data, 'id');

        foreach ($array as $k => $v) {
            Storage::disk('local')->put('merchant_export_data/'.$k.'.json', json_encode($array[$k]));
        }
        print 'Success';
    }

    function setPrimaryKey($data, $key)
    {
        $data = json_decode(json_encode($data), 1);
        $array = [];
        foreach ($data as $k => $v) {
            $array[$v[$key]] = $v;
        }
        return $array;
    }
}
