<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Model\ParentModel;
use Illuminate\Support\Facades\Auth;

class MerchantData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $request->user = $user;
            $request->user_id = $user->user_id;
            
            $model = new ParentModel();
            $merchantdetail = $model->getTableRow('merchant', 'user_id', $request->user_id);
            //$merchantdetail = Merchant::where('user_id', $request->user_id)->first();
            
            if (!empty($merchantdetail)) {
                $request->merchant = $merchantdetail;
                $request->merchant_id = $merchantdetail->merchant_id;
            } else {
                throw new Exception('Merchant details not found for this user ' . $request->user_id);
            }
        }
        return $next($request);
    }

    
}
