<?php

namespace App\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Paresh
 */
use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Master extends Model {

    public function saveEmployee($request, $name, $email, $mobile, $pan, $address, $adharcard, $license, $image_file, $payment, $join_date, $payment_day, $account_no, $account_holder_name, $ifsc_code, $bank_name, $account_type, $admin_id, $user_id) {
        $photo = $this->uploadImage($request);
        $id = DB::table('employee')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'name' => $name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'pan' => $pan,
                    'address' => $address,
                    'adharcard' => $adharcard,
                    'license' => $license,
                    'photo' => $photo,
                    'payment' => $payment,
                    'join_date' => $join_date,
                    'payment_day' => $payment_day,
                    'account_no' => $account_no,
                    'account_holder_name' => $account_holder_name,
                    'ifsc_code' => $ifsc_code,
                    'bank_name' => $bank_name,
                    'account_type' => $account_type,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function updateEmployee($request, $employee_id, $photo, $name, $email, $mobile, $pan, $address, $adharcard, $license, $image_file, $payment, $join_date, $payment_day, $account_no, $account_holder_name, $ifsc_code, $bank_name, $account_type, $admin_id, $user_id) {
        $image = $this->uploadImage($request);
        if ($image != '') {
            $photo = $image;
        }
        DB::table('employee')
                ->where('employee_id', $employee_id)
                ->update([
                    'name' => $name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'pan' => $pan,
                    'address' => $address,
                    'adharcard' => $adharcard,
                    'license' => $license,
                    'photo' => $photo,
                    'payment' => $payment,
                    'join_date' => $join_date,
                    'payment_day' => $payment_day,
                    'account_no' => $account_no,
                    'account_holder_name' => $account_holder_name,
                    'ifsc_code' => $ifsc_code,
                    'bank_name' => $bank_name,
                    'account_type' => $account_type,
                    'last_update_by' => $user_id
        ]);
    }

    public function saveCompany($name, $email, $gst_number, $address, $join_date, $admin_id, $user_id) {
        $id = DB::table('company')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'name' => $name,
                    'email' => $email,
                    'gst_number' => $gst_number,
                    'address' => $address,
                    'join_date' => $join_date,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function updateCompany($company_id, $name, $email, $gst_number, $address, $join_date, $admin_id, $user_id) {
        DB::table('company')
                ->where('company_id', $company_id)
                ->update([
                    'name' => $name,
                    'email' => $email,
                    'gst_number' => $gst_number,
                    'address' => $address,
                    'join_date' => $join_date,
                    'last_update_by' => $user_id
        ]);
    }

    public function saveVehicle($name, $brand, $car_type, $number, $model, $purchase_date, $admin_id, $user_id) {
        $id = DB::table('vehicle')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'name' => $name,
                    'brand' => $brand,
                    'car_type' => $car_type,
                    'number' => $number,
                    'model' => $model,
                    'purchase_date' => $purchase_date,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function updateVehicle($vehicle_id, $name, $brand, $car_type, $number, $model, $purchase_date, $admin_id, $user_id) {
        DB::table('vehicle')
                ->where('vehicle_id', $vehicle_id)
                ->update([
                    'name' => $name,
                    'brand' => $brand,
                    'car_type' => $car_type,
                    'number' => $number,
                    'model' => $model,
                    'purchase_date' => $purchase_date,
                    'last_update_by' => $user_id
        ]);
    }

    public function uploadImage($request) {
        if ($request->hasFile('uploaded_file')) {
            if ($request->file('uploaded_file')->isValid()) {
                try {
                    $file = $request->file('uploaded_file');
                    $name = time() . '.' . $file->getClientOriginalExtension();

                    $request->file('uploaded_file')->move("dist/uploads/employee", $name);
                    return $name;
                } catch (Illuminate\Filesystem\FileNotFoundException $e) {
                    
                }
            }
        }
        return '';
    }

    public function getMaster($table, $admin_id, $column_name = 'admin_id') {
        $retObj = DB::table($table)
                ->select(DB::raw('*'))
                ->where('is_active', 1)
                ->where($column_name, $admin_id)
                ->get();
        return $retObj;
    }

    public function getMasterDetail($table, $column, $id) {
        $retObj = DB::table($table)
                ->select(DB::raw('*'))
                ->where($column, $id)
                ->first();
        return $retObj;
    }

    public function deleteReccord($table, $column, $id, $user_id) {
        DB::table($table)
                ->where($column, $id)
                ->update([
                    'is_active' => 0,
                    'last_update_by' => $user_id
        ]);
    }

}
