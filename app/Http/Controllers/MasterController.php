<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;
use App\Http\Lib\Encryption;

class MasterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $model = null;

    public function __construct()
    {
        $this->model = new MasterModel();
    }

    public function projectList()
    {
        $data['selectedMenu'] = [6];
        $data['menus'] = Session::get('menus');
        $data['list'] = $this->model->getProject(Session::get('project_access'));
        return view('web.master.project', $data);
    }

    public function create($type)
    {
        $data['selectedMenu'] = [11, 12];
        switch ($type) {
            case 'shift':
                $data['selectedMenu'] = [22, 23];
                $data['project_list'] = $this->model->getProject(Session::get('project_access'));
                break;
            case 'slab':
                $data['selectedMenu'] = [26, 27];
                $data['project_list'] = $this->model->getProject(Session::get('project_access'));
                $data['slab_array'] = array('Slab 1', 'Slab 2', 'Slab 3', 'Slab 4', 'Slab 5', 'Slab 6');
                $data['car_type_array'] = array('Sedan', 'Hatchback', 'Suv');
                break;
        }
        $data['menus'] = Session::get('menus');

        return view('web.' . $type . '.create', $data);
    }

    public function update($type, $id)
    {
        $data['selectedMenu'] = [11, 13];
        switch ($type) {
            case 'driver':
                $data['det'] = $this->model->getTableRow('driver', 'id', $id);
                break;
            case 'slab':
                $data['selectedMenu'] = [26, 28];
                $data['det'] = $this->model->getTableRow('zone', 'zone_id', $id);
                $data['det']->id = $data['det']->zone_id;
                $data['project_list'] = $this->model->getProject(Session::get('project_access'));
                $data['slab_array'] = array('Slab 1', 'Slab 2', 'Slab 3', 'Slab 4', 'Slab 5', 'Slab 6');
                $data['car_type_array'] = array('Sedan', 'Hatchback', 'Suv');
                break;
        }
        $data['menus'] = Session::get('menus');

        return view('web.' . $type . '.create', $data);
    }

    public function save(Request $request, $type)
    {
        $user_id = Session::get('user_id');
        foreach ($request->drivers as $row) {
            if ($type == 'shift') {
                $row['project_id'] = $request->project_id;
                $this->model->saveTable('shift', $row, $user_id);
            } else if ($type == 'slab') {
                $row['admin_id'] = 1;
                $row['project_id'] = $request->project_id;
                $row['company_id'] = $this->model->getColumnValue('project', 'project_id', $request->project_id, 'company_id');
                if ($request->id > 0) {
                    $this->model->updateArray('zone', 'zone_id', $request->id, $row);
                } else {
                    $this->model->saveTable('zone', $row, $user_id);
                }
            } else {
                if ($request->id > 0) {
                    $this->model->updateArray('driver', 'id', $request->id, $row);
                } else {
                    $this->model->saveTable('driver', $row, $user_id);
                }
            }
        }
        return redirect()->back()->withSuccess(ucfirst($type) . ' saved successfully');
    }

    public function list($type)
    {
        $data['selectedMenu'] = [11, 13];
        switch ($type) {
            case 'shift':
                $data['selectedMenu'] = [22, 24];
                break;
            case 'slab':
                $data['project_list'] = $this->model->getProject(Session::get('project_access'));
                $data['selectedMenu'] = [26, 28];
                break;
            case 'invoice':
                $data['selectedMenu'] = [25];
                break;
        }

        $data['menus'] = Session::get('menus');
        $data['enc'] = Encryption::encode(date('Y-m-d'));

        return view('web.' . $type . '.list', $data);
    }

    public function delete($type, $id, $id_col = 'id')
    {
        $this->model->updateTable($type, $id_col, $id, 'is_active', 0);
        return redirect()->back()->withSuccess(ucfirst($type) . ' deleted successfully');
    }

    public function Ajax($type, $project_id = 0)
    {
        if ($type == 'invoice') {
            $data['data'] = $this->model->getInvoiceList(Session::get('project_access'));
        } else if ($type == 'slab') {
            if ($project_id > 0) {
                $project_array[] = $project_id;
            } else {
                $project_array = Session::get('project_access');
            }
            $data['data'] = $this->model->getTableList('zone', 'is_active', 1, 0, $project_array);
        } else {
            $data['data'] = $this->model->getTableList($type, 'is_active', 1);
        }
        return json_encode($data);
    }


    public function ajaxShift($project_id = 0, $type)
    {
        if ($project_id > 0) {
            $data['data'] = $this->model->getTableList('shift', 'project_id', $project_id, 0, [], ['type' => $type]);
            $array = [];
            if (!empty($data['data'])) {
                foreach ($data['data'] as $row) {
                    $array[$row->id] = $row;
                }
            }
            return json_encode($array, 1);
        }
    }
}
