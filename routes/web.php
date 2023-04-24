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
    return view('welcome');
});

Auth::routes(['register' => false]);
Route::get('/login/auth', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/login/verify', [App\Http\Controllers\Auth\LoginController::class, 'verify']);
Route::group(['middleware' => array('auth', 'access')], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/passenger/create', [App\Http\Controllers\PassengerController::class, 'create']);
    Route::get('/passenger/list', [App\Http\Controllers\PassengerController::class, 'list']);
    Route::get('/project/list', [App\Http\Controllers\MasterController::class, 'projectList']);
    Route::post('/passenger/save', [App\Http\Controllers\PassengerController::class, 'save']);
    Route::get('/ajax/passenger', [App\Http\Controllers\PassengerController::class, 'ajaxPassenger']);
});

Route::get('/trip/{type}/{passenger_id}/{link}', [App\Http\Controllers\TripController::class, 'tripDetails']);


Route::get('/app/home/{token}', [App\Http\Controllers\HomeController::class, 'home']);
Route::get('/app/trips/{token}', [App\Http\Controllers\HomeController::class, 'trips']);
Route::get('/app/notification/{token}', [App\Http\Controllers\HomeController::class, 'notification']);
