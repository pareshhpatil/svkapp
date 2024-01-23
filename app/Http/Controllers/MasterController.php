<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;

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
            } else {
                $this->model->saveTable('driver', $row, $user_id);
            }
        }
        return redirect()->back()->withSuccess(ucfirst($type) . ' added successfully');
    }

    public function list($type)
    {
        $data['selectedMenu'] = [11, 13];
        switch ($type) {
            case 'shift':
                $data['selectedMenu'] = [22, 24];
                break;
            case 'invoice':
                $data['selectedMenu'] = [25];
                break;
        }

        $data['menus'] = Session::get('menus');

        return view('web.' . $type . '.list', $data);
    }

    public function delete($type, $id)
    {
        $this->model->updateTable($type, 'id', $id, 'is_active', 0);
        return redirect()->back()->withSuccess(ucfirst($type) . ' deleted successfully');
    }

    public function Ajax($type)
    {
        if ($type == 'invoice') {
            $data['data'] = $this->model->getInvoiceList(Session::get('project_access'));
            
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
