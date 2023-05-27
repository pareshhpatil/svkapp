<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;
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

    public function ajaxRoster($project_id = 0,  $date = 'na')
    {
        if (strlen($date) < 5) {
            $date = 'na';
        }
        if ($date != 'na') {
            $date = $this->sqlDate($date);
        }
        $data['data'] = $this->model->getRoster($project_id, $date);
        return json_encode($data);
    }
}
