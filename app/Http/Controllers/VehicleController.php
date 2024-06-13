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
use App\Model\Bill;
use App\Model\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class VehicleController extends Controller
{

    private $vehicle_model;
    private $master_model;

    function __construct()
    {
        parent::__construct();
        $this->validateSession(array(1));
        $this->vehicle_model = new Vehicle();
        $this->master_model = new Master();
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function replacecab(Request $request)
    {
        $replace_list = $this->vehicle_model->getReplaceList($this->admin_id);
        $vehicle_list = $this->master_model->getMaster('vehicle', $this->admin_id);
        $data['title'] = 'Replace Cab';
        $data['list'] = $replace_list;
        $data['vehicle_list'] = $vehicle_list;
        $data['addnew_button'] = 1;
        return view('vehicle.replacecab', $data);
    }

    public function savereplacecab(Request $request)
    {
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


    public function fuel($add = false)
    {
        $this->setFilterDates();
        $vehicle_list = $this->master_model->getMaster('vehicle', 1, 'fuel_enable');
        $source_list = $this->master_model->getMaster('paymentsource',  $this->admin_id);
        $employee_list = $this->master_model->getMaster('employee', $this->admin_id);
        $fuel_list = $this->vehicle_model->getFuelList($this->from_date, $this->to_date);
        $data['title'] = 'Fuel entry';
        $data['vehicle_list'] = $vehicle_list;
        $data['list'] = $fuel_list;
        $data['add'] = $add;
        $data['source_list'] = $source_list;
        $data['employee_list'] = $employee_list;
        $data['addnew_button'] = 1;
        if ($add == true) {
            $data['success_message'] = 'Fuel has been save successfully';
        }
        return view('vehicle.fuel', $data);
    }

    public function fuelsave(Request $request)
    {
        $this->bill_model = new Bill();
        $date = $request->date;
        $request->photo = $this->master_model->uploadImage($request, 'bills');
        if ($request->intrest > 0) {
            $request->intrest_charge = $request->amount * $request->intrest / 100;
        } else {
            $request->intrest_charge = 0;
        }
        $request->date = date('Y-m-d', strtotime($request->date));
        $request->km_reading = 0;
        $this->vehicle_model->saveFuelCab($request, $this->user_id);

        $vehicle = $this->master_model->getMasterDetail('vehicle', 'vehicle_id', $request->vehicle_id);
        if ($request->employee_id > 0) {
            $this->bill_model->saveTransaction(1, $request->employee_id, $request->date, $request->amount, 'FUEL', 'Fuel charges ' . $request->litre . ' ltr in ' . $vehicle->number . ' on ' . $date, '', $request->source_id, 1, $this->user_id, $this->admin_id);
            if ($request->intrest_charge > 0) {
                $this->bill_model->saveTransaction(1, $request->employee_id, $request->date, $request->intrest_charge, 'FUEL', 'Fuel intrest charge on ' . $date, '', 5, 1, $this->user_id, $this->admin_id);
            }
            $this->master_model->updateEmployeeBalance($request->amount + $request->intrest_charge, $request->employee_id);
        } else {
            $this->bill_model->saveBill(191, 'FUEL', 0, 0, $request->amount, 'Fuel charges ' . $request->litre . ' ltr in ' . $vehicle->number . ' on ' . $date . ' ' . $request->note, $request->date, $request->amount, $this->user_id, $this->admin_id);
        }

        $this->master_model->updateBankBalance($request->amount, $request->source_id);

        return $this->fuel(true);
    }
}
