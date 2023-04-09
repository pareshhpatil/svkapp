<?php
$origin = getenv('API_ORIGIN');
header('Access-Control-Allow-Origin: '.$origin);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
use Illuminate\Http\Request;

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

Route::middleware('api')->post('/v1/company','ApiController@company');
Route::middleware('api')->post('/v1/policy','ApiController@policy');
Route::middleware('api')->post('/v1/user','ApiController@user');
Route::middleware('api')->post('/v1/payment-request','ApiController@paymentrequest');

