<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;
use App\Models\RideModel;
use App\Http\Controllers\ApiController;
use App\Http\Lib\Encryption;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell_DataType;

class RideController extends Controller
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


    public function details($id)
    {
        // $id = Encryption::decode($link);
        //$id = 508;
        $this->model = new RideModel();
        $data['selectedMenu'] = [14, 15];
        $data['menus'] = Session::get('menus');
        $data['det'] = $this->model->getTableRow('ride', 'id', $id);
        $data['det']->date = $this->htmlDate($data['det']->date, 1);
        $data['det']->start_time = $this->htmlTime($data['det']->start_time);
        $data['det']->end_time = $this->htmlTime($data['det']->end_time);

        $data['driver'] = $this->model->getTableRow('driver', 'id', $data['det']->driver_id);
        $data['company_address'] = $this->model->getColumnValue('project', 'project_id', $data['det']->project_id, 'address');
        $data['vehicle'] = $this->model->getTableRow('vehicle', 'vehicle_id', $data['det']->vehicle_id);
        $data['ride_passengers'] = $this->model->getRidePassenger($id);
        $data['driver_photo'] = env('MOBILE_APP_URL') . '/assets/img/driver.png';
        if ($data['driver'] != false) {
            if ($data['driver_photo'] != '') {
                $data['driver_photo'] = env('MOBILE_APP_URL') . $data['driver']->photo;
            }
        }


        foreach ($data['ride_passengers'] as $k => $row) {
            if ($row->icon == '') {
                if ($row->gender == 'Female') {
                    $data['ride_passengers'][$k]->icon = env('MOBILE_APP_URL') . '/assets/img/map-female.png';
                } else {
                    $data['ride_passengers'][$k]->icon = env('MOBILE_APP_URL') . '/assets/img/map-male.png';
                }
            } else {
                $data['ride_passengers'][$k]->icon = env('MOBILE_APP_URL') . $row->icon;
            }
        }
        //  dd($data);
        return view('web.ride.details', $data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create($id = null)
    {
        $data['selectedMenu'] = [14, 8];
        $data['menus'] = Session::get('menus');
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        $data['passenger_list'] = $this->model->getTableList('passenger', 'is_active', 1, 0, Session::get('project_access'));
        $data['det'] = [];
        if ($id > 0) {
            $data['det'] = $this->model->getTableRow('ride', 'id', $id);
            $data['det']->date = $this->htmlDate($data['det']->date, 1);
            $data['det']->start_time = $this->sqlTime($data['det']->start_time);
            $data['det']->end_time = $this->sqlTime($data['det']->end_time);
            $data['passengers'] = $this->model->getTableList('ride_passenger', 'ride_id', $id);
        }
        $passenger_list = [];
        //  if (!empty($data['passenger_list'])) {
        //  foreach ($data['passenger_list'] as $p) {
        //   $passenger_list[$p['project_id']] = $p;
        //  }
        //  }
        // $data['passenger_list'] = $passenger_list;
        return view('web.ride.create', $data);
    }

    public function delete($id)
    {
        $this->model->updateTable('ride', 'id', $id, 'is_active', 0);
    }

    public function save(Request $request)
    {
        $user_id = Session::get('user_id');
        $array['project_id'] = $request->project_id;
        $array['date'] = $this->sqlDate($request->date);
        $array['type'] = $request->type;
        $array['title'] = $request->title;
        $array['escort'] = $request->escort;
        $array['start_location'] = $request->start_location;
        $array['end_location'] = $request->end_location;
        $array['total_passengers'] = count($request->passengers);
        $array['start_time'] = $array['date'] . ' ' . $this->sqlTime($request->start_time);
        $array['end_time'] = $array['date'] . ' ' . $this->sqlTime($request->end_time);
        if ($request->ride_id > 0) {
            $ride_id = $request->ride_id;
            $this->model->updateArray('ride', 'id', $ride_id, $array);
            $this->model->updateTable('ride_passenger', 'ride_id', $ride_id, 'is_active', 0);
        } else {
            $ride_id = $this->model->saveTable('ride', $array, $user_id);
        }

        if ($array['escort'] > 0) {
            $ride_passenger['roster_id'] = 0;
            $ride_passenger['ride_id'] = $ride_id;
            $ride_passenger['passenger_id'] = 0;
            $ride_passenger['pickup_location'] = $array['start_location'];
            $ride_passenger['drop_location'] = $array['end_location'];
            $ride_passenger['pickup_time'] = $array['start_time'];
            $ride_passenger['otp'] = rand(1111, 9999);
            $this->model->saveTable('ride_passenger', $ride_passenger, $user_id);
        }

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
            if ($row['pid'] > 0) {
                $pid = $row['pid'];
                unset($row['pid']);
                $row['is_active'] = 1;
                $this->model->updateArray('ride_passenger', 'id', $pid, $row);
            } else {
                unset($row['pid']);
                $row['otp'] = rand(1111, 9999);
                $this->model->saveTable('ride_passenger', $row, $user_id);
            }
        }
        if ($request->ride_id > 0) {
            return redirect('/ride/list')->withSuccess('Ride updated successfully');
        } else {
            return redirect()->back()->withSuccess('Ride added successfully');
        }
    }


    public function list(Request $request, $bulk_id = 0, $type = 0)
    {
        $data['selectedMenu'] = [14, 9];
        $data['menus'] = Session::get('menus');
        $data['bulk_id'] = $bulk_id;
        $data['type'] = $type;
        $data['status'] = 'na';
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        return view('web.ride.list', $data);
    }

    public function rideList(Request $request, $bulk_id = 0, $type = 0)
    {
        $data['selectedMenu'] = [14, 15];
        $data['menus'] = Session::get('menus');
        $data['bulk_id'] = $bulk_id;
        $data['type'] = $type;
        $data['status'] = 'ride';
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        return view('web.ride.list', $data);
    }

    public function assign()
    {
        $data['selectedMenu'] = [14, 10];
        $data['menus'] = Session::get('menus');
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        $data['vehicle_list'] = $this->model->getTableList('vehicle', 'is_active', 1);
        $data['driver_list'] = $this->model->getTableList('driver', 'is_active', 1);
        return view('web.ride.assign', $data);
    }

    public function assignCab($ride_id, $driver_id, $vehicle_id, $escort_id = 0)
    {
        $array['driver_id'] = $driver_id;
        $array['vehicle_id'] = $vehicle_id;
        $array['escort'] = ($escort_id > 0) ? 1 : 0;
        $array['status'] = 1;
        $this->model->updateArray('ride', 'id', $ride_id, $array);
        if ($escort_id > 0) {
            $this->model->updateWhereArray('ride_passenger', ['ride_id' => $ride_id, 'passenger_id' => 0], ['passenger_id' => $escort_id]);
        }
        $apiController = new ApiController();
        $link = Encryption::encode($ride_id);
        $url = 'https://app.svktrv.in/driver/ride/' . $link;
        $apiController->sendNotification($driver_id, 4, 'A new trip has been assigned', 'Please make sure to arrive at the pick-up location on time and provide a safe and comfortable ride to the passenger', $url);
        $passengers = $this->model->getTableList('ride_passenger', 'ride_id', $ride_id);
        $ride = $this->model->getTableRow('ride', 'id', $ride_id);
        foreach ($passengers as $row) {
            $link = Encryption::encode($row->id);
            $url = 'https://app.svktrv.in/passenger/ride/' . $link;
            $apiController->sendNotification($row->passenger_id, 5, 'Cab has been assigned for your next ride', 'Please be ready at your pickup location. Have a safe and pleasant journey.', $url);
            $short_url = $this->random();
            $this->model->saveTable('short_url', ['short_url' => $short_url, 'long_url' => $url]);
            $url = 'https://app.svktrv.in/l/' . $short_url;

            $message_ = 'Cab assigned for ' . $ride->type . ' on ' . $this->htmlDate($row->pickup_time) . ' Please reach your pickup point at ' . $this->htmlTime($row->pickup_time) . ' Trip details ' . $url . ' - Siddhivinayak Travels House';
            $apiController->sendSMS($row->passenger_id, 5, $message_, '1107168138570499675');
        }
    }

    public function ajaxRide($project_id = 0,  $date = 'na', $status = 'na', $type = 'na')
    {
        $statusarray = [];
        if (strlen($date) < 5) {
            $date = 'na';
        }
        if ($date != 'na') {
            $date = $this->sqlDate($date);
        }
        if ($status != 'na') {
            if ($status == 'ride') {
                $statusarray = array(5);
            } else {
                $statusarray[] = $status;
            }
        }

        $data['data'] = $this->model->getRide($project_id, $date, $statusarray, Session::get('project_access'));
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
        return $short;
    }
}