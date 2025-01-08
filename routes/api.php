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



Route::post('saveMataka', [App\Http\Controllers\ApiController::class, 'saveMataka']);
Route::post('loginMataka', [App\Http\Controllers\ApiController::class, 'loginMataka']);
Route::get('getMataka/{type}/{date}', [App\Http\Controllers\ApiController::class, 'getMataka']);
Route::get('getMatakaNumbers/{number}/{date}', [App\Http\Controllers\ApiController::class, 'getMatakaNumbers']);
Route::get('getMatakaLatest/{count}', [App\Http\Controllers\ApiController::class, 'getMatakaLatest']);
Route::get('getMatakaDetail/{id}', [App\Http\Controllers\ApiController::class, 'getMatakaDetail']);
Route::get('getMatakaSummary/{type}', [App\Http\Controllers\ApiController::class, 'getMatakaSummary']);
