<?php

namespace App\Models;

use App\Models\ParentModel;
use Illuminate\Support\Facades\DB;


class InvoiceModel extends ParentModel
{

    public function getInvoiceList($project_ids = [])
    {
        $retObj = DB::table('project as a')
            ->join('company as ea', 'ea.company_id', '=', 'a.company_id')
            ->join('ridetrack_invoice as i', 'i.project_id', '=', 'a.project_id')
            ->where('i.is_active', 1)
            ->select(DB::raw("i.id as invoice_id,i.title,invoice_number,DATE_FORMAT(month, '%b %Y') as bill_month,DATE_FORMAT(bill_date, '%d %b %Y') as bill_date,format(amount,2,'en_IN') as grand_total,ea.name as company_name"))
            ->orderBy('i.bill_date', 'desc');
        if (!empty($project_ids)) {
            $retObj->whereIn('a.project_id', $project_ids);
        }

        return $retObj->get();
    }


}
