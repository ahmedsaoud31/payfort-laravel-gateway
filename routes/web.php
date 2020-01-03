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
Route::get('/pay', 'PaymentController@index');
Route::get('/', 'PaymentController@index');
Route::post('/ajaxGetFormData', 'PaymentController@ajaxGetFormData');
Route::get('/callback', 'PaymentController@callback');
Auth::routes();

Route::get('/home', function(){
	return redirect('dashboard');
});

Route::group(['prefix' => 'dashboard'], function(){
    Route::get('/', function (){
		$data['title'] = 'Dashboard';
		return View('panel.home', $data);
    })->middleware('auth');
	
	Route::get('/payments', function (){
		$data['title'] = 'Payments';
		return View('panel.payments', $data);
    })->middleware('auth');
	Route::post('/payments/add', 'PaymentDashboardController@add');
	Route::get('/payments/{id}/edit', 'PaymentDashboardController@edit');
	Route::post('/payments/{id}/update', 'PaymentDashboardController@update');
	Route::post('/payments/{id}/delete', 'PaymentDashboardController@delete');
});
