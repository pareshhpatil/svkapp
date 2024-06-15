<?php

namespace App\Models;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payout
 *
 * @author Paresh
 */

use Log;
use App\Models\ParentModel;
use Illuminate\Support\Facades\DB;
use Exception;

class MasterModel extends ParentModel
{

    public function getProject()
    {
        $retObj = DB::table('project as a')
            ->join('company as ea', 'ea.company_id', '=', 'a.company_id')
            ->where('a.is_active', 1)
            ->select(DB::raw('a.*,ea.name as company_name'))
            ->get();
        return $retObj;
    }

    public function getImport()
    {
        $retObj = DB::table('project as a')
            ->join('import as ea', 'ea.project_id', '=', 'a.project_id')
            ->where('ea.is_active', 1)
            ->select(DB::raw('ea.*,a.name as project_name'))
            ->get();
        return $retObj;
    }

    public function getRidePassengers($ride_id, $passenger_id)
    {
        $retObj = DB::table('ride_passenger as a')
            ->join('users as u', 'u.parent_id', '=', 'a.passenger_id')
            ->where('a.ride_id', $ride_id)
            ->where('a.is_active', 1)
            ->where('u.user_type', 5)
            ->where('a.passenger_id', '<>', $passenger_id)
            ->select(DB::raw('u.id,u.name'))
            ->get();
        return $retObj;
    }

    public function getChatMessages($group_id)
    {
        $retObj = DB::table('chat_message as c')
            ->join('users as u', 'c.user_id', '=', 'u.id')
            ->where('c.is_active', 1)
            ->where('c.group_id', $group_id)
            ->select(DB::raw('c.*,u.gender,DATE_FORMAT(c.created_date, "%l:%i %p") as time'));
        $array = $retObj->get();
        return json_decode(json_encode($array), 1);
    }

    public function getWhatsappMessages($mobile)
    {
        $retObj = DB::table('whatsapp_messages as c')
            ->where('c.is_active', 1)
            ->where('c.mobile', $mobile)
            ->select(DB::raw('c.*,"Male" as gender,DATE_FORMAT(c.last_update_date, "%l:%i %p") as time'));
        $array = $retObj->get();
        return json_decode(json_encode($array), 1);
    }

    public function getChatMembers($group_id, $user_id)
    {
        $retObj = DB::table('chat_group_member as c')
            ->join('users as u', 'c.user_id', '=', 'u.id')
            ->where('c.is_active', 1)
            ->where('u.id', '<>', $user_id)
            ->where('c.group_id', $group_id)
            ->select(DB::raw('u.token'));
        $array = $retObj->get();
        return json_decode(json_encode($array), 1);
    }
}
