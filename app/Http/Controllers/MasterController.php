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



    public function masterAdd($type)
    {
        $data['menu'] = 0;
        $data['title'] = 'Add ' . $type;
        return view('master.' . $type . '-add', $data);
    }

    public function masterSave(Request $request, $type)
    {
        $array = $request->all();
        if ($type == 'driver') {
            unset($array['_token']);
            $this->model->saveTable('driver', $array, Session::get('user_id'));
        } else if ($type == 'vehicle') {
            unset($array['_token']);
            $array['name'] = $array['brand'] . ' - ' . $array['number'];
            $array['admin_id'] = 1;
            $this->model->saveTable('vehicle', $array, Session::get('user_id'));
        }

        return redirect()->back()->with('message', 'Added successfully');
    }
}
