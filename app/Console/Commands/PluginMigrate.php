<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;

ini_set('memory_limit', '-1');

class PluginMigrate extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Invoice plugin';

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

        $start = 0;
        $limit = 10000;
        $count = 1;
        //$int = 1;
        while ($count > 0) {
            $model = new MigrateModel();
            $list = $model->getPaymentRequestPlugin($start, $limit);
            $count = count($list);
            $start = $start + $limit;
            //echo 'batch no:' . $int . ' count rows' . $count;
            //$int++;
            foreach ($list as $row) {
                //echo '.';
                $template_row = $model->getTemplateDetail($row->template_id);
                $plugin = json_decode($template_row->plugin, 1);
                $group_row = $model->getGroupID('DF', $row->payment_request_id);
                if (!empty($group_row)) {
                    $plugin['has_deductible'] = 1;
                    $plugin['deductible'] = array();
                    foreach ($group_row as $key => $gr) {
                        $gprows = $model->getGroupData($gr->column_group_id, 'DS', $row->payment_request_id);
                        $data = (object) array();
                        $data->payment_request_id = $row->payment_request_id;
                        $data->created_date = $gr->created_date;
                        $data->last_update_date = $gr->created_date;
                        $data->tax_name = $gr->column_name;
                        $data->tax_name = '';
                        $data->applicable = 0;
                        $data->tax_amount = 0;
                        foreach ($gprows as $gprow) {
                            switch ($gprow->column_name) {
                                case 'Percentage':
                                    $data->tax_percent = $gprow->value;
                                    break;
                                case 'Applicable on (RS)':
                                    $data->applicable = $gprow->value;
                                    break;
                                case 'Absolute cost':
                                    $data->tax_amount = $gprow->value;
                                    break;
                            }
                        }
                        $plugin['deductible'][$key]['tax_name'] = $data->tax_name;
                        $plugin['deductible'][$key]['percent'] = $data->tax_percent;
                        $plugin['deductible'][$key]['applicable'] = $data->applicable;
                        $plugin['deductible'][$key]['total'] = $data->tax_amount;
                    }
                }

                $cc = $model->getGroupID('CC', $row->payment_request_id);
                if (!empty($cc)) {
                    $plugin['has_cc'] = 1;
                    $plugin['cc_email'] = array();
                    foreach ($cc as $email) {
                        $plugin['cc_email'][] = $email->value;
                    }
                }
                if (isset($plugin['has_franchise'])) {
                    if ($plugin['has_franchise'] == 1) {
                        $plugin['franchise_name_invoice'] = $row->franchise_name_invoice;
                    }
                }
                if (isset($plugin['has_coupon'])) {
                    if ($plugin['has_coupon'] == 1) {
                        $plugin['coupon_id'] = $row->coupon_id;
                    }
                }
                if (isset($plugin['has_custom_notification'])) {
                    if ($plugin['has_custom_notification'] == 1) {
                        $plugin['custom_email_subject'] = $row->custom_subject;
                        $plugin['custom_sms'] = $row->custom_sms;
                    }
                }
                if ($row->has_custom_reminder == 1) {
                    $plugin['has_custom_reminder'] = $row->has_custom_reminder;
                } else {
                    unset($plugin['has_custom_reminder']);
                }
                if ($row->invoice_title != '') {
                    $plugin['invoice_title'] = $row->invoice_title;
                }
                if ($row->enable_partial_payment == 1) {
                    $plugin['has_partial'] = 1;
                    $plugin['partial_min_amount'] = $row->partial_min_amount;
                } else {
                    unset($plugin['has_partial']);
                    unset($plugin['partial_min_amount']);
                }
                if (!empty($plugin)) {
                    $model->updateInvoicePlugin($row->payment_request_id, json_encode($plugin),$row->created_date,$row->last_update_date);
                }
            }
        }
    }

}
