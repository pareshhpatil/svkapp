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

    public function getProject($project_ids = [])
    {
        $retObj = DB::table('project as a')
            ->join('company as ea', 'ea.company_id', '=', 'a.company_id')
            ->where('a.is_active', 1)
            ->select(DB::raw('a.*,ea.name as company_name'));
        if (!empty($project_ids)) {
            $retObj->whereIn('a.project_id', $project_ids);
        }

        return $retObj->get();
    }

    public function getImport($project_ids = [], $type = 1)
    {
        $retObj = DB::table('project as a')
            ->join('import as ea', 'ea.project_id', '=', 'a.project_id')
            ->where('ea.is_active', 1)
            ->where('ea.bulk_type', $type)
            ->select(DB::raw('ea.*,a.name as project_name'));
        if (!empty($project_ids)) {
            $retObj->whereIn('a.project_id', $project_ids);
        }
        return $retObj->get();
    }

    public function getEmployeeCount($project_ids)
    {
        $retObj = DB::table('passenger')
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender');
        if (!empty($project_ids)) {
            $retObj->whereIn('project_id', $project_ids);
        }
        return $retObj->pluck('total', 'gender');
    }

    public function getRideCount($project_ids, $from_date, $to_date)
    {
        $retObj = DB::table('ride_passenger as p')
            ->join('ride as r', 'p.ride_id', '=', 'r.id')
            ->select(DB::raw('DATE_FORMAT(p.pickup_time,"%d")  as day'), DB::raw('count(*) as total'))
            ->whereIn('r.status', [1, 2, 5])
            ->whereIn('p.status', [0, 1, 2, 5])
            ->whereDate('p.pickup_time', '>=', $from_date)
            ->whereDate('p.pickup_time', '<=', $to_date)
            ->groupBy('day');
        if (!empty($project_ids)) {
            $retObj->whereIn('project_id', $project_ids);
        }
        return $retObj->pluck('total', 'day');
    }

    public function getRideCountDetail($project_ids, $from_date, $to_date)
    {
        $retObj = DB::table('ride_passenger as p')
            ->join('ride as r', 'p.ride_id', '=', 'r.id')
            ->select('p.status', DB::raw('count(*) as total'))
            ->whereIn('r.status', [1, 2, 5])
            ->whereDate('p.pickup_time', '>=', $from_date)
            ->whereDate('p.pickup_time', '<=', $to_date)
            ->groupBy('status');
        if (!empty($project_ids)) {
            $retObj->whereIn('project_id', $project_ids);
        }
        return $retObj->pluck('total', 'status');
    }
    public function getSlabCountDetail($project_ids, $from_date, $to_date)
    {
        $retObj = DB::table('ride as r')
            ->join('zone as z', 'z.zone_id', '=', 'r.slab_id')
            ->select(DB::raw('COUNT(company_slab) as count'), 'company_slab')
            ->whereBetween('r.date', [$from_date, $to_date])
            ->groupBy('company_slab')
            ->orderBy('company_slab');
        if (!empty($project_ids)) {
            $retObj->whereIn('r.project_id', $project_ids);
        }

        return [
            'labels' => json_encode($retObj->pluck('company_slab')->toArray()),
            'values' => json_encode($retObj->pluck('count')->toArray()),
        ];
    }

    public function getRide($project_id, $date, $status, $project_ids = [])
    {
        $retObj = DB::table('ride as a')
            ->join('project as ea', 'ea.project_id', '=', 'a.project_id')
            ->join('config as c', 'c.value', '=', 'a.status')
            ->where('a.is_active', 1)
            ->where('c.type', 'ride_status')
            ->select(DB::raw('a.*,ea.name as project_name,c.description'));
        if ($project_id > 0) {
            $retObj->where('a.project_id', $project_id);
        } else {
            if (!empty($project_ids)) {
                $retObj->whereIn('a.project_id', $project_ids);
            }
        }
        if ($date != 'na') {
            $retObj->where('a.date', $date);
        }
        if (!empty($status)) {
            $retObj->whereIn('a.status', $status);
        }

        $array =   $retObj->get();
        return $array;
    }

    public function getInvoiceList($project_ids = [])
    {
        $retObj = DB::table('project as a')
            ->join('company as ea', 'ea.company_id', '=', 'a.company_id')
            ->join('logsheet_invoice as i', 'i.company_id', '=', 'a.company_id')
            ->join('vehicle as v', 'i.vehicle_id', '=', 'v.vehicle_id')
            ->where('a.is_active', 1)
            ->where('i.is_active', 1)
            ->select(DB::raw("invoice_id,v.number,invoice_number,DATE_FORMAT(date, '%b %Y') as bill_month,DATE_FORMAT(bill_date, '%d %b %Y') as bill_date,format(grand_total,2,'en_IN') as grand_total,ea.name as company_name"));
        if (!empty($project_ids)) {
            $retObj->whereIn('a.project_id', $project_ids);
        }

        return $retObj->get();
    }
}
