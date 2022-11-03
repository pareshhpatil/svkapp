<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;

class EasybizMerchant extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easybiz:merchant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Easybiz merchant';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        try {
            $model = new MigrateModel();
            $merchant_rows = $model->getMerchantDetails();
            foreach ($merchant_rows as $row) {
                echo $row->merchant_id . PHP_EOL;
                $row->first_name = str_replace("'", "", $row->first_name);
                $row->last_name = str_replace("'", "", $row->last_name);
                $row->company_name = str_replace("'", "", $row->company_name);
                $response = $model->saveMerchant($row->email_id, $row->first_name, $row->last_name, $row->mobile_no, $row->company_name);
                $model->updateMerchantId($row->merchant_id, $response->merchant_id, $response->user_id);
                $model->updateUserStatus($response->user_id);
                $model->setUserAddr($row->user_id, $response->user_id);
                $model->setMerchantAddr($row->merchant_id, $response->merchant_id);
                $model->saveMerchantCustomerGroup($row->merchant_id, $response->merchant_id);
                $model->saveMerchantCustomerMetadata($row->merchant_id, $response->merchant_id);
                $model->saveMerchantCustomer($row->merchant_id, $response->merchant_id, $response->user_id);
                $model->saveMerchantCustomerValues($row->merchant_id, $response->merchant_id);
                $model->saveMerchantAutoInvoiceNo($row->merchant_id, $response->merchant_id);
                $model->saveMerchantLanding($row->merchant_id, $response->merchant_id);
                $model->saveMerchantBank($row->merchant_id, $response->merchant_id);
                $model->setMerchantTax($row->merchant_id, $response->merchant_id);
            }
        } catch (Exception $e) {
            Log::error(__CLASS__ . __METHOD__ . $e->getMessage());
        }
    }

}
