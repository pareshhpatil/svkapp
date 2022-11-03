<?php

namespace App\Model;

/**
 * Description of Datatable
 *
 * @author Paresh
 */

use Log;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Model\ParentModel;

class Datatable extends ParentModel
{

    public function getCustomerList($merchant_id, $column_name, $where, $order, $limit, $bulk_id)
    {
        $retObj = DB::select("call get_customer_list('" . $merchant_id . "','" . $column_name . "','" . $where . "','" . $order . "','" . $limit . "'," . $bulk_id . ");");
        $data = json_decode(json_encode($retObj), true);
        return $data;
    }
}
