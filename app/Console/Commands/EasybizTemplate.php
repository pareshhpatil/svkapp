<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;

class EasybizTemplate extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easybiz:template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Easybiz template';

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
            $template_rows = $model->getTemplateDetails();
            foreach ($template_rows as $row) {
                $template_id = $row->template_id;
                $meta_rows = $model->getTableRows('e_invoice_column_metadata', 'template_id', $row->template_id);
                $pr = array();
                foreach ($meta_rows as $mr) {
                    if ($mr->column_type == 'PC' && $mr->is_active == 1) {
                        $pr[$mr->system_col_name] = $mr->column_name;
                    }
                }
                $taxes = json_decode($row->default_tax);
                $tax_rows = array();
                if (!empty($taxes)) {
                    foreach ($taxes as $tr) {
                        $tax_rows[] = $model->getEasybizTaxID($tr);
                    }
                }

                $plugin = array();
                if ($row->is_cc == 1) {
                    $plugin['has_cc'] = 1;
                }
                if ($row->is_roundoff == 1) {
                    $plugin['roundoff'] = 1;
                }
                $row->user_id = $row->new_user_id;
                $row->merchant_id = $row->new_merchant_id;
                $row->particular_column = json_encode($pr);
                $row->default_tax = json_encode($tax_rows);
                $row->plugin = null;
                if (!empty($plugin)) {
                    $row->plugin = json_encode($plugin);
                }
                $row->created_by = $row->new_user_id;
                $row->last_update_by = $row->new_user_id;
                $template_id = $model->saveEasybizTemplate($row);
                $model->saveTemplateMetadata($row->template_id, $template_id, $row->new_user_id);
            }
        } catch (Exception $e) {
            Log::error(__CLASS__ . __METHOD__ . 'Payment request id: ' . $template_id . $e->getMessage());
        }
    }

}
