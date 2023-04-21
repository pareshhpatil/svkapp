<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('sendotp', [App\Http\Controllers\ApiController::class, 'sendOTP'])->middleware('throttle:3,1');
Route::post('validateotp', [App\Http\Controllers\ApiController::class, 'validateOTP']);
