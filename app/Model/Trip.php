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

class Trip extends Model {

    public function saveTrip($data, $user_id) {
        $id = DB::table('trip_request')->insertGetId(
                [
                    'vehicle_type' => $data['vehicle_type'],
                    'date' => $data['date'],
                    'time' => $data['pickup_time'],
                    'total_passengers' => $data['total_passengers'],
                    'passengers' => $data['passengers'],
                    'pickup_location' => $data['pickup_location'],
                    'drop_location' => $data['drop_location'],
                    'note' => $data['note'],
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }
    
    public function saveReview($data, $user_id) {
        $id = DB::table('review_complaints')->insertGetId(
                [
                    'trip_id' => $data['trip_id'],
                    'type' => $data['type'],
                    'name' => $data['name'],
                    'text' => $data['note'],
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function saveTripDetail($data, $user_id) {
        $id = DB::table('trip')->insertGetId(
                [
                    'employee_id' => $data['employee_id'],
                    'vehicle_id' => $data['vehicle_id'],
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'total_passengers' => $data['total_passengers'],
                    'passengers' => $data['passengers'],
                    'pickup_location' => $data['pickup_location'],
                    'drop_location' => $data['drop_location'],
                    'note' => $data['note'],
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function updateTripRating($rating, $id) {
        DB::table('trip')
                ->where('trip_id', $id)
                ->update([
                    'rating' => $rating
        ]);
    }

    public function getTripList($user_id, $array) {
        $retObj = DB::table('trip_request')
                ->select(DB::raw('*'))
                ->where('is_active', 1)
                ->where('created_by', $user_id)
                ->whereIn('status', $array)
                ->get();
        return $retObj;
    }

    public function getAdminTripList($array) {
        $retObj = DB::table('trip_request')
                ->select(DB::raw('*'))
                ->where('is_active', 1)
                ->whereIn('status', $array)
                ->get();
        return $retObj;
    }

}
