<?php

Route::post('/v2/merchant/payment/status', 'API\PaymentController@status');
Route::post('/v2/einvoice/queue', 'API\PaymentController@einvoiceQueue');

