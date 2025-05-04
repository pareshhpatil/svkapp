<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\RideModel;
use App\Http\Lib\Encryption;

class RideReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ride:reminder';

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
        $currentTime = Carbon::now();
        $apiController = new ApiController();
        $RideModel = new RideModel();
        $currentDateTime = now();  // Get current date and time
        $futureDateTime = $currentDateTime->copy()->addMinutes(30);
        $rides = DB::table('ride')
            ->join('driver', 'ride.driver_id', '=', 'driver.id') // Join the driver table on the driver_id
            ->select('ride.id as ride_id', 'ride.start_time', 'driver.mobile')
            ->where('start_time', '>', $currentDateTime)
            ->where('start_time', '<', $futureDateTime)
            ->where('reminder_sent',  0)
            ->where('status',  1)
            ->get();
        
        foreach ($rides as $ride) {
            $rideStartTime = Carbon::parse($ride->start_time);
            $remainingMinutes = $currentTime->diffInMinutes($rideStartTime, false);
            $long_url = 'https://app.svktrv.in/driver/ride/' . Encryption::encode($ride->ride_id);
            $short_url = $RideModel->getColumnValue('short_url', 'long_url', $long_url, 'short_url');
            $params[] = array('type' => 'text', 'text' => $remainingMinutes);
            $apiController->sendWhatsappMessage($ride->mobile, 'mobile', 'ride_reminder', $params, $short_url, 'hi', 1);
            $RideModel->updateTable('ride', 'id', $ride->ride_id, 'reminder_sent', 1);
        }
    }
}
