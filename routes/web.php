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

$this->get('admin', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('admin', 'Auth\LoginController@login');
$this->post('admin/logout', 'Auth\LoginController@logout')->name('logout');

$this->get('admin/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('admin/register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('admin/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('admin/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('admin/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('admin/password/reset', 'Auth\ResetPasswordController@reset');

Route::get('admin/dashboard', 'HomeController@index')->name('admin');

Route::get('customer/dashboard', 'Customer\HomeController@index')->name('customer');



Route::get('admin/order-management', 'Admin\AdminController@orderManagement');
Route::get('admin/buyer-management', 'Admin\AdminController@buyerManagement');
Route::get('admin/supplier-management', 'Admin\AdminController@supplierManagement');
Route::get('admin/template-management', 'Admin\AdminController@templateManagement');

Route::get('admin/user-management', 'Admin\UserManagement\UserManagementController@index');
Route::post('admin/user-management/users', 'Admin\UserManagement\UserManagementController@users');
Route::post('admin/user-management/roles', 'Admin\UserManagement\UserManagementController@roles');
Route::post('admin/user-management/add-jga-user', 'Admin\UserManagement\UserManagementController@addJgaUser');
Route::get('admin/user-management/get-user-details', 'Admin\UserManagement\UserManagementController@getUserById');
Route::put('admin/user-management/update-user-details', 'Admin\UserManagement\UserManagementController@updateUserById');
Route::delete('admin/user-management/delete-user', 'Admin\UserManagement\UserManagementController@deleteUserById');

Route::group(['middleware' => 'customer_guest'], function()
{
	Route::get('customer/register', 'CustomerAuth\RegisterController@showRegistrationForm');
	Route::post('customer/register', 'CustomerAuth\RegisterController@register');

	Route::get('customer', 'CustomerAuth\LoginController@showLoginForm');
	Route::post('customer', 'CustomerAuth\LoginController@login');
});

//Only logged in Customers can access or send requests to these pages
Route::group(['middleware' => 'customer_auth'], function()
{
	Route::post('customer/logout', 'CustomerAuth\LoginController@logout');
	Route::get('/customer/dashboard', function(){
	  return view('customer.home');
	});

});


Route::group(['middleware' => 'merchandiser_guest'], function()
{
	Route::get('merchandiser/register', 'MerchandiserAuth\RegisterController@showRegistrationForm');
	Route::post('merchandiser/register', 'MerchandiserAuth\RegisterController@register');
	Route::get('merchandiser', 'MerchandiserAuth\LoginController@showLoginForm');
	Route::post('merchandiser', 'MerchandiserAuth\LoginController@login');
});

Route::group(['middleware' => 'merchandiser_auth'], function()
{
	Route::post('merchandiser/logout', 'MerchandiserAuth\LoginController@logout');
	Route::get('/merchandiser/dashboard', function(){
	  return view('merchandiser.home');
	});

});

//Order Management

Route::get('admin/orchestra/order-management', 'Admin\Orchestra\OrderManagementCntrl@index');
Route::get('admin/tesco/order-management', 'Admin\Tesco\OrderManagementCntrl@index');
Route::post('admin/orchestra/order-management/label-orders', 'Admin\Orchestra\OrderManagementCntrl@labelOrders');
Route::post('admin/orchestra/order-management/back-label-orders', 'Admin\Orchestra\OrderManagementCntrl@backLabelOrders');
Route::post('admin/orchestra/order-management/sticker-orders', 'Admin\Orchestra\OrderManagementCntrl@stickerOrders');
Route::post('admin/orchestra/order-management/order-details', 'Admin\Orchestra\OrderManagementCntrl@orderDetails');

//Template Management

Route::get('admin/orchestra/template-management', 'Admin\Orchestra\TemplateManagementCntrl@index');
Route::get('admin/tesco/template-management', 'Admin\Tesco\TemplateManagementCntrl@index');
Route::post('admin/orchestra/template-management/create-template-view', 'Admin\Orchestra\TemplateManagementCntrl@orchestraCreateTemplateView');


// Route::get('upload', 'UploadController@index');
Route::post('admin/order-management/upload', 'UploadController@csvUpload');
Route::get('multi-upload', 'UploadController@multiUploadView');
Route::post('multi-upload', 'UploadController@csvMultiUpload');

