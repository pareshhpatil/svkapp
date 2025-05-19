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
use App\Http\Lib\Encryption;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\Job;
use Carbon\Carbon;

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
    private $created_date;
    private $notification_send;

    public function __construct(
        public int $passenger_id,
        public int $status
    ) {}

    public function handle(Job $job)
    {
        $this->notification_send = true;
        $this->setCreatedDateFromJob($job);

        $ridePassengerRow = $this->getTableRow('ride_passenger', 'id', $this->passenger_id);
        if (!$ridePassengerRow) return;

        $this->ride_id = $ridePassengerRow->ride_id;
        $status = $this->status;

        // Build notification details
        $notification = $this->buildNotificationData($ridePassengerRow, $status);
        if (!$notification) return;

        // Save the notification
        $this->saveNotification($notification['title'], $notification['message'], $notification['url'], $notification['type']);

        // Send to passenger if applicable
        if ($this->notification_send == true) {
            $this->maybeSendPassengerNotification($ridePassengerRow, $status, $notification['url']);
            // Send to supervisors
            $this->sendSupervisorNotifications($notification);
        }
    }

    private function setCreatedDateFromJob(Job $job)
    {
        $payload = $job->payload();
        $timestamp = $payload['created_at'] ?? time();
        $this->created_date = Carbon::createFromTimestamp($timestamp)->toDateTimeString();
    }

    private function maybeSendPassengerNotification($row, $status, $url)
    {
        $api = new ApiController();

        if ($status == 5) {
            $api->sendNotification(
                $row->passenger_id,
                5,
                'Cab Arrived',
                'Your cab has arrived at your pickup location. We hope you have a pleasant ride',
                $url
            );
        } elseif ($status == 2) {
            $api->sendNotification(
                $row->passenger_id,
                5,
                'Your ride has been completed',
                'We hope you had a pleasant journey with us. Please rate your ride experience',
                $url
            );
        }
    }

    private function buildNotificationData($row, $status)
    {
        $ride = $this->getTableRow('ride', 'id', $this->ride_id);
        if (!$ride) return null;

        $driverName = $this->getColumnValue('driver', 'id', $ride->driver_id, 'name');
        $passengerName = $this->getColumnValue('passenger', 'id', $row->passenger_id, 'employee_name');
        $pickupTime = date('h:i:A', strtotime($row->pickup_time));
        $url = 'https://app.svktrv.in/admin/ride/' . Encryption::encode($ride->id);

        $notification = [
            'title' => '',
            'message' => '',
            'url' => $url,
            'type' => 1,
        ];

        switch ($status) {
            case 6:
                $notification['title'] = "$passengerName picked up from {$row->pickup_location} by $driverName";
                $notification['message'] = "Ride location {$row->pickup_location} to {$row->drop_location} Pickup time: $pickupTime";
                break;
            case 2:
                $notification['title'] = "$passengerName dropped at {$row->drop_location} by $driverName";
                $notification['message'] = "Ride location {$row->pickup_location} to {$row->drop_location} Type: {$ride->type}";
                break;
            case 5:
                $notification['title'] = "$driverName has reached $passengerName location";
                $notification['message'] = "Ride location {$row->pickup_location} to {$row->drop_location} Pickup time: $pickupTime";
                $notification['type'] = 2;
                break;
            case 1:
                $notification['title'] = "$passengerName picked up from {$row->pickup_location} by $driverName";
                $notification['message'] = "Ride location {$row->pickup_location} to {$row->drop_location} Pickup time: $pickupTime";
                break;
            case 4:
                $notification['title'] = "$driverName marked no-show for $passengerName";
                $notification['message'] = "Ride location {$row->pickup_location} to {$row->drop_location} Pickup time: $pickupTime";
                $notification['type'] = 2;
                break;
        }

        return $notification['title'] ? $notification : null;
    }

    private function sendSupervisorNotifications($notification)
    {
        $api = new ApiController();
        $tokens = [];

        $supervisors = $this->getTableList('users', 'field_supervisor', 1);
        foreach ($supervisors as $user) {
            if (!empty($user->token) && $user->app_notification == 1) {
                $tokens[] = $user->token;
            }
        }

        if (!empty($tokens)) {
            $api->sendNotification(0, 0, $notification['title'], $notification['message'], $notification['url'], '', $tokens);
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
        $this->insertTable('notifications', $array);
    }

    public function getTableList($table, $where, $value, $col = '*')
    {

        $retObj = DB::table($table)
            ->select(DB::raw($col))
            ->where('is_active', 1)
            ->where($where, $value)
            ->get();
        return $retObj;
    }

    public function getColumnValue($table, $where, $value, $column_name, $param = [], $orderby = null)
    {

        $retObj = DB::table($table)
            ->select(DB::raw($column_name . ' as value'))
            ->where($where, $value);
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                $retObj->where($k, $v);
            }
        }
        if ($orderby != null) {
            $retObj->orderByDesc($orderby);
        }
        $array = $retObj->first();
        if (!empty($array)) {
            return $array->value;
        } else {
            return false;
        }
    }

    public function getTableRow($table, $where, $value, $active = 0, $param = [])
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where($where, $value);
        if ($active == 1) {
            $retObj->where('is_active', 1);
        }
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                $retObj->where($k, $v);
            }
        }
        $array = $retObj->first();
        if (!empty($array)) {
            return $array;
        } else {
            return false;
        }
    }

    public function insertTable($table_name, $array, $created_by = '0')
    {
        $array['created_by'] = $created_by;
        $array['last_update_by'] = $created_by;
        $array['created_date'] = $this->created_date;
        $array['last_update_date'] = $this->created_date;
        $id = DB::table($table_name)->insertGetId(
            $array
        );
        return $id;
    }
}
