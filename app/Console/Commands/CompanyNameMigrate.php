<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;

class CompanyNameMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companyName:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Company name migration into customer table';

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
        $model = new MigrateModel();
        $get_customer_metadata = $model->getCustomerMetadata();
        foreach ($get_customer_metadata as $row) {
            $model->setCustomerCompanyName($row->column_id);
            $model->updateCompanyMetadata($row->column_id);
        }
        return 0;
    }
}
