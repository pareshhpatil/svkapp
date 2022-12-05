<?php

namespace App\Console\Commands;

use App\Imports\CustomerImport;

use App\Model\User;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Console\Command;

class ImportBriqData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importdata:briq {--merchant_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import briq sample data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->user_model = new User();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $merchant_id = $this->option('merchant_id');
        $this->insertData( $merchant_id);
       
    }

    function insertData( $merchant_id){
        $merchant_data = $this->user_model->getTableRow('merchant', 'merchant_id', $merchant_id);
        $user_id = $merchant_data->user_id;

        Excel::import(new CustomerImport($merchant_id, $user_id), env('BRIQ_TEST_DATA_CUSTOMER_FILE'));
        echo $merchant_id;
        return true;

    }
}
