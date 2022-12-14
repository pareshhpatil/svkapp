<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PartialPaidProcedureWithoutPluginCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'partial:paid:stored:procedure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will migrate partial paid without plugin stored procedures.';

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
        $this->info("Stored procedures import started");

        $data_files = File::allFiles('database/storedprocedures');

        foreach($data_files as $file){
            if($file->getFilename() == 'set_partialypaid_amount_without_plugin.sql') {
                $path = $file->getPathName();
                $this->info($path);
                DB::unprepared(file_get_contents($path));
                $this->info('-----------------------------------------');
            }
        }
    }
}
