<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\RosterModel;
use App\Http\Controllers\ApiController;
use App\Http\Lib\Encryption;

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
        $this->model = new RosterModel();
        $this->user_id = Session::get('user_id');
    }


    public function list(Request $request, $bulk_id = 0, $type = 0)
    {
        $data['selectedMenu'] = [7, 19];
        $data['menus'] = Session::get('menus');
        $data['bulk_id'] = $bulk_id;
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        return view('web.roster.list', $data);
    }

    public function route(Request $request, $bulk_id = 0)
    {
        $data['selectedMenu'] = [7, 21];
        $data['menus'] = Session::get('menus');
        $data['bulk_id'] = $bulk_id;
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        $data['project_id'] = (count($data['project_list']) == 1) ? $data['project_list'][0]->project_id : 0;
        return view('web.roster.route', $data);
    }

    public function routeSave(Request $request)
    {
        $dataarray = json_decode($request->data, 1);
        $times = json_decode($request->times, 1);
        $user_id = Session::get('user_id');
        $project_location = $this->model->getColumnValue('project', 'project_id', $request->project_id, 'location');
        $ptimes = [];
        foreach ($times as $t) {
            $ptimes[$t['key']] = $t['value'];
        }
        foreach ($dataarray as $row) {
            if ($row['is_active'] == 1) {
                $array = [];
                $array['project_id'] = $request->project_id;
                $array['date'] = $this->sqlDate($row['date']);
                $array['type'] = $row['type'];
                $array['slab_id'] = $row['slab'];
                $array['title'] = $row['type'] . ' ' . $row['slab_text'];
                $array['start_location'] = ($row['type'] == 'Pickup') ? $row['slab_text'] : $project_location;
                $array['end_location'] = ($row['type'] == 'Drop') ? $row['slab_text'] : $project_location;
                $array['escort'] = ($row['escort'] == 1) ? 1 : 0;
                $array['total_passengers'] = count($row['ids']);
                if ($row['type'] == 'Pickup') {
                    $array['start_time'] = $array['date'] . ' ' . $this->sqlTime($ptimes[$row['ids'][0]]);
                    $array['end_time'] = $array['date'] . ' ' . $this->sqlTime($row['time']);
                } else {
                    $array['start_time'] = $array['date'] . ' ' . $this->sqlTime($row['time']);
                    $array['end_time'] =  date('Y-m-d H:i:s', strtotime($array['start_time'] . ' + 3 hours'));
                }
                $ride_id = $this->model->saveTable('ride', $array, $user_id);

                if ($array['escort'] > 0) {
                    $ride_passenger['roster_id'] = 0;
                    $ride_passenger['ride_id'] = $ride_id;
                    $ride_passenger['passenger_id'] = 0;
                    if ($array['type'] == 'Pickup') {
                        $ride_passenger['pickup_location'] = $array['start_location'];
                        $ride_passenger['drop_location'] = $array['end_location'];
                        $ride_passenger['pickup_time'] = $array['start_time'];
                    } else {
                        $ride_passenger['pickup_location'] = $array['start_location'];
                        $ride_passenger['drop_location'] = $array['end_location'];
                        $ride_passenger['pickup_time'] = $array['start_time'];
                    }
                    $ride_passenger['otp'] = rand(1111, 9999);
                    $this->model->saveTable('ride_passenger', $ride_passenger, $user_id);
                }

                foreach ($row['ids'] as $roster_id) {
                    $ride_passenger['roster_id'] = $roster_id;
                    $ride_passenger['ride_id'] = $ride_id;
                    $passenger_id = $this->model->getColumnValue('roster', 'id', $roster_id, 'passenger_id');
                    $booking_id = $this->model->getColumnValue('roster', 'id', $roster_id, 'booking_id');
                    $ride_passenger['passenger_id'] = $passenger_id;
                    $emp_location = $this->model->getColumnValue('passenger', 'id', $ride_passenger['passenger_id'], 'location');

                    if ($array['type'] == 'Pickup') {
                        $ride_passenger['pickup_location'] = $emp_location;
                        $ride_passenger['drop_location'] = $array['end_location'];
                        $ride_passenger['pickup_time'] = $array['date'] . ' ' . $this->sqlTime($ptimes[$roster_id]);
                    } else {
                        $ride_passenger['pickup_location'] = $array['start_location'];
                        $ride_passenger['drop_location'] = $emp_location;
                        $ride_passenger['pickup_time'] = $array['start_time'];
                    }

                    $ride_passenger['otp'] = rand(1111, 9999);
                    $this->model->saveTable('ride_passenger', $ride_passenger, $user_id);
                    $this->model->updateTable('roster', 'id', $roster_id, 'status', 1);
                    $this->model->updateTable('ride_request', 'id', $booking_id, 'status', 2);
                }
            }
        }
    }

    public function save(Request $request)
    {
        $emp['project_id'] = $request->emp_project_id;
        $emp['employee_name'] = $request->emp_name;
        $emp['mobile'] = $request->emp_mobile;
        $emp['email'] = '';
        $emp['gender'] = $request->emp_gender;
        $emp['address'] = $request->emp_address;
        $emp['location'] = $request->emp_location;

        $emp['employee_name'] = str_replace(array("\r", "\n", "'"), "", $emp['employee_name']);
        $emp['address'] = str_replace(array("\r", "\n", "'"), "", $emp['address']);
        $emp['location'] = str_replace(array("\r", "\n", "'"), "", $emp['location']);



        if ($request->emp_id > 0) {
            $this->model->updateArray('passenger', 'id', $request->emp_id, $emp);
            $passenger_id = $request->emp_id;
        } else {
            $passenger_id = $this->model->getColumnValue('passenger', 'mobile', $emp['mobile'], 'id');
            if ($passenger_id != false) {
                $this->model->updateArray('passenger', 'id', $passenger_id, $emp);
            } else {
                $passenger_id = $this->model->saveTable('passenger', $emp, Session::get('user_id'));
            }
        }
        $array['project_id'] = $emp['project_id'];
        $array['passenger_id'] = $passenger_id;
        $array['type'] = $request->roster_type;
        $array['date'] = $request->roster_date;
        $array['shift'] = $request->roster_shift;
        $array['start_time'] = $this->sqlDate($array['date']) . ' ' . $this->sqlTime($request->roster_time);
        $array['end_time'] = $this->sqlDate($array['date']) . ' ' . $this->sqlTime($request->roster_in_time);
        $array['date'] = $this->sqlDate($array['date']);
        $this->model->saveTable('roster', $array, Session::get('user_id'));
    }

    public function ajaxRoster($date = 'na', $project_id = 0,  $bulk_id = 0, $shift = null)
    {
        $table = 'roster';
        $from_date = null;
        $to_date = null;
        $date_array = explode(' - ', $date);
        if (!empty($date_array) && $bulk_id == 0) {
            $from_date = $this->sqlDateTime($date_array[0]);
            $to_date = $this->sqlDateTime($date_array[1]);
        }
        if ($bulk_id > 0) {
            $status = $this->model->getColumnValue('import', 'id', $bulk_id, 'status');
            if ($status == 3) {
                $table = 'staging_roster';
            }
        }
        $data['data'] = $this->model->getRoster($table, $project_id, $bulk_id, $from_date, $to_date, Session::get('project_access'), $shift);
        return json_encode($data);
    }

    public function ajaxRosterRoute($date = 'na', $project_id = 0, $type = '',  $shift = '')
    {
        $table = 'roster';
        $from_date = null;
        $to_date = null;
        $date_array = explode(' - ', $date);
        if (!empty($date_array)) {
            $from_date = $this->sqlDateTime($date_array[0]);
            $to_date = $this->sqlDateTime($date_array[1]);
        }
        $data['rosterdata'] = $this->model->getRoster($table, $project_id, 0, $from_date, $to_date, Session::get('project_access'), $type, $shift, 0);
        $data['passenger_list'] = $this->model->getTableList('passenger', 'project_id', $project_id, [], [], [], 'id,passenger_type,employee_name,gender,location');
        $data['zone_list'] = $this->model->getTableList('zone', 'project_id', $project_id, [], [], [], 'zone_id,zone,car_type');
        $data['shift'] = [];
        $data['roster'] = [];
        if (!empty($data['rosterdata'])) {
            foreach ($data['rosterdata'] as $k => $row) {
                if ($row->status == 0) {
                    $shift = ($row->shift == '') ? 'NA' : $row->shift;
                    if (!in_array($shift, $data['shift'])) {
                        $data['shift'][] = $shift;
                    }

                    if ($row->photo == '') {
                        if ($row->gender == 'Female') {
                            $data['rosterdata'][$k]->photo = env('MOBILE_APP_URL') . '/assets/img/map-female.png';
                        } else {
                            $data['rosterdata'][$k]->photo = env('MOBILE_APP_URL') . '/assets/img/map-male.png';
                        }
                    } else {
                        $data['rosterdata'][$k]->photo = env('MOBILE_APP_URL') . $row->photo;
                    }
                    $data['rosterdata'][$k]->title = $row->location . ' - ' . $row->employee_name . ' - ' . $row->type . ' - ' . $row->date . ' - ' . $row->display_start_time;
                    $data['roster'][$row->id] = $data['rosterdata'][$k];
                }
            }
        }
        return json_encode($data);
    }



    public function delete($id, $bulk_id = 0)
    {
        $table = 'roster';
        if ($bulk_id > 0) {
            $status = $this->model->getColumnValue('import', 'id', $bulk_id, 'status');
            if ($status == 3) {
                $table = 'staging_roster';
            }
        }
        $this->model->updateTable($table, 'id', $id, 'is_active', 0);
    }
}
