<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\FranchiseSale;
use App\Model\ParentModel;
use Carbon\Carbon;
use Exception;
use Log;

class FoodFranchiseInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'franchise:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save franchise invoice';

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
        $invoiceModel = new FranchiseSale();
        $list = FranchiseSale::getFoodFranchiseList();
        $model = new ParentModel();
        $bill_date = Carbon::now()->format('Y-m-d');
        $cyclename = Carbon::now()->format('M,Y');
        foreach ($list as $row) {
            try {
                $config = FranchiseSale::getFranchiseConfig($row->merchant_id);

                $summary = FranchiseSale::getSaleSummary($row->customer_id);
                if ($summary != false) {
                    if (isset($summary->sum_gross_sale)) {
                        if ($summary->sum_gross_sale > 0) {
                            $user_id = $model->getColumnValue('merchant', 'merchant_id', $row->merchant_id, 'user_id');
                            $balance = $model->getColumnValue('customer', 'customer_id', $row->customer_id, 'balance');
                            $plugin = $model->getColumnValue('invoice_template', 'template_id', $config->template_id, 'plugin');
                            $invoice_number = 'System generated' . $config->invoice_no_seq;
                            $previous_dues = 0;
                            $penalty = 0;

                            $invoice_type = (isset($config->invoice_type)) ? $config->invoice_type : 1;
                            if ($invoice_type == 2) {
                                $plugin = json_decode($plugin, 1);
                                $plugin['invoice_title'] = 'Proforma invoice';
                                $plugin = json_encode($plugin);
                            }

                            $ids = $config->invoice_no_column;
                            $due_date = Carbon::now()->addDay($config->due_day)->format('Y-m-d');

                            $sum_billable_sale = $summary->sum_billable_sale + $summary->sum_billable_sale_online - $summary->sum_delivery_partner_commission;
                            $gross_fee = round($sum_billable_sale * $row->franchise_fee_commission / 100, 2);
                            $waiver_fee = round($sum_billable_sale * $row->franchise_fee_waiver / 100, 2);
                            $net_fee = round($sum_billable_sale * $row->franchise_net_commission / 100, 2);

                            $sum_non_brand_billable_sale = $summary->sum_non_brand_billable_sale + $summary->sum_non_brand_billable_sale_online - $summary->sum_non_brand_delivery_partner_commission;
                            $non_brand_gross_fee = round($sum_non_brand_billable_sale * $row->non_brand_fee_commission / 100, 2);
                            $non_brand_waiver_fee = round($sum_non_brand_billable_sale * $row->non_brand_fee_waiver / 100, 2);
                            $non_brand_net_fee = round($sum_non_brand_billable_sale * $row->non_brand_net_commission / 100, 2);

                            if ($balance > 0) {
                                $current_day = Carbon::now()->format('d');
                                if ($current_day != "01") {
                                    $previous_dues = $balance;
                                    $penalty = round($previous_dues * $row->penalty_percentage / 100);
                                    if ($penalty < 100) {
                                        $penalty = 100;
                                    }
                                }
                            }


                            $amount = $net_fee + $non_brand_net_fee + $penalty;

                            $tax = round($amount * $config->gst_rate / 100, 2);

                            $narrative = '';
                            $result = $invoiceModel->saveInvoice(
                                $row->merchant_id,
                                $user_id,
                                $row->customer_id,
                                $invoice_number,
                                $config->template_id,
                                $invoice_number,
                                $ids,
                                $bill_date,
                                $due_date,
                                $cyclename,
                                $narrative,
                                $amount,
                                $tax,
                                $previous_dues,
                                $plugin,
                                $invoice_type,
                                $config->notification
                            );
                            $tax_id = implode('~', $config->tax_ids);
                            $tax_rate = $config->gst_rate / 2;
                            $tax_percent = $tax_rate . '~' . $tax_rate;
                            $tax_applicable = $amount . '~' . $amount;
                            $tx_amt = $tax / 2;
                            $tax_amt = $tx_amt . '~' . $tx_amt;
                            if (isset($result->request_id)) {
                                $payment_request_id = $result->request_id;
                                $bill_period = $summary->start_date . ' To ' . $summary->end_date;
                                $invoiceModel->saveTax($payment_request_id, $tax_id, $tax_percent, $tax_applicable, $tax_amt, '0~0', $user_id);
                                $invoiceModel->saveInvoiceSummary($payment_request_id, $summary->sum_gross_sale, $summary->sum_billable_sale, $summary->sum_gross_sale_online, $summary->sum_billable_sale_online, $summary->sum_non_brand_gross_sale, $summary->sum_non_brand_billable_sale, $summary->sum_non_brand_gross_sale_online, $summary->sum_non_brand_billable_sale_online, $row->franchise_fee_commission, $row->franchise_fee_waiver, $row->franchise_net_commission, $row->non_brand_fee_commission, $row->non_brand_fee_waiver, $row->non_brand_net_commission, $gross_fee, $waiver_fee, $net_fee, $non_brand_gross_fee, $non_brand_waiver_fee, $non_brand_net_fee, $summary->sum_delivery_partner_commission, $summary->sum_non_brand_delivery_partner_commission, $penalty, $bill_period, $user_id);
                                $invoiceModel->updateFranchiseSales($payment_request_id, $row->customer_id);
                            } else {
                                throw new Exception($result->Message);
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                app('sentry')->captureException($e);
                Log::error(__METHOD__ . 'Error: ' . $e->getMessage());
            }
        }
    }
}
