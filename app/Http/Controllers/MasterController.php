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
        $data['menus'] = Session::get('menus');

        return view('web.' . $type . '.create', $data);
    }

    public function save(Request $request, $type)
    {
        $user_id = Session::get('user_id');
        foreach ($request->drivers as $row) {
            $this->model->saveTable('driver', $row, $user_id);
        }

        return redirect()->back()->withSuccess('Drivers added successfully');
    }

    public function list($type)
    {
        $data['selectedMenu'] = [11, 13];
        $data['menus'] = Session::get('menus');

        return view('web.' . $type . '.list', $data);
    }

    public function Ajax($type)
    {
        $data['data'] = $this->model->getTableList($type, 'is_active', 1);
        return json_encode($data);
    }
}
