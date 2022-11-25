<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;

class TaxMigrate extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tax:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tax template';

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
        $tax_meta = $model->getMetaTaxCol();
        foreach ($tax_meta as $row) {
            echo '.' ;
            $merchant_id = $model->getMerchantID($row->user_id);
            $row2 = $model->getMetaColumnRow($row->column_id + 1);
            $percent = $row2->default_column_value;
            $tax_name = $row->column_name;
            if ($merchant_id == true) {
                if ($percent > 0) {
                    $tax_id = $model->getTaxID($tax_name, $percent, $merchant_id);
                    $model->updateTaxID($row->column_id, $tax_id,$row->created_date,$row->last_update_date);
                } else {
                    echo $tax_name . ' Col id: ' . $row->column_id . $row->template_id . PHP_EOL;
                }
            } else {
                echo 'user id not found Template id ' . $row->template_id . PHP_EOL;
            }
        }
    }

}
