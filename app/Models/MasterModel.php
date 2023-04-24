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
}
