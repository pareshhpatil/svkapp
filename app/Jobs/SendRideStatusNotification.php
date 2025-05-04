<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ApiController;
use App\Models\ParentModel;
use App\Http\Lib\Encryption;

class SendRideStatusNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $model;

    public function __construct(
        public int $ride_id,
        public int $status
    ) {
        $this->model =  new ParentModel();
    }

    public function handle()
    {
        $ride_id = $this->ride_id;
        $status = $this->status;
        $apiController = new ApiController();
        $link = Encryption::encode($ride_id);
        if ($status == 2) {
            $passengers = $this->model->getTableList('ride_passenger', 'ride_id', $ride_id);
            foreach ($passengers as $row) {
                if ($row->status == 0) {
                    $link = Encryption::encode($row->id);
                    $url = 'https://app.svktrv.in/passenger/ride/' . $link;
                    $apiController->sendNotification($row->passenger_id, 5, 'Your ride has been started', 'Our driver is en route to your pickup location. Enjoy your journey with us.', $url);
                }
            }
        }

        $ride = $this->model->getTableRow('ride', 'id', $ride_id);
        $driver_name = $this->model->getColumnValue('driver', 'id', $ride->driver_id, 'name');
        $link = Encryption::encode($ride_id);
        $url = 'https://app.svktrv.in/admin/ride/' . $link;
        $title = '';
        $notification_type = 2;
        if ($status == 2) {
            $title = 'Ride started for ' . $ride->type . ' by ' . $driver_name;
            $message = "Ride location " . $ride->start_location . ' to ' . $ride->end_location . ' Start time: ' . date('h:i:A', strtotime($ride->start_time));
        } elseif ($status == 5) {
            $title = 'Ride completed for ' . $ride->type . ' by ' . $driver_name;
            $message = "Ride location " . $ride->start_location . ' to ' . $ride->end_location . ' End time: ' . date('h:i:A', strtotime($ride->end_time));
            $notification_type = 1;
        }
        $tokens = [];
        if ($title != '') {
            $this->saveNotification($title, $message, $url, $notification_type);
            $supervisors = $this->model->getTableList('users', 'field_supervisor', 1);
            foreach ($supervisors as $row) {
                if ($row->token != '' && $row->app_notification == 1) {
                    $tokens[] = $row->token;
                }
            }
            if (!empty($tokens)) {
                $apiController->sendNotification(0, 0, $title, $message, $url, '', $tokens);
            }
        }
    }

    public function saveNotification($title, $message, $link, $type = 2, $user_type = 3, $user_id = 0)
    {
        $array['title'] = $title;
        $array['message'] = $message;
        $array['status'] = 1;
        $array['link'] = $link;
        $array['type'] = $type;
        $array['user_type'] = $user_type;
        $array['user_id'] = $user_id;
        $array['ride_id'] = $this->ride_id;
        $this->model->insertTable('notifications', $array);
    }
}
