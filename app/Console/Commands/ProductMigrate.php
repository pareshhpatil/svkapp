<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;

class ProductMigrate extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product unit type migration';

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
        $unit_type = $model->getProductUnit();
        foreach ($unit_type as $row) {
            echo '.';
            $unit_type_id = $model->getUnitTypeId($row->merchant_id, $row->unit_type);
            if ($unit_type_id == false) {
                $unit_type_id = $model->addUnitType($row->merchant_id, $row->unit_type);
            }
            $model->updateUnitType($row->merchant_id, $row->unit_type, $unit_type_id);
        }
    }
}
