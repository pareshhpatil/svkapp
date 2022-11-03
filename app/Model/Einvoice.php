<?php

namespace App\Model;

/**
 *
 * @author Paresh
 */

use Log;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Model\ParentModel;
use Illuminate\Database\Eloquent\Model;

class Einvoice extends ParentModel
{
   

    public function GetAllData($merchant_id,$from_date,$to_date)
    {

        $retObj = DB::table('einvoice_request as e')
            ->select(DB::raw('e.*,CONCAT_WS("", c.first_name, c.last_name) AS customer_name'))
            ->join('customer as c', 'e.customer_id', '=', 'c.customer_id')
            ->where('e.merchant_id', $merchant_id);
            if ($from_date!='' && $to_date!='') {
                $retObj->whereDate('e.created_date', '>=', $from_date);
               $retObj->whereDate('e.created_date', '<=', $to_date);
            }
          
       
        $data = $retObj->get();
        return $data;
    }

   
}
