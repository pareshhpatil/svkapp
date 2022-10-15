<?php

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

Route::get('/', 'LoginController@index');

Route::post('/admin/credit/save', 'BillController@savecredit');


Route::get('/error', 'ErrorController@display');
Route::get('/404', 'ErrorController@pagenotfound');

Route::get('/login', 'LoginController@index');
Route::get('/login/getwordmoney/{money}', 'LoginController@getwordmoney');
Route::post('/login/failed', 'LoginController@check');
Route::get('/logout', 'LoginController@logout');
Route::get('/login/expired', 'LoginController@expired');
Route::get('/login/accessdenied', 'LoginController@accessdenied');
Route::get('/cron/subscriptionrun', 'EmployeeController@subscriptionrequest');
//Auth::routes();
Route::get('/admin/dashboard', 'DashboardController@admin');
Route::get('/admin/bankstatement', 'DashboardController@bankstatement');
Route::get('/admin/profile', 'DashboardController@profile');
Route::post('/admin/profilesave', 'DashboardController@profilesave');



Route::get('/employee/dashboard', 'DashboardController@employee');
Route::get('/client/dashboard', 'DashboardController@client');
Route::get('/vendor/dashboard', 'DashboardController@vendor');

Route::get('/employee/accessdenied', 'EmployeeController@accessdenied');


Route::get('/company/dashboard', 'DashboardController@company');

#master
Route::get('/admin/{master}/create', 'MasterController@mastercreate');
Route::get('/admin/{master}/list', 'MasterController@masterlist');
Route::get('/admin/{master}/view/{id}', 'MasterController@masterview');
Route::get('/admin/{master}/update/{id}', 'MasterController@masterupdate');
Route::get('/admin/{master}/delete/{id}', 'MasterController@masterdelete');

#Employee
Route::post('/admin/employee/save', 'MasterController@employeesave');
Route::post('/admin/location/save', 'MasterController@locationsave');
Route::post('/admin/zone/save', 'MasterController@zonesave');

Route::post('/admin/zone/updatesave', 'MasterController@zoneupdatesave');
Route::post('/admin/employee/updatesave', 'MasterController@employeeupdatesave');

Route::get('/admin/employee/absent', 'EmployeeController@absent');
Route::get('/admin/employee/advance', 'EmployeeController@advance');
Route::get('/admin/employee/overtime', 'EmployeeController@overtime');
Route::any('/admin/employee/salary', 'EmployeeController@salary');
Route::any('/admin/employee/subscription', 'EmployeeController@subscription');
Route::any('/admin/employee/subscription/create', 'EmployeeController@subscriptioncreate');
Route::get('/admin/employee/salarydetail/{id}', 'EmployeeController@salarydetail');

Route::post('/admin/employee/saveabsent', 'EmployeeController@saveabsent');
Route::post('/admin/employee/saveadvance', 'EmployeeController@saveadvance');
Route::post('/admin/employee/saveovertime', 'EmployeeController@saveovertime');
Route::post('/admin/employee/subscriptionsave', 'EmployeeController@subscriptionsave');
Route::post('/admin/employee/subscriptionupdatesave', 'EmployeeController@subscriptionupdatesave');

#Company
Route::post('/admin/company/save', 'MasterController@companysave');
Route::post('/admin/company/updatesave', 'MasterController@companyupdatesave');

#Vendor
Route::post('/admin/vendor/save', 'MasterController@vendorsave');
Route::post('/admin/vendor/updatesave', 'MasterController@vendorupdatesave');

#Paymentsource
Route::post('/admin/paymentsource/save', 'MasterController@paymentsourcesave');
Route::post('/admin/paymentsource/updatesave', 'MasterController@paymentsourceupdatesave');

#Paymentsource


#Vehicle
Route::post('/admin/vehicle/save', 'MasterController@vehiclesave');
Route::post('/admin/vehicle/updatesave', 'MasterController@vehicleupdatesave');

Route::post('/admin/vehicle/savereplacecab', 'VehicleController@savereplacecab');
Route::get('/admin/vehicle/replacecab', 'VehicleController@replacecab');



