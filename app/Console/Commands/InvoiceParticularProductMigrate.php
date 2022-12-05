<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;

class InvoiceParticularProductMigrate extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoiceparticular:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invoice particular product migration';

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
        $particulars = $model->getInvoiceParticular();
        $this->manageProducts($model, $particulars);

        $particulars = $model->getStagingInvoiceParticular();
        $this->manageProducts($model, $particulars,'staging_');
    }

    function manageProducts($model, $particulars, $table = '')
    {
        foreach ($particulars as $row) {
            echo '.';
            $product_id = $model->getProductId($row->merchant_id, $row->item);
            if ($product_id == false) {
                $product = $model->getInvoiceParticularInfo($row->merchant_id, $row->item,$table);
				if($product!=false)
				{
                $price = ($product->rate > 0) ? $product->rate : $product->total_amount;
                $product_id = $model->addProduct($row->merchant_id, $row->item, $product->sac_code, $product->unit_type, $product->description, $product->gst, $price);
				}
            }
			if($product_id!=false)
			{
            $model->updateInvoiceParticularProductId($row->merchant_id, $row->item, $product_id,$table);
			}
        }
    }
}
