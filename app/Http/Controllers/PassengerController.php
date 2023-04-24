<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;

class PassengerController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $data['selectedMenu'] = [2, 3];
        $data['menus'] = Session::get('menus');
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1);

        return view('web.passenger.create', $data);
    }

    public function list()
    {
        $data['selectedMenu'] = [2, 4];
        $data['menus'] = Session::get('menus');
        return view('web.passenger.list', $data);
    }

    public function ajaxPassenger(Request $request)
    {
        $data['data'] = $this->model->getTableList('passenger', 'is_active', 1);
        return json_encode($data);
    }

    public function save(Request $request)
    {
        $this->user_id = Session::get('user_id');
        foreach ($_POST['group-a'] as $row) {
            $row['project_id'] = $request->project_id;
            $this->model->saveTable('passenger', $row, $this->user_id);
        }

        return redirect()->back()->withSuccess('Passengers added successfully');
    }
}
