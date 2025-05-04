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

class SendPassengerStatusNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $model;
    private $ride_id;

    public function __construct(
        public int $passenger_id,
        public int $status
    ) {
        $this->model =  new ParentModel();
    }

    public function handle()
    {
        //$passenger = PassengerRide::find($this->passenger_id);
        // if (!$passenger) return;
        $ride_passenger_id = $this->passenger_id;
        $status = $this->status;
        $apiController = new ApiController();
        $model =  new ParentModel();
        $row = $model->getTableRow('ride_passenger', 'id', $ride_passenger_id);
        $ride_id = $row->ride_id;
        $this->ride_id = $row->ride_id;
        if ($status == 5 || $status == 1) {
            if ($status == 5) {
                $link = Encryption::encode($ride_passenger_id);
                $url = 'https://app.svktrv.in/passenger/ride/' . $link;
                $apiController->sendNotification($row->passenger_id, 5, 'Cab Arrived', 'Your cab has arrived at your pickup location. We hope you have a pleasant ride', $url);
            }
        }
        if ($status == 2) {
            $url = 'https://app.svktrv.in/dashboard';
            $apiController->sendNotification($row->passenger_id, 5, 'Your ride has been completed', 'We hope you had a pleasant journey with us. Please rate your ride experience', $url);
        }



        $ride = $this->model->getTableRow('ride', 'id', $ride_id);
        $driver_name = $this->model->getColumnValue('driver', 'id', $ride->driver_id, 'name');
        $passenger_name = $this->model->getColumnValue('passenger', 'id', $row->passenger_id, 'employee_name');
        $link = Encryption::encode($row->id);
        $url = 'https://app.svktrv.in/admin/ride/' . $link;
        $title = '';
        $notification_type = 1;
        if ($status == 6) {
            $title = $passenger_name . ' picked up from ' . $row->pickup_location . ' by ' . $driver_name;
            $message = "Ride location " . $ride->start_location . ' to ' . $ride->end_location . ' Pickup time: ' . date('h:i:A', strtotime($ride->pickup_time));
        } elseif ($status == 2) {
            $title = $passenger_name . ' droped at ' . $row->drop_location . ' by ' . $driver_name;
            $message = "Ride location " . $ride->start_location . ' to ' . $ride->end_location . ' Type: ' . $ride->type;
            $notification_type = 1;
        } elseif ($status == 5) {
            $title = $driver_name . ' has reached at ' . $passenger_name . ' location';
            $message = "Ride location " . $ride->start_location . ' to ' . $ride->end_location . ' Pickup time: ' . date('h:i:A', strtotime($ride->pickup_time));
            $notification_type = 2;
        } elseif ($status == 4) {
            $title = $driver_name . ' mark no show for ' . $passenger_name;
            $message = "Ride location " . $ride->start_location . ' to ' . $ride->end_location . ' Pickup time: ' . date('h:i:A', strtotime($ride->pickup_time));
            $notification_type = 2;
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
