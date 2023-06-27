<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\RideModel;
use App\Http\Controllers\ApiController;
use App\Http\Lib\Encryption;

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
        $this->model = new RideModel();
        $this->user_id = Session::get('user_id');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function details($id)
    {
       // $id = Encryption::decode($link);
        //$id = 508;
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
        if ($data['driver']->photo == '') {
            $data['driver']->photo = env('MOBILE_APP_URL') . '/assets/img/driver.png';
        } else {
            $data['driver']->photo = env('MOBILE_APP_URL') . $data['driver']->photo;
        }

        foreach ($data['ride_passengers'] as $k => $row) {
            if ($row->icon == '') {
                if ($row->gender == 'Female') {
                    $data['ride_passengers'][$k]->icon = env('MOBILE_APP_URL') . '/assets/img/map-male.png';
                } else {
                    $data['ride_passengers'][$k]->icon = env('MOBILE_APP_URL') . '/assets/img/map-female.png';
                }
            } else {
                $data['ride_passengers'][$k]->icon = env('MOBILE_APP_URL') . $row->icon;
            }
        }
        //  dd($data);
        return view('web.ride.details', $data);
    }
}
