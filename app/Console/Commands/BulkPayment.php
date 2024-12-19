<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell_DataType;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterModel;
use App\Models\StaffModel;

class BulkPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $model = new StaffModel();
        $data = $model->getBillList(1);
        dd($data);
    }
}
