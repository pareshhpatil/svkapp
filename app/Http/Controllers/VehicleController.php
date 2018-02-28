<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DashboardController
 *
 * @author Paresh
 */

namespace App\Http\Controllers;

use App\Model\Vehicle;
use App\Model\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class VehicleController extends Controller {

    private $vehicle_model;
    private $master_model;

    function __construct() {
        parent::__construct();
        $this->validateSession(1);
        $this->vehicle_model = new Vehicle();
        $this->master_model = new Master();
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function replacecab(Request $request) {
        $replace_list = $this->vehicle_model->getReplaceList($this->admin_id);
        $vehicle_list = $this->master_model->getMaster('vehicle', $this->admin_id);
        $data['title'] = 'Replace Cab';
        $data['list'] = $replace_list;
        $data['vehicle_list'] = $vehicle_list;
        $data['addnew_button'] = 1;
        return view('vehicle.replacecab', $data);
    }

    public function savereplacecab(Request $request) {
        $in_time = date('H:i:s', strtotime($request->in_time));
        $out_time = date('H:i:s', strtotime($request->out_time));
        $date = date('Y-m-d', strtotime($request->date));
        $is_paid = (isset($request->is_paid)) ? $request->is_paid : 0;
        $amount = ($request->amount > 0) ? $request->amount : 0;
        $result = $this->vehicle_model->saveReplaceCab($request->vehicle_id, $request->owner, $request->vehicle_number, $in_time, $out_time, $amount, $date, $request->remark, $is_paid, $this->user_id, $this->admin_id);
        $this->setSuccess('Replace cab entry has been save successfully');
        header('Location: /admin/vehicle/replacecab');
        exit;
    }

}
