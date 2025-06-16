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

Route::any('/ride/track/{id}', [App\Http\Controllers\RideController::class, 'rideLiveTrack']);
Route::get('/ride/track/location/{ride_id}', [App\Http\Controllers\RideController::class, 'rideLocation']);


Route::group(['middleware' => array('auth', 'access')], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard/{date?}', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
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
    Route::post('/roster/routedownload', [App\Http\Controllers\RosterController::class, 'rosterRouteDownload']);
    Route::post('/roster/MISDownload', [App\Http\Controllers\RosterController::class, 'MISDownload']);


    Route::post('/{type}/importsave', [App\Http\Controllers\PassengerController::class, 'importsave']);
    Route::get('/project/list', [App\Http\Controllers\MasterController::class, 'projectList']);
    Route::post('/passenger/save', [App\Http\Controllers\PassengerController::class, 'save']);
    Route::get('/ajax/passenger/{project_id}/{bulk_id?}/{type?}', [App\Http\Controllers\PassengerController::class, 'ajaxPassenger']);
    Route::get('/passenger/update/{id}', [App\Http\Controllers\PassengerController::class, 'create']);

    Route::get('/ride/create', [App\Http\Controllers\RideController::class, 'create']);
    Route::post('/ride/save', [App\Http\Controllers\RideController::class, 'save']);
    Route::any('/route/list', [App\Http\Controllers\RosterController::class, 'routelist']);
    Route::any('/download/mis', [App\Http\Controllers\RideController::class, 'downloadMIS']);
    Route::any('/ride/assign', [App\Http\Controllers\RideController::class, 'assign']);
    Route::any('/ride/list/completed', [App\Http\Controllers\RideController::class, 'rideList']);
    Route::any('/mis/generate', [App\Http\Controllers\RideController::class, 'generateMis']);
    Route::any('/mis/generate/{id}', [App\Http\Controllers\RideController::class, 'generateRideMis']);
    Route::get('/ajax/mis/generate/{project_id}/{date?}', [App\Http\Controllers\RideController::class, 'ajaxMISGenerate']);

    Route::get('/ride/details/{id}', [App\Http\Controllers\RideController::class, 'details']);

    Route::get('/passenger/delete/{id}', [App\Http\Controllers\PassengerController::class, 'delete']);
    Route::get('/ride/delete/{id}', [App\Http\Controllers\RideController::class, 'delete']);

    Route::get('/ride/update/{id}', [App\Http\Controllers\RideController::class, 'create']);

    Route::get('/ajax/route/{project_id}/{date?}/{status?}/{type?}', [App\Http\Controllers\RosterController::class, 'ajaxRoute']);
    Route::get('/ajax/rides/{project_id}/{date?}/{status?}/{type?}', [App\Http\Controllers\RosterController::class, 'ajaxRides']);

    Route::any('/ride/assign/{ride_id}/{driver_id}/{cab_id}/{escort_id}', [App\Http\Controllers\RideController::class, 'assignCab']);


    Route::get('/ajax/shift/{project_id}/{type}', [App\Http\Controllers\MasterController::class, 'ajaxShift']);



    Route::get('/master/{type}/create', [App\Http\Controllers\MasterController::class, 'create']);
    Route::get('/master/{type}/update/{id}', [App\Http\Controllers\MasterController::class, 'update']);
    Route::get('/master/{type}/list', [App\Http\Controllers\MasterController::class, 'list']);
    Route::post('/master/{type}/save', [App\Http\Controllers\MasterController::class, 'save']);
    Route::get('/master/{type}/ajax/{project_id?}', [App\Http\Controllers\MasterController::class, 'Ajax']);
    Route::get('/master/{type}/delete/{id}/{id_col?}', [App\Http\Controllers\MasterController::class, 'delete']);

    Route::get('/ride/detail/track/{id}', [App\Http\Controllers\RideController::class, 'detailTrack']);
    Route::get('/live/tracking', [App\Http\Controllers\RideController::class, 'liveTracking']);
    Route::get('/live/rides', [App\Http\Controllers\RideController::class, 'liveRides']);
    Route::get('/api/rides', [App\Http\Controllers\RideController::class, 'getLiveRides']);
    Route::get('/api/ride/location/{ride_id}', [App\Http\Controllers\RideController::class, 'getLiveRideLocation']);
    Route::get('/api/ride/updates/{ride_id}', [App\Http\Controllers\RideController::class, 'getRideUpdates']);
    Route::get('/api/ride/passengers/{ride_id}', [App\Http\Controllers\RideController::class, 'getRidePassengers']);

    Route::get('/invoice/create', [App\Http\Controllers\InvoiceController::class, 'create']);
    Route::get('/invoice/list', [App\Http\Controllers\InvoiceController::class, 'list']);
    Route::get('/invoice/delete/{id}', [App\Http\Controllers\InvoiceController::class, 'delete'])->name('invoice.delete');
    Route::post('/invoice/save', [App\Http\Controllers\InvoiceController::class, 'save']);
});

#Route::get('/trip/{type}/{passenger_id}/{link}', [App\Http\Controllers\TripController::class, 'tripDetails']);

Route::any('/ride/track/{id}', [App\Http\Controllers\RideController::class, 'rideLiveTrack'])->middleware('throttle:10,1');

Route::get('/app/home/{token}', [App\Http\Controllers\HomeController::class, 'home']);
Route::get('/app/trips/{token}', [App\Http\Controllers\HomeController::class, 'trips']);
Route::get('/app/notification/{token}', [App\Http\Controllers\HomeController::class, 'notification']);
