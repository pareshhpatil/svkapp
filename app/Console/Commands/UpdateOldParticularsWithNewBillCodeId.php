<?php

namespace App\Console\Commands;

use App\ContractParticular;
use Illuminate\Console\Command;

class UpdateOldParticularsWithNewBillCodeId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:old-particulars-with-new-bill-code-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update old particulars of Contract, Invoice, CO';

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
        $contracts = ContractParticular::where('particulars', '!=', '')->get();
        foreach ($contracts as $contract) {

        }
    }
}
