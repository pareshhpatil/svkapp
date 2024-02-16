<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ParentModel;
use App\Models\RideModel;
use Validator;
use Intervention\Image\ImageManager;
use Image;
use App\Http\Lib\Encryption;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $model;

    public function __construct()
    {
        //$this->middleware('auth');
        $this->model = new RideModel();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = json_decode($this->dashboardData(), 1);
        $data['settings'] = $this->model->getTableRow('users', 'id', Session::get('user_id'));
        return view('passenger.index', $data);
    }

    public function dashboardData()
    {
        $data['menu'] = 1;
        $data['title'] = 'dashboard';
        if (Session::get('user_type') == 5) {
            $data['data']['total_ride'] = $this->model->getTableCount('ride_passenger', 'passenger_id', Session::get('parent_id'), 1);
            $data['data']['completed_ride'] = $this->model->getTableCount('ride_passenger', 'passenger_id', Session::get('parent_id'), 1, ['status' => 2]);
            $data['live_ride'] = $this->EncryptList($this->model->passengerLiveRide(Session::get('parent_id')), 1);
            $data['last_ride'] = $this->EncryptList($this->model->passengerLastRide(Session::get('parent_id')), 1);
            $data['data']['upcoming'] = $this->model->passengerUpcomingRides(Session::get('parent_id'), 1);
            if (!empty($data['data']['upcoming'])) {
                $data['data']['upcoming']['link'] = '/passenger/ride/' . Encryption::encode($data['data']['upcoming']['pid']);
            }
        } else if (Session::get('user_type') == 4) {
            $data['data']['total_ride'] = $this->model->getTableCount('ride', 'driver_id', Session::get('parent_id'), 1);
            $data['data']['completed_ride'] = $this->model->getTableCount('ride', 'driver_id', Session::get('parent_id'), 1, ['status' => 5]);

            $data['live_ride'] = [];
            $data['data']['upcoming'] = $this->model->driverUpcomingRides(Session::get('parent_id'), 1);
            if (!empty($data['data']['upcoming'])) {
                $data['data']['upcoming']['link'] = '/driver/ride/' . Encryption::encode($data['data']['upcoming']['pid']);
            }
            Session::put('token', 'aa');
        } else if (Session::get('user_type') == 3) {
            $data['data']['total_ride'] = $this->model->getTableCount('ride', 'is_active',  1);
            $data['data']['completed_ride'] = $this->model->getTableCount('ride', 'is_active', 1, 0, ['status' => 5]);

            $data['live_ride'] = [];
            $data['data']['upcoming'] = false;
        } else if (Session::get('user_type') == 2) {
            $data['data']['total_ride'] = $this->model->getTableCount('ride', 'is_active',  1);
            $data['data']['completed_ride'] = $this->model->getTableCount('ride', 'is_active', 1, 0, ['status' => 5]);

            $data['live_ride'] = [];
            $data['data']['upcoming'] = false;
        } else {
            $data['data']['total_ride'] = 0;
            $data['data']['completed_ride'] = 0;

            $data['live_ride'] = [];
            $data['data']['upcoming'] = false;
        }


        $data['data']['ride'] = $data['live_ride'];
        if (!empty($data['live_ride'])) {
            $ride_passengers = $this->model->getRidePassenger($data['live_ride']['ride_id']);
            $data['data']['ride_passengers'] = $ride_passengers;
            $data['data']['ride_passenger']['passenger_id'] = $data['live_ride']['passenger_id'];
            $data['data']['ride_passenger']['id'] = $data['live_ride']['pid'];
            $data['data']['ride_passenger']['ride_id'] = $data['live_ride']['ride_id'];
        }
        $data['data']['pending_request'] = 0;
        $data['data']['pending_request_show'] = 0;
        $data['data']['blogs'] = [];
        if (Session::get('user_type') == 3 || Session::get('user_type') == 2) {
            $data['data']['pending_request'] = $this->model->getPendingRequestCount(Session::get('parent_id'));
            $data['data']['pending_request_show'] = 1;
        }
        if (Session::get('user_type') != 3 && Session::get('user_type') != 2) {
            $data['data']['blogs'] = $this->model->getTableList('blogs', 'is_active', 1)->toArray();
        }
        $data['settings'] = $this->model->getTableRow('users', 'id', Session::get('user_id'));
        $data['current_date'] = $this->htmlDate(date('Y-m-d'));
        $data['type'] = 'upcoming';

        //dd($data);
        $data['rides'] = json_decode($this->ridesData(), 1);
        return json_encode($data);
    }


    public function dashboard()
    {

        $data['menu'] = 1;
        $data['title'] = 'dashboard';
        if (Session::get('user_type') == 5) {
            $data['data']['total_ride'] = $this->model->getTableCount('ride_passenger', 'passenger_id', Session::get('parent_id'), 1);
            $data['data']['completed_ride'] = $this->model->getTableCount('ride_passenger', 'passenger_id', Session::get('parent_id'), 1, ['status' => 2]);
            $data['live_ride'] = $this->EncryptList($this->model->passengerLiveRide(Session::get('parent_id')), 1);
            $data['last_ride'] = $this->EncryptList($this->model->passengerLastRide(Session::get('parent_id')), 1);
            $data['data']['upcoming'] = $this->model->passengerUpcomingRides(Session::get('parent_id'), 1);
            if (!empty($data['data']['upcoming'])) {
                $data['data']['upcoming']['link'] = '/passenger/ride/' . Encryption::encode($data['data']['upcoming']['pid']);
            }
        } else if (Session::get('user_type') == 4) {
            $data['data']['total_ride'] = $this->model->getTableCount('ride', 'driver_id', Session::get('parent_id'), 1);
            $data['data']['completed_ride'] = $this->model->getTableCount('ride', 'driver_id', Session::get('parent_id'), 1, ['status' => 5]);

            $data['live_ride'] = [];
            $data['data']['upcoming'] = $this->model->driverUpcomingRides(Session::get('parent_id'), 1);
            if (!empty($data['data']['upcoming'])) {
                $data['data']['upcoming']['link'] = '/driver/ride/' . Encryption::encode($data['data']['upcoming']['pid']);
            }
            Session::put('token', 'aa');
        } else if (Session::get('user_type') == 3) {
            $data['data']['total_ride'] = $this->model->getTableCount('ride', 'is_active',  1);
            $data['data']['completed_ride'] = $this->model->getTableCount('ride', 'is_active', 1, 0, ['status' => 5]);

            $data['live_ride'] = [];
            $data['data']['upcoming'] = false;
        } else if (Session::get('user_type') == 2) {
            $data['data']['total_ride'] = $this->model->getTableCount('ride', 'is_active',  1);
            $data['data']['completed_ride'] = $this->model->getTableCount('ride', 'is_active', 1, 0, ['status' => 5]);

            $data['live_ride'] = [];
            $data['data']['upcoming'] = false;
        } else {
            $data['data']['total_ride'] = 0;
            $data['data']['completed_ride'] = 0;

            $data['live_ride'] = [];
            $data['data']['upcoming'] = false;
        }


        $data['data']['ride'] = $data['live_ride'];
        if (!empty($data['live_ride'])) {
            $ride_passengers = $this->model->getRidePassenger($data['live_ride']['ride_id']);
            $data['data']['ride_passengers'] = $ride_passengers;
            $data['data']['ride_passenger']['passenger_id'] = $data['live_ride']['passenger_id'];
            $data['data']['ride_passenger']['id'] = $data['live_ride']['pid'];
            $data['data']['ride_passenger']['ride_id'] = $data['live_ride']['ride_id'];
        }
        $data['data']['pending_request'] = 0;
        $data['data']['pending_request_show'] = 0;
        $data['data']['blogs'] = [];
        if (Session::get('user_type') == 3 || Session::get('user_type') == 2) {
            $data['data']['pending_request'] = $this->model->getPendingRequestCount(Session::get('parent_id'));
            $data['data']['pending_request_show'] = 1;
        }
        if (Session::get('user_type') != 3 && Session::get('user_type') != 2) {
            $data['data']['blogs'] = $this->model->getTableList('blogs', 'is_active', 1)->toArray();
        }
        $data['current_date'] = $this->htmlDate(date('Y-m-d'));
        $data['type'] = 'upcoming';

        //dd($data);
        return view('passenger.dashboard', $data);
    }

    public function passengerRideDetail($link)
    {
        if ($link == '__manifest.json') {
            return false;
        }
        $id = Encryption::decode($link);

        $ride_passenger = $this->model->getRowArray('ride_passenger', 'id', $id);

        $ride = $this->model->getRowArray('ride', 'id', $ride_passenger['ride_id']);
        $project = $this->model->getRowArray('project', 'project_id', $ride['project_id']);
        $driver = [];
        $vehicle = [];
        if ($ride['driver_id'] > 0) {
            $driver = $this->model->getRowArray('driver', 'id', $ride['driver_id']);
        }
        if ($ride['vehicle_id'] > 0) {
            $vehicle = $this->model->getRowArray('vehicle', 'vehicle_id', $ride['vehicle_id']);
        }
        $ride_passengers = $this->model->getRidePassenger($ride_passenger['ride_id']);
        $ride_passenger['pickup_time'] = $this->htmlDateTime($ride_passenger['pickup_time']);
        $data['data']['ride_passengers'] = $ride_passengers;
        $ride['start_time'] = $this->htmlDateTime($ride['start_time']);
        $ride['end_time'] = $this->htmlDateTime($ride['end_time']);
        $data['data']['ride_passenger'] = $ride_passenger;
        $data['data']['ride'] = $ride;
        $data['data']['project'] = $project;
        $data['data']['driver'] = $driver;
        $data['data']['vehicle'] = $vehicle;

        $data['data']['link'] = env('APP_URL') . '/passenger/ride/' . $link;
        $data['menu'] = 0;
        $data['title'] = 'Ride detail';
        $data['enc_link'] = $link;
        return view('passenger.ride-detail', $data);
    }

    public function adminRideDetail($link, $type = '')
    {
        $ride_id = Encryption::decode($link);
        $ride = $this->model->getRowArray('ride', 'id', $ride_id);
        if ($ride_id == false) {
            return redirect('/my-rides');
        }
        $project = $this->model->getRowArray('project', 'project_id', $ride['project_id']);
        $driver = [];
        $vehicle = [];
        if ($ride['driver_id'] > 0) {
            $driver = $this->model->getRowArray('driver', 'id', $ride['driver_id']);
        }
        if ($ride['vehicle_id'] > 0) {
            $vehicle = $this->model->getRowArray('vehicle', 'vehicle_id', $ride['vehicle_id']);
        }
        $data['title'] = 'Ride detail';
        if ($type == 'assign') {
            $data['driver_list'] = $this->model->getTableList('driver', 'is_active', 1, 'id,name,location');
            $data['vehicle_list'] = $this->model->getTableList('vehicle', 'is_active', 1, 'vehicle_id,number');
            $data['escort_list'] = $this->model->getList('passenger', ['project_id' => $ride['project_id'], 'passenger_type' => 2], "concat(employee_name,' - ', location) as title,id");
            $data['title'] = 'Assign cab';
            $data['passenger_list'] = $this->model->getTableList('passenger', 'project_id', $ride['project_id'], "concat(employee_name,' - ', location) as title,id");
        }
        $ride_passengers = $this->model->getRidePassenger($ride_id);
        $ride_passenger['pickup_time'] = $this->htmlDateTime($ride['start_time']);
        $ride_passenger['passenger_id'] = 0;
        $ride['start_time'] = $this->htmlDateTime($ride['start_time']);
        $ride['end_time'] = $this->htmlDateTime($ride['end_time']);
        $data['data']['ride_start_time'] = $this->sqlTime($ride['start_time']);
        $data['data']['ride_passenger'] = $ride_passenger;
        $data['data']['ride'] = $ride;
        $data['data']['project'] = $project;
        $data['data']['driver'] = $driver;
        $data['data']['vehicle'] = $vehicle;
        $data['data']['ride_passengers'] = $ride_passengers;
        $data['enc_link'] = $link;
        $data['data']['link'] = env('APP_URL') . '/admin/ride/' . $link;
        $data['type'] = $type;
        $data['menu'] = 0;
        $data['ride_id'] = $ride_id;
        return view('passenger.ride-detail', $data);
    }

    public function adminRideAssign($link)
    {
        return $this->adminRideDetail($link, 'assign');
    }



    public function driverRideDetail($link)
    {
        if ($link == '__manifest.json') {
            return false;
        }
        $ride_id = Encryption::decode($link);
        $ride = $this->model->getRowArray('ride', 'id', $ride_id);
        $project = $this->model->getRowArray('project', 'project_id', $ride['project_id']);
        $driver = [];
        $vehicle = [];
        if ($ride['driver_id'] > 0) {
            $driver = $this->model->getRowArray('driver', 'id', $ride['driver_id']);
        }
        if ($ride['vehicle_id'] > 0) {
            $vehicle = $this->model->getRowArray('vehicle', 'vehicle_id', $ride['vehicle_id']);
        }
        $ride_passengers = $this->model->getRidePassenger($ride_id);
        $ride_passenger['pickup_time'] = $this->htmlDateTime($ride['start_time']);
        $ride['start_time'] = $this->htmlDateTime($ride['start_time']);
        $ride['end_time'] = $this->htmlTime($ride['end_time']);
        $data['data']['ride'] = $ride;
        $data['data']['project'] = $project;
        $data['data']['driver'] = $driver;
        $data['data']['vehicle'] = $vehicle;
        $data['data']['ride_passengers'] = $ride_passengers;
        $data['data']['link'] = env('APP_URL') . '/driver/ride/' . $link;
        $data['ride_id'] = $ride_id;
        $data['menu'] = 0;
        $data['title'] = 'Ride detail';
        return view('driver.ride-detail', $data);
    }

    public function rideTrack($link)
    {
        if (Session::get('user_type') == 3) {
            return $this->adminrideTrack($link);
        }
        $id = Encryption::decode($link);
        $ride_passenger = $this->model->getRowArray('ride_passenger', 'id', $id);
        $ride = $this->model->getRowArray('ride', 'id', $ride_passenger['ride_id']);
        $project = $this->model->getRowArray('project', 'project_id', $ride['project_id']);
        $driver = [];
        $vehicle = [];
        if ($ride['driver_id'] > 0) {
            $driver = $this->model->getRowArray('driver', 'id', $ride['driver_id']);
        }
        if ($ride['vehicle_id'] > 0) {
            $vehicle = $this->model->getRowArray('vehicle', 'vehicle_id', $ride['vehicle_id']);
        }
        $passenger = $this->model->getRowArray('users', 'parent_id', $ride_passenger['passenger_id'], 0, ['user_type' => 5]);
        $ride_passengers = $this->model->getRidePassenger($ride_passenger['ride_id']);
        $data['live_location'] = $this->model->getColumnValue('ride_location_track', 'ride_id', $ride_passenger['ride_id'], 'live_location', [], 'id');
        $ride_passenger['pickup_time'] = $this->htmlDateTime($ride_passenger['pickup_time']);
        $ride['start_time'] = $this->htmlDateTime($ride['start_time']);
        $ride['end_time'] = $this->htmlDateTime($ride['end_time']);

        $data['data']['passenger'] = $passenger;
        $data['data']['ride_passenger'] = $ride_passenger;
        $data['data']['ride'] = $ride;
        $data['data']['project'] = $project;
        $data['data']['driver'] = $driver;
        $data['data']['vehicle'] = $vehicle;
        $data['data']['ride_passengers'] = $ride_passengers;
        $data['data']['link'] = env('APP_URL') . '/passenger/ride/' . $link;
        $data['menu'] = 0;
        $data['ride_id'] = $ride_passenger['ride_id'];
        $data['live_location'] = json_decode($data['live_location'], 1);
        $data['title'] = 'Ride Tracking';
        $data['onload'] = 'initialize()';
        return view('passenger.ride-track', $data);
    }

    public function adminrideTrack($link)
    {
        $ride_id = Encryption::decode($link);
        $ride = $this->model->getRowArray('ride', 'id', $ride_id);
        $project = $this->model->getRowArray('project', 'project_id', $ride['project_id']);
        $driver = [];
        $vehicle = [];
        if ($ride['driver_id'] > 0) {
            $driver = $this->model->getRowArray('driver', 'id', $ride['driver_id']);
        }
        if ($ride['vehicle_id'] > 0) {
            $vehicle = $this->model->getRowArray('vehicle', 'vehicle_id', $ride['vehicle_id']);
        }
        // $passenger = $this->model->getRowArray('users', 'parent_id', $ride_passenger['passenger_id'], 0, ['user_type' => 5]);
        $ride_passengers = $this->model->getRidePassenger($ride_id);
        $data['live_location'] = $this->model->getColumnValue('ride_location_track', 'ride_id', $ride_id, 'live_location', [], 'id');
        $ride_passenger['pickup_time'] = $this->htmlDateTime($ride['start_time']);
        $ride['start_time'] = $this->htmlDateTime($ride['start_time']);
        $ride['end_time'] = $this->htmlDateTime($ride['end_time']);
        $data['data']['passenger'] = [];
        $data['data']['passenger']['gender'] = 'Male';
        $data['data']['passenger']['icon'] = '';
        $data['data']['passenger']['name'] = Session::get('name');
        $data['data']['ride_passenger'] = $ride_passenger;
        $data['data']['ride'] = $ride;
        $data['data']['project'] = $project;
        $data['data']['driver'] = $driver;
        $data['data']['vehicle'] = $vehicle;
        $data['data']['ride_passengers'] = $ride_passengers;
        $data['data']['link'] = env('APP_URL') . '/admin/ride/' . $link;
        $data['menu'] = 0;
        $data['ride_id'] = $ride_id;
        $data['live_location'] = json_decode($data['live_location'], 1);
        $data['title'] = 'Ride Tracking';
        $data['onload'] = 'initialize()';
        return view('passenger.ride-track', $data);
    }


    public function rides($type = 'upcoming')
    {
        $data['menu'] = 2;
        $data['title'] = 'My Rides';
        $data['type'] = $type;
        $data['current_date'] = $this->htmlDate(date('Y-m-d'));
        if (Session::get('user_type') == 5) {
            $data['data']['upcoming'] = $this->EncryptList($this->model->passengerUpcomingRides(Session::get('parent_id')));
            $data['data']['live'] = $this->EncryptList($this->model->passengerLiveRide(Session::get('parent_id'), 0), 0);
            $data['data']['past'] = $this->EncryptList($this->model->passengerPastRides(Session::get('parent_id')));
            $data['data']['booking'] = $this->EncryptList($this->model->passengerBookingRides(Session::get('parent_id')), 0, '/passenger/booking/', 'id');
        } else if (Session::get('user_type') == 4) {
            $data['data']['upcoming'] = $this->EncryptList($this->model->driverUpcomingRides(Session::get('parent_id')), 0, '/driver/ride/');
            $data['data']['live'] = $this->EncryptList($this->model->driverLiveRide(Session::get('parent_id'), 0), 0, '/driver/ride/');
            $data['data']['past'] = $this->EncryptList($this->model->driverPastRides(Session::get('parent_id')), 0, '/driver/ride/');
        } else if (Session::get('user_type') == 3) {
            $data['data']['pending'] = $this->EncryptList($this->model->adminPendingRides(), 0, '/admin/ride/assign/');
            $data['data']['upcoming'] = $this->EncryptList($this->model->driverUpcomingRides(Session::get('parent_id')), 0, '/admin/ride/');
            $data['data']['live'] = $this->EncryptList($this->model->driverLiveRide(Session::get('parent_id'), 0), 0, '/admin/ride/');
            $data['data']['past'] = $this->EncryptList($this->model->driverPastRides(Session::get('parent_id')), 0, '/admin/ride/');
        } else if (Session::get('user_type') == 2) {
            $data['data']['upcoming'] = $this->EncryptList($this->model->driverUpcomingRides(Session::get('parent_id')), 0, '/admin/ride/');
            $data['data']['live'] = $this->EncryptList($this->model->driverLiveRide(Session::get('parent_id'), 0), 0, '/admin/ride/');
            $data['data']['past'] = $this->EncryptList($this->model->driverPastRides(Session::get('parent_id')), 0, '/admin/ride/');
        } else {
            $data['data']['upcoming'] = [];
            $data['data']['live'] = [];
            $data['data']['past'] = [];
        }
        if ($type == 'request') {
            $data['data']['request'] = $this->EncryptList($this->model->pendingBookingRides(Session::get('project_id')), 0, '/passenger/booking/', 'id');
        }
        return view('passenger.my-rides', $data);
    }

    public function ridesData($type = 'upcoming')
    {
        $data['menu'] = 2;
        $data['title'] = 'My Rides';
        $data['type'] = $type;
        $data['current_date'] = $this->htmlDate(date('Y-m-d'));
        if (Session::get('user_type') == 5) {
            $data['data']['upcoming'] = $this->EncryptList($this->model->passengerUpcomingRides(Session::get('parent_id')));
            $data['data']['live'] = $this->EncryptList($this->model->passengerLiveRide(Session::get('parent_id'), 0), 0);
            $data['data']['past'] = $this->EncryptList($this->model->passengerPastRides(Session::get('parent_id')));
            $data['data']['booking'] = $this->EncryptList($this->model->passengerBookingRides(Session::get('parent_id')), 0, '/passenger/booking/', 'id');
        } else if (Session::get('user_type') == 4) {
            $data['data']['upcoming'] = $this->EncryptList($this->model->driverUpcomingRides(Session::get('parent_id')), 0, '/driver/ride/');
            $data['data']['live'] = $this->EncryptList($this->model->driverLiveRide(Session::get('parent_id'), 0), 0, '/driver/ride/');
            $data['data']['past'] = $this->EncryptList($this->model->driverPastRides(Session::get('parent_id')), 0, '/driver/ride/');
        } else if (Session::get('user_type') == 3) {
            $data['data']['pending'] = $this->EncryptList($this->model->adminPendingRides(), 0, '/admin/ride/assign/');
            $data['data']['upcoming'] = $this->EncryptList($this->model->driverUpcomingRides(Session::get('parent_id')), 0, '/admin/ride/');
            $data['data']['live'] = $this->EncryptList($this->model->driverLiveRide(Session::get('parent_id'), 0), 0, '/admin/ride/');
            $data['data']['past'] = $this->EncryptList($this->model->driverPastRides(Session::get('parent_id')), 0, '/admin/ride/');
        } else if (Session::get('user_type') == 2) {
            $data['data']['upcoming'] = $this->EncryptList($this->model->driverUpcomingRides(Session::get('parent_id')), 0, '/admin/ride/');
            $data['data']['live'] = $this->EncryptList($this->model->driverLiveRide(Session::get('parent_id'), 0), 0, '/admin/ride/');
            $data['data']['past'] = $this->EncryptList($this->model->driverPastRides(Session::get('parent_id')), 0, '/admin/ride/');
        } else {
            $data['data']['upcoming'] = [];
            $data['data']['live'] = [];
            $data['data']['past'] = [];
        }
        if ($type == 'request') {
            $data['data']['request'] = $this->EncryptList($this->model->pendingBookingRides(Session::get('project_id')), 0, '/passenger/booking/', 'id');
        }
        return json_encode($data['data']);
    }

    public function EncryptList($array, $single = 0, $link = '/passenger/ride/', $key = 'pid')
    {
        if (!empty($array)) {
            if ($single == 0) {
                foreach ($array as $k => $v) {
                    $array[$k]['link'] = $link . Encryption::encode($v[$key]);
                }
            } else {
                $array['link'] = $link . Encryption::encode($array[$key]);
            }
        }
        return $array;
    }


    public function chats()
    {
        $data['menu'] = 0;
        $data['title'] = 'Chats';
        $chats = $this->model->chatList(Session::get('user_id'));
        $data['chats'] = $this->EncryptList($chats, 0, '/chat/', 'id');
        return view('master.chats', $data);
    }

    public function settings()
    {
        $data['menu'] = 5;
        $data['title'] = 'Settings';
        $data['data'] = $this->model->getTableRow('users', 'id', Session::get('user_id'));
        if ($data['data']->icon == '') {
            $data['data']->icon = '/assets/img/avatars/' . $data['data']->gender . '.png?v=3';
        }
        return view('passenger.settings', $data);
    }

    public function settingsData()
    {
        $data['menu'] = 5;
        $data['title'] = 'Settings';
        $data['data'] = $this->model->getTableRow('users', 'id', Session::get('user_id'));
        if ($data['data']->icon == '') {
            $data['data']->icon = '/assets/img/avatars/' . $data['data']->gender . '.png?v=3';
        }
        return json_encode($data['data']);
    }
    public function profile()
    {
        $data['menu'] = 5;
        $data['title'] = 'Settings';
        $data['data'] = $this->model->getTableRow('users', 'id', Session::get('user_id'));
        if ($data['data']->icon == '') {
            $data['data']->icon = '/assets/img/avatars/' . $data['data']->gender . '.png?v=3';
        }
        return view('passenger.profile', $data);
    }

    public function calendar()
    {
        $data['menu'] = 4;
        $data['title'] = 'Calendar';
        if (Session::get('user_type') == 5) {
            $rides = $this->model->passengerAllRides(Session::get('parent_id'));
            $array = $this->EncryptList($rides);
        } else if (Session::get('user_type') == 4) {
            $rides = $this->model->driverAllRides(Session::get('parent_id'));
            $array = $this->EncryptList($rides, 0, '/driver/ride/');
        } else if (Session::get('user_type') == 3) {
            $rides = $this->model->driverAllRides(Session::get('parent_id'));
            $array = $this->EncryptList($rides, 0, '/admin/ride/');
        } else {
            $rides = [];
            $array = [];
        }

        $all_rides = [];
        foreach ($array as $v) {
            $row['driver_image'] = ($v['photo'] != '') ? $v['photo'] : '/assets/img/driver.png';
            $row['pickup_time'] = $v['pickup_time'];
            $row['link'] = $v['link'];
            $row['type'] = 'Pickup at';
            $row['location'] = $v['pickup_location'] . ' - ' . $v['drop_location'];
            $all_rides[$this->sqlDate($v['date'], 'Y')][ltrim($this->sqlDate($v['date'], 'm'), "0")][ltrim($this->sqlDate($v['date'], 'd'), "0")][] = $row;
        }
        $data['rides'] = $all_rides;
        return view('passenger.calendar', $data);
    }


    public function calendarData()
    {
        $data['menu'] = 4;
        $data['title'] = 'Calendar';
        if (Session::get('user_type') == 5) {
            $rides = $this->model->passengerAllRides(Session::get('parent_id'));
            $array = $this->EncryptList($rides);
        } else if (Session::get('user_type') == 4) {
            $rides = $this->model->driverAllRides(Session::get('parent_id'));
            $array = $this->EncryptList($rides, 0, '/driver/ride/');
        } else if (Session::get('user_type') == 3) {
            $rides = $this->model->driverAllRides(Session::get('parent_id'));
            $array = $this->EncryptList($rides, 0, '/admin/ride/');
        } else {
            $rides = [];
            $array = [];
        }

        $all_rides = [];
        foreach ($array as $v) {
            $row['driver_image'] = ($v['photo'] != '') ? $v['photo'] : '/assets/img/driver.png';
            $row['pickup_time'] = $v['pickup_time'];
            $row['link'] = $v['link'];
            $row['type'] = 'Pickup at';
            $row['location'] = $v['pickup_location'] . ' - ' . $v['drop_location'];
            $all_rides[$this->sqlDate($v['date'], 'Y')][ltrim($this->sqlDate($v['date'], 'm'), "0")][ltrim($this->sqlDate($v['date'], 'd'), "0")][] = $row;
        }
        $data['rides'] = $all_rides;
        return json_encode($all_rides);
    }

    public function profileSave(Request $request)
    {
        $array = $request->all();
        Session::put('name', $request->name);
        unset($array['_token']);
        $data['data'] = $this->model->updateTableData('users', 'id', Session::get('user_id'), $array);
        return redirect('/profile');
    }
    public function updateSetting($col, $val)
    {
        $val = ($val == 'true') ? 1 : 0;
        if ($col == 'dark_mode') {
            if ($val == 1) {
                Session::put('mode', 'dark-mode');
            } else {
                Session::put('mode', '');
            }
        }
        $data['data'] = $this->model->updateTable('users', 'id', Session::get('user_id'), $col, $val);
    }

    public function uploadFile(Request $request, $type)
    {
        $request->validate([
            'image' => 'required'
        ]);

        $croped_image = $request->image;
        list($type, $croped_image) = explode(';', $croped_image);
        list(, $croped_image)      = explode(',', $croped_image);
        $croped_image = base64_decode($croped_image);

        $file_name = time() . rand(1, 999) . '.png';
        Storage::disk('public')->put('uploads/' . $file_name, $croped_image);
        //$file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
        $file_path = 'uploads/' . $file_name;

        $path = '/storage/' . $file_path;
        // if (Session::get('user_type') == 4) {
        //  $img = Image::make('storage/uploads/' . $file_name)->resize(140, 140);
        //  } else {
        //     $img = Image::make('storage/uploads/' . $file_name)->resize(80, 80);
        // }
        //echo '3';
        $compress = 'storage/uploads/' . $file_name;
        //$img->save($compress);
        $this->model->updateTable('users', 'id', Session::get('user_id'), 'image', $path);
        $this->model->updateTable('users', 'id', Session::get('user_id'), 'icon', '/' . $compress);
        if (Session::get('user_type') == 4) {
            $this->model->updateTable('driver', 'id', Session::get('parent_id'), 'photo', '/' . $compress);
        }

        Session::put('icon', '/' . $compress);
        return response()->json(['image' => '/' . $compress]);
    }

    public function bookRide()
    {
        $project_id = Session::get('project_id');
        $list = $this->model->getList('shift', ['project_id' => $project_id, 'is_active' => 1], '*');
        $array = [];
        if ($list != false) {
            foreach ($list as $row) {
                $array[$row->type][$row->shift_time] =  $row->name;
            }
        }
        $data['array'] = $array;
        $data['list'] = $list;
        $data['menu'] = 3;
        $data['title'] = 'Book a Ride';
        $data['date'] = date('Y-m-d');
        $data['data'] = '';
        return view('passenger.book-ride', $data);
    }

    public function bookRideData()
    {
        $project_id = Session::get('project_id');
        $list = $this->model->getList('shift', ['project_id' => $project_id, 'is_active' => 1], '*');
        $array = [];
        if ($list != false) {
            foreach ($list as $row) {
                $array[$row->type][$row->shift_time] =  $row->name;
            }
        }
        $data['array'] = $array;
        $data['list'] = $list;
        $data['menu'] = 3;
        $data['title'] = 'Book a Ride';
        $data['date'] = date('Y-m-d');
        $data['data'] = '';
        return json_encode($data);
    }

    public function passengerSOS(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'ride_passenger_id' => 'required',
            'ride_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors(), 422);
        }
        $array = $request->all();
        unset($array['_token']);
        $array['emergency'] = json_encode($request->emergency);
        $array['created_by'] = $request->ride_passenger_id;
        $array['last_update_by'] = $request->ride_passenger_id;
        $this->model->saveTable('ride_emergency', $array);
        $ApiController = new ApiController();
        $ApiController->sendSMS('9730946150', '{#var#} is in an emergency and needs immediate assistance {#var#} - Siddhivinayak Travels House -Siddhivinayak Travels House', '1107168138576339315');
        return redirect('/thank-you');
    }
    public function passengerHelp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ride_passenger_id' => 'required',
            'ride_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors(), 422);
        }
        $array = $request->all();
        unset($array['_token']);
        $array['created_by'] = $request->ride_passenger_id;
        $array['last_update_by'] = $request->ride_passenger_id;
        $this->model->saveTable('ride_help', $array);
        $ApiController = new ApiController();
        $ApiController->sendSMS('9730946150', 'HELP is OTP to verify your mobile number with Siddhivinayak Travels House', '1107168138576339315');
        return redirect('/thank-you');
    }
    public function rideCancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ride_passenger_id' => 'required',
            'ride_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors(), 422);
        }
        $array = $request->all();
        unset($array['_token']);
        $array['created_by'] = $request->ride_passenger_id;
        $array['last_update_by'] = $request->ride_passenger_id;
        $this->model->saveTable('ride_cancel', $array);
        $this->model->updateTable('ride_passenger', 'id', $request->ride_passenger_id, 'status', 4);
        return redirect('/passenger/ride/' . Encryption::encode($request->ride_passenger_id));
    }

    public function bookingCancel(Request $request)
    {
        if ($request->no_show == 1) {
            $this->model->updateTable('ride_request', 'id', $request->booking_id, 'status', 2);
            $array['status'] = 0;
        } else {
            $this->model->updateTable('ride_request', 'id', $request->booking_id, 'status', 3);
            $this->model->updateTable('roster', 'booking_id', $request->booking_id, 'is_active', 0);
            $array['status'] = 1;
        }
        $ride = $this->model->getTableRow('roster', 'booking_id', $request->booking_id);
        $array['ride_passenger_id'] = $ride->passenger_id;
        $array['roster_id'] = $ride->id;
        $array['no_show'] = $request->no_show;
        $array['message'] = '';
        $array['created_by'] = $ride->passenger_id;
        $array['last_update_by'] = $ride->passenger_id;
        $this->model->saveTable('ride_cancel', $array);
        return redirect('/my-rides/booking');
    }

    public function bookingApprove(Request $request)
    {
        $ride = $this->model->getTableRow('ride_request', 'id', $request->booking_id);

        if ($request->approve_type == 'Approve') {
            if ($ride->status == 2) {
                $this->model->updateTable('ride_request', 'id', $request->booking_id, 'status', 3);
                $this->model->updateTable('roster', 'booking_id', $request->booking_id, 'is_active', 0);
                $description = 'Your ad hoc cancel request has been approved by Admin';
            } else {
                $this->model->updateTable('ride_request', 'id', $request->booking_id, 'status', 1);
                $this->model->updateTable('roster', 'booking_id', $request->booking_id, 'is_active', 1);
                $description = 'Your ad hoc booking request has been approved by Admin';
            }
            $message = 'Ad hoc Request Approved';
        } else {
            if ($ride->status == 2) {
                $this->model->updateTable('ride_request', 'id', $request->booking_id, 'status', 1);
                $this->model->updateTable('roster', 'booking_id', $request->booking_id, 'is_active', 1);
                $description = 'Your ad hoc cancel request has been rejected by Admin';
            } else {
                $this->model->updateTable('ride_request', 'id', $request->booking_id, 'status', 4);
                $this->model->updateTable('roster', 'booking_id', $request->booking_id, 'is_active', 0);
                $description = 'Your ad hoc booking request has been rejected by Admin';
            }
            $message = 'Ad hoc Request Rejected';
        }
        $this->model->updateTable('ride_request', 'id', $request->booking_id, 'last_update_by', Session::get('user_id'));
        $apiController = new ApiController();
        $url = 'https://app.svktrv.in/my-rides/booking';
        $apiController->sendNotification($ride->passenger_id, 5, $message, $description, $url);
        return redirect('/my-rides/request');
    }

    public function saveRide(Request $request)
    {
        $request->date = $this->sqlDate($request->date);
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after:yesterday',
            'time' => 'required'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors(), 422);
        }
        $array = $request->all();
        unset($array['_token']);
        $array['time'] = $request->date . ' ' . $this->sqlTime($request->time);
        $array['project_id'] = Session::get('project_id');
        $array['passenger_id'] = Session::get('parent_id');
        $array['created_by'] = Session::get('user_id');
        $array['created_date'] = date('Y-m-d H:i:s');
        $array['last_update_by'] = Session::get('user_id');
        $array['booking_id'] = $this->model->saveTable('ride_request', $array);
        $array['start_time'] = $array['time'];
        unset($array['time']);
        $array['shift'] = $this->model->getColumnValue('shift', 'shift_time', $this->sqlTime($request->time), 'name', ['project_id' => $array['project_id'], 'type' => $array['type']]);
        if ($array['status'] == 0) {
            $array['is_active'] = 0;

            $admin_list = $this->model->getList('users', ['user_type' => 2, 'is_active' => 1, 'project_id' => $array['project_id']], 'parent_id');
            $apiController = new ApiController();
            $url = 'https://app.svktrv.in/my-rides/request';
            $passenger_name = $this->model->getColumnValue('passenger', 'id', $array['passenger_id'], 'employee_name');
            foreach ($admin_list as $row) {
                $apiController->sendNotification($row->parent_id, 2, 'Ad hoc booking request for approval', $passenger_name . ' requested Ad hoc booking for ' . $array['shift'], $url);
            }
        }
        unset($array['status']);
        $this->model->saveTable('roster', $array);
        return redirect('/my-rides/booking');
    }

    public function home($token)
    {
        return view('home');
    }
    public function trips($token)
    {
        return view('trips');
    }
    public function notification($token)
    {
        return view('notification');
    }

    public function blog($id, $title)
    {
        $data['menu'] = 0;
        $data['id'] = $id;
        $data['blogs'] = $this->model->getTableList('blogs', 'is_active', 1)->toJson();
        return view('blog.index', $data);
    }
    public function blogs()
    {
        $data['menu'] = 0;
        $data['blogs'] = $this->model->getTableList('blogs', 'is_active', 1)->toJson();
        return view('blog.list', $data);
    }



    public function contactus(Request $request)
    {
        $model =  new ParentModel();
        $array = $request->all();
        unset($array['_token']);
        $model->saveTable('contactus', $array);
        $ApiController = new ApiController();
        $ApiController->sendSMS('9730946150', 'CONTACTUS is OTP to verify your mobile number with Siddhivinayak Travels House', '1107168138576339315');
        return redirect('/thank-you');
    }

    public function driverRideStatus($ride_id, $status)
    {
        $model =  new ParentModel();
        $model->updateTable('ride', 'id', $ride_id, 'status', $status);
        if ($status == 5) {
            $model->updateTable('ride', 'id', $ride_id, 'ride_ended', date('Y-m-d H:i:s'));
            return redirect('/my-rides/past');
        }

        $model->updateTable('ride', 'id', $ride_id, 'ride_started', date('Y-m-d H:i:s'));
        $apiController = new ApiController();
        $link = Encryption::encode($ride_id);
        $passengers = $this->model->getTableList('ride_passenger', 'ride_id', $ride_id);
        foreach ($passengers as $row) {
            if ($row->status == 0) {
                $link = Encryption::encode($row->id);
                $url = 'https://app.svktrv.in/passenger/ride/' . $link;
                $apiController->sendNotification($row->passenger_id, 5, 'Your ride has been started', 'Our driver is en route to your pickup location. Enjoy your journey with us.', $url);
            }
        }




        return redirect('/driver/ride/' . Encryption::encode($ride_id));
    }

    public function driverPassengerRideStatus($ride_passenger_id, $status)
    {
        $apiController = new ApiController();
        $row = $this->model->getTableRow('ride_passenger', 'id', $ride_passenger_id);
        $model =  new ParentModel();
        if ($status == 5 || $status == 1) {
            if ($status == 5) {
                $model->updateTable('ride_passenger', 'id', $ride_passenger_id, 'cab_time', date('Y-m-d H:i:s'));
                $link = Encryption::encode($ride_passenger_id);
                $url = 'https://app.svktrv.in/passenger/ride/' . $link;
                $apiController->sendNotification($row->passenger_id, 5, 'Cab Arrived', 'Your cab has arrived at your pickup location. We hope you have a pleasant ride', $url);
            } else {
                $model->updateTable('ride_passenger', 'id', $ride_passenger_id, 'in_time', date('Y-m-d H:i:s'));
                if ($row->cab_time == '') {
                    $model->updateTable('ride_passenger', 'id', $ride_passenger_id, 'cab_time', date('Y-m-d H:i:s'));
                }
            }
        }
        if ($status == 2) {
            $model->updateTable('ride_passenger', 'id', $ride_passenger_id, 'drop_time', date('Y-m-d H:i:s'));
            $url = 'https://app.svktrv.in/dashboard';
            $apiController->sendNotification($row->passenger_id, 5, 'Your ride has been completed', 'We hope you had a pleasant journey with us. Please rate your ride experience', $url);
        }

        $model->updateTable('ride_passenger', 'id', $ride_passenger_id, 'status', $status);
    }

    public function resendOTP($ride_passenger_id)
    {
        $apiController = new ApiController();
        $row = $this->model->getTableRow('ride_passenger', 'id', $ride_passenger_id);
        $mobile = $this->model->getColumnValue('passenger', 'id', $row->passenger_id, 'mobile');
        $message = $row->otp . ' is OTP to verify your mobile number with Siddhivinayak Travels House';
        $apiController->sendSMS($mobile, $message, '1107168138576339315');
        $link = Encryption::encode($ride_passenger_id);
        $url = 'https://app.svktrv.in/passenger/ride/' . $link;
        $apiController->sendNotification($row->passenger_id, 5, $row->otp . ' is OTP to verify your mobile number', $url);
        $tokens[] = env('MY_TOKEN');
        $apiController->sendNotification(1, 3, 'Resend otp ', $ride_passenger_id . ' to ' . $mobile,  '', '', $tokens);
    }

    public function passengerAdd($ride_id, $passenger_id, $time)
    {
        $ride = $this->model->getRowArray('ride', 'id', $ride_id);
        $exist = $this->model->isExistData('ride_passenger', 'passenger_id', $passenger_id);
        if ($exist == false) {
            $passenger = $this->model->getRowArray('passenger', 'id', $passenger_id);
            $project = $this->model->getRowArray('project', 'project_id', $ride['project_id']);
            $array['ride_id'] = $ride_id;
            $array['passenger_id'] = $passenger_id;
            $array['otp'] = rand(1111, 9999);
            $array['pickup_time'] = $ride['date'] . ' ' . $this->sqlTime($time);
            if ($ride['type'] == 'Drop') {
                $array['pickup_location'] = $project['location'];
                $array['drop_location'] = $passenger['location'];
            } else {
                $array['pickup_location'] = $passenger['location'];
                $array['drop_location'] = $project['location'];
            }
            $this->model->saveTable('ride_passenger', $array, Session::get('user_id'));
        }
        $ride_passengers = $this->model->getRidePassenger($ride_id);
        return json_encode($ride_passengers);
    }

    public function passengerRemove($id)
    {
        $detail = $this->model->getRowArray('ride_passenger', 'id', $id);
        $this->model->updateTable('ride_passenger', 'id', $id, 'is_active', 0);
        // $ride_passengers = $this->model->getRidePassenger($detail['ride_id']);
        // return json_encode($ride_passengers);
    }
}
