<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class APIAccess
{
    /**
     * Handle an API incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);

        //Get request JSON data

        if (isset($_POST['data'])) {
        } else {
            $data = file_get_contents('php://input');
            if (!isset($data)) {
                abort(403, 'Access denied');
            }
            $_POST['data'] = $data;
        }

        $array = json_decode($_POST['data'], 1);
        $request->APIdata = $array;
        $key = $array['access_key_id'];

        #get Access key config array

        $Validkeys = config('accesskey');
        if (!in_array($key, $Validkeys)) {
            return response()->json(['errcode' => 'ER01003', 'errmsg' => 'Invalid API key.']);
        }

        $secret = $array['secret_access_key'];

        $detail = $this->getMerchantData($key, $secret);
        if ($detail == false) {
            return response()->json(['errcode' => 'ER01003', 'errmsg' => 'Invalid API key.']);
        }

        $request->merchant_id = $detail->merchant_id;
        $request->user_id = $detail->user_id;
        define('REQ_TIME', date("Y-m-d H:i:s"));
        return $next($request);
    }

    private function getMerchantData($key, $secret)
    {
        $retObj = DB::table('merchant_security_key')
            ->select(DB::raw('merchant_id,user_id'))
            ->where('access_key_id', $key)
            ->where('secret_access_key', $secret)
            ->first();
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }
}
