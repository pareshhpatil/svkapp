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
    Route::get('/passenger/create', [App\Http\Controllers\PassengerController::class, 'create']);
    Route::any('/passenger/list', [App\Http\Controllers\PassengerController::class, 'list']);
    Route::any('/passenger/list/{bulk_id}/{type}', [App\Http\Controllers\PassengerController::class, 'list']);
    Route::get('/passenger/import/format', [App\Http\Controllers\PassengerController::class, 'format']);
    Route::get('/passenger/import', [App\Http\Controllers\PassengerController::class, 'import']);
    Route::post('/passenger/importsave', [App\Http\Controllers\PassengerController::class, 'importsave']);
    Route::get('/project/list', [App\Http\Controllers\MasterController::class, 'projectList']);
    Route::post('/passenger/save', [App\Http\Controllers\PassengerController::class, 'save']);
    Route::get('/ajax/passenger/{project_id}/{type}/{bulk_id}', [App\Http\Controllers\PassengerController::class, 'ajaxPassenger']);
    Route::get('/passenger/import/{type}/{link}', [App\Http\Controllers\PassengerController::class, 'changeStatus']);
    Route::get('/passenger/update/{id}', [App\Http\Controllers\PassengerController::class, 'create']);

    Route::get('/roster/create', [App\Http\Controllers\RosterController::class, 'create']);
    Route::post('/roster/save', [App\Http\Controllers\RosterController::class, 'save']);
    Route::any('/roster/list', [App\Http\Controllers\RosterController::class, 'list']);
    Route::any('/roster/assign', [App\Http\Controllers\RosterController::class, 'assign']);

    Route::get('/ride/details/{id}', [App\Http\Controllers\RideController::class, 'details']);

    Route::get('/passenger/delete/{id}', [App\Http\Controllers\PassengerController::class, 'delete']);
    Route::get('/roster/delete/{id}', [App\Http\Controllers\RosterController::class, 'delete']);

    Route::get('/roster/update/{id}', [App\Http\Controllers\RosterController::class, 'create']);


    Route::get('/ajax/roster/{project_id}/{date?}/{status?}', [App\Http\Controllers\RosterController::class, 'ajaxRoster']);

    Route::any('/roster/assign/{ride_id}/{driver_id}/{cab_id}', [App\Http\Controllers\RosterController::class, 'assignCab']);


    Route::get('/master/{type}/create', [App\Http\Controllers\MasterController::class, 'create']);
    Route::get('/master/{type}/list', [App\Http\Controllers\MasterController::class, 'list']);
    Route::post('/master/{type}/save', [App\Http\Controllers\MasterController::class, 'save']);
    Route::get('/master/{type}/ajax', [App\Http\Controllers\MasterController::class, 'Ajax']);
});

Route::get('/trip/{type}/{passenger_id}/{link}', [App\Http\Controllers\TripController::class, 'tripDetails']);


Route::get('/app/home/{token}', [App\Http\Controllers\HomeController::class, 'home']);
Route::get('/app/trips/{token}', [App\Http\Controllers\HomeController::class, 'trips']);
Route::get('/app/notification/{token}', [App\Http\Controllers\HomeController::class, 'notification']);
