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

class Dashboard extends Model {

    public function profilesave($request, $name, $email, $mobile, $gst_number, $address, $pan_no, $sac_code, $logo, $admin_id, $user_id) {
        $image = $this->uploadImage($request);
        if ($image != '') {
            $logo = $image;
        }
        DB::table('admin')
                ->where('admin_id', $admin_id)
                ->update([
                    'name' => $name,
                    'mobile' => $mobile,
                    'email' => $email,
                    'gst_number' => $gst_number,
                    'address' => $address,
                    'pan_number' => $pan_no,
                    'logo' => $logo,
                    'sac_code' => $sac_code,
                    'last_update_by' => $user_id
        ]);
    }

    public function uploadImage($request) {
        if ($request->hasFile('uploaded_file')) {
            if ($request->file('uploaded_file')->isValid()) {
                try {
                    $file = $request->file('uploaded_file');
                    $name = time() . '.' . $file->getClientOriginalExtension();

                    $request->file('uploaded_file')->move("dist/img/", $name);
                    return $name;
                } catch (Illuminate\Filesystem\FileNotFoundException $e) {
                    
                }
            }
        }
        return '';
    }

    public function getAdminDetail($id) {
        $retObj = DB::table('admin')
                ->select(DB::raw('*'))
                ->where('admin_id', $id)
                ->first();
        return $retObj;
    }

}
