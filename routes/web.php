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


use Illuminate\Support\Facades\Storage;

Route::get('/store-data', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'age' => 30
    ];

    $json = json_encode($data);

    Storage::disk('local')->put('data.json', $json);

    return 'Data stored successfully.';
});

//Route::get('/', function () {
//   return redirect('/login');
//});

Route::get('/', [App\Http\Controllers\HomeController::class, 'homea'])->name('home1');

Route::get('/app/notification/{type}', function () {
    return redirect('/dashboard');
});

Route::redirect('/chat/create/{user_type}/{ride_id}/{type}/{user_id}/{request_id}', '/contact-us', 301);

Route::redirect('/chat', '/contact-us', 301);


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
Route::get('/demo', function () {
    return view('guest.demo');
});
Route::get('/faq', function () {
    return view('passenger.faq');
});

Route::get('/blogs', [App\Http\Controllers\HomeController::class, 'blogs']);
Route::get('/blog/{id}/{title}', [App\Http\Controllers\HomeController::class, 'blog']);
Route::any('/ride/track/{id}', [App\Http\Controllers\TripController::class, 'rideLiveTrack']);
Route::any('/app/ping', [App\Http\Controllers\HomeController::class, 'ping']);
Route::post('/passenger/sos', [App\Http\Controllers\HomeController::class, 'passengerSOS']);
Route::post('/passenger/help', [App\Http\Controllers\HomeController::class, 'passengerHelp']);
Route::post('/passenger/ride/cancel', [App\Http\Controllers\HomeController::class, 'rideCancel']);
Route::post('/passenger/booking/cancel', [App\Http\Controllers\HomeController::class, 'bookingCancel']);
Route::post('/passenger/booking/approve', [App\Http\Controllers\HomeController::class, 'bookingApprove']);
Route::get('/passenger/ride/{link}/track', [App\Http\Controllers\HomeController::class, 'rideTrack']);
Route::get('/admin/ride/{link}/track', [App\Http\Controllers\HomeController::class, 'rideTrack']);
Route::get('/ride/track/location/{ride_id}', [App\Http\Controllers\TripController::class, 'rideLocation']);
Route::get('/passenger/ride/rating/{ride_id}/{rating}', [App\Http\Controllers\TripController::class, 'rating']);
Route::get('/date/fetch/{date}/{type}', [App\Http\Controllers\TripController::class, 'dateFetch']);
Route::get('/l/{short}', [App\Http\Controllers\TripController::class, 'shortUrl']);
Route::get('/passenger/ride/{link}', [App\Http\Controllers\HomeController::class, 'passengerRideDetail']);
Route::get('/driver/ride/{link}', [App\Http\Controllers\HomeController::class, 'driverRideDetail']);
Route::get('/driver/app-ride/{id}', [App\Http\Controllers\TripController::class, 'driverAppRideDetail']);
Route::post('/upload/ride/file', [App\Http\Controllers\HomeController::class, 'uploadRideFile']);
Route::get('/ride/detail/{link}', [App\Http\Controllers\HomeController::class, 'RideDetail']);

Route::get('/driver/ride/status/{ride_id}/{status}', [App\Http\Controllers\HomeController::class, 'driverRideStatus']);
Route::get('/casual/ride/status/{ride_id}/{status}', [App\Http\Controllers\HomeController::class, 'casualRideStatus']);


