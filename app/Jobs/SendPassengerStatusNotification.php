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
    ) {}

    public function handle()
    {
        //$passenger = PassengerRide::find($this->passenger_id);
        // if (!$passenger) return;
        $ride_passenger_id = $this->passenger_id;
        $status = $this->status;
        $apiController = new ApiController();
        $row = $this->getTableRow('ride_passenger', 'id', $ride_passenger_id);
        $ride_id = $row->ride_id;
        $this->ride_id = $row->ride_id;
        if ($status == 5) {
            $link = Encryption::encode($ride_passenger_id);
            $url = 'https://app.svktrv.in/passenger/ride/' . $link;
            $apiController->sendNotification($row->passenger_id, 5, 'Cab Arrived', 'Your cab has arrived at your pickup location. We hope you have a pleasant ride', $url);
        }
        if ($status == 2) {
            $url = 'https://app.svktrv.in/dashboard';
            $apiController->sendNotification($row->passenger_id, 5, 'Your ride has been completed', 'We hope you had a pleasant journey with us. Please rate your ride experience', $url);
        }

        $ride = $this->getTableRow('ride', 'id', $ride_id);
        $driver_name = $this->getColumnValue('driver', 'id', $ride->driver_id, 'name');
        $passenger_name = $this->getColumnValue('passenger', 'id', $row->passenger_id, 'employee_name');
        $link = Encryption::encode($ride->id);
        $url = 'https://app.svktrv.in/admin/ride/' . $link;
        $title = '';
        $notification_type = 1;
        if ($status == 6) {
            $title = $passenger_name . ' picked up from ' . $row->pickup_location . ' by ' . $driver_name;
            $message = "Ride location " . $row->pickup_location . ' to ' . $row->drop_location . ' Pickup time: ' . date('h:i:A', strtotime($row->pickup_time));
        } elseif ($status == 2) {
            $title = $passenger_name . ' droped at ' . $row->drop_location . ' by ' . $driver_name;
            $message = "Ride location " . $row->pickup_location . ' to ' . $row->drop_location . ' Type: ' . $ride->type;
            $notification_type = 1;
        } elseif ($status == 5) {
            $title = $driver_name . ' has reached at ' . $passenger_name . ' location';
            $message = "Ride location " . $row->pickup_location . ' to ' . $row->drop_location . ' Pickup time: ' . date('h:i:A', strtotime($row->pickup_time));
            $notification_type = 2;
        } elseif ($status == 1) {
            $title = $passenger_name . ' picked up from ' . $row->pickup_location . ' by ' . $driver_name;
            $message = "Ride location " . $row->pickup_location . ' to ' . $row->drop_location . ' Pickup time: ' . date('h:i:A', strtotime($row->pickup_time));
            $notification_type = 1;
        } elseif ($status == 4) {
            $title = $driver_name . ' mark no show for ' . $passenger_name;
            $message = "Ride location " . $row->pickup_location . ' to ' . $row->drop_location . ' Pickup time: ' . date('h:i:A', strtotime($row->pickup_time));
            $notification_type = 2;
        }
        $tokens = [];
        if ($title != '') {
            $this->saveNotification($title, $message, $url, $notification_type);
            $supervisors = $this->getTableList('users', 'field_supervisor', 1);
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
        $array['created_date'] = date('Y-m-d H:i:s');
        $id = DB::table($table_name)->insertGetId(
            $array
        );
        return $id;
    }
}
