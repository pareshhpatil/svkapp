<?php

Route::post('/v2/merchant/payment/status', 'API\PaymentController@status');
Route::post('/v2/einvoice/queue', 'API\PaymentController@einvoiceQueue');

Route::middleware('auth:sanctum','merchantdata')->group(function () {
    //Route::post('/getContractApi','ContractController@getContractApi');
    Route::post('/v1/getProjectList','ProjectController@getProjectList');
    Route::get('/v1/getProjectDetails/{project_id}','ProjectController@getProjectDetails');
    Route::post('/v1/createProject','ProjectController@createProject');
    Route::post('/v1/getBillCodesList','ProjectController@getBillCodesList');
    Route::get('/v1/getBillCodeDetails/{billcode_id}','ProjectController@getBillCodeDetails');
    Route::post('/v1/createBillCode','ProjectController@createBillCode');
    Route::post('/v1/updateBillCode','ProjectController@updateBillCode');
    Route::post('/v1/deleteBillCode','ProjectController@deleteBillCode');
    Route::post('/v1/deleteProject','ProjectController@deleteProject');
    Route::post('/v1/updateProject','ProjectController@updateProject');

    Route::post('/v1/createContract','ContractController@createContract');
    Route::post('/v1/updateContract','ContractController@updateContract');
    Route::get('/v1/getContractDetails/{contract_id}','ContractController@getContractDetails');
    Route::post('/v1/getContractList','ContractController@getContractList');
    Route::post('/v1/deleteContract','ContractController@deleteContract');
});