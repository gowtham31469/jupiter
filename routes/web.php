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

Route::get('admin/template-management', 'Admin\AdminController@templateManagement');
/** User Management **/
Route::get('admin/user-management', 'Admin\UserManagement\UserManagementController@index');
Route::get('admin/user-management/users', 'Admin\UserManagement\UserManagementController@users');
Route::get('admin/user-management/get-user-roles', 'Admin\UserManagement\UserManagementController@getUserRoles');
Route::get('admin/user-management/roles', 'Admin\UserManagement\UserManagementController@roles');
Route::post('admin/user-management/add-jga-user', 'Admin\UserManagement\UserManagementController@addJgaUser');
Route::get('admin/user-management/get-user-details', 'Admin\UserManagement\UserManagementController@getUserById');
Route::put('admin/user-management/update-user-details', 'Admin\UserManagement\UserManagementController@updateUserById');
Route::delete('admin/user-management/delete-user', 'Admin\UserManagement\UserManagementController@deleteUserById');
Route::post('admin/user-management/add-user-role', 'Admin\UserManagement\UserManagementController@addUserRole');
Route::get('admin/user-management/get-user-role-details', 'Admin\UserManagement\UserManagementController@getUserRoleById');
Route::put('admin/user-management/update-user-role', 'Admin\UserManagement\UserManagementController@updateUserRoleById');
Route::delete('admin/user-management/delete-user-role', 'Admin\UserManagement\UserManagementController@deleteUserRoleById');
/** supplier Management **/
Route::get('admin/supplier-management', 'Admin\SupplierManagement\SupplierManagementController@index');
Route::post('admin/supplier-management/suppliers', 'Admin\SupplierManagement\SupplierManagementController@suppliers');
/** buyer Management **/
Route::get('admin/buyer-management', 'Admin\BuyerManagement\BuyerManagementController@index');
Route::get('admin/buyer-management/buyers', 'Admin\BuyerManagement\BuyerManagementController@buyerList');
Route::get('admin/buyer-management/get-buyers', 'Admin\BuyerManagement\BuyerManagementController@getBuyers');
Route::post('admin/buyer-management/add-jga-buyer', 'Admin\BuyerManagement\BuyerManagementController@addJgaBuyer');
Route::get('admin/buyer-management/get-buyer-details', 'Admin\BuyerManagement\BuyerManagementController@getBuyerById');
Route::put('admin/buyer-management/update-buyer-details', 'Admin\BuyerManagement\BuyerManagementController@updateBuyerById');
Route::delete('admin/buyer-management/delete-buyer', 'Admin\BuyerManagement\BuyerManagementController@deleteBuyerById');

Route::get('admin/buyer-management/merchandiser', 'Admin\BuyerManagement\BuyerManagementController@merchandiserList');
Route::post('admin/buyer-management/add-merchandiser', 'Admin\BuyerManagement\BuyerManagementController@addMerchandiser');
Route::get('admin/buyer-management/get-merchandiser-details', 'Admin\BuyerManagement\BuyerManagementController@getMerchandiserById');
Route::put('admin/buyer-management/update-merchandiser-details', 'Admin\BuyerManagement\BuyerManagementController@updateMerchandiserById');
Route::delete('admin/buyer-management/delete-merchandiser', 'Admin\BuyerManagement\BuyerManagementController@deleteMerchandiserById');

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
Route::post('admin/orchestra/order-management/back-sticker-orders', 'Admin\Orchestra\OrderManagementCntrl@backStickerOrders');
Route::post('admin/orchestra/order-management/sticker-orders', 'Admin\Orchestra\OrderManagementCntrl@stickerOrders');
Route::post('admin/orchestra/order-management/label-order-details', 'Admin\Orchestra\OrderManagementCntrl@labelOrderDetails');
Route::post('admin/orchestra/order-management/sticker-order-details', 'Admin\Orchestra\OrderManagementCntrl@stickerOrderDetails');

Route::post('/admin/order-management/order_sub_modules', 'Admin\AdminController@orderSubModules');
Route::post('/admin/template-management/sub_modules', 'Admin\AdminController@templateSubModules');

//Template Management

Route::get('admin/orchestra/template-management', 'Admin\Orchestra\TemplateManagementCntrl@index');
Route::get('admin/tesco/template-management', 'Admin\Tesco\TemplateManagementCntrl@index');
Route::post('admin/orchestra/template-management/create-template-view', 'Admin\Orchestra\TemplateManagementCntrl@orchestraCreateTemplateView');


// Route::get('upload', 'UploadController@index');
Route::post('admin/order-management/upload', ['as'=>'dropzone.store','uses'=>'UploadController@csvUpload']);
// Route::post('dropzone/store', ['as'=>'dropzone.store','uses'=>'HomeController@dropzoneStore']);

Route::post('admin/orchestra/template-management/template-list', 'Admin\Orchestra\TemplateManagementCntrl@templateList');

Route::get('multi-upload', 'UploadController@multiUploadView');
Route::post('admin/order-management/multi-upload', ['as'=>'dropzone.multi-store','uses'=>'UploadController@csvMultiUpload']);
// Route::post('multi-upload', 'UploadController@csvMultiUpload');
