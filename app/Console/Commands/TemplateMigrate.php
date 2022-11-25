<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;

class TemplateMigrate extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'template:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate template';

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
        $model = new MigrateModel();
        $template_list = $model->getTableData('invoice_template');
        foreach ($template_list as $row) {
            echo '.';
            $template_id = $row->template_id;
            $row->particular_column = '';
            $row->default_particular = '';
            $row->default_tax = '';
            switch ($row->template_type) {
                case 'society':
                    $row->particular_column = '{"item":"Particular name","qty":"No of units","rate":"Unit price","total_amount":"Absolute cost","narrative":"Narrative"}';
                    break;
                case 'school':
                    $row->particular_column = '{"item":"Fee type","description":"Duration","total_amount":"Absolute cost","narrative":"Narrative"}';
                    break;
                case 'hotel':
                    $row->particular_column = '{"item":"Product","qty":"Qty","rate":"Unit price","total_amount":"Absolute cost"}';
                    break;
                case 'isp':
                    $row->particular_column = '{"sr_no":"#","item":"Description","annual_recurring_charges":"Annual recurring charges","description":"Time period","total_amount":"Absolute cost"}';
                    break;
                case 'travel_car_booking':
                    $row->particular_column = '{"item":"Particular name","qty":"No of units","rate":"Unit price","total_amount":"Absolute cost","narrative":"Narrative"}';
                    break;
            }
            $pf = $model->getParticularCol($template_id);
            $tax_col = $model->getTaxCol($template_id);
            $supplier = $model->getSupplier($template_id);
            $deduct = $model->getDeduct($template_id);
            $dparticular = array();
            if (!empty($pf)) {
                foreach ($pf as $particular) {
                    $dparticular[] = $particular->column_name;
                }
            }
            $dtax = array();
            if (!empty($tax_col)) {
                foreach ($tax_col as $tax) {
                    if ($tax->default_column_value > 0) {
                        $dtax[] = $tax->default_column_value;
                    }
                }
            }
            $row->default_tax = json_encode($dtax);
            $row->default_particular = json_encode($dparticular);

            $plugin = array();
            if ($row->is_coupon == 1) {
                $plugin['has_coupon'] = 1;
            }
            if ($row->is_roundoff == 1) {
                $plugin['roundoff'] = 1;
            }
            if ($row->is_cc == 1) {
                $cc = $model->getCC($template_id);
                $plugin['has_cc'] = 1;
                if (!empty($cc)) {
                    foreach ($cc as $email) {
                        $plugin['cc_email'][] = $email->column_name;
                    }
                }
            }
            if ($row->has_vendor == 1) {
                $plugin['has_vendor'] = 1;
            }
            if ($row->is_franchise == 1) {
                $plugin['has_franchise'] = 1;
                $plugin['franchise_notify_email'] = $row->franchise_notify_email;
                $plugin['franchise_notify_sms'] = $row->franchise_notify_sms;
                $plugin['franchise_name_invoice'] = $row->franchise_name_invoice;
            }
            if ($row->has_acknowledgement == 1) {
                $plugin['has_acknowledgement'] = 1;
            }
            if ($row->is_prepaid == 1) {
                $plugin['is_prepaid'] = 1;
            }
            if ($row->has_covering_note == 1) {
                $plugin['has_covering_note'] = 1;
                $plugin['default_covering_note'] = $row->covering_id;
            }
            if ($row->has_custom_notification == 1) {
                $plugin['has_custom_notification'] = 1;
                $plugin['custom_email_subject'] = $row->custom_subject;
                $plugin['custom_sms'] = $row->custom_sms;
            }
            if ($row->has_autocollect == 1) {
                $plugin['has_autocollect'] = 1;
            }
            if ($row->has_custom_reminder == 1) {
                $plugin['has_custom_reminder'] = 1;
                $reminders = explode(',', $row->custom_reminder);
                $rsubject = json_decode($row->custom_reminder, 1);
                $rsms = json_decode($row->custom_reminder_sms, 1);
                foreach ($reminders as $key => $day) {
                    $plugin['reminders'][$day] = array('email_subject' => $rsubject[$key], 'sms' => $rsms[$key]);
                }
            }
            if ($row->enable_partial_payment == 1) {
                $plugin['has_partial'] = 1;
                $plugin['partial_min_amount'] = $row->partial_min_amount;
            }

            if (!empty($supplier)) {
                $plugin['has_supplier'] = 1;
            }



            $row->plugin = json_encode($plugin);
            $row->particular_total = 'Particular Total';
            $row->tax_total = 'Tax Total';
            $row->invoice_title = 'Performa Invoice';
            $model->saveTemplate($row);
        }
    }

}
