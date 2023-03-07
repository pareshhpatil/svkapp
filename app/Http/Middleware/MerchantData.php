<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Merchant;
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
            
            $merchant = Merchant::where('user_id', $request->user_id)->first();
            
            if (!empty($merchant)) {
                $request->merchant = $merchant;
                $request->merchant_id = $merchant->merchant_id;
            } else {
                throw new Exception('Merchant details not found for this user ' . $request->user_id);
            }
        }
       
        dd($request);
        return $next($request);
    }
}
