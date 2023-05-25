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

        $data['menu'] = 1;
        $data['title'] = 'dashboard';
        $data['data']['total_ride'] = $this->model->getTableCount('ride_passenger', 'passenger_id', Session::get('parent_id'), 1);
        $data['data']['completed_ride'] = $this->model->getTableCount('ride_passenger', 'passenger_id', Session::get('parent_id'), 1, ['status' => 2]);
        $data['live_ride'] = $this->EncryptList($this->model->passengerLiveRide(Session::get('parent_id')), 1);
        $data['data']['blogs'] = $this->model->getTableList('blogs', 'is_active', 1)->toArray();
        $data['data']['upcoming'] = $this->model->passengerUpcomingRides(Session::get('parent_id'), 1);
        if (!empty($data['data']['upcoming'])) {
            $data['data']['upcoming']['link'] = '/passenger/ride/' . Encryption::encode($data['data']['upcoming']['pid']);
        }
        return view('passenger.dashboard', $data);
    }

    public function passengerRideDetail($link)
    {
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
        $ride['start_time'] = $this->htmlDateTime($ride['start_time']);
        $ride['end_time'] = $this->htmlDateTime($ride['end_time']);
        $data['data']['ride_passenger'] = $ride_passenger;
        $data['data']['ride'] = $ride;
        $data['data']['project'] = $project;
        $data['data']['driver'] = $driver;
        $data['data']['vehicle'] = $vehicle;
        $data['data']['ride_passengers'] = $ride_passengers;
        $data['data']['link'] = env('APP_URL') . '/passenger/ride/' . $link;
        $data['menu'] = 0;
        $data['title'] = 'Ride detail';
        return view('passenger.ride-detail', $data);
    }
    public function rideTrack($link)
    {
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
        $data['live_location'] = $this->model->getColumnValue('ride_live_location', 'ride_id', $ride_passenger['ride_id'], 'live_location');
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


    public function rides($type = 'upcoming')
    {
        $data['menu'] = 2;
        $data['title'] = 'My Rides';
        $data['type'] = $type;
        $data['data']['upcoming'] = $this->EncryptList($this->model->passengerUpcomingRides(Session::get('parent_id')));
        $data['data']['live'] = $this->EncryptList($this->model->passengerLiveRide(Session::get('parent_id')), 1);
        $data['data']['past'] = $this->EncryptList($this->model->passengerPastRides(Session::get('parent_id')));
        $data['data']['booking'] = $this->EncryptList($this->model->passengerBookingRides(Session::get('parent_id')), 0, '/passenger/booking/', 'id');
        return view('passenger.my-rides', $data);
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


    public function notifications()
    {
        $data['menu'] = 4;
        $data['title'] = 'Notifications';
        $notifications = $this->model->getTableListOrderby('notification', 'user_id', Session::get('user_id'), 'DESC');
        $data['data']['notification'] = json_decode(json_encode($notifications, true), 1);
        return view('passenger.notifications', $data);
    }

    public function settings()
    {
        $data['menu'] = 5;
        $data['title'] = 'Settings';
        $data['data'] = $this->model->getTableRow('users', 'id', Session::get('user_id'));
        return view('passenger.settings', $data);
    }
    public function profile()
    {
        $data['menu'] = 5;
        $data['title'] = 'Settings';
        $data['data'] = $this->model->getTableRow('users', 'id', Session::get('user_id'));
        return view('passenger.profile', $data);
    }

    public function calendar()
    {
        $data['menu'] = 4;
        $data['title'] = 'Calendar';
        $rides = $this->model->passengerAllRides(Session::get('parent_id'));
        $array = $this->EncryptList($rides);
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
            'file' => 'required|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->file()) {
            $file_name = time() . rand(1, 999) . '.' . $request->file->extension();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
            $path = '/storage/' . $file_path;
            $img = Image::make('storage/uploads/' . $file_name)->resize(80, 80);
            $compress = 'storage/uploads/compres-' . $file_name;
            $img->save($compress);
            $this->model->updateTable('users', 'id', Session::get('user_id'), 'image', $path);
            $this->model->updateTable('users', 'id', Session::get('user_id'), 'icon', '/' . $compress);
            Session::put('icon', '/' . $compress);
            return response()->json(['image' => '/' . $compress]);
        }
    }

    public function bookRide()
    {
        $data['menu'] = 3;
        $data['title'] = 'Book a Ride';
        $data['date'] = date('Y-m-d');
        $data['data'] = '';
        return view('passenger.book-ride', $data);
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
        $this->model->updateTable('ride_passenger', 'id', $request->ride_passenger_id, 'status', 3);
        return redirect('/passenger/ride/' . Encryption::encode($request->ride_passenger_id));
    }

    public function bookingCancel(Request $request)
    {
        $this->model->updateTable('ride_request', 'id', $request->booking_id, 'is_active', 0);
        return redirect('/my-rides/booking');
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
        $this->model->saveTable('ride_request', $array);
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
        return redirect('/thank-you');
    }
}
