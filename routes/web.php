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

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

$this->get('admin/login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('admin/login', 'Auth\LoginController@login');
$this->post('admin/logout', 'Auth\LoginController@logout')->name('logout');

$this->get('admin/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('admin/register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('admin/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
 $this->post('admin/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('admin/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('admin/password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'customer_guest'], function() 
{
	Route::get('customer/register', 'CustomerAuth\RegisterController@showRegistrationForm');
	Route::post('customer/register', 'CustomerAuth\RegisterController@register');

	Route::get('customer/login', 'CustomerAuth\LoginController@showLoginForm');
	Route::post('customer/login', 'CustomerAuth\LoginController@login');
});

//Only logged in Customers can access or send requests to these pages
Route::group(['middleware' => 'customer_auth'], function()
{

	Route::post('customer/logout', 'CustomerAuth\LoginController@logout');
	Route::get('/customer', function(){
	  return view('customer.home');
	});

});


Route::group(['middleware' => 'merchandiser_guest'], function() 
{
	Route::get('merchandiser/register', 'MerchandiserAuth\RegisterController@showRegistrationForm');
	Route::post('merchandiser/register', 'MerchandiserAuth\RegisterController@register');
	Route::get('merchandiser/login', 'MerchandiserAuth\LoginController@showLoginForm');
	Route::post('merchandiser/login', 'MerchandiserAuth\LoginController@login');
});	

Route::group(['middleware' => 'merchandiser_auth'], function()
{
	Route::post('merchandiser/logout', 'MerchandiserAuth\LoginController@logout');
	Route::get('/merchandiser', function(){
	  return view('merchandiser.home');
	});

});

Route::get('upload', 'UploadController@index');
Route::post('upload', 'UploadController@csvUpload');
