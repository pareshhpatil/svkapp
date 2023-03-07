<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\ParentModel;
use Illuminate\Support\Facades\DB;
use App\Customer;

class Project extends Model
{
    use HasFactory;
    //protected $table = 'project';
    
    public function getProjectList($merchant_id, $start, $limit)
    {
        $retObj = DB::table('project as a')
            ->select(DB::raw('a.*,ifnull(b.company_name, concat(b.first_name," " ,  b.last_name)) company_name'))
            ->join('customer as b', 'a.customer_id', '=', 'b.customer_id')
            ->where('a.is_active', 1)
            ->where('a.merchant_id', $merchant_id)
            ->orderBy('a.id', 'DESC');
       
        $retObj =  $retObj->offset($start)
            ->limit($limit)
            ->get();
        return $retObj;
    }
}
