<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Redis;
class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    public function getSearchParamRedis($list_name=null,$merchant_id=null) {
        $getRediscache = Redis::get('merchantSearchCriteria'.$merchant_id);
        $redis_items = json_decode($getRediscache, 1); 
        
        if (!empty($_POST)) {
            $redis_items[$list_name]['search_param'] = $_POST;
            Redis::set('merchantSearchCriteria'.$merchant_id, json_encode($redis_items));
        }
        return $redis_items;
    }
}
