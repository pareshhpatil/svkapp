<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ApiController;
use App\Http\Lib\Encryption;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SendRideStatusNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $model;
    private $created_date;
    private $notification_send;

    public function __construct(
        public int $ride_id,
        public int $status
    ) {}

    public function handle()
    {
        $this->notification_send = true;
        $this->setCreatedDateFromJob();

        $rideId = $this->ride_id;
        $status = $this->status;

        // 1. Build supervisor notification content
        $notification = $this->buildSupervisorNotificationData($rideId, $status);

        // 2. Save notification (if available)
        if ($notification) {
            $this->saveNotification($notification['title'], $notification['message'], $notification['url'], $notification['type']);
            // 3. Send notification to supervisors (only after saving)
            if ($this->notification_send == true) {
                $this->sendSupervisorNotifications($notification);
            }
        }

        // 4. Notify passengers on ride start (if applicable) - this runs regardless of supervisor notifications
        if ($status == 2 && $this->notification_send == true) {
            $this->notifyPassengersOnRideStart($rideId);
        }
    }

    private function setCreatedDateFromJob()
    {
        // Get the timestamp from the job's payload
        $timestamp = $this->job->payload()['created_at'] ?? time();
        $this->created_date = Carbon::createFromTimestamp($timestamp)->toDateTimeString();
    }

    private function buildSupervisorNotificationData($rideId, $status)
    {
        $ride = $this->getTableRow('ride', 'id', $rideId);
        if (!$ride) return null;

        $driverName = $this->getColumnValue('driver', 'id', $ride->driver_id, 'name');
        $url = 'https://app.svktrv.in/admin/ride/' . Encryption::encode($rideId);

        $notification = [
            'title' => '',
            'message' => '',
            'url' => $url,
            'type' => 2, // Default type for completed
        ];

        // Build the notification content based on the status
        if ($status == 2) {
            $notification['title'] = "Ride started for {$ride->type} by $driverName";
            $notification['message'] = "Ride location {$ride->start_location} to {$ride->end_location} Start time: " . date('h:i:A', strtotime($ride->start_time));
        } elseif ($status == 5) {
            $notification['title'] = "Ride completed for {$ride->type} by $driverName";
            $notification['message'] = "Ride location {$ride->start_location} to {$ride->end_location} End time: " . date('h:i:A', strtotime($ride->end_time));
            $notification['type'] = 1; // Completed rides
        }

        return $notification['title'] ? $notification : null;
    }

    private function sendSupervisorNotifications($notification)
    {
        $api = new ApiController();
        $tokens = [];

        // Get all supervisors' tokens
        $supervisors = $this->getTableList('users', 'field_supervisor', 1);
        foreach ($supervisors as $user) {
            if (!empty($user->token) && $user->app_notification == 1) {
                $tokens[] = $user->token;
            }
        }

        // Send notification to all supervisors
        if (!empty($tokens)) {
            $api->sendNotification(0, 0, $notification['title'], $notification['message'], $notification['url'], '', $tokens);
        }
    }

    private function notifyPassengersOnRideStart($rideId)
    {
        $passengers = $this->getTableList('ride_passenger', 'ride_id', $rideId);
        $api = new ApiController();

        foreach ($passengers as $passenger) {
            if ($passenger->status == 0) {
                $link = Encryption::encode($passenger->id);
                $url = 'https://app.svktrv.in/passenger/ride/' . $link;

                $api->sendNotification(
                    $passenger->passenger_id,
                    5,
                    'Your ride has been started',
                    'Our driver is en route to your pickup location. Enjoy your journey with us.',
                    $url
                );
            }
        }
    }

    public function getTableList($table, $where, $value, $col = '*')
    {
        return DB::table($table)
            ->select(DB::raw($col))
            ->where('is_active', 1)
            ->where($where, $value)
            ->get();
    }

    public function getColumnValue($table, $where, $value, $column_name, $param = [], $orderby = null)
    {
        $query = DB::table($table)
            ->select(DB::raw($column_name . ' as value'))
            ->where($where, $value);
        
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                $query->where($k, $v);
            }
        }
        
        if ($orderby != null) {
            $query->orderByDesc($orderby);
        }
        
        $result = $query->first();
        return $result ? $result->value : false;
    }

    public function getTableRow($table, $where, $value, $active = 0, $param = [])
    {
        $query = DB::table($table)
            ->select(DB::raw('*'))
            ->where($where, $value);
        
        if ($active == 1) {
            $query->where('is_active', 1);
        }
        
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                $query->where($k, $v);
            }
        }
        
        $result = $query->first();
        return $result ?: false;
    }

    public function saveNotification($title, $message, $link, $type = 2, $user_type = 3, $user_id = 0)
    {
        $data = [
            'title' => $title,
            'message' => $message,
            'status' => 1,
            'link' => $link,
            'type' => $type,
            'user_type' => $user_type,
            'user_id' => $user_id,
            'ride_id' => $this->ride_id,
        ];

        $this->insertTable('notifications', $data);
    }

    public function insertTable($table_name, $data, $created_by = '0')
    {
        $data['created_by'] = $created_by;
        $data['last_update_by'] = $created_by;
        $data['created_date'] = $this->created_date;
        $data['last_update_date'] = $this->created_date;
        
        return DB::table($table_name)->insertGetId($data);
    }
}
