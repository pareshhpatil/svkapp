<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;
use App\Http\Controllers\ApiController;
use App\Http\Lib\Encryption;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell_DataType;

class RosterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $model = null;
    public $user_id = null;

    public function __construct()
    {
        $this->model = new MasterModel();
        $this->user_id = Session::get('user_id');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $data['selectedMenu'] = [7, 8];
        $data['menus'] = Session::get('menus');
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1);
        $data['passenger_list'] = $this->model->getTableList('passenger', 'is_active', 1);
        $passenger_list = [];
        //  if (!empty($data['passenger_list'])) {
        //  foreach ($data['passenger_list'] as $p) {
        //   $passenger_list[$p['project_id']] = $p;
        //  }
        //  }
        // $data['passenger_list'] = $passenger_list;
        return view('web.roster.create', $data);
    }

    public function save(Request $request)
    {
        $user_id = Session::get('user_id');
        $array['project_id'] = $request->project_id;
        $array['date'] = $this->sqlDate($request->date);
        $array['type'] = $request->type;
        $array['title'] = $request->title;
        $array['start_location'] = $request->start_location;
        $array['end_location'] = $request->end_location;
        $array['total_passengers'] = count($request->passengers);
        $array['start_time'] = $array['date'] . ' ' . $this->sqlTime($request->start_time);
        $array['end_time'] = $array['date'] . ' ' . $this->sqlTime($request->end_time);
        $ride_id = $this->model->saveTable('ride', $array, $user_id);
        foreach ($request->passengers as $row) {
            $row['pickup_time'] = $array['date'] . ' ' . $this->sqlTime($row['pickup_time']);
            $row['ride_id'] = $ride_id;
            $emp_location = $this->model->getColumnValue('passenger', 'id', $row['passenger_id'], 'location');

            if ($array['type'] == 'Pickup') {
                $row['pickup_location'] = $emp_location;
                $row['drop_location'] = $array['end_location'];
            } else {
                $row['pickup_location'] = $array['start_location'];
                $row['drop_location'] = $emp_location;
            }
            $row['otp'] = rand(1111, 9999);
            $this->model->saveTable('ride_passenger', $row, $user_id);
        }

        return redirect()->back()->withSuccess('Roster added successfully');
    }


    public function list(Request $request, $bulk_id = 0, $type = 0)
    {
        $data['selectedMenu'] = [7, 9];
        $data['menus'] = Session::get('menus');
        $data['bulk_id'] = $bulk_id;
        $data['type'] = $type;
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1);
        return view('web.roster.list', $data);
    }

    public function assign()
    {
        $data['selectedMenu'] = [7, 10];
        $data['menus'] = Session::get('menus');
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1);
        $data['vehicle_list'] = $this->model->getTableList('vehicle', 'is_active', 1);
        $data['driver_list'] = $this->model->getTableList('driver', 'is_active', 1);
        return view('web.roster.assign', $data);
    }

    public function assignCab($ride_id, $driver_id, $vehicle_id)
    {
        $array['driver_id'] = $driver_id;
        $array['vehicle_id'] = $vehicle_id;
        $array['status'] = 1;
        $this->model->updateArray('ride', 'id', $ride_id, $array);
        $apiController = new ApiController();
        $link = Encryption::encode($ride_id);
        $url = 'https://app.svktrv.in/driver/ride/' . $link;
        $apiController->sendNotification($driver_id, 4, 'A new trip has been assigned', 'Please make sure to arrive at the pick-up location on time and provide a safe and comfortable ride to the passenger', $url);
        $passengers = $this->model->getTableList('ride_passenger', 'ride_id', $ride_id);
        $ride = $this->model->getTableRow('ride', 'ride_id', $ride_id);
        foreach ($passengers as $row) {
            $link = Encryption::encode($row->id);
            $url = 'https://app.svktrv.in/passenger/ride/' . $link;
            $apiController->sendNotification($row->passenger_id, 5, 'Cab has been assigned for your next ride', 'Please be ready at your pickup location. Have a safe and pleasant journey.', $url);
            $short_url = $this->random();
            $this->model->saveTable('short_url', ['short_url' => $short_url, 'long_url' => $url]);
            $url = 'app.svktrv.in/l/' . $short_url;
            $message_ = 'Cab assigned for ' . $ride->type . ' on ' . $this->htmlDate($row->pickup_time) . ' Please reach your pickup point at ' . $this->htmlTime($row->pickup_time) . ' Trip details ' . $url . ' - Siddhivinayak Travels House';
            $apiController->sendSMS($row->passenger_id, 5, $message_, '1107168138570499675');
        }
    }

    public function ajaxRoster($project_id = 0,  $date = 'na', $status = 'na')
    {
        if (strlen($date) < 5) {
            $date = 'na';
        }
        if ($date != 'na') {
            $date = $this->sqlDate($date);
        }
        $data['data'] = $this->model->getRoster($project_id, $date, $status);
        return json_encode($data);
    }

    function random($length_of_string = 4)
    {
        // String of all alphanumeric character
        $str_result = '0123456789bcdfghjklmnpqrstvwxyz';
        // Shuffle the $str_result and returns substring
        // of specified length
        $exist = true;
        while ($exist == true) {
            $short = substr(
                str_shuffle($str_result),
                0,
                $length_of_string
            );
            $exist = $this->model->getTableRow('short_url', 'short_url', $short);
        }
    }
}
