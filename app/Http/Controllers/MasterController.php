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

use App\Model\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class MasterController extends Controller {

    private $master_model;

    function __construct() {
        parent::__construct();
        $this->validateSession(1);
        $this->master_model = new Master();
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function masterlist($master) {
        $list = $this->master_model->getMaster($master, $this->admin_id);
        $int = 0;
        foreach ($list as $item) {
            $link = $this->encrypt->encode($item->{$master . '_id'});
            $list[$int]->link = $link;
            $int++;
        }
        $data['title'] = 'List ' . ucfirst($master);
        $data['list'] = $list;
        $data['addnewlink'] = '/admin/' . $master . '/create';
        return view('master.' . $master . '.list', $data);
    }

    public function masterview($master, $link) {
        $id = $this->encrypt->decode($link);
        $detail = $this->master_model->getMasterDetail($master, $master . '_id', $id);
        $data['title'] = ucfirst($master) . ' detail';
        $data['det'] = $detail;
        return view('master.' . $master . '.view', $data);
    }

    public function mastercreate($master) {
        $data['title'] = 'Create ' . ucfirst($master);
        return view('master.' . $master . '.create', $data);
    }

    public function masterdelete($master, $link) {
        $id = $this->encrypt->decode($link);
        $this->master_model->deleteReccord($master, $master . '_id', $id, $this->user_id);
        $this->setSuccess(ucfirst($master) . ' has been deleted successfully');
        header('Location: /admin/' . $master . '/list');
        exit;
    }

    public function masterupdate($master, $link) {
        $id = $this->encrypt->decode($link);
        $detail = $this->master_model->getMasterDetail($master, $master . '_id', $id);

        $data['title'] = ucfirst($master) . ' update';
        $data['det'] = $detail;
        return view('master.' . $master . '.update', $data);
    }

    #Employee

    public function employeesave(Request $request) {
        $join_date = date('Y-m-d', strtotime($request->join_date));
        $employee_id = $this->master_model->saveEmployee($request, $request->employee_code,$request->name, $request->email, $request->mobile, $request->pan, $request->address, $request->adharcard, $request->license, $request->uploaded_file, $request->payment, $join_date, $request->payment_day, $request->account_no, $request->holder_name, $request->ifsc_code, $request->bank_name, $request->account_type, $this->admin_id, $this->user_id);
        $link = $this->encrypt->encode($employee_id);
        $data['title'] = 'Success Employee';
        $data['success'] = 'Employee has been saved successfully';
        $data['link'] = $link;
        return view('master.employee.saved', $data);
    }

    public function employeeupdatesave(Request $request) {
        $join_date = date('Y-m-d', strtotime($request->join_date));
        $this->master_model->updateEmployee($request, $request->employee_id,$request->employee_code, $request->photo, $request->name, $request->email, $request->mobile, $request->pan, $request->address, $request->adharcard, $request->license, $request->uploaded_file, $request->payment, $join_date, $request->payment_day, $request->account_no, $request->holder_name, $request->ifsc_code, $request->bank_name, $request->account_type, $this->admin_id, $this->user_id);
        $link = $this->encrypt->encode($request->employee_id);
        $data['title'] = 'Success Employee';
        $data['success'] = 'Employee has been saved successfully';
        $data['link'] = $link;
        return view('master.employee.saved', $data);
    }

    #Company

    public function companysave(Request $request) {
        $join_date = date('Y-m-d', strtotime($request->join_date));
        $company_id = $this->master_model->saveCompany($request->name, $request->email, $request->gst_number, $request->address, $join_date, $this->admin_id, $this->user_id);
        $link = $this->encrypt->encode($company_id);
        $data['title'] = 'Success Company';
        $data['success'] = 'Company has been saved successfully';
        $data['link'] = $link;
        return view('master.company.saved', $data);
    }

    public function companyupdatesave(Request $request) {
        $join_date = date('Y-m-d', strtotime($request->join_date));
        $this->master_model->updateCompany($request->company_id, $request->name, $request->email, $request->gst_number, $request->address, $join_date, $this->admin_id, $this->user_id);
        $link = $this->encrypt->encode($request->company_id);
        $data['title'] = 'Success Company';
        $data['success'] = 'Company has been saved successfully';
        $data['link'] = $link;
        return view('master.company.saved', $data);
    }

    #Vendor

    public function vendorsave(Request $request) {
        $company_id = $this->master_model->saveVendor($request->business_name, $request->name, $request->email, $request->mobile, $request->gst_number, $request->address, $this->admin_id, $this->user_id);
        $link = $this->encrypt->encode($company_id);
        $data['title'] = 'Success Vendor';
        $data['success'] = 'Vendor has been saved successfully';
        $data['link'] = $link;
        return view('master.vendor.saved', $data);
    }

    public function vendorupdatesave(Request $request) {
        $this->master_model->updateVendor($request->vendor_id, $request->business_name, $request->name, $request->email, $request->mobile, $request->gst_number, $request->address, $this->admin_id, $this->user_id);
        $link = $this->encrypt->encode($request->vendor_id);
        $data['title'] = 'Success Vendor';
        $data['success'] = 'Vendor has been saved successfully';
        $data['link'] = $link;
        return view('master.vendor.saved', $data);
    }

    #Payment Source

    public function paymentsourcesave(Request $request) {
        $company_id = $this->master_model->savePaymentsource($request->name, $request->bank, $request->card_number, $request->type, $this->admin_id, $this->user_id);
        $this->setSuccess('Payment source has been deleted successfully');
        header('Location: /admin/paymentsource/list');
        exit();
    }

    public function paymentsourceupdatesave(Request $request) {
        $this->master_model->updatePaymentsource($request->id, $request->name, $request->bank, $request->card_number, $request->type, $this->admin_id, $this->user_id);
        $this->setSuccess('Payment source has been deleted successfully');
        header('Location: /admin/paymentsource/list');
        exit();
    }

    #Vehicle

    public function vehiclesave(Request $request) {
        $date = date('Y-m-d', strtotime($request->purchase_date));
        $vehicle_id = $this->master_model->saveVehicle($request->name, $request->brand, $request->car_type, $request->number, $request->model, $date, $this->admin_id, $this->user_id);
        $link = $this->encrypt->encode($vehicle_id);
        $data['title'] = 'Success Vehicle';
        $data['success'] = 'Vehicle has been saved successfully';
        $data['link'] = $link;
        return view('master.vehicle.saved', $data);
    }

    public function vehicleupdatesave(Request $request) {
        $date = date('Y-m-d', strtotime($request->purchase_date));
        $this->master_model->updateVehicle($request->vehicle_id, $request->name, $request->brand, $request->car_type, $request->number, $request->model, $date, $this->admin_id, $this->user_id);
        $link = $this->encrypt->encode($request->vehicle_id);
        $data['title'] = 'Success Vehicle';
        $data['success'] = 'Vehicle has been saved successfully';
        $data['link'] = $link;
        return view('master.vehicle.saved', $data);
    }

}
