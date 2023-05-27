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

    public function getRoster($project_id, $date, $status)
    {
        $retObj = DB::table('ride as a')
            ->join('project as ea', 'ea.project_id', '=', 'a.project_id')
            ->join('config as c', 'c.value', '=', 'a.status')
            ->where('a.is_active', 1)
            ->where('c.type', 'ride_status')
            ->select(DB::raw('a.*,ea.name as project_name,c.description'));
        if ($project_id > 0) {
            $retObj->where('a.project_id', $project_id);
        }
        if ($date != 'na') {
            $retObj->where('a.date', $date);
        }
        if ($status != 'na') {
            $retObj->where('a.status', $status);
        }

        $array =   $retObj->get();
        return $array;
    }
}
