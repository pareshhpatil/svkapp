<?php

namespace App\Console\Commands;

use App\Imports\CustomerImport;
use App\Imports\CostTypeImport;

use App\Model\User;
use App\Model\InvoiceFormat;

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
        $merchant_data = $this->user_model->getTableRow('merchant', 'merchant_id', $merchant_id);
        $user_id = $merchant_data->user_id;

        $this->insertData( $merchant_id,  $user_id);
       
    }

    function insertData( $merchant_id,  $user_id){

        Excel::import(new CustomerImport($merchant_id, $user_id), env('BRIQ_TEST_DATA_CUSTOMER_FILE'));
        Excel::import(new CostTypeImport($merchant_id, $user_id), env('BRIQ_TEST_DATA_COST_TYPE_FILE'));
        $this->insertInvoiceTemplate($merchant_id, $user_id);
        echo $merchant_id;
        return true;

    }

    function insertInvoiceTemplate($merchant_id, $user_id){
        $formatModel = new InvoiceFormat();
        $template_id = $formatModel->getSequenceId('Template_id');
        $template_id = $formatModel->insertDefaultConstructionFormat($template_id, $merchant_id, $user_id);
        
    }

}
