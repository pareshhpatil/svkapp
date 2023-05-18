<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ParentModel;
use App\Models\RideModel;
use Validator;
use Intervention\Image\ImageManager;
use Image;

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
        $data['data']['total_ride'] = '20';
        $data['data']['total_review'] = '10';
        $data['data']['blogs'] = $this->model->getTableList('blogs', 'is_active', 1)->toArray();
        $data['data']['upcoming'] = $this->model->passengerUpcomingRides(Session::get('parent_id'), 1);
        return view('passenger.dashboard', $data);
    }

    public function rides($type = 'upcoming')
    {
        $data['menu'] = 2;
        $data['title'] = 'My rides';
        $data['type'] = $type;
        $data['data']['upcoming'] = $this->model->passengerUpcomingRides(Session::get('parent_id'));
        $data['data']['live'] = $this->model->passengerLiveRide(Session::get('parent_id'));
        $data['data']['past'] = $this->model->passengerPastRides(Session::get('parent_id'));
        $data['data']['booking'] = $this->model->passengerBookingRides(Session::get('parent_id'));
        return view('passenger.my-rides', $data);
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
    public function profileSave(Request $request)
    {
        $array = $request->all();
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

            return response()->json(['image' => '/' . $compress]);
        }
    }

    public function bookRide()
    {
        $data['menu'] = 3;
        $data['title'] = 'Book a ride';
        $data['data'] = '';
        return view('passenger.book-ride', $data);
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
        $data['menu'] = 1;
        $data['id'] = $id;
        $data['blogs'] = $this->model->getTableList('blogs', 'is_active', 1)->toJson();
        return view('blog.index', $data);
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
