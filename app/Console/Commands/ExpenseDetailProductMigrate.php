<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;

class ExpenseDetailProductMigrate extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expensedetail:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expense detail product migration';

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
        $model = new MigrateModel();
        $particulars = $model->getExpenseParticular();
        foreach ($particulars as $row) {
            echo '.';
            $product_id = $model->getProductId($row->merchant_id, $row->particular_name);
            if ($product_id == false) {
                $product = $model->getExpenseParticularInfo($row->merchant_id, $row->particular_name);
				if($product!=false)
				{
                $product_id = $model->addProduct($row->merchant_id, $row->particular_name, $product->sac_code, null, '', $product->gst_percent, 0, $product->rate);
				}
			}
			if ($product_id != false) {
            $model->updateExpenseDetailProductId($row->merchant_id, $row->particular_name, $product_id);
			}
        }
    }
}