Route::group(['middleware' => array('auth', 'access')], function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::get('/my-rides/{type?}', [App\Http\Controllers\HomeController::class, 'rides'])->name('rides');
    Route::get('/book-ride', [App\Http\Controllers\HomeController::class, 'bookRide'])->name('book-ride');
    Route::post('/ridesave', [App\Http\Controllers\HomeController::class, 'saveRide'])->name('save-ride');
    Route::get('/chats', [App\Http\Controllers\HomeController::class, 'chats'])->name('chats');
    Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
    Route::get('/settings', [App\Http\Controllers\HomeController::class, 'settings'])->name('settings');
    Route::get('/setting/update/{col}/{val}', [App\Http\Controllers\HomeController::class, 'updateSetting']);
    Route::post('/upload/file/{type}', [App\Http\Controllers\HomeController::class, 'uploadFile']);

    Route::post('/profile/save', [App\Http\Controllers\HomeController::class, 'profileSave']);
    Route::get('/calendar', [App\Http\Controllers\HomeController::class, 'calendar']);

    Route::get('/admin/ride/{link}', [App\Http\Controllers\HomeController::class, 'adminRideDetail']);
    Route::get('/admin/ride/assign/{link}', [App\Http\Controllers\HomeController::class, 'adminRideAssign']);

    Route::post('/admin/assign/cab', [App\Http\Controllers\TripController::class, 'assignCab']);

    Route::get('/master/add/{type}', [App\Http\Controllers\MasterController::class, 'masterAdd']);
    Route::post('/master/save/{type}', [App\Http\Controllers\MasterController::class, 'masterSave']);

    Route::get('/ride/passenger/add/{ride_id}/{p_id}/{time}', [App\Http\Controllers\HomeController::class, 'passengerAdd']);
    Route::get('/ride/passenger/remove/{id}', [App\Http\Controllers\HomeController::class, 'passengerRemove']);

    Route::get('/chat/create/{user_type}/{ride_id}/{type}/{user_id}/{request_id}', [App\Http\Controllers\MasterController::class, 'chatCreate']);
    Route::get('/chat/{group_id}', [App\Http\Controllers\MasterController::class, 'chat']);

    Route::get('/ajax/chat/{group_id}', [App\Http\Controllers\MasterController::class, 'chatMessage']);
    Route::post('/ajax/chat/submit', [App\Http\Controllers\MasterController::class, 'chatSubmit']);

    Route::get('/driver/ride/passenger/status/{passenger_id}/{status}', [App\Http\Controllers\HomeController::class, 'driverPassengerRideStatus']);
    Route::get('/driver/ride/passenger/resendotp/{passenger_id}', [App\Http\Controllers\HomeController::class, 'resendOTP']);

    Route::get('/driver/ride/location/status/{type}/{id}/{status}/{lat}/{long}', [App\Http\Controllers\HomeController::class, 'driverLocationRideStatus']);



    Route::get('/whatsapp/{group_id}', [App\Http\Controllers\MasterController::class, 'whatsapp']);
    Route::get('/ajax/whatsapp/{group_id}', [App\Http\Controllers\MasterController::class, 'whatsappMessage']);
    Route::post('/ajax/whatsapp/submit', [App\Http\Controllers\MasterController::class, 'whatsappSubmit']);

    Route::get('/call/{mobile}', [App\Http\Controllers\MasterController::class, 'callIVR']);



    Route::get('staff/dashboard', [App\Http\Controllers\StaffController::class, 'dashboard'])->name('payment_dashboard');
    Route::get('staff/payment/request', [App\Http\Controllers\StaffController::class, 'paymentrequest'])->name('payment_request');
    Route::get('staff/payment/send', [App\Http\Controllers\StaffController::class, 'paymentSend'])->name('payment_send');
    Route::get('staff/payment/pending', [App\Http\Controllers\StaffController::class, 'paymentPending'])->name('payment_pending');
    Route::get('staff/payment/detail/{id}/{ajax?}', [App\Http\Controllers\StaffController::class, 'paymentDetail'])->name('payment_detail');
    Route::post('staff/payment/paymentsave', [App\Http\Controllers\StaffController::class, 'paymentSave'])->name('paymentsave');
    Route::post('staff/payment/requestPaymentSave', [App\Http\Controllers\StaffController::class, 'requestPaymentSave'])->name('paymentsave');
    Route::post('staff/payment/requestsave', [App\Http\Controllers\StaffController::class, 'requestsave'])->name('requestsave');
    Route::any('staff/payment/transactions', [App\Http\Controllers\StaffController::class, 'transactions'])->name('transactions');
    Route::get('staff/transaction/detail/{id}', [App\Http\Controllers\StaffController::class, 'transactionDetail'])->name('transactionDetail');

    Route::get('/whatsapp/getdata/{id}', [App\Http\Controllers\HomeController::class, 'whatsapp'])->name('whatsappgetdata');
    Route::get('/whatsapp', [App\Http\Controllers\HomeController::class, 'whatsapp'])->name('whatsapp');
    Route::get('/whatsapp/{id}', [App\Http\Controllers\HomeController::class, 'whatsappMessage'])->name('whatsappMessage');
    Route::any('/signature/{id}', [App\Http\Controllers\HomeController::class, 'signature'])->name('signature');
});

Route::get('transaction/detail/{id}', [App\Http\Controllers\StaffController::class, 'GuestTransactionDetail'])->name('transactionDetailguest');
Route::get('group/transaction/detail/{id}', [App\Http\Controllers\StaffController::class, 'GuestGroupTransactionDetail'])->name('transactionGroupDetailguest');
Route::any('/facebook/webhook', [App\Http\Controllers\WebhookController::class, 'facebookWebhook'])->name('facebookWebhook');
Route::any('/cashfree/webhook', [App\Http\Controllers\WebhookController::class, 'cashfreeWebhook'])->name('cashfreeWebhook');

Route::any('/notification/trip/detail/{id}', [App\Http\Controllers\WebhookController::class, 'notificationBooking'])->name('notificationBooking');


if (env('APP_ENV') != 'local') {
    URL::forceScheme('https');
}

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
