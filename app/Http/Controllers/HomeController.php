<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;

class HomeController extends Controller
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
    public function index()
    {
        $data['selectedMenu'] = [1];
        $data['menus'] = Session::get('menus');
        $employee = $this->model->getEmployeeCount(Session::get('project_access'));

        $data['male'] = (isset($employee['Male'])) ? $employee['Male'] : 0;
        $data['female'] = (isset($employee['Female'])) ? $employee['Female'] : 0;
        $data['total_employee'] = $data['male'] + $data['female'];
        $data['malePercent'] = round($data['male'] * 100 / $data['total_employee']);
        $data['femalePercent'] = round($data['female'] * 100 / $data['total_employee']);

        $from_date = date('Y-06-01');
        $to_date = date('Y-06-30');

        $rides = $this->model->getRideCount(Session::get('project_access'), $from_date, $to_date);
        $rideStatus = $this->model->getRideCountDetail(Session::get('project_access'), $from_date, $to_date);

        $data['all'] = 0;
        $data['cancelled'] = 0;
        $data['noshow'] = 0;
        $data['completed'] = 0;
        $data['cancelledPercent'] = 0;
        $data['noshowPercent'] = 0;
        $data['completedPercent'] = 0;
        if (!empty($rideStatus)) {
            foreach ($rideStatus as $k => $v) {
                $data['all'] = $data['all'] + $v;
                if ($k == 3) {
                    $data['cancelled'] = $v;
                } else if ($k == 4) {
                    $data['noshow'] = $v;
                } else {
                    $data['completed'] = $data['completed'] + $v;
                }
            }
            if ($data['cancelled'] > 0) {
                $data['cancelledPercent'] = round($data['cancelled'] * 100 / $data['all']);
            }
            if ($data['noshow'] > 0) {
                $data['noshowPercent'] = round($data['noshow'] * 100 / $data['all']);
            }
            if ($data['completed'] > 0) {
                $data['completedPercent'] = round($data['completed'] * 100 / $data['all']);
            }
        }


        $data['days'] = [];
        $data['dayValues'] = [];
        if (!empty($rides)) {
            foreach ($rides as $k => $v) {
                $data['days'][] = (int) $k;
                $data['dayValues'][] = $v;
            }
        }

        $data['days'] = json_encode($data['days']);
        $data['dayValues'] = json_encode($data['dayValues']);
        return view('web.dashboard', $data);
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
}