Route::any('/admin/logsheet', 'LogsheetController@logsheet');
Route::any('/admin/logsheet/getlogsheet', 'LogsheetController@getlogsheet');
Route::get('/admin/mis/createmis', 'MisController@create');
Route::any('/admin/mis/listmis', 'MisController@mislist');
Route::any('/admin/mis/listmiscompany', 'MisController@listmiscompany');
Route::post('/admin/mis/confirmmis', 'MisController@confirmmis');
Route::post('/admin/mis/confirmcompanymis', 'MisController@confirmcompanymis');
Route::post('/admin/mis/savemis', 'MisController@savemis');
Route::post('/admin/mis/savecompanymis', 'MisController@savecompanymis');
Route::get('/admin/mis/deletemis/{id}', 'MisController@deletemis');

Route::any('/admin/mis/createcompanymis', 'MisController@createcompanymis');

Route::get('/admin/mis/updatekm/{from_date}/{to_date}', 'MisController@updatekm');

Route::get('/admin/mis/saveemployee/{name}', 'MisController@saveemployee');
Route::get('/admin/mis/savemislocation/{name}/{km}', 'MisController@savemislocation');



Route::any('/admin/logsheet/approve', 'LogsheetController@approve');
Route::post('/admin/logsheet/confirmlogsheet', 'LogsheetController@confirmlogsheet');
Route::post('/admin/logsheet/savelogsheet', 'LogsheetController@savelogsheet');
Route::any('/admin/logsheet/generatebill/{id?}', 'LogsheetController@generatebill');
Route::get('/admin/logsheet/deletebill/{id}', 'LogsheetController@logsheetdelete');
Route::get('/admin/logsheet/printlogsheet/{id}', 'LogsheetController@printlogsheet');
Route::get('/admin/logsheet/printbill/{id}', 'LogsheetController@printbill');
Route::get('/admin/logsheet/downloadbill/{id}', 'LogsheetController@downloadbill');
Route::get('/admin/logsheet/downloadlogsheet/{id}', 'LogsheetController@downloadlogsheet');
Route::get('/admin/logsheet/download/{id}', 'LogsheetController@download');
Route::get('/admin/logsheet/downloadexcel/{id}', 'LogsheetController@downloadexcel');
Route::post('/admin/logsheet/logsheetbillsave', 'LogsheetController@logsheetbillsave');

Route::get('/admin/bill', 'BillController@bill');
Route::get('/admin/bill/new', 'BillController@billcreate');
Route::post('/admin/bill/save', 'BillController@billsave');
Route::post('/admin/bill/paymentsave', 'BillController@paymentsave');
Route::get('/admin/payment/{type}/{id}', 'BillController@billpayment');
Route::get('/admin/transaction', 'BillController@transaction');
Route::get('/admin/request', 'BillController@request');

Route::get('/employee/form', 'EmployeeController@form');
Route::post('/employee/formsave', 'EmployeeController@formsave');

Route::any('/admin/vehicle/fuel', 'VehicleController@fuel');
Route::get('/admin/vehicle/fuellist', 'VehicleController@fuellist');
Route::post('/admin/vehicle/fuelsave', 'VehicleController@fuelsave');

Route::get('/createshorturl', 'TripController@createshorturl');
Route::post('/saveshorturl', 'TripController@saveshorturl');

Route::get('/s/{id}', 'TripController@short');


Route::get('/trip/rating/{id}/{rating}', 'TripController@rating');

Route::get('/trip/schedule/{id}', 'TripController@schedule');
Route::post('/trip/schedulesave', 'TripController@schedulesave');
Route::get('/trip/add', 'TripController@addtrip');

Route::get('/trip/review/{id}', 'TripController@review');
Route::post('/trip/reviewsave', 'TripController@savereview');
Route::post('/trip/save', 'TripController@savetrip');
Route::get('/trip/list/{type}', 'TripController@listtrip');

Route::get('/trip/{id}', 'TripController@trip');

Route::get('/admin/paymentsource/credit', 'BillController@credit');
Route::get('/admin/paymentsource/creditlist', 'BillController@creditlist');
Route::get('/admin/paymentsource/statement/{id}', 'BillController@statement');








