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

        $data['selectedMenu'] = [5];
        $data['menus'] = Session::get('menus');
        $data['list'] = $this->model->getProject();
        return view('web.master.project', $data);
    }
}
