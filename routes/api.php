<?php
use Illuminate\Http\Request;

Route::post('/v2/merchant/payment/status', 'API\PaymentController@status');
Route::post('/v2/einvoice/queue', 'API\PaymentController@einvoiceQueue');

Route::middleware('auth:sanctum','merchantdata')->group(function () {
    Route::post('/getContractApi','ContractController@getContractApi');
});