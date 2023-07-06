<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes(['register' => false]);
Route::get('/login/auth', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/login/verify', [App\Http\Controllers\Auth\LoginController::class, 'verify']);
Route::group(['middleware' => array('auth', 'access')], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/escort/create', [App\Http\Controllers\PassengerController::class, 'escortCreate']);
    Route::any('/escort/list', [App\Http\Controllers\PassengerController::class, 'escortList']);
    Route::get('/passenger/create', [App\Http\Controllers\PassengerController::class, 'create']);
    Route::any('/passenger/list', [App\Http\Controllers\PassengerController::class, 'list']);
    Route::any('/passenger/list/{bulk_id}/{type}', [App\Http\Controllers\PassengerController::class, 'list']);
    Route::get('/{type}/import/format', [App\Http\Controllers\PassengerController::class, 'format']);
    Route::get('/{type}/import', [App\Http\Controllers\PassengerController::class, 'import']);
    Route::get('/{type}/import/{type1}/{link}', [App\Http\Controllers\PassengerController::class, 'changeStatus']);

    Route::any('/roster/list/{bulk_id?}/{type?}', [App\Http\Controllers\RosterController::class, 'list']);
    Route::get('/ajax/roster/route/{date}/{project_id}/{type?}/{shift?}', [App\Http\Controllers\RosterController::class, 'ajaxRosterRoute']);

    Route::get('/ajax/roster/{date}/{project_id}/{bulk_id}', [App\Http\Controllers\RosterController::class, 'ajaxRoster']);
    Route::get('/roster/delete/{id}/{bulk_id?}', [App\Http\Controllers\RosterController::class, 'delete']);

    Route::any('/roster/route', [App\Http\Controllers\RosterController::class, 'route']);
    Route::post('/roster/save', [App\Http\Controllers\RosterController::class, 'save']);
    Route::post('/roster/route/save', [App\Http\Controllers\RosterController::class, 'routeSave']);


    Route::post('/{type}/importsave', [App\Http\Controllers\PassengerController::class, 'importsave']);
    Route::get('/project/list', [App\Http\Controllers\MasterController::class, 'projectList']);
    Route::post('/passenger/save', [App\Http\Controllers\PassengerController::class, 'save']);
    Route::get('/ajax/passenger/{project_id}/{bulk_id?}/{type?}', [App\Http\Controllers\PassengerController::class, 'ajaxPassenger']);
    Route::get('/passenger/update/{id}', [App\Http\Controllers\PassengerController::class, 'create']);

    Route::get('/ride/create', [App\Http\Controllers\RideController::class, 'create']);
    Route::post('/ride/save', [App\Http\Controllers\RideController::class, 'save']);
    Route::any('/ride/list', [App\Http\Controllers\RideController::class, 'list']);
    Route::any('/ride/assign', [App\Http\Controllers\RideController::class, 'assign']);
    Route::any('/ride/list/completed', [App\Http\Controllers\RideController::class, 'rideList']);

    Route::get('/ride/details/{id}', [App\Http\Controllers\RideController::class, 'details']);

    Route::get('/passenger/delete/{id}', [App\Http\Controllers\PassengerController::class, 'delete']);
    Route::get('/ride/delete/{id}', [App\Http\Controllers\RideController::class, 'delete']);

    Route::get('/ride/update/{id}', [App\Http\Controllers\RideController::class, 'create']);

    Route::get('/ajax/ride/{project_id}/{date?}/{status?}/{type?}', [App\Http\Controllers\RideController::class, 'ajaxRide']);

    Route::any('/ride/assign/{ride_id}/{driver_id}/{cab_id}', [App\Http\Controllers\RideController::class, 'assignCab']);


    Route::get('/master/{type}/create', [App\Http\Controllers\MasterController::class, 'create']);
    Route::get('/master/{type}/list', [App\Http\Controllers\MasterController::class, 'list']);
    Route::post('/master/{type}/save', [App\Http\Controllers\MasterController::class, 'save']);
    Route::get('/master/{type}/ajax', [App\Http\Controllers\MasterController::class, 'Ajax']);
});

Route::get('/trip/{type}/{passenger_id}/{link}', [App\Http\Controllers\TripController::class, 'tripDetails']);


Route::get('/app/home/{token}', [App\Http\Controllers\HomeController::class, 'home']);
Route::get('/app/trips/{token}', [App\Http\Controllers\HomeController::class, 'trips']);
Route::get('/app/notification/{token}', [App\Http\Controllers\HomeController::class, 'notification']);
