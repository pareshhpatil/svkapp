<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\RideModel;
use App\Http\Lib\Encryption;

class BookingReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:reminder';

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
        $apiController = new ApiController();
        $rides = DB::table('passenger as p')
            ->leftJoin('roster as r', function ($join) {
                $join->on('r.passenger_id', '=', 'p.id')
                    ->where('r.start_time', '>', date('Y-m-d H:i:s')); 
            })
            ->select('p.mobile', 'p.id as passenger_id')
            ->where('p.project_id',  7)
            ->where('p.is_active',  1)
            ->where('p.passenger_type',  1)
            ->where('r.id',  null)
            ->get();

        if (empty($rides)) {
            return;
        }

        foreach ($rides as $ride) {
            $apiController->sendWhatsappMessage($ride->passenger_id, 5, 'booking_reminder', [], 'book', 'en', 1);
        }

        $apiController->sendWhatsappMessage('9730946150', 'mobile', 'booking_reminder', [], 'book', 'en', 1);
    }
}
