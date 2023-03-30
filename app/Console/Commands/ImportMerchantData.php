<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;
use Illuminate\Support\Facades\Storage;

class ImportMerchantData extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:merchant {merchant_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import merchant data';

    private $merchant_id = null;
    private $user_id = null;
    private $model = null;

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
        $this->model = new MigrateModel();
        $this->merchant_id = $this->argument('merchant_id');
        $merchant = $this->model->getTableRow('merchant', 'merchant_id', $this->merchant_id);
        $this->user_id = $merchant->user_id;
        $this->saveCustomer();
        $this->saveInvoiceNumber();
        $this->saveProject();
        $this->saveBillCode();
        $this->saveCostType();
        $this->saveTemplate();
        $this->saveMetadata();
        $this->saveFunctionMapping();
        $this->saveContract();
        $this->saveChangeOrder();
        $this->saveBillingCycle();
        $this->saveInvoice();
        $this->saveAttachments();
        $this->saveColumnValues();
        $this->saveInvoiceParticular();





        print 'Success';
    }

    function saveCustomer()
    {
        $table = 'customer';
        $id_column = 'customer_id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            $row['merchant_id'] = $this->merchant_id;
            $row['created_by'] = $this->user_id;
            $row['last_update_by'] = $this->user_id;
            unset($row[$id_column]);
            $id = $this->model->saveTable($table, $row);
            $row[$id_column] = $id;
            $data[$k] = $row;
        }
        $json = Storage::disk('local')->put('merchant_import_data/customer.json', json_encode($data));
    }




    function saveInvoiceNumber()
    {
        $table = 'merchant_auto_invoice_number';
        $id_column = 'auto_invoice_id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            $row['merchant_id'] = $this->merchant_id;
            $row['created_by'] = $this->user_id;
            $row['last_update_by'] = $this->user_id;
            unset($row[$id_column]);
            $id = $this->model->saveTable($table, $row);
            $row[$id_column] = $id;
            $data[$k] = $row;
        }
        $json = Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }

    function saveProject()
    {

        $customer = $this->getImportData('customer');
        $merchant_auto_invoice_number = $this->getImportData('merchant_auto_invoice_number');

        $table = 'project';
        $id_column = 'id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            $row['merchant_id'] = $this->merchant_id;
            $row['customer_id'] = $customer[$row['customer_id']]['customer_id'];
            $row['sequence_number'] = $merchant_auto_invoice_number[$row['sequence_number']]['auto_invoice_id'];
            $row['created_by'] = $this->user_id;
            $row['last_update_by'] = $this->user_id;
            unset($row[$id_column]);
            $id = $this->model->saveTable($table, $row);
            $row[$id_column] = $id;
            $data[$k] = $row;
        }
        Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }
    function saveBillCode()
    {
        $project = $this->getImportData('project');
        $table = 'csi_code';
        $id_column = 'id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            $row['merchant_id'] = $this->merchant_id;
            if ($row['project_id'] > 0) {
                $row['project_id'] = (isset($project[$row['project_id']]['id'])) ? $project[$row['project_id']]['id'] : 0;
            }
            $row['created_by'] = $this->user_id;
            $row['last_update_by'] = $this->user_id;
            unset($row[$id_column]);
            $id = $this->model->saveTable($table, $row);
            $row[$id_column] = $id;
            $data[$k] = $row;
        }
        Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }


    function saveCostType()
    {
        $table = 'cost_types';
        $id_column = 'id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            $row['merchant_id'] = $this->merchant_id;
            $row['created_by'] = $this->user_id;
            $row['last_update_by'] = $this->user_id;
            unset($row[$id_column]);
            $id = $this->model->saveTable($table, $row);
            $row[$id_column] = $id;
            $data[$k] = $row;
        }
        Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }

    function saveTemplate()
    {
        $table = 'invoice_template';
        $id_column = 'template_id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            $row['merchant_id'] = $this->merchant_id;
            $row['created_by'] = $this->user_id;
            $row['last_update_by'] = $this->user_id;
            $row[$id_column] = $this->model->saveSeq('Template_id');
            $this->model->saveTable($table, $row);
            $data[$k] = $row;
        }
        Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }

    function saveMetadata()
    {
        $invoice_template = $this->getImportData('invoice_template');
        $table = 'invoice_column_metadata';
        $id_column = 'column_id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            // $row['merchant_id'] = $this->merchant_id;
            $row['template_id'] = (isset($invoice_template[$row['template_id']]['template_id'])) ? $invoice_template[$row['template_id']]['template_id'] : 0;
            $row['created_by'] = $this->user_id;
            $row['last_update_by'] = $this->user_id;
            unset($row[$id_column]);
            $id = $this->model->saveTable($table, $row);
            $row[$id_column] = $id;
            $data[$k] = $row;
        }
        Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }


    function saveFunctionMapping()
    {
        $invoice_column_metadata = $this->getImportData('invoice_column_metadata');
        $table = 'column_function_mapping';
        $id_column = 'id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            // $row['merchant_id'] = $this->merchant_id;
            $row['column_id'] = (isset($invoice_column_metadata[$row['column_id']]['column_id'])) ? $invoice_column_metadata[$row['column_id']]['column_id'] : 0;
            if ($row['param'] == 'system_generated') {
                if ($row['value'] > 0) {
                    $row['value'] = (isset($merchant_auto_invoice_number[$row['value']]['auto_invoice_id'])) ? $merchant_auto_invoice_number[$row['value']]['auto_invoice_id'] : '';
                }
            }
            $row['created_by'] = $this->user_id;
            $row['last_update_by'] = $this->user_id;
            unset($row[$id_column]);
            $id = $this->model->saveTable($table, $row);
            $row[$id_column] = $id;
            $data[$k] = $row;
        }
        Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }


    function saveContract()
    {
        $project = $this->getImportData('project');
        $customer = $this->getImportData('customer');
        $invoice_template = $this->getImportData('invoice_template');
        $csi_code = $this->getImportData('csi_code');
        $cost_types = $this->getImportData('cost_types');
        $table = 'contract';
        $id_column = 'contract_id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            $row['merchant_id'] = $this->merchant_id;
            $row['project_id'] = (isset($project[$row['project_id']]['id'])) ? $project[$row['project_id']]['id'] : 0;
            $row['customer_id'] = (isset($customer[$row['customer_id']]['customer_id'])) ? $customer[$row['customer_id']]['customer_id'] : 0;
            $row['template_id'] = (isset($invoice_template[$row['template_id']]['template_id'])) ? $invoice_template[$row['template_id']]['template_id'] : '';
            $row['created_by'] = $this->user_id;
            $particulars = json_decode($row['particulars'], 1);
            if (!empty($particulars)) {
                foreach ($particulars as $pk => $pv) {
                    $particulars[$pk]['bill_code'] = (isset($csi_code[$pv['bill_code']]['id'])) ? $csi_code[$pv['bill_code']]['id'] : $pv['bill_code'];
                    $particulars[$pk]['cost_type'] = (isset($cost_types[$pv['cost_type']]['id'])) ? $cost_types[$pv['cost_type']]['id'] : $pv['cost_type'];
                }
                $row['particulars'] = json_encode($particulars);
            }
            $row['last_update_by'] = $this->user_id;
            unset($row[$id_column]);
            $id = $this->model->saveTable($table, $row);
            $row[$id_column] = $id;
            $data[$k] = $row;
        }
        Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }


    function saveChangeOrder()
    {
        $contract = $this->getImportData('contract');
        $csi_code = $this->getImportData('csi_code');
        $cost_types = $this->getImportData('cost_types');
        $table = 'order';
        $id_column = 'order_id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            $row['merchant_id'] = $this->merchant_id;
            $row['contract_id'] = (isset($contract[$row['contract_id']]['contract_id'])) ? $contract[$row['contract_id']]['contract_id'] : 0;
            $row['created_by'] = $this->user_id;
            $particulars = json_decode($row['particulars'], 1);
            if (!empty($particulars)) {
                foreach ($particulars as $pk => $pv) {
                    $particulars[$pk]['bill_code'] = (isset($csi_code[$pv['bill_code']]['id'])) ? $csi_code[$pv['bill_code']]['id'] : $pv['bill_code'];
                    if (isset($pv['cost_type'])) {
                        $particulars[$pk]['cost_type'] = (isset($cost_types[$pv['cost_type']]['id'])) ? $cost_types[$pv['cost_type']]['id'] : $pv['cost_type'];
                    }
                }
                $row['particulars'] = json_encode($particulars);
            }
            $row['last_update_by'] = $this->user_id;
            unset($row[$id_column]);
            $id = $this->model->saveTable($table, $row);
            $row[$id_column] = $id;
            $data[$k] = $row;
        }
        Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }


    function saveBillingCycle()
    {
        $table = 'billing_cycle_detail';
        $id_column = 'billing_cycle_id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            $row['user_id'] = $this->user_id;
            $row['created_by'] = $this->user_id;
            $row['last_update_by'] = $this->user_id;
            $row[$id_column] = $this->model->saveSeq('Billing_cycle_id');
            $id = $this->model->saveTable($table, $row);
            $data[$k] = $row;
        }
        $json = Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }

    function saveInvoice()
    {

        $billing_cycle_detail = $this->getImportData('billing_cycle_detail');
        $customer = $this->getImportData('customer');
        $contract = $this->getImportData('contract');
        $invoice_template = $this->getImportData('invoice_template');
        $order = $this->getImportData('order');
        $table = 'payment_request';
        $id_column = 'payment_request_id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            $row['merchant_id'] = $this->merchant_id;
            $row['user_id'] = $this->user_id;
            $row['billing_cycle_id'] = (isset($billing_cycle_detail[$row['billing_cycle_id']]['billing_cycle_id'])) ? $billing_cycle_detail[$row['billing_cycle_id']]['billing_cycle_id'] : 0;
            $row['customer_id'] = (isset($customer[$row['customer_id']]['customer_id'])) ? $customer[$row['customer_id']]['customer_id'] : 0;
            $row['template_id'] = (isset($invoice_template[$row['template_id']]['template_id'])) ? $invoice_template[$row['template_id']]['template_id'] : '';
            $row['contract_id'] = (isset($contract[$row['contract_id']]['contract_id'])) ? $contract[$row['contract_id']]['contract_id'] : 0;
            $row['created_by'] = $this->user_id;
            $change_order_ids = json_decode($row['change_order_id'], 1);
            if (!empty($change_order_ids)) {
                foreach ($change_order_ids as $pk => $pv) {
                    $change_order_ids[$pk] = (isset($order[$pv]['order_id'])) ? $order[$pv]['order_id'] : '';
                }
                $row['change_order_id'] = json_encode($change_order_ids);
            }
            $row['last_update_by'] = $this->user_id;
            $row[$id_column] = $this->model->saveSeq('Pay_Req_Id');
            $id = $this->model->saveTable($table, $row);
            $data[$k] = $row;
        }
        Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }


    function saveAttachments()
    {
        $payment_request = $this->getImportData('payment_request');
        $table = 'invoice_attatchments';
        $id_column = 'id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            // $row['merchant_id'] = $this->merchant_id;
            $row['payment_request_id'] = (isset($payment_request[$row['payment_request_id']]['payment_request_id'])) ? $payment_request[$row['payment_request_id']]['payment_request_id'] : 0;
            $row['created_by'] = $this->user_id;
            $row['last_update_by'] = $this->user_id;
            unset($row[$id_column]);
            $id = $this->model->saveTable($table, $row);
            $row[$id_column] = $id;
            $data[$k] = $row;
        }
        Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }

    function saveColumnValues()
    {
        $payment_request = $this->getImportData('payment_request');
        $invoice_column_metadata = $this->getImportData('invoice_column_metadata');
        $table = 'invoice_column_values';
        $id_column = 'invoice_id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            // $row['merchant_id'] = $this->merchant_id;
            $row['payment_request_id'] = (isset($payment_request[$row['payment_request_id']]['payment_request_id'])) ? $payment_request[$row['payment_request_id']]['payment_request_id'] : 0;
            $row['column_id'] = (isset($invoice_column_metadata[$row['column_id']]['column_id'])) ? $invoice_column_metadata[$row['column_id']]['column_id'] : 0;
            $row['created_by'] = $this->user_id;
            $row['last_update_by'] = $this->user_id;
            unset($row[$id_column]);
            $id = $this->model->saveTable($table, $row);
            $row[$id_column] = $id;
            $data[$k] = $row;
        }
        Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }

    function saveInvoiceParticular()
    {
        $payment_request = $this->getImportData('payment_request');
        $csi_code = $this->getImportData('csi_code');
        $cost_types = $this->getImportData('cost_types');
        $table = 'invoice_construction_particular';
        $id_column = 'id';
        $data = $this->getData($table);
        foreach ($data as $k => $row) {
            // $row['merchant_id'] = $this->merchant_id;
            $row['payment_request_id'] = (isset($payment_request[$row['payment_request_id']]['payment_request_id'])) ? $payment_request[$row['payment_request_id']]['payment_request_id'] : 0;
            $row['bill_code'] = (isset($csi_code[$row['bill_code']]['id'])) ? $csi_code[$row['bill_code']]['id'] : 0;
            $row['cost_type'] = (isset($cost_types[$row['cost_type']]['id'])) ? $cost_types[$row['cost_type']]['id'] : 0;

            $row['created_by'] = $this->user_id;
            $row['last_update_by'] = $this->user_id;
            unset($row[$id_column]);
            $id = $this->model->saveTable($table, $row);
            $row[$id_column] = $id;
            $data[$k] = $row;
        }
        Storage::disk('local')->put('merchant_import_data/' . $table . '.json', json_encode($data));
    }

    public function getValue($table, $id_column, $old_value)
    {
        $json = Storage::disk('local')->get('merchant_import_data/' . $table . '.json');
        $data = json_decode($json, 1);
        return $data[$old_value][$id_column];
    }

    public function getData($table)
    {
        $json = Storage::disk('local')->get('merchant_export_data/' . $table . '.json');
        $data = json_decode($json, 1);
        return $data;
    }

    public function getImportData($table)
    {
        $json = Storage::disk('local')->get('merchant_import_data/' . $table . '.json');
        $data = json_decode($json, 1);
        return $data;
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
