<?php

namespace App\Jobs;

use App\Constants\Models\IColumn;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('briq_roles')
            ->insert([
                'merchant_id' => 'M000000041',
                'name' => "Job Role",
                'description' => "Test Job Role",
                'created_by' => 'U000000165',
                'last_updated_by' => 'U000000165',
                IColumn::CREATED_AT  => Carbon::now()->toDateTimeString(),
                IColumn::UPDATED_AT  => Carbon::now()->toDateTimeString()
            ]);
    }

    public function failed()
    {
        DB::table('briq_roles')
            ->insert([
                'merchant_id' => 'M000000041',
                'name' => "Failed Job Role",
                'description' => "Test Job Role",
                'created_by' => 'U000000165',
                'last_updated_by' => 'U000000165',
                IColumn::CREATED_AT  => Carbon::now()->toDateTimeString(),
                IColumn::UPDATED_AT  => Carbon::now()->toDateTimeString()
            ]);
    }
}
