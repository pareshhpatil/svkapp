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
Route::get('/login/otp/{link}', [App\Http\Controllers\Auth\LoginController::class, 'otp']);
Route::get('/login/otp/resend/{link}', [App\Http\Controllers\Auth\LoginController::class, 'resendotp']);
Route::post('/login/sendotp', [App\Http\Controllers\Auth\LoginController::class, 'sendotp']);
Route::post('/login/validateotp', [App\Http\Controllers\Auth\LoginController::class, 'validateOTP']);
Route::post('/contact/submit', [App\Http\Controllers\HomeController::class, 'contactus']);
Route::get('/contact-us', function () {
    return view('guest.contact');
});
Route::get('/thank-you', function () {
    return view('guest.thankyou');
});

Route::get('/blog/{id}/{title}', [App\Http\Controllers\HomeController::class, 'blog']);


Route::group(['middleware' => array('auth', 'access')], function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::get('/my-rides/{type?}', [App\Http\Controllers\HomeController::class, 'rides'])->name('rides');
    Route::get('/book-ride', [App\Http\Controllers\HomeController::class, 'bookRide'])->name('book-ride');
    Route::post('/ridesave', [App\Http\Controllers\HomeController::class, 'saveRide'])->name('save-ride');
    Route::get('/notifications', [App\Http\Controllers\HomeController::class, 'notifications'])->name('notifications');
    Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
    Route::get('/settings', [App\Http\Controllers\HomeController::class, 'settings'])->name('settings');
    Route::get('/setting/update/{col}/{val}', [App\Http\Controllers\HomeController::class, 'updateSetting']);
    Route::post('/upload/file/{type}', [App\Http\Controllers\HomeController::class, 'uploadFile']);
    Route::post('/profile/save', [App\Http\Controllers\HomeController::class, 'profileSave']);
});
