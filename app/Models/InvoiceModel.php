<?php

namespace App\Models;

use App\Models\ParentModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


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

    public function getCompanyListForExport($adminId)
    {
        return DB::table('company')
            ->select('company_id', 'name')
            ->where('admin_id', $adminId)
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
    }

    public function getLogsheetInvoiceExportRows($adminId, $companyId = 0, $fromDate = null, $toDate = null)
    {
        $table = 'logsheet_invoice';
        $monthColumn = Schema::hasColumn($table, 'month') ? 'i.date' : 'i.date';
        $grossTotalColumn = Schema::hasColumn($table, 'gross_total') ? 'i.gross_total' : (Schema::hasColumn($table, 'base_total') ? 'i.base_total' : '0');
        $gstRcmColumn = Schema::hasColumn($table, 'gst_rcm') ? 'i.gst_rcm' : "''";
        $gstRcmPctColumn = Schema::hasColumn($table, 'gst_rcm_percentage') ? 'i.gst_rcm_percentage' : "''";
        $gstRcmAmtColumn = Schema::hasColumn($table, 'gst_rcm_amount') ? 'i.gst_rcm_amount' : (Schema::hasColumn($table, 'total_gst') ? 'i.total_gst' : '0');

        $q = DB::table('logsheet_invoice as i')
            ->join('company as c', 'c.company_id', '=', 'i.company_id')
            ->where('i.is_active', 1)
            ->where('i.admin_id', (int) $adminId)
            ->select(DB::raw("
                c.name as company_name,
                i.invoice_number,
                i.bill_date,
                {$monthColumn} as month_value,
                {$grossTotalColumn} as gross_total,
                c.rcm as gst_rcm,
                {$gstRcmPctColumn} as gst_rcm_percentage,
                {$gstRcmAmtColumn} as gst_rcm_amount,
                i.grand_total
            "))
            ->orderBy('i.bill_date', 'desc');

        if ((int) $companyId > 0) {
            $q->where('i.company_id', (int) $companyId);
        }
        if (!empty($fromDate)) {
            $q->whereDate('i.bill_date', '>=', $fromDate);
        }
        if (!empty($toDate)) {
            $q->whereDate('i.bill_date', '<=', $toDate);
        }

        return $q->get();
    }


}
