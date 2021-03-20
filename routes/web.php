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



Route::get('/error', 'ErrorController@display');
Route::get('/404', 'ErrorController@pagenotfound');

Route::get('/login', 'LoginController@index');
Route::post('/login/failed', 'LoginController@check');
Route::get('/logout', 'LoginController@logout');
Route::get('/login/expired', 'LoginController@expired');
Route::get('/login/accessdenied', 'LoginController@accessdenied');


//Auth::routes();
Route::get('/admin/dashboard', 'DashboardController@admin');
Route::get('/admin/profile', 'DashboardController@profile');
Route::post('/admin/profilesave', 'DashboardController@profilesave');



Route::get('/employee/dashboard', 'DashboardController@employee');

Route::get('/employee/accessdenied', 'EmployeeController@accessdenied');

#master
Route::get('/admin/{master}/create', 'MasterController@mastercreate');
Route::get('/admin/{master}/list', 'MasterController@masterlist');
Route::get('/admin/{master}/view/{id}', 'MasterController@masterview');
Route::get('/admin/{master}/update/{id}', 'MasterController@masterupdate');
Route::get('/admin/{master}/delete/{id}', 'MasterController@masterdelete');

#Employee
Route::post('/admin/employee/save', 'MasterController@employeesave');
Route::post('/admin/employee/updatesave', 'MasterController@employeeupdatesave');

Route::get('/admin/employee/absent', 'EmployeeController@absent');
Route::get('/admin/employee/advance', 'EmployeeController@advance');
Route::get('/admin/employee/overtime', 'EmployeeController@overtime');
Route::any('/admin/employee/salary', 'EmployeeController@salary');
Route::get('/admin/employee/salarydetail/{id}', 'EmployeeController@salarydetail');

Route::post('/admin/employee/saveabsent', 'EmployeeController@saveabsent');
Route::post('/admin/employee/saveadvance', 'EmployeeController@saveadvance');
Route::post('/admin/employee/saveovertime', 'EmployeeController@saveovertime');

#Company
Route::post('/admin/company/save', 'MasterController@companysave');
Route::post('/admin/company/updatesave', 'MasterController@companyupdatesave');

#Vendor
Route::post('/admin/vendor/save', 'MasterController@vendorsave');
Route::post('/admin/vendor/updatesave', 'MasterController@vendorupdatesave');

#Paymentsource
Route::post('/admin/paymentsource/save', 'MasterController@paymentsourcesave');
Route::post('/admin/paymentsource/updatesave', 'MasterController@paymentsourceupdatesave');

#Vehicle
Route::post('/admin/vehicle/save', 'MasterController@vehiclesave');
Route::post('/admin/vehicle/updatesave', 'MasterController@vehicleupdatesave');

Route::post('/admin/vehicle/savereplacecab', 'VehicleController@savereplacecab');
Route::get('/admin/vehicle/replacecab', 'VehicleController@replacecab');



Route::get('/admin/logsheet', 'LogsheetController@logsheet');
Route::post('/admin/logsheet/confirmlogsheet', 'LogsheetController@confirmlogsheet');
Route::post('/admin/logsheet/savelogsheet', 'LogsheetController@savelogsheet');
Route::any('/admin/logsheet/generatebill/{id?}', 'LogsheetController@generatebill');
Route::get('/admin/logsheet/deletebill/{id}', 'LogsheetController@logsheetdelete');
Route::get('/admin/logsheet/printlogsheet/{id}', 'LogsheetController@printlogsheet');
Route::get('/admin/logsheet/printbill/{id}', 'LogsheetController@printbill');
Route::post('/admin/logsheet/logsheetbillsave', 'LogsheetController@logsheetbillsave');

Route::get('/admin/bill', 'BillController@bill');
Route::get('/admin/bill/new', 'BillController@billcreate');
Route::post('/admin/bill/save', 'BillController@billsave');
Route::post('/admin/bill/paymentsave', 'BillController@paymentsave');
Route::get('/admin/payment/{type}/{id}', 'BillController@billpayment');
Route::get('/admin/transaction', 'BillController@transaction');





