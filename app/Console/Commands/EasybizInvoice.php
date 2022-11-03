<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;

class EasybizInvoice extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easybiz:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Easybiz invoice';

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
            $invoice_rows = $model->getInvoiceDetails();
            foreach ($invoice_rows as $row) {
                $payment_request_id = $row->payment_request_id;
                $row->user_id = $row->new_user_id;
                $row->merchant_id = $row->new_merchant_id;
                $row->customer_id = $row->customer_id;
                $row->template_id = $row->template_id;
                $row->plugin_value = $row->plugin;
                $row->created_by = $row->new_user_id;
                $row->last_update_by = $row->new_user_id;
                $row->billing_cycle_id = $model->saveEasybizBillingCycle($row->cycle_name, $row->new_user_id);
                $request_id = $model->saveEasybizInvoice($row);
                $model->saveInvoiceParticular($row->payment_request_id, $request_id, $row->new_user_id);
                $model->saveInvoiceTax($row->payment_request_id, $request_id, $row->new_user_id);
                $model->saveInvoiceColValue($row->payment_request_id, $request_id, $row->new_user_id);
            }
        } catch (Exception $e) {
            Log::error(__CLASS__ . __METHOD__ . 'Payment request id: ' . $payment_request_id . $e->getMessage());
        }
    }

}
