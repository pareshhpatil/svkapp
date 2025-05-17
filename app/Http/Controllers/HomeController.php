<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;
use App\Services\DynamoDBService;
use Carbon\Carbon;

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

    public function show()
    {
        $dynamo = new DynamoDBService();
        $item = $dynamo->getAllRows('data', 1123);
        return response()->json($item);
    }
    public function index($date_change = null)
    {
        if ($date_change != null) {
            $from_date = Carbon::parse(Session::get('from_date'));
            $to_date = Carbon::parse(Session::get('to_date'));
            if ($date_change == 'next') {
                $from_change = $from_date->copy()->addMonth();
                $to_change = $to_date->copy()->addMonth();
            } elseif ($date_change == 'prev') {
                $from_change = $from_date->copy()->subMonth();
                $to_change = $to_date->copy()->subMonth();
            }

            Session::put('from_date', $from_change->toDateString());
            Session::put('to_date', $to_change->toDateString());
            return redirect()->route('home');
        }
        $data['selectedMenu'] = [1];
        $data['menus'] = Session::get('menus');
        $employee = $this->model->getEmployeeCount(Session::get('project_access'));

        $data['male'] = (isset($employee['Male'])) ? $employee['Male'] : 0;
        $data['female'] = (isset($employee['Female'])) ? $employee['Female'] : 0;
        $data['total_employee'] = $data['male'] + $data['female'];
        if ($data['total_employee'] > 0) {
            $data['malePercent'] = round($data['male'] * 100 / $data['total_employee']);
            $data['femalePercent'] = round($data['female'] * 100 / $data['total_employee']);
        } else {
            $data['malePercent'] = 0;
            $data['femalePercent'] = 0;
        }
        if (Session::has('from_date')) {
            $from_date = Session::has('from_date') ? Session::get('from_date') : date('Y-m-01');
            $to_date = Session::has('to_date') ? Session::get('to_date') : Carbon::now()->endOfMonth()->toDateString();
        } else {
            $from_date = date('Y-m-01');
            $to_date = Carbon::now()->endOfMonth()->toDateString();
            Session::put('from_date', $from_date);
            Session::put('to_date', $to_date);
        }
        $selectedDate = Carbon::parse($from_date);
        $monthYear = $selectedDate->format('F Y');

        $rides = $this->model->getRideCount(Session::get('project_access'), $from_date, $to_date);
        $rideStatus = $this->model->getRideCountDetail(Session::get('project_access'), $from_date, $to_date);
        $slabs = $this->model->getSlabCountDetail(Session::get('project_access'), $from_date, $to_date);
        $data['month'] = $monthYear;
        $data['all'] = 0;
        $data['cancelled'] = 0;
        $data['noshow'] = 0;
        $data['completed'] = 0;
        $data['cancelledPercent'] = 0;
        $data['noshowPercent'] = 0;
        $data['completedPercent'] = 0;
        $data['slabs'] = $slabs;
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

        $data['slabs'] = $slabs['labels'];
        $data['slabsValues'] = $slabs['values'];
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
