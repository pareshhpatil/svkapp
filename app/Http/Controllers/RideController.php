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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\DynamoDBService;


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


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function rideLiveTrack(Request $request, $ride_id)
    {
        // $response = $this->model->updateTable('ride_live_location', 'ride_id', $ride_id, 'live_location', json_encode($request->all()));

        //   Log::info('Tracking POST: ' . json_encode($_POST));
        //  $seq = rand(0, 5);
        // sleep($seq);

        $live_location = $request->all();
        $array['latitude'] = $live_location['latitude'];
        $array['longitude'] = $live_location['longitude'];
        $array['speed'] = $live_location['speed'];
        $array['speedAccuracy'] = (isset($live_location['speedAccuracy'])) ? $live_location['speedAccuracy'] : 0;
        $array['live_location'] = json_encode($array);
        $array['ride_id'] = $ride_id;

        $value = Cache::get('ride_' . $ride_id);
        if ($value != false) {
            $cache_array = json_decode($value, true);
            if ($cache_array['latitude'] == $array['latitude'] && $cache_array['longitude'] == $array['longitude']) {
                Log::error('Tracking: Same');
                return;
            }
        }
        //Log::error('Tracking: ' . json_encode($request->all()));

        Log::error('Tracking: ' . json_encode($request->all()));

        Cache::put('ride_' . $ride_id, $array['live_location'], 8600); // 3600 seconds = 1 hour

        //  if ($response == false) {
        //  $this->model->saveTable('ride_live_location', $array);
        //  }
        $this->model->saveTable('ride_location_track', $array);
    }

    public function rideLocation($ride_id)
    {
        $value = Cache::get('ride_' . $ride_id);
        if ($value != false) {
            return $value;
        }
        $array = $this->model->getColumnValue('ride_location_track', 'ride_id', $ride_id, 'live_location', [], 'id');
        return $array;
    }

    public function liveTracking()
    {
        $dynamo = new DynamoDBService();
        $data['ride'] = $dynamo->getItemsByRideIds([1204, 1203]);

        dd($data['ride']);
    }

    public function liveRides(Request $request)
    {
        $data['selectedMenu'] = [14, 30];
        $data['menus'] = Session::get('menus');
        $data['status'] = 'ride';
        $data['date'] = date('Y-m-d');
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        return view('web.ride.live', $data);
    }

    public function getLiveRideLocation($id)
    {
        $dynamo = new DynamoDBService();
        $data['ride'] = $dynamo->getItemsByRideIds([1204, 1203]);
        return response()->json([
            'lat' => 15.3020,
            'lng' => 74.1260,
        ]);
    }

    public function getRidePassengers($id)
    {
        $this->model = new RideModel();
        $passengers = $this->model->getRidePassenger($id);
        return response()->json($passengers);
    }

    public function getRideUpdates($id)
    {
        $data = $this->model->getTableList('notifications', 'ride_id', $id);
        return response()->json($data);
    }

    public function getLiveRides(Request $request)
    {
        $project_id = ($request->has('project')) ? $request->project : 0;
        $status = ($request->has('status')) ? $request->status : 0;
        $date = ($request->has('date')) ? $request->date : date('Y-m-d');
        $this->model = new RideModel();
        $list = $this->model->getRides($date, $project_id, $status, Session::get('project_access'));
        $list = json_decode(json_encode($list), 1);
        $rides = [];
        foreach ($list as $k => $ride) {
            $rideId = $ride['ride_status'] . $ride['ride_id'];
            // Initialize group if not already
            if (!isset($rides[$rideId])) {
                $type = ($ride['type'] == 'Pickup') ? 'Login' : 'Logout';

                switch ($ride['ride_status']) {
                    case 1:
                        $ride_status = 'Not Started';
                        break;
                    case 2:
                        $ride_status = 'Live';
                        break;
                    case 5:
                        $ride_status = 'Completed';
                        break;
                }
                $rides[$rideId] = [
                    'ride_id'        => $ride['ride_id'],
                    'driver_name'    => $ride['driver_name'],
                    'office_location'    => $ride['office_location'],
                    'mobile'         => $ride['mobile'],
                    'vehicle_number' => $ride['vehicle_number'],
                    'type'           => $ride['type'],
                    'ride_title'     => $type . ' @' . $this->htmlTime($ride['start_time']),
                    'start_time'     => $this->htmlTime($ride['start_time']),
                    'end_time'       => $this->htmlTime($ride['end_time']),
                    'start_location' => $ride['start_location'],
                    'end_location'   => $ride['end_location'],
                    'project_cords'   => $ride['project_cords'],
                    'status'    => $ride['ride_status'],
                    'ride_status'    => $ride_status,
                    'photo'           => ($ride['photo'] != '') ? $ride['photo'] : 'https://app.svktrv.in/assets/img/driver.png',
                    'passengers' => [],
                    'live_passengers' => [],
                ];
            }
            $photo = '';
            if ($ride['passenger_type'] == 2) {
                $photo = 'https://admin.ridetrack.in/assets/img/escort.png';
            } else {
                $photo = ($ride['gender'] == 'Female') ? 'https://app.svktrv.in/assets/img/map-female.png' : 'https://app.svktrv.in/assets/img/map-male.png';
            }
            $passenger = [
                'id'              => $ride['id'],
                'passenger_type'   => $ride['passenger_type'],
                'employee_name'   => $ride['employee_name'],
                'gender'          => $ride['gender'],
                'location'        => $ride['location'],
                'pickup_time'     => $this->htmlTime($ride['pickup_time']),
                'pickup_location' => $ride['pickup_location'],
                'drop_location'   => $ride['drop_location'],
                'drop_location'   => $ride['drop_location'],
                'address'   => $ride['passenger_address'],
                'photo'   => $photo,
                'pstatus'         => $ride['pstatus'],

            ];
            // Add passenger to the list
            $rides[$rideId]['passengers'][] = $passenger;
            if ($ride['pstatus'] == 1 || $ride['pstatus'] == 5) {
                $rides[$rideId]['live_passengers'][] = $passenger;
            }
        }
        return response()->json($rides);
    }

    public function detailTrack($id)
    {
        $dynamo = new DynamoDBService();
        $data['ride'] = $dynamo->getAllRows('data', $id);

        $speed = array_map(function ($item) {
            return [
                'speed' => $item['speed'],
                'timestamp' => $item['timestamp'],
            ];
        }, $data['ride']);

        $data['speed'] = json_encode($speed);

        $this->model = new RideModel();
        $data['selectedMenu'] = [14, 15];
        $data['menus'] = Session::get('menus');
        $data['det'] = $this->model->getTableRow('ride', 'id', $id);
        $data['company_address'] = $this->model->getColumnValue('project', 'project_id', $data['det']->project_id, 'lat_long');
        $data['ride_passengers'] = $this->model->getRidePassenger($id);

        foreach ($data['ride_passengers'] as $k => $row) {
            if ($row->icon == '') {
                if ($row->gender == 'Female') {
                    $data['ride_passengers'][$k]->icon = env('MOBILE_APP_URL') . '/assets/img/map-female.png';
                } else {
                    $data['ride_passengers'][$k]->icon = env('MOBILE_APP_URL') . '/assets/img/map-male.png';
                }
            } else {
                $data['ride_passengers'][$k]->icon = $row->icon;
            }
        }
        // dd($data['ride_passengers']);
        //  dd($data);
        return view('web.ride.detail-track', $data);
    }
    public function details($id)
    {
        // $id = Encryption::decode($link);
        //$id = 508;
        $this->model = new RideModel();
        $data['selectedMenu'] = [14, 15];
        $data['menus'] = Session::get('menus');
        $data['role_id'] = Session::get('role_id');
        $data['det'] = $this->model->getTableRow('ride', 'id', $id);
        $data['det']->date = $this->htmlDate($data['det']->date, 1);
        $data['det']->start_time = $this->htmlTime($data['det']->ride_started);
        $data['det']->end_time = $this->htmlTime($data['det']->ride_ended);

        $data['driver'] = $this->model->getTableRow('driver', 'id', $data['det']->driver_id);
        $data['company_address'] = $this->model->getColumnValue('project', 'project_id', $data['det']->project_id, 'address');
        $data['vehicle'] = $this->model->getTableRow('vehicle', 'vehicle_id', $data['det']->vehicle_id);
        $data['escort'] = $this->model->getTableList('passenger', 'passenger_type', 2);
        $data['ride_passengers'] = $this->model->getRidePassenger($id);
        $data['driver_photo'] = env('MOBILE_APP_URL') . '/assets/img/driver.png';
        if ($data['driver'] != false) {
            if ($data['driver_photo'] != '') {
                $data['driver_photo'] =  $data['driver']->photo;
            }
        }
        $data['ride_status'] = array('0' => 'Created', '1' => 'Assigned', '2' => 'Start', '3' => 'Cancelled', '4' => 'Rejected', '5' => 'Completed', '6' => 'Reached', '7' => 'Picked Up');
        $data['status'] = array('0' => 'Pending', '1' => 'Start', '2' => 'Complete', '3' => 'Cancelled', '4' => 'No show', '5' => 'Reached', '6' => 'Pickup');


        foreach ($data['ride_passengers'] as $k => $row) {
            if ($row->icon == '') {
                if ($row->gender == 'Female') {
                    $data['ride_passengers'][$k]->icon = env('MOBILE_APP_URL') . '/assets/img/map-female.png';
                } else {
                    $data['ride_passengers'][$k]->icon = env('MOBILE_APP_URL') . '/assets/img/map-male.png';
                }
            } else {
                $data['ride_passengers'][$k]->icon = $row->icon;
            }
        }
        return view('web.ride.details', $data);
    }

    public function verify(Request $request)
    {
        $array['toll'] = $request->toll;
        $array['escort_id'] = $request->escort_id;
        $array['ride_started'] = $request->ride_start_time;
        $array['ride_ended'] = $request->ride_end_date;
        $this->model->updateArray('ride', 'id', $request->ride_id, $array);
        $array=[];
        foreach ($request->rp_id as $key => $id) {
            $array['in_time'] = $request->rp_in_time[$key];
            $array['status'] = $request->rp_status[$key];
            $array['drop_time'] = $request->rp_out_time[$key];
            $this->model->updateArray('ride_passenger', 'id', $id, $array);
        }

        return redirect('/ride/details/' . $request->ride_id)->withSuccess('Ride updated successfully');
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
            $ride_passenger['passenger_type'] = 2;
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
            $roster = $this->model->getTableRow('roster', 'passenger_id', $row['passenger_id'], 1, ['start_time' => $row['pickup_time'], 'status' => 0], 'booking_id,id');
            if ($roster != false) {
                $this->model->updateTable('roster', 'id', $roster->id, 'status', 1);
                if ($roster->booking_id > 0) {
                    $this->model->updateTable('ride_request', 'id', $roster->booking_id, 'status', 2);
                }
            }

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

    public function generateMis(Request $request, $bulk_id = 0, $type = 0)
    {
        $data['selectedMenu'] = [14, 15];
        $data['menus'] = Session::get('menus');
        $data['bulk_id'] = $bulk_id;
        $data['type'] = $type;
        $data['status'] = 'ride';
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        return view('web.mis.list', $data);
    }

    public function generateRideMis($id)
    {
        dd($id);
        $data['selectedMenu'] = [14, 15];
        $data['menus'] = Session::get('menus');
        $data['bulk_id'] = $bulk_id;
        $data['type'] = $type;
        $data['status'] = 'ride';
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        return view('web.mis.list', $data);
    }

    public function ajaxMISGenerate($project_id = 0,  $date = 'na')
    {
        $this->model = new RideModel();
        if (strlen($date) < 5) {
            $date = 'na';
        }

        $from_date = null;
        $to_date = null;
        if ($date != 'na') {
            $date_array = explode(' - ', $date);
            if (!empty($date_array)) {
                if (count($date_array) == 1) {
                    $from_date = $this->sqlDateTime($date_array[0]);
                    $to_date = $this->sqlDateTime($date_array[0] . ' 23:59');
                } else {
                    $from_date = $this->sqlDateTime($date_array[0]);
                    $to_date = $this->sqlDateTime($date_array[1]);
                }
            }
        }
        $data['data'] = $this->model->getPendingMis($project_id, $from_date, $to_date, Session::get('project_access'));
        return json_encode($data);
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
        $ride = $this->model->getTableRow('ride', 'id', $ride_id);
        $type = $ride->type;
        $vehicle_number = $this->model->getColumnValue('vehicle', 'vehicle_id', $vehicle_id, 'number');
        $project_location = $this->model->getColumnValue('project', 'project_id', $ride->project_id, 'location');
        $array['title'] = $type . ' ' . substr($vehicle_number, -4);
        if ($type == 'Drop') {
            $location = $this->model->getColumnValue('ride_passenger', 'ride_id', $ride_id, 'drop_location', [], 'id');
            $array['start_location'] = $project_location;
            $array['end_location'] = $location;
        } else {
            $location = $this->model->getColumnValue('ride_passenger', 'ride_id', $ride_id, 'pickup_location');
            $array['start_location'] = $location;
            $array['end_location'] = $project_location;
        }
        $this->model->updateArray('ride', 'id', $ride_id, $array);
        if ($escort_id > 0) {
            $this->model->updateWhereArray('ride_passenger', ['ride_id' => $ride_id, 'passenger_type' => 2], ['passenger_id' => $escort_id]);
        }
        $apiController = new ApiController();
        $link = Encryption::encode($ride_id);
        $url = 'https://app.svktrv.in/driver/ride/' . $link;
        $apiController->sendNotification($driver_id, 4, 'A new trip has been assigned', 'Please make sure to arrive at the pick-up location on time and provide a safe and comfortable ride to the passenger', $url);
        $driver_name = $this->model->getColumnValue('driver', 'id', $driver_id, 'name');

        $short_url = $this->random();
        $this->model->saveTable('short_url', ['short_url' => $short_url, 'long_url' => $url]);
        $params = [];
        $params[] = array('type' => 'text', 'text' => $driver_name);
        $params[] = array('type' => 'text', 'text' => $array['start_location']);
        $params[] = array('type' => 'text', 'text' => $array['end_location']);
        $params[] = array('type' => 'text', 'text' => $this->htmlShortDateTime($ride->start_time));

        $apiController->sendWhatsappMessage($driver_id, 4, 'driver_assign', $params, $short_url, 'hi', 1);

        $params = [];
        $passengers = $this->model->getTableList('ride_passenger', 'ride_id', $ride_id);

        foreach ($passengers as $row) {
            $link = Encryption::encode($row->id);
            $url = 'https://app.svktrv.in/passenger/ride/' . $link;
            $apiController->sendNotification($row->passenger_id, 5, 'Cab has been assigned for your next ride', 'Please be ready at your pickup location. Have a safe and pleasant journey.', $url);
            $short_url = $this->random();
            $this->model->saveTable('short_url', ['short_url' => $short_url, 'long_url' => $url]);
            $url = 'app.svktrv.in/l/' . $short_url;

            //$message_ = 'Cab assigned for ' . $ride->type . ' on ' . $this->htmlDate($row->pickup_time) . ' Please reach your pickup point at ' . $this->htmlTime($row->pickup_time) . ' Trip details ' . $url . ' - Siddhivinayak Travels House';
            $params['var1'] = $ride->type;
            $params['var2'] = $this->htmlDate($row->pickup_time);
            $params['var3'] = $this->htmlTime($row->pickup_time);
            $params['var4'] = $url;
            $apiController->sendSMS($row->passenger_id, 5, $params, '6804878cd6fc0553042e8f65');
            $employee_name = $this->model->getColumnValue('passenger', 'id', $row->passenger_id, 'employee_name');
            if ($type == 'Drop') {
                $start_location = "Office";
                $end_location = "Home";
            } else {
                $start_location = "Home";
                $end_location = "Office";
            }
            $params = [];
            $params[] = array('type' => 'text', 'text' => $employee_name);
            $params[] = array('type' => 'text', 'text' => $start_location);
            $params[] = array('type' => 'text', 'text' => $end_location);
            $params[] = array('type' => 'text', 'text' => $this->htmlShortDateTime($row->pickup_time));
            $params[] = array('type' => 'text', 'text' => $driver_name);
            $params[] = array('type' => 'text', 'text' => $vehicle_number);
            $params[] = array('type' => 'text', 'text' => $row->otp);
            $apiController->sendWhatsappMessage($row->passenger_id, 5, 'ride_confirmation', $params, $short_url, 'en', 1);
        }
    }

    public function downloadMIS(Request $request)
    {
        $data['selectedMenu'] = [33, 14];
        $data['menus'] = Session::get('menus');
        $data['status'] = 'na';
        $data['current_date_range'] = date('d M Y') . ' 05:00 PM - ' . Date('d M Y', strtotime('+1 days')) . ' 05:00 PM';
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));

        return view('web.mis.download', $data);
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
