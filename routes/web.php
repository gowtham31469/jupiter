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


Route::get('ostatusT', function () {
    return view('orderStatus_T');
});
Route::get('ostatusO', function () {
    return view('orderStatus_O');
});
Route::get('getOrderStatusesTesco', ['as'=>'getOrderStatusesTesco','uses'=>'ApiTest@getOrderStatusesTesco']);
Route::get('getOrderStatusesOrch', ['as'=>'getOrderStatusesOrch','uses'=>'ApiTest@getOrderStatusesOrch']);


Route::get('testApi', 'ApiTest@testApi')->name('testApi');


Route::get('getTemplateDetails', 'Admin\TemplateManagement\TemplateController@getTemplateDetails')->name('getTemplateDetails');

Route::get('updatePath', 'Admin\TemplateManagement\TemplateController@updatePath')->name('updatePath');

Route::get('delete_now', function () {
    // App\OrchestraTemplate::truncate();
    // App\OrchestraTemplate::where('id','>',0)
    //     ->update(['template_file' => '/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB1_WOVEN_DISNEYBABY@DISNEY_M.L.png']);
    dd(App\OrchestraTemplate::where('item_id',60)->first());
});


// Auth::routes();

$this->get('admin', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('admin', 'Auth\LoginController@login');
$this->post('admin/logout', 'Auth\LoginController@logout')->name('logout');

$this->get('admin/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('admin/register', 'Auth\RegisterController@register');

// Password Reset Routes for Admin...
$this->get('admin/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('admin/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('admin/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('admin/password/reset', 'Auth\ResetPasswordController@reset');

Route::get('admin/dashboard', 'HomeController@index')->name('admin');




Route::get('admin/order-management', 'Admin\OrderManagement\OrderController@dashboard');

Route::post('admin/order-management/new_orders', 'Admin\OrderManagement\DashboardController@newOrders');
Route::post('admin/order-management/aowp', 'Admin\OrderManagement\DashboardController@aowp');
Route::post('admin/order-management/approved_orders', 'Admin\OrderManagement\DashboardController@approvedOrders');
Route::post('admin/order-management/pending_orders', 'Admin\OrderManagement\DashboardController@pendingOrders');
Route::post('admin/order-management/viewed_pen_production', 'Admin\OrderManagement\DashboardController@viewedPenProduction');

Route::post('admin/order-management/viewed_pen_production_all', 'Admin\OrderManagement\DashboardController@viewedPenProductionAll');

Route::post('admin/order-management/viewedneworders', 'Admin\OrderManagement\DashboardController@viewedNewOrders');

Route::post('admin/order-management/viewedneworders_all', 'Admin\OrderManagement\DashboardController@viewedNewOrdersAll');



Route::post('admin/order-management/viewedpenorders', 'Admin\OrderManagement\DashboardController@viewedPenOrders');

Route::post('admin/order-management/viewedpenorders_all', 'Admin\OrderManagement\DashboardController@viewedPenOrdersAll');

Route::post('admin/order-management/viewedapporders_all', 'Admin\OrderManagement\DashboardController@viewedAppOrdersAll');

Route::post('admin/order-management/viewedapporders', 'Admin\OrderManagement\DashboardController@viewedAppOrders');

Route::post('admin/order-management/viewedaowp', 'Admin\OrderManagement\DashboardController@viewedAOWP');

Route::post('admin/order-management/viewedaowp_all', 'Admin\OrderManagement\DashboardController@viewedAOWPAll');

Route::get('admin/order-management/orders', 'Admin\OrderManagement\OrderController@orders');
Route::get('admin/order-management/order-status', 'Admin\OrderManagement\OrderController@orderStatus');
Route::get('admin/order-management/non-artwork-orders', 'Admin\OrderManagement\OrderController@nonArtworkOrders');
Route::get('admin/order-management/reorder', 'Admin\OrderManagement\OrderController@reorder');
Route::post('admin/orchestra/order-management/compositions-details', 'Admin\Orchestra\OrderManagementCntrl@compositionDetails');

Route::post('admin/orchestra/order-management/po_select_wcl', 'Admin\Orchestra\OrderManagementCntrl@poSelectWcl');

Route::post('admin/order-management/print-pdf', 'Admin\OrderManagement\OrderController@printPdf');
Route::post('admin/order-management/print-pdf-arr', 'Admin\OrderManagement\OrderController@printPdfArr');
Route::post('admin/order-management/print-pdf-small', 'Admin\OrderManagement\OrderController@printPdfSmall');
Route::post('admin/order-management/print-pdf-table', 'Admin\OrderManagement\OrderController@printPdfTable');


Route::post('admin/order-management/proof-pdf', 'Admin\OrderManagement\OrderController@proofPdf');
Route::post('admin/order-management/proof-pdf-arr', 'Admin\OrderManagement\OrderController@proofPdfArr');
Route::post('admin/order-management/proof-pdf-small', 'Admin\OrderManagement\OrderController@proofPdfSmall');
Route::post('admin/order-management/proof-pdf-table', 'Admin\OrderManagement\OrderController@proofPdfTable');

Route::post('admin/order-management/print-hang-pdf', 'Admin\OrderManagement\OrderController@printHangPdf');
Route::post('admin/order-management/print-special-pdf', 'Admin\OrderManagement\OrderController@printSpecialPdf');

Route::get('admin/order-management/get-tsl-size', 'Admin\OrderManagement\OrderController@getTescoTSLSizechart');

Route::post('admin/order-management/print-wash-pdf', 'Admin\OrderManagement\OrderController@printWashPdf');

Route::get('admin/template-management', 'Admin\TemplateManagement\TemplateController@templateList');
Route::get('admin/tesco/template-details', 'Admin\TemplateManagement\TemplateController@getTemplateNotes');
Route::post('admin/tesco/update-notes', 'Admin\TemplateManagement\TemplateController@setTemplateNotes');

Route::post('admin/template-management/template-hierarchy', 'Admin\TemplateManagement\TemplateController@templateHierarchy');


Route::post('admin/template-management/save-taxation', 'Admin\TemplateManagement\TemplateController@saveTax');

Route::post('admin/template-management/add-template', 'Admin\TemplateManagement\TemplateController@addTemplate');

Route::post('admin/template-management/template-view', 'Admin\TemplateManagement\TemplateController@templateView');
Route::get('admin/template-management/get-company-list', 'Admin\TemplateManagement\TemplateController@getCompanyList');

Route::post('admin/orchestra/template-management/template-list-view', 'Admin\Orchestra\TemplateManagementCntrl@templateListView');

Route::post('admin/orchestra/template-management/template-view', 'Admin\Orchestra\TemplateManagementCntrl@templateView');

Route::post('/admin/template-management/base64_to_jpeg', 'Admin\TemplateManagement\TemplateController@base64ToJpeg');
/** customer Management **/
Route::get('admin/customer-management', 'Admin\CustomerManagement\CustomerManagementController@index');
Route::post('admin/customer-management/customers-list', 'Admin\CustomerManagement\CustomerManagementController@customersList');
Route::post('admin/customer-management/add-customer', 'Admin\CustomerManagement\CustomerManagementController@addCustomer');
Route::put('admin/customer-management/make-customer-active', 'Admin\CustomerManagement\CustomerManagementController@makeCustomerActive');
Route::put('admin/customer-management/make-customer-inactive', 'Admin\CustomerManagement\CustomerManagementController@makeCustomerInactive');
Route::get('admin/customer-management/get-customers', 'Admin\CustomerManagement\CustomerManagementController@getCustomerById');
Route::put('admin/customer-management/update-customer', 'Admin\CustomerManagement\CustomerManagementController@updateCustomer');

Route::get('admin/customer-management/company', 'Admin\CustomerManagement\CustomerManagementController@customers');
Route::get('admin/customer-management/pricing-taxation','Admin\CustomerManagement\CustomerManagementController@custumerPriceList');
Route::get('admin/customer-management/get-buyers-customers','Admin\CustomerManagement\CustomerManagementController@getBuyersCustomers');
Route::post('admin/customer-management/save-price-tax', 'Admin\CustomerManagement\CustomerManagementController@savePriceTax');
Route::get('admin/customer-management/get-price-tax', 'Admin\CustomerManagement\CustomerManagementController@getPriceTax');
Route::post('admin/tesco/customer-management/template-view', 'Admin\CustomerManagement\CustomerManagementController@templateView');
Route::get('admin/customer-management/get-customer-details', 'Admin\CustomerManagement\CustomerManagementController@getCustomerDetailsById');

Route::get('admin/customer-management/customers', 'Admin\CustomerManagement\CustomerManagementController@customerProfile');


Route::put('admin/customer-management/make-customer-profile-active', 'Admin\CustomerManagement\CustomerManagementController@makeCustomerProfileActive');
Route::put('admin/customer-management/make-customer-profile-inactive', 'Admin\CustomerManagement\CustomerManagementController@makeCustomerProfileInactive');
Route::get('admin/customer-management/get-customers-profile', 'Admin\CustomerManagement\CustomerManagementController@getCustomerProfileById');

Route::put('admin/customer-management/update-customerProfile', 'Admin\CustomerManagement\CustomerManagementController@updateCustomerProfile');

Route::post('admin/customer-management/add-customerProfile', 'Admin\CustomerManagement\CustomerManagementController@addCustomerProfile');

Route::post('admin/customer-management/customer-company-buyer', 'Admin\CustomerManagement\CustomerManagementController@customerCompanyBuyer');

/** buyer Management **/
Route::get('admin/buyer-management', 'Admin\BuyerManagement\BuyerManagementController@index');
Route::get('admin/buyer-management/buyers-list', 'Admin\BuyerManagement\BuyerManagementController@buyerList');
Route::post('admin/buyer-management/add-jga-buyer', 'Admin\BuyerManagement\BuyerManagementController@addJgaBuyer');
Route::get('admin/buyer-management/get-buyer-details', 'Admin\BuyerManagement\BuyerManagementController@getBuyerById');
Route::post('admin/buyer-management/update-buyer-details', 'Admin\BuyerManagement\BuyerManagementController@updateBuyerById');
Route::put('admin/buyer-management/make-buyer-active', 'Admin\BuyerManagement\BuyerManagementController@makeBuyerActive');
Route::put('admin/buyer-management/make-buyer-inactive', 'Admin\BuyerManagement\BuyerManagementController@makeBuyerInactive');
Route::get('admin/buyer-management/buyers', 'Admin\BuyerManagement\BuyerManagementController@buyers');

Route::get('admin/buyer-management/merchandiser-list', 'Admin\BuyerManagement\BuyerManagementController@merchandiserList');
Route::post('admin/buyer-management/add-merchandiser', 'Admin\BuyerManagement\BuyerManagementController@addMerchandiser');
Route::get('admin/buyer-management/get-merchandiser-details', 'Admin\BuyerManagement\BuyerManagementController@getMerchandiserById');
Route::put('admin/buyer-management/update-merchandiser-details', 'Admin\BuyerManagement\BuyerManagementController@updateMerchandiserById');
Route::put('admin/buyer-management/make-merchandiser-active', 'Admin\BuyerManagement\BuyerManagementController@makeMerchandiserActive');
Route::put('admin/buyer-management/make-merchandiser-inactive', 'Admin\BuyerManagement\BuyerManagementController@makeMerchandiserInactive');
Route::get('admin/buyer-management/merchandisers', 'Admin\BuyerManagement\BuyerManagementController@merchandisers');

Route::group(['middleware' => 'customer_guest'], function()
{
	Route::get('customer/register', 'CustomerAuth\RegisterController@showRegistrationForm');
	Route::post('customer/register', 'CustomerAuth\RegisterController@register');

	Route::get('customer', 'CustomerAuth\LoginController@showLoginForm')->name('customer.login');
	Route::post('customer', 'CustomerAuth\LoginController@login');
	// Password Reset Routes for customer...
	Route::get('customer/password/reset', 'CustomerAuth\ForgotPasswordController@showLinkRequestForm')->name('customer.password.request');
	Route::post('customer/password/email', 'CustomerAuth\ForgotPasswordController@sendResetLinkEmail')->name('customer.password.email');
	Route::get('customer/password/reset/{token}', 'CustomerAuth\ResetPasswordController@showResetForm')->name('customer.password.reset');
	Route::post('customer/password/reset', 'CustomerAuth\ResetPasswordController@reset');
});

//Only logged in Customers can access or send requests to these pages
Route::group(['middleware' => 'customer_auth'], function()
{
	Route::post('customer/logout', 'CustomerAuth\LoginController@logout')->name('customer.logout');
	Route::get('customer/dashboard', 'Customer\HomeController@index')->name('customer');
	Route::get('customer/order-status', 'Customer\HomeController@customerOrderStatus');
	Route::get('customer/create-order', 'Customer\HomeController@customerOrders');

	Route::post('customer/tesco/order-management/approved-orders', 'Customer\HomeController@getApprovedOrdersList');
	Route::post('customer/tesco/order-management/viewed-approved', 'Customer\HomeController@viewedApprovedOrder');

	Route::post('customer/tesco/order-management/viewed-selected-approved', 'Customer\HomeController@viewedSelectedApprovedOrder');

	Route::post('customer/tesco/order-management/pending-orders', 'Customer\HomeController@getPendingOrdersList');

	Route::post('customer/tesco/order-management/viewed-pending', 'Customer\HomeController@viewedPendingOrder');

	Route::post('customer/tesco/order-management/viewed-selected-pending', 'Customer\HomeController@viewedSelectedPendingOrder');

	Route::post('customer/tesco/order-management/cancelled-orders', 'Customer\HomeController@getCancelledOrdersList');
	Route::post('customer/tesco/order-management/viewed-cancelled', 'Customer\HomeController@viewedCancelledOrder');
	Route::post('customer/tesco/order-management/viewed-selected-cancelled', 'Customer\HomeController@viewedSelectedCancelledOrder');
	
	//ORCHESTRA STATUS
	Route::post('customer/orchestra/order-status', 'Customer\Orchestra\OrderController@orchestraOrderStatus');
	Route::post('customer/orchestra/order-management/show-orchestra-pi', 'Customer\Orchestra\OrderController@getOrchestraPI');
	Route::post('customer/orchestra/approve-orchestra-pi', 'Customer\Orchestra\OrderController@approveOrchestraPI');
	Route::post('customer/orchestra/reject-orchestra-pi', 'Customer\Orchestra\OrderController@rejectOrchestraPI');
	Route::post('customer/orchestra/download-orchestra-pi', 'Customer\Orchestra\OrderController@downloadOrchestraPI');

	//TESCO STATUS
	Route::get('customer/tesco/create-order', 'Customer\Tesco\OrderController@createOrder');
	
	Route::get('customer/customers', 'Customer\CustomerController@customers');
	
	
	Route::put('customer/customer-management/make-customer-profile-active', 'Customer\CustomerController@makeCustomerProfileActive');
	Route::put('customer/customer-management/make-customer-profile-inactive', 'Customer\CustomerController@makeCustomerProfileInactive');
	Route::get('customer/customer-management/get-customers-profile', 'Customer\CustomerController@getCustomerProfileById');

	Route::put('customer/customer-management/update-customerProfile', 'Customer\CustomerController@updateCustomerProfile');

	Route::post('customer/customer-management/add-customerProfile', 'Customer\CustomerController@addCustomerProfile');

	Route::post('customer/customer-management/customer-company-buyer', 'Customer\CustomerController@customerCompanyBuyer');
	

	//TESCO VIEW ORDER
	Route::post('customer/tesco/view-order', 'Customer\Tesco\OrderController@viewOrders');

	//TESCO ORDER STATUS
	Route::post('customer/tesco/tesco-orders', 'Customer\Tesco\OrderStatusController@index');
	Route::post('customer/tesco/order-status', 'Customer\Tesco\OrderStatusController@index');
	Route::post('customer/tesco/order-management/set-po-number', 'Customer\Tesco\OrderStatusController@setPONumber');
	Route::post('customer/tesco/order-management/show-tesco-pi', 'Customer\Tesco\OrderStatusController@getTescoPI');
	Route::post('customer/tesco/approve-tesco-pi', 'Customer\Tesco\OrderStatusController@approveTescoPI');
	Route::post('customer/tesco/reject-tesco-pi', 'Customer\Tesco\OrderStatusController@rejectTescoPI');
	Route::post('customer/tesco/edit-artwork', 'Customer\Tesco\OrderStatusController@editArtwork');
	
	Route::post('customer/orchestra/edit-artwork', 'Customer\Orchestra\OrderStatusCntrl@editArtwork');
	
	Route::get('customer/tesco/get-artworks', 'Customer\Tesco\OrderStatusController@getArtwork');
	Route::post('customer/tesco/update-po', 'Customer\Tesco\OrderStatusController@updateArtworkPO');
	Route::post('customer/tesco/copy-po', 'Customer\Tesco\OrderStatusController@copyArtworkPO');
	Route::post('customer/tesco/get-po-file', 'Customer\Tesco\OrderStatusController@getPOFile');
	Route::post('customer/tesco/download-tesco-pi', 'Customer\Tesco\OrderStatusController@downloadTescoPI');

	Route::post('customer/tesco/update-delivery-date', 'Customer\Tesco\OrderStatusController@updateDeliveryDate');
	Route::post('customer/tesco/set-tesco-order-status', 'Customer\Tesco\OrderStatusController@setTescoOrderStatus');
	Route::post('customer/tesco/set-po-file', 'Customer\Tesco\OrderStatusController@setCustomerPOFile');
	Route::post('customer/tesco/get-order-details', 'Customer\Tesco\OrderStatusController@getOrderDetails');
	Route::get('customer/tesco/get-erp-status', 'Customer\Tesco\OrderStatusController@checkERPStatus');
	Route::post('customer/tesco/order-management/orders', 'Customer\Tesco\OrderController@Orders');
	Route::get('customer/tesco/templates-details', 'Customer\Tesco\OrderController@getTescoTemplateDetails');
	Route::post('customer/tesco/template-view', 'Customer\Tesco\OrderController@templateView');
	Route::post('customer/tesco/po-upload', 'Customer\Tesco\OrderController@createStickerOrders');
    Route::get('customer/tesco/get-tsl-size', 'Customer\Tesco\OrderController@getTescoTSLSizechart');
    Route::post('customer/tesco/set-ce-ratio-template-orders', 'Customer\Tesco\OrderController@setCERatioOrders');
	Route::post('customer/tesco/set-ce-single-template-orders', 'Customer\Tesco\OrderController@setCESingleOrders');

	Route::post('customer/tesco/set-uk-ratio-template-orders', 'Customer\Tesco\OrderController@setUKRatioOrders');
	Route::post('customer/tesco/set-uk-single-template-orders', 'Customer\Tesco\OrderController@setUKSingleOrders');
	Route::post('customer/tesco/upload-tesco-combined-po-file', 'Customer\Tesco\OrderController@uploadCombinedOrdersPO');

	Route::put('customer/tesco/save-ce-ratio-template-orders', 'Customer\Tesco\OrderController@saveCERatioOrders');
	Route::put('customer/tesco/save-ce-single-template-orders', 'Customer\Tesco\OrderController@saveCESingleOrders');

	Route::put('customer/tesco/save-uk-ratio-template-orders', 'Customer\Tesco\OrderController@saveUKRatioOrders');
	Route::put('customer/tesco/save-uk-single-template-orders', 'Customer\Tesco\OrderController@saveUKSingleOrders');
	Route::put('customer/tesco/approve-tesco-order', 'Customer\Tesco\OrderController@approveTescoOrder');
	Route::put('customer/tesco/cancel-tesco-order', 'Customer\Tesco\OrderController@cancelTescoOrder');
    
    /** customer profile related routes */
    Route::put('customer/update-customer-password', 'Customer\CustomerProfile\CustomerProfileController@updateCustomerPassword');
    Route::get('customer/get-profile-details', 'Customer\CustomerProfile\CustomerProfileController@getCustomerDetailsById');
	Route::put('customer/update-customer-profile-details', 'Customer\CustomerProfile\CustomerProfileController@updateCustomerDetailsById');
	
	
	
	Route::post('customer/order-management/print-pdf', 'Customer\OrderManagement\OrderController@printPdf');
	Route::post('customer/order-management/print-pdf-arr', 'Customer\OrderManagement\OrderController@printPdfArr');
	Route::post('customer/order-management/print-pdf-small', 'Customer\OrderManagement\OrderController@printPdfSmall');
	Route::post('customer/order-management/print-pdf-table', 'Customer\OrderManagement\OrderController@printPdfTable');
	Route::post('customer/order-management/print-hang-pdf', 'Customer\OrderManagement\OrderController@printHangPdf');
	Route::post('customer/order-management/print-special-pdf', 'Customer\OrderManagement\OrderController@printSpecialPdf');
	Route::post('customer/order-management/print-wash-pdf', 'Customer\OrderManagement\OrderController@printWashPdf');
	Route::get('customer/order-management/get-tsl-size', 'Customer\OrderManagement\OrderController@getTescoTSLSizechart');
	
});


Route::group(['middleware' => 'merchandiser_guest'], function()
{
	Route::get('merchandiser/register', 'MerchandiserAuth\RegisterController@showRegistrationForm');
	Route::post('merchandiser/register', 'MerchandiserAuth\RegisterController@register');
	Route::get('merchandiser', 'MerchandiserAuth\LoginController@showLoginForm')->name('merchandiser.login');
	Route::post('merchandiser', 'MerchandiserAuth\LoginController@login');

	// Password Reset Routes for merchandiser...
	Route::get('merchandiser/password/reset', 'MerchandiserAuth\ForgotPasswordController@showLinkRequestForm')->name('merchandiser.password.request');
	Route::post('merchandiser/password/email', 'MerchandiserAuth\ForgotPasswordController@sendResetLinkEmail')->name('merchandiser.password.email');
	Route::get('merchandiser/password/reset/{token}', 'MerchandiserAuth\ResetPasswordController@showResetForm')->name('merchandiser.password.reset');
	Route::post('merchandiser/password/reset', 'MerchandiserAuth\ResetPasswordController@reset');
});

Route::group(['middleware' => 'merchandiser_auth'], function()
{
	Route::post('merchandiser/logout', 'MerchandiserAuth\LoginController@logout')->name('merchandiser.logout');
	Route::get('/merchandiser/pending-orders', 'Merchandiser\HomeController@index');
	Route::get('/merchandiser/get-wcl-details', 'Merchandiser\HomeController@getWCLById');
	Route::post('/merchandiser/set-wcl-merchandiser-approve-comment', 'Merchandiser\HomeController@addWCLApproveCommentById');
	Route::post('/merchandiser/set-wcl-merchandiser-reject-comment', 'Merchandiser\HomeController@addWCLRejectCommentById');
	Route::get('/merchandiser/past-orders', 'Merchandiser\HomeController@pastOrders');
	/** Merchandiser profile related routes */
	Route::get('merchandiser/get-profile-details', 'Merchandiser\MerchandiserProfileController@getMerchandiserDetailsById');
	Route::put('merchandiser/update-merchandiser-profile-details', 'Merchandiser\MerchandiserProfileController@updateMerchandiserById');
	Route::put('merchandiser/update-merchandiser-password', 'Merchandiser\MerchandiserProfileController@updateMerchandiserPassword');

});

//Orchestra Order Management - Admin

Route::get('admin/orchestra/order-management', 'Admin\Orchestra\OrderManagementCntrl@index');
Route::post('admin/orchestra/order-management/label-orders', 'Admin\Orchestra\OrderManagementCntrl@labelOrders');
Route::post('admin/orchestra/order-management/reorder', 'Admin\Orchestra\OrderManagementCntrl@reOrder');
Route::post('admin/orchestra/order-management/reorder-artwork', 'Admin\Orchestra\OrderManagementCntrl@reOrderArtwork');
Route::post('admin/orchestra/order-management/extra-quantity', 'Admin\Orchestra\OrderManagementCntrl@extraQuantity');
Route::post('admin/orchestra/order-management/back-label-orders', 'Admin\Orchestra\OrderManagementCntrl@backLabelOrders');
Route::post('admin/orchestra/order-management/back-sticker-orders', 'Admin\Orchestra\OrderManagementCntrl@backStickerOrders');
Route::post('admin/orchestra/order-management/sticker-orders', 'Admin\Orchestra\OrderManagementCntrl@stickerOrders');
Route::post('admin/orchestra/order-management/label-order-details', 'Admin\Orchestra\OrderManagementCntrl@labelOrderDetails');
Route::post('admin/orchestra/order-management/sticker-order-details', 'Admin\Orchestra\OrderManagementCntrl@stickerOrderDetails');

Route::post('admin/orchestra/order-management/orders', 'Admin\Orchestra\OrderManagementCntrl@Orders');
Route::post('admin/orchestra/order-management/order-status', 'Admin\Orchestra\OrderManagementCntrl@orderStatus');


Route::post('admin/orchestra/order-management/set-po-number', 'Admin\Orchestra\OrderManagementCntrl@setPONumber');
Route::post('admin/orchestra/order-management/show-orchestra-pi', 'Admin\Orchestra\OrderManagementCntrl@getOrchestraPI');
Route::post('admin/orchestra/set-po-file', 'Admin\Orchestra\OrderManagementCntrl@setCustomerPOFile');
Route::post('admin/orchestra/approve-orchestra-pi', 'Admin\Orchestra\OrderManagementCntrl@approveOrchestraPI');
Route::post('admin/orchestra/reject-orchestra-pi', 'Admin\Orchestra\OrderManagementCntrl@rejectOrchestraPI');
Route::post('admin/orchestra/download-orchestra-pi', 'Admin\Orchestra\OrderManagementCntrl@downloadOrchestraPI');

Route::post('admin/orchestra/order-management/set_hang_tag', 'Admin\Orchestra\OrderManagementCntrl@setHangTag');

Route::post('admin/orchestra/order-management/remove_hang_tag', 'Admin\Orchestra\OrderManagementCntrl@removeHangTag');
Route::post('admin/orchestra/order-management/remove_special_tag', 'Admin\Orchestra\OrderManagementCntrl@removeSpecialTag');
Route::post('admin/orchestra/order-management/remove_heat_transfer', 'Admin\Orchestra\OrderManagementCntrl@removeHeatTransfer');
Route::post('admin/orchestra/order-management/remove_main_label', 'Admin\Orchestra\OrderManagementCntrl@removeMainLabel');
Route::post('admin/orchestra/order-management/remove_size_label', 'Admin\Orchestra\OrderManagementCntrl@removeSizeLabel');

Route::post('admin/orchestra/order-management/remove_standard_stickers', 'Admin\Orchestra\OrderManagementCntrl@removeStandardStickers');
Route::post('admin/orchestra/order-management/remove_solid_stickers', 'Admin\Orchestra\OrderManagementCntrl@removeSolidStickers');


Route::post('admin/orchestra/order-management/remove_wcl', 'Admin\Orchestra\OrderManagementCntrl@removeWCL');

Route::post('admin/orchestra/order-management/set_heat_transfer', 'Admin\Orchestra\OrderManagementCntrl@setHeatTransfer');
Route::post('admin/orchestra/order-management/set_special_tag', 'Admin\Orchestra\OrderManagementCntrl@setSpecialTag');
Route::post('admin/orchestra/order-management/set_main_label', 'Admin\Orchestra\OrderManagementCntrl@setMainLabel');
Route::post('admin/orchestra/order-management/set_size_label', 'Admin\Orchestra\OrderManagementCntrl@setSizeLabel');
Route::post('admin/orchestra/order-management/remove_wash_label', 'Admin\Orchestra\OrderManagementCntrl@removeWashLabel');
Route::post('admin/orchestra/order-management/set_wash_label', 'Admin\Orchestra\OrderManagementCntrl@setWashLabel');
Route::post('admin/orchestra/order-management/set_wash_labelos', 'Admin\Orchestra\OrderManagementCntrl@setWashLabelOs');
Route::post('admin/orchestra/order-management/set_solid_stickers', 'Admin\Orchestra\OrderManagementCntrl@setSolidStickers');
Route::post('admin/orchestra/order-management/set_solid_stickers_os', 'Admin\Orchestra\OrderManagementCntrl@setSolidStickersOs');
Route::post('admin/orchestra/order-management/set_solid_poly_bag_stickers_os', 'Admin\Orchestra\OrderManagementCntrl@setSolidPolyBagStickersOs');
Route::post('admin/orchestra/order-management/set_standard_carton_stickers_os', 'Admin\Orchestra\OrderManagementCntrl@setStandardCartonStickersOs');
Route::post('admin/orchestra/order-management/set_standard_poly_bag_stickers_os', 'Admin\Orchestra\OrderManagementCntrl@setStandardPolyBagStickersOs');
Route::post('admin/orchestra/order-management/set_standard_stickers', 'Admin\Orchestra\OrderManagementCntrl@setStandardStickers');

Route::get('admin/orchestra/order-management/get-existing-label-orders-po', 'Admin\Orchestra\OrderManagementCntrl@getExistingLabelOrdersPo');
Route::get('admin/orchestra/order-management/get-existing-standard-sticker-orders-po', 'Admin\Orchestra\OrderManagementCntrl@getExistingStandardStickerOrdersPo');
Route::get('admin/orchestra/order-management/get-existing-solid-sticker-orders-po', 'Admin\Orchestra\OrderManagementCntrl@getExistingSolidStickerOrdersPo');
Route::get('admin/orchestra/order-management/get-existing-label-orders-status-po', 'Admin\Orchestra\OrderManagementCntrl@getExistingLabelOrderStatusPo');

Route::Post('admin/orchestra/order-management/map-label-orders-po', 'Admin\Orchestra\OrderManagementCntrl@mapLabelOrdersPo');
Route::Post('admin/orchestra/order-management/update-label-orders-po', 'Admin\Orchestra\OrderManagementCntrl@updateLabelOrdersPO');
Route::get('admin/orchestra/template-management/get-company-list', 'Admin\Orchestra\TemplateManagementCntrl@getCompanyList');
Route::get('admin/orchestra/get-erp-status', 'Admin\Orchestra\OrderManagementCntrl@checkERPStatus');

Route::post('/admin/order-management/order_sub_modules', 'Admin\AdminController@orderSubModules');
Route::post('/admin/template-management/sub_modules', 'Admin\AdminController@templateSubModules');
/** Tesco order-management**/
Route::get('admin/tesco/order-management', 'Admin\Tesco\OrderManagementCntrl@index');
Route::post('admin/tesco/template-management/template-view', 'Admin\Tesco\TemplateManagementCntrl@templateView');
Route::post('admin/tesco/template-management/save-taxation', 'Admin\Tesco\TemplateManagementCntrl@saveTax');

Route::post('admin/orchestra/template-management/save-taxation', 'Admin\Orchestra\TemplateManagementCntrl@saveTax');

Route::get('admin/tesco/template-management/get-company', 'Admin\Tesco\TemplateManagementCntrl@getCompany');
Route::post('admin/tesco/order-management/orders', 'Admin\Tesco\OrderManagementCntrl@Orders');
Route::get('admin/tesco/template-management', 'Admin\Tesco\TemplateManagementCntrl@index');
Route::get('admin/tesco/templates-details', 'Admin\Tesco\OrderManagementCntrl@getTescoTemplateDetails');
Route::post('admin/tesco/po-upload', 'Admin\Tesco\OrderManagementCntrl@createStickerOrders');
Route::post('admin/tesco/view-order', 'Admin\Tesco\OrderManagementCntrl@viewOrders');
Route::get('admin/tesco/get-tsl-size', 'Admin\Tesco\OrderManagementCntrl@getTescoTSLSizechart');
Route::get('admin/tesco/get-moq-value', 'Admin\Tesco\OrderManagementCntrl@getTescoTemplateMoq');

Route::post('admin/tesco/print-div-tesco', 'Admin\Tesco\OrderManagementCntrl@printDivTesco');
Route::post('admin/tesco/print-div-tesco-small', 'Admin\Tesco\OrderManagementCntrl@printDivTescoSmall');

Route::post('admin/tesco/generate-div-tesco', 'Admin\Tesco\OrderManagementCntrl@generateDivTesco');
Route::post('admin/tesco/generate-div-tesco-small', 'Admin\Tesco\OrderManagementCntrl@generateDivTescoSmall');
Route::post('admin/tesco/pdfbox-div-tesco-small', 'Admin\Tesco\OrderManagementCntrl@pdfboxDivTescoSmall');

Route::post('admin/tesco/set-ce-ratio-template-orders', 'Admin\Tesco\OrderManagementCntrl@setCERatioOrders');
Route::post('admin/tesco/set-ce-single-template-orders', 'Admin\Tesco\OrderManagementCntrl@setCESingleOrders');

Route::post('admin/tesco/set-uk-ratio-template-orders', 'Admin\Tesco\OrderManagementCntrl@setUKRatioOrders');
Route::post('admin/tesco/set-uk-single-template-orders', 'Admin\Tesco\OrderManagementCntrl@setUKSingleOrders');
Route::post('admin/tesco/upload-tesco-combined-po-file', 'Admin\Tesco\OrderManagementCntrl@uploadCombinedOrdersPO');

Route::put('admin/tesco/save-ce-ratio-template-orders', 'Admin\Tesco\OrderManagementCntrl@saveCERatioOrders');
Route::put('admin/tesco/save-ce-single-template-orders', 'Admin\Tesco\OrderManagementCntrl@saveCESingleOrders');

Route::put('admin/tesco/save-uk-ratio-template-orders', 'Admin\Tesco\OrderManagementCntrl@saveUKRatioOrders');
Route::put('admin/tesco/save-uk-single-template-orders', 'Admin\Tesco\OrderManagementCntrl@saveUKSingleOrders');
Route::put('admin/tesco/approve-tesco-order', 'Admin\Tesco\OrderManagementCntrl@approveTescoOrder');
Route::put('admin/tesco/cancel-tesco-order', 'Admin\Tesco\OrderManagementCntrl@cancelTescoOrder');


//Template Management
Route::get('admin/orchestra/template-management', 'Admin\Orchestra\TemplateManagementCntrl@index');
Route::post('admin/orchestra/template-management/create-template-view', 'Admin\Orchestra\TemplateManagementCntrl@orchestraCreateTemplateView');

//Reorder

Route::post('admin/tesco/order-management/reorder', 'Admin\Tesco\OrderManagementCntrl@reOrder');
Route::post('admin/tesco/order-management/reorder-artwork', 'Admin\Tesco\OrderManagementCntrl@reOrderArtwork');
Route::post('admin/tesco/order-management/repeatorder-artwork', 'Admin\Tesco\OrderManagementCntrl@repeatOrderArtwork');
Route::post('admin/tesco/order-management/extra-quantity', 'Admin\Tesco\OrderManagementCntrl@extraQuantity');
Route::post('admin/tesco/order-management/extra-quantity-reorder', 'Admin\Tesco\OrderManagementCntrl@extraQuantityReorder');
Route::post('admin/tesco/order-management/set-tesco-reorder', 'Admin\Tesco\OrderManagementCntrl@setTescoReorder');
Route::post('admin/tesco/order-management/set-tesco-repeat-order', 'Admin\Tesco\OrderManagementCntrl@setTescoRepeatorder');

Route::post('admin/tesco/generatebarcodetsl1', 'Admin\Tesco\OrderManagementCntrl@generateBarcodeTsl1');
Route::post('admin/tesco/generatebarcodetsl3', 'Admin\Tesco\OrderManagementCntrl@generateBarcodeTsl3');

Route::post('admin/tesco/generatebarcodeeanbig', 'Admin\Tesco\OrderManagementCntrl@generateBarcodeEanBig');
Route::post('admin/tesco/generatebarcodeitfbig', 'Admin\Tesco\OrderManagementCntrl@generateBarcodeItfBig');

Route::post('admin/orchestra/template-management/template-list', 'Admin\Orchestra\TemplateManagementCntrl@templateList');

Route::post('admin/tesco/template-management/template-list', 'Admin\Tesco\TemplateManagementCntrl@templateList');

/** upload csv **/

Route::get('admin/upload-csv', 'Admin\UploadCSV\UploadCsvController@index');
Route::post('admin/upload-orchestra-po-file-csv','Admin\UploadCSV\UploadCsvController@uploadPOCSVFile');
Route::post('admin/upload-orchestra-price-file-csv','Admin\UploadCSV\UploadCsvController@uploadPriceCSVFile');
Route::get('admin/csv-conflicts', 'Admin\UploadCSV\CSVDataHandlingController@conflictsCSV');
Route::get('admin/partial-csv', 'Admin\UploadCSV\CSVDataHandlingController@partialCSV');
Route::get('admin/get-uvc-data', 'Admin\UploadCSV\CSVDataHandlingController@getDataByUVC');
Route::post('admin/confirm-uvc-data','Admin\UploadCSV\CSVDataHandlingController@confirmUVCOrders');
Route::post('admin/cancel-uvc-data','Admin\UploadCSV\CSVDataHandlingController@cancelUVCOrders');
Route::get('admin/csv-conflicts-page','Admin\UploadCSV\CSVDataHandlingController@orderChangesPage');
Route::get('admin/get-order-changes-data','Admin\UploadCSV\CSVDataHandlingController@getOrderChangesData');



/**orchestra Settings Page**/
Route::get('admin/settings', 'Admin\Settings\Orchestra\OrchestraSettings@index');
Route::post('admin/orchestra/settings/garments', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraGarments');
Route::post('admin/orchestra/add-orchestra-garments', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraGarments');
Route::get('admin/orchestra/get-orchestra-garments-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraGarmentsById');
Route::put('admin/orchestra/update-orchestra-garments', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraGarmentsById');
Route::put('admin/orchestra/make-garment-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeGarmentEnabled');
Route::put('admin/orchestra/make-garment-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeGarmentDisabled');

Route::post('admin/orchestra/settings/age-group', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraAgeGroup');
Route::post('admin/orchestra/add-orchestra-agegroup', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraAgeGroup');
Route::get('admin/orchestra/get-orchestra-agegroup-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraAgeGroupById');
Route::put('admin/orchestra/update-orchestra-agegroup', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraAgeGroupById');
Route::put('admin/orchestra/make-agegroup-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeAgeGroupEnabled');
Route::put('admin/orchestra/make-agegroup-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeAgeGroupDisabled');

Route::post('admin/orchestra/settings/countries', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraCountries');
Route::post('admin/orchestra/add-orchestra-country', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraCountry');
Route::get('admin/orchestra/get-orchestra-country-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraCountryById');
Route::put('admin/orchestra/update-orchestra-country', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraCountryById');
Route::put('admin/orchestra/make-country-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeCountryEnabled');
Route::put('admin/orchestra/make-country-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeCountryDisabled');

Route::post('admin/orchestra/settings/productfamily', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraProductFamily');
Route::post('admin/orchestra/add-orchestra-productfamily', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraProductFamily');
Route::get('admin/orchestra/get-orchestra-productfamily-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraProductFamilyById');
Route::put('admin/orchestra/update-orchestra-productfamily', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraProductFamilyById');
Route::put('admin/orchestra/make-productfamily-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeProductFamilyEnabled');
Route::put('admin/orchestra/make-productfamily-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeProductFamilyDisabled');

Route::post('admin/orchestra/settings/special-instructions', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraSpecialInstructions');
Route::post('admin/orchestra/add-orchestra-special-instructions', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraSpecialInstructions');
Route::get('admin/orchestra/get-orchestra-special-instruction-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraSpecialInstructionById');
Route::put('admin/orchestra/update-orchestra-special-instruction', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraSpecialInstructionById');
Route::put('admin/orchestra/make-special-instruction-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeSpecialInstructionEnabled');
Route::put('admin/orchestra/make-special-instruction-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeSpecialInstructionDisabled');

Route::post('admin/orchestra/settings/compositions', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraCompositions');
Route::post('admin/orchestra/add-orchestra-compositions', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraCompositions');
Route::get('admin/orchestra/get-orchestra-composition-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraCompositionById');
Route::put('admin/orchestra/update-orchestra-composition', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraCompositionById');
Route::put('admin/orchestra/make-composition-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeCompositionEnabled');
Route::put('admin/orchestra/make-composition-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeCompositionDisabled');

Route::post('admin/orchestra/settings/parts', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraParts');
Route::post('admin/orchestra/add-orchestra-parts', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraParts');
Route::get('admin/orchestra/get-orchestra-part-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraPartById');
Route::put('admin/orchestra/update-orchestra-part', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraPartById');
Route::put('admin/orchestra/make-part-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makePartEnabled');
Route::put('admin/orchestra/make-part-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makePartDisabled');

Route::post('admin/orchestra/settings/color', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraColorCode');
Route::post('admin/settings/orchestra/add-color-code', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraColorCode');
Route::get('admin/orchestra/get-orchestra-color-code-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraColorCodeById');
Route::put('admin/orchestra/make-color-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeColorEnabled');
Route::put('admin/orchestra/make-color-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeColorDisabled');
Route::put('admin/orchestra/update-orchestra-color-code', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraColorCodeById');

Route::post('admin/orchestra/settings/size-chart', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraSizeCharts');
Route::post('admin/orchestra/add-orchestra-size-chart', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraSizeCharts');
Route::get('admin/orchestra/get-orchestra-size-chart-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraSizeChartById');
Route::put('admin/orchestra/update-orchestra-size-chart', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraSizeChartById');
Route::put('admin/orchestra/make-size-chart-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeSizeChartEnabled');
Route::put('admin/orchestra/make-size-chart-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeSizeChartDisabled');

Route::post('admin/orchestra/settings/symbols', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraSymbols');
Route::post('admin/orchestra/settings/addOrchestraSymbols', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraSymbols');
Route::post('admin/orchestra/settings/editOrchestraSymbols', 'Admin\Settings\Orchestra\OrchestraSettings@editOrchestraSymbols');
Route::put('admin/orchestra/make-symbol-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeSymbolEnabled');
Route::put('admin/orchestra/make-symbol-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeSymbolDisabled');

/**tesco Settings Page**/
Route::post('admin/tesco/settings/brands', 'Admin\Settings\Tesco\TescoSettings@tescoBrands');
Route::post('admin/tesco/add-tesco-brands', 'Admin\Settings\Tesco\TescoSettings@addTescoBrands');
Route::get('admin/tesco/get-tesco-brands-details', 'Admin\Settings\Tesco\TescoSettings@getTescoBrandsById');
Route::put('admin/tesco/update-tesco-brands', 'Admin\Settings\Tesco\TescoSettings@updateTescoBrandsById');
Route::put('admin/tesco/make-brand-enabled', 'Admin\Settings\Tesco\TescoSettings@makeBrandEnabled');
Route::put('admin/tesco/make-brand-disabled', 'Admin\Settings\Tesco\TescoSettings@makeBrandDisabled');
Route::post('admin/tesco/settings/departments', 'Admin\Settings\Tesco\TescoSettings@tescoDepartments');
Route::post('admin/tesco/add-tesco-departments', 'Admin\Settings\Tesco\TescoSettings@addTescoDepartment');
Route::get('admin/tesco/get-tesco-departments-details', 'Admin\Settings\Tesco\TescoSettings@getTescoDepartmentsById');
Route::put('admin/tesco/update-tesco-departments', 'Admin\Settings\Tesco\TescoSettings@updateTescoDepartmentsById');
Route::put('admin/tesco/make-departments-enabled', 'Admin\Settings\Tesco\TescoSettings@makeDepartmentEnabled');
Route::put('admin/tesco/make-departments-disabled', 'Admin\Settings\Tesco\TescoSettings@makeDepartmentDisabled');
Route::post('admin/tesco/settings/suppliers', 'Admin\Settings\Tesco\TescoSettings@tescoSuppliers');
Route::post('admin/tesco/add-tesco-suppliers', 'Admin\Settings\Tesco\TescoSettings@addTescoSuppliers');
Route::get('/admin/tesco/get-tesco-suppliers-details', 'Admin\Settings\Tesco\TescoSettings@getTescoSuppliersById');
Route::put('/admin/tesco/update-tesco-suppliers', 'Admin\Settings\Tesco\TescoSettings@updateTescoSuppliersById');
Route::put('/admin/tesco/make-supplier-enabled', 'Admin\Settings\Tesco\TescoSettings@makeSupplierEnabled');
Route::put('/admin/tesco/make-supplier-disabled', 'Admin\Settings\Tesco\TescoSettings@makeSupplierDisabled');
Route::post('admin/tesco/settings/sizechart', 'Admin\Settings\Tesco\TescoSettings@tescoSizeChart');
Route::post('admin/tesco/add-tesco-size-charts', 'Admin\Settings\Tesco\TescoSettings@addTescoSizeCharts');
Route::get('admin/tesco/get-tesco-Sizecharts-details', 'Admin\Settings\Tesco\TescoSettings@getTescoSizeChartsById');
Route::put('admin/tesco/update-tesco-sizecharts', 'Admin\Settings\Tesco\TescoSettings@updateTescoSizechartsById');
Route::put('admin/tesco/make-sizechart-enabled', 'Admin\Settings\Tesco\TescoSettings@makeSizechartEnabled');
Route::put('admin/tesco/make-sizechart-disabled', 'Admin\Settings\Tesco\TescoSettings@makeSizechartDisabled');
Route::post('admin/tesco/settings/tax-code', 'Admin\Settings\Tesco\TescoSettings@taxCode');
Route::post('admin/tesco/add-tesco-tax-code', 'Admin\Settings\Tesco\TescoSettings@addTaxCodes');
Route::get('admin/tesco/get-tesco-tax-code-details', 'Admin\Settings\Tesco\TescoSettings@getTaxCodesById');
Route::put('admin/tesco/update-tesco-tax-code', 'Admin\Settings\Tesco\TescoSettings@updateTaxCodesById');
Route::post('admin/tesco/settings/material', 'Admin\Settings\Tesco\TescoSettings@rawMaterials');
Route::post('admin/tesco/add-raw-material', 'Admin\Settings\Tesco\TescoSettings@addRawMaterial');
Route::get('admin/tesco/get-raw-material-details', 'Admin\Settings\Tesco\TescoSettings@getRawMaterialById');
Route::put('admin/tesco/update-raw-material', 'Admin\Settings\Tesco\TescoSettings@updateRawMaterialById');
Route::put('admin/tesco/make-material-enabled', 'Admin\Settings\Tesco\TescoSettings@makeMaterialEnabled');
Route::put('admin/tesco/make-material-disabled', 'Admin\Settings\Tesco\TescoSettings@makeMaterialDisabled');

Route::post('admin/tesco/settings/error-report-emails', 'Admin\Settings\Tesco\TescoSettings@tescoErrorReportEmails');
Route::post('admin/tesco/add-tesco-error-report-email', 'Admin\Settings\Tesco\TescoSettings@addTescoErrorReportEmail');
Route::get('admin/tesco/get-tesco-error-report-email-details', 'Admin\Settings\Tesco\TescoSettings@getTescoErrorReportEmailById');
Route::put('admin/tesco/update-tesco-error-report-email', 'Admin\Settings\Tesco\TescoSettings@updateTescoErrorReportEmailById');
Route::put('admin/tesco/make-error-report-email-enabled', 'Admin\Settings\Tesco\TescoSettings@makeErrorReportEmailEnabled');
Route::put('admin/tesco/make-error-report-email-disabled', 'Admin\Settings\Tesco\TescoSettings@makeErrorReportEmailDisabled');


Route::get('admin/common-settings', 'Admin\Settings\SettingsController@index');

/** Tesco order-status**/
Route::post('admin/tesco/tesco-orders', 'Admin\Tesco\OrderStatusCntrl@index');
Route::post('admin/tesco/order-management/set-po-number', 'Admin\Tesco\OrderStatusCntrl@setPONumber');
Route::post('admin/tesco/set-po-file', 'Admin\Tesco\OrderStatusCntrl@setCustomerPOFile');

Route::post('admin/tesco/order-management/show-tesco-pi', 'Admin\Tesco\OrderStatusCntrl@getTescoPI');
Route::post('admin/tesco/approve-tesco-pi', 'Admin\Tesco\OrderStatusCntrl@approveTescoPI');
Route::post('admin/tesco/reject-tesco-pi', 'Admin\Tesco\OrderStatusCntrl@rejectTescoPI');
Route::post('admin/tesco/edit-artwork', 'Admin\Tesco\OrderStatusCntrl@editArtwork');
Route::post('admin/orchestra/edit-artwork', 'Admin\Orchestra\OrderStatusCntrl@editArtwork');
Route::get('admin/tesco/get-artworks', 'Admin\Tesco\OrderStatusCntrl@getArtwork');
Route::post('admin/tesco/update-po', 'Admin\Tesco\OrderStatusCntrl@updateArtworkPO');
Route::post('admin/tesco/copy-po', 'Admin\Tesco\OrderStatusCntrl@copyArtworkPO');
Route::post('admin/tesco/get-po-file', 'Admin\Tesco\OrderStatusCntrl@getPOFile');

Route::post('admin/tesco/download-tesco-pi', 'Admin\Tesco\OrderStatusCntrl@downloadTescoPI');



Route::post('admin/tesco/update-delivery-date', 'Admin\Tesco\OrderStatusCntrl@updateDeliveryDate');

Route::post('admin/tesco/set-tesco-order-status', 'Admin\Tesco\OrderStatusCntrl@setTescoOrderStatus');

Route::post('admin/tesco/enable-artwork-edit', 'Admin\Tesco\OrderStatusCntrl@enableArtworkEdit');
Route::post('admin/tesco/disable-artwork-edit', 'Admin\Tesco\OrderStatusCntrl@disableArtworkEdit');

Route::post('admin/tesco/edit-check-exist-po-repeat', 'Admin\Tesco\OrderStatusCntrl@editCheckExistPoRepeat');
Route::get('admin/tesco/get-erp-status', 'Admin\Tesco\OrderStatusCntrl@checkERPStatus');

Route::get('customer/reorder', 'Customer\HomeController@reorder');


//Customer Reorder

Route::post('customer/tesco/reorder', 'Customer\Tesco\ReorderController@reOrder');
Route::post('customer/tesco/reorder-artwork', 'Customer\Tesco\ReorderController@reOrderArtwork');
Route::post('customer/tesco/repeatorder-artwork', 'Customer\Tesco\ReorderController@repeatOrderArtwork');
Route::post('customer/tesco/extra-quantity', 'Customer\Tesco\ReorderController@extraQuantity');
Route::post('customer/tesco/extra-quantity-reorder', 'Customer\Tesco\ReorderController@extraQuantityReorder');
Route::post('customer/tesco/set-tesco-reorder', 'Customer\Tesco\ReorderController@setTescoReorder');
Route::post('customer/tesco/set-tesco-repeat-order', 'Customer\Tesco\ReorderController@setTescoRepeatorder');

Route::post('customer/tesco/edit-check-exist-po-repeat', 'Customer\Tesco\ReorderController@editCheckExistPoRepeat');

Route::get('customer/tesco/get-tsl-size', 'Customer\Tesco\ReorderController@getTescoTSLSizechart');



//Customer Print Option

Route::post('customer/tesco/print-div-tesco', 'Customer\Tesco\PrintController@printDivTesco');
Route::post('customer/tesco/print-div-tesco-small', 'Customer\Tesco\PrintController@printDivTescoSmall');

//Route::get('admin/tesco/PDFMiner', 'Admin\Tesco\OrderManagementCntrl@createOrderByPDFMiner');

// Email related routes
Route::post('feedback-mail/send', 'Mail\MailController@sendFeedback');

/** admin role Management **/
Route::get('admin/admin-role-management', 'Admin\AdminRoleManagement\AdminRoleManagementController@index');
Route::get('admin/admin-role-management/admin-list', 'Admin\AdminRoleManagement\AdminRoleManagementController@adminList');
Route::post('admin/admin-role-management/add-new-admin', 'Admin\AdminRoleManagement\AdminRoleManagementController@addNewAdmin');
Route::put('admin/admin-role-management/make-admin-active', 'Admin\AdminRoleManagement\AdminRoleManagementController@makeAdminActive');
Route::put('admin/admin-role-management/make-admin-inactive', 'Admin\AdminRoleManagement\AdminRoleManagementController@makeAdminInactive');

/** Admin profile related routes */
Route::get('admin/get-profile-details', 'Admin\AdminProfile\AdminProfileController@getAdminDetailsById');
Route::put('admin/update-admin-profile-details', 'Admin\AdminProfile\AdminProfileController@updateAdminById');
Route::put('admin/update-admin-password', 'Admin\AdminProfile\AdminProfileController@updateAdminPassword');

Route::post('admin/tesco/enable-artwork-edit', 'Admin\Tesco\OrderStatusCntrl@enableArtworkEdit');
Route::post('admin/tesco/disable-artwork-edit', 'Admin\Tesco\OrderStatusCntrl@disableArtworkEdit');

/** admin role Management **/
Route::get('admin/audit-trail', 'Admin\AuditTrail\AuditTrailController@index');
Route::get('admin/audit-trail/get-comparison-details', 'Admin\AuditTrail\AuditTrailController@getComparisonDataById');
/* ERP Related Routes */
Route::group(['prefix' => 'erp'], function () {
	Route::get('report-generator','ErpUser\Reports\ReportController@index')->name('report-generator');
	Route::get('/', 'ErpUser\Admin\UserController@index')->name('erp');
	Route::get('/home', 'ErpUser\Admin\UserController@index')->name('home');
	Route::get('/login', 'ErpuserAuth\LoginController@showLoginForm')->name('erp.login');
	Route::post('/login', 'ErpuserAuth\LoginController@login');
	Route::post('/logout', 'ErpuserAuth\LoginController@logout')->name('logout');
	Route::get('/register', 'ErpuserAuth\RegisterController@showRegistrationForm')->name('register');
	Route::post('/register', 'ErpuserAuth\RegisterController@register');
	Route::post('/password/email', 'ErpuserAuth\ForgotPasswordController@sendResetLinkEmail')->name('erp-password.request');
	Route::post('/password/reset', 'ErpuserAuth\ResetPasswordController@reset')->name('erp-password.email');
	Route::get('/password/reset', 'ErpuserAuth\ForgotPasswordController@showLinkRequestForm')->name('erp-password.reset');
	Route::get('/password/reset/{token}', 'ErpuserAuth\ResetPasswordController@showResetForm');
	/* Routes for User Management */
	Route::get('/users', 'ErpUser\Admin\UserController@show')->name('erp-users');
	Route::post('users', 'ErpUser\Admin\UserController@store')->name('erp.user.add');
	Route::put('users',  'ErpUser\Admin\UserController@update')->name('erp.user.update');
	Route::delete('users', 'ErpUser\Admin\UserController@destroy')->name('erp.user.delete');
	Route::post('get-template', 'ErpUser\Admin\UserController@getTemplate')->name('erp.user.getTemplate');
	Route::get('get-user', 'ErpUser\Admin\UserController@getUser')->name('erp.user.getUser');
	/* Routes for User Profile */
	Route::get('profile', 'ErpUser\ProfileController@getProfile')->name('erp.user.getProfile');
	Route::put('profile', 'ErpUser\ProfileController@putProfile')->name('erp.user.putProfile');
	Route::put('password', 'ErpUser\ProfileController@putPassword')->name('erp.user.putPwd');
	/* Routes for Admin Order Management */
	Route::get('order-management', 'ErpUser\Admin\OrderController@show')->name('erp.admin.orders.show');
	Route::get('erp-order', 'ErpUser\Admin\OrderManagementController@edit')->name('erp.admin.order.details');
	Route::delete('erp-order', 'ErpUser\Admin\OrderManagementController@destroy')->name('erp.admin.order.delete');
	/* Routes for Admin - Order Item Management */
	Route::put('erp-order-item', 'ErpUser\Admin\OrderManagementController@update')->name('erp.admin.order-item.update');
	
	
	/* Routes for Invoice Management */
	 
	Route::get('/invoice', 'ErpUser\Invoice\InvoiceController@index')->name('invoice'); 
	Route::get('/setinvoicecustrefname', 'ErpUser\Invoice\InvoiceController@setInvoiceCustRefName')->name('invoice'); 
	Route::get('/create-invoice', 'ErpUser\Invoice\InvoiceController@create')->name('create-invoice');
	Route::post('/update-invoice', 'ErpUser\Invoice\InvoiceController@updateInvoice')->name('update-invoice');
	
	/* Routes for PO Manager Module */

	Route::get('/erp-po-list', 'ErpUser\Invoice\InvoiceController@erpPOList')->name('erp-po'); 
	
	Route::get('/erp-order-list', 'ErpUser\Invoice\InvoiceController@erpOrderList')->name('erp-orders');
	Route::post('/save-invoice', 'ErpUser\Invoice\InvoiceController@setInvoice')->name('save-invoice');
	Route::get('/get-invoice-details', 'ErpUser\Invoice\InvoiceController@getInvoiceDetails')->name('get-invoice-details');
	Route::get('/get-invoice', 'ErpUser\Invoice\InvoiceController@getInvoice')->name('get-invoice');
	Route::post('/download-invoice', 'ErpUser\Invoice\InvoiceController@downloadInvoice')->name('download-invoice');
	
	Route::get('/po-manager', 'ErpUser\Pomanager\PoManagerController@index')->name('poManager'); 
	
	Route::get('/po-orders-list', 'ErpUser\Pomanager\PoManagerController@poOrdersList')->name('po-orders-list');	
	
	
	Route::Post('/po-manager/get-order-details', 'ErpUser\Pomanager\PoManagerController@getOrderDetails');
	
	Route::post('/po-manager/order-details', 'ErpUser\Pomanager\PoManagerController@orderDetails');
	Route::post('/po-manager/submit-po-verify', 'ErpUser\Pomanager\PoManagerController@submitPoVerify'); 
	Route::post('/po-manager/change_po_updated_status', 'ErpUser\Pomanager\PoManagerController@changePoUpdatedStatus'); 
	Route::post('/po-manager/set_company_session', 'ErpUser\Pomanager\PoManagerController@setCompanySession'); 
	
	/* Routes for Print Manager Module */
	Route::get('/print-manager', 'ErpUser\Printmanager\PrintManagerController@index'); 
	
	/* Routes for jobcard Manager Module */
	Route::get('job-manager', 'ErpUser\Jobmanager\JobManagerController@index')->name('jobcard-manager'); 
	Route::post('/job-manager/get-order-details', 'ErpUser\Jobmanager\JobManagerController@getOrderDetails'); 
	Route::get('job-manager/get-jobcard-order-details', 'ErpUser\Jobmanager\JobManagerController@getOrderDetailsByIds'); 
	Route::post('job-manager/store-jobcard-details', 'ErpUser\Jobmanager\JobManagerController@storeJobcardData'); 
	
	Route::post('job-manager/tesco/print-div-tesco', 'ErpUser\Jobmanager\JobManagerController@printDivTesco');
	Route::post('job-manager/tesco/print-div-tesco-small', 'ErpUser\Jobmanager\JobManagerController@printDivTescoSmall');
	
	
	Route::post('job-manager/tesco/generate-div-tesco', 'ErpUser\Jobmanager\JobManagerController@generateDivTesco');
	Route::post('job-manager/tesco/generate-div-tesco-small', 'ErpUser\Jobmanager\JobManagerController@generateDivTescoSmall');
	
	
	Route::post('job-manager/generatebarcodetsl1', 'ErpUser\Jobmanager\JobManagerController@generateBarcodeTsl1');
	Route::post('job-manager/generatebarcodetsl3', 'ErpUser\Jobmanager\JobManagerController@generateBarcodeTsl3');

	Route::post('job-manager/generatebarcodeeanbig', 'ErpUser\Jobmanager\JobManagerController@generateBarcodeEanBig');
	Route::post('job-manager/generatebarcodeitfbig', 'ErpUser\Jobmanager\JobManagerController@generateBarcodeItfBig');
	

	Route::get('job-manager/tesco/get-tsl-size', 'ErpUser\Jobmanager\JobManagerController@getTescoTSLSizechart');

	Route::get('job-manager/script-check-orders', 'ErpUser\Jobmanager\JobManagerController@scriptCheckOrder');


	Route::get('job-manager/jobcards-list', 'ErpUser\Jobmanager\JobManagerController@getJobcards')->name('jobcards-list'); 
	Route::get('job-manager/jobcard-details', 'ErpUser\Jobmanager\JobManagerController@getJobcardDetailsById');  
	Route::put('job-manager/update-jobcard-details', 'ErpUser\Jobmanager\JobManagerController@updateJobcardById'); 
	Route::get('job-manager/script-update-order-status', 'ErpUser\Jobmanager\JobManagerController@scriptUpdateOrderStatusId');
	Route::get('job-manager/get-customers-list', 'ErpUser\Jobmanager\JobManagerController@getCustomersListByBuyerId'); 
	Route::get('job-manager/get-jobcard-id-list', 'ErpUser\Jobmanager\JobManagerController@getJobcardIdListByBuyerCustomerId'); 
	Route::get('job-manager/get-jobcards-list', 'ErpUser\Jobmanager\JobManagerController@getJobcardsListByBuyerCustomerJobcardId'); 
	Route::post('job-manager/pdf-merge', 'ErpUser\Jobmanager\JobManagerController@pdfMerge'); 
	Route::get('job-manager/script-update-uuid', 'ErpUser\Jobmanager\JobManagerController@scriptUpdateUUID');

	/* Orchestra Print Manager*/
	Route::post('job-manager/orchestra/print-pdf', 'ErpUser\Jobmanager\JobManagerController@printPdf');
	Route::post('job-manager/orchestra/print-pdf-arr', 'ErpUser\Jobmanager\JobManagerController@printPdfArr');
	Route::post('job-manager/orchestra/print-pdf-small', 'ErpUser\Jobmanager\JobManagerController@printPdfSmall');
	Route::post('job-manager/orchestra/print-pdf-table', 'ErpUser\Jobmanager\JobManagerController@printPdfTable');


	Route::post('job-manager/orchestra/print-wash-pdf', 'ErpUser\Jobmanager\JobManagerController@printWashPdf');
	
	Route::post('job-manager/orchestra/print-heat-transfer', 'ErpUser\Jobmanager\JobManagerController@printHeatTransfer');
	Route::post('job-manager/orchestra/print-special-tag', 'ErpUser\Jobmanager\JobManagerController@printSpecialTag');
	Route::post('job-manager/orchestra/print-main-label', 'ErpUser\Jobmanager\JobManagerController@printMainLabel');
	Route::post('job-manager/orchestra/print-size-label', 'ErpUser\Jobmanager\JobManagerController@printSizeLabel');


	Route::post('job-manager/print-hang-pdf', 'ErpUser\Jobmanager\JobManagerController@printHangPdf');
	/* Routes for ERP-Settings Management */
	Route::get('/settings', 'ErpUser\ERPSettings\ERPSettingsController@home')->name('erp-settings');
	Route::get('/settings/dispatch-mode', 'ErpUser\ERPSettings\ERPSettingsController@getDispatchMode')->name('erp-dispatch-mode');
	Route::post('/settings/add-dispatch-mode', 'ErpUser\ERPSettings\ERPSettingsController@addDispatchMode');
	Route::get('/settings/get-dispatch-mode-by-id', 'ErpUser\ERPSettings\ERPSettingsController@getDispatchModeById');
	Route::put('/settings/update-dispatch-mode-by-id', 'ErpUser\ERPSettings\ERPSettingsController@updateDispatchModeById');
	Route::put('/settings/make-dispatch-mode-enabled', 'ErpUser\ERPSettings\ERPSettingsController@makeDispatchModeEnabled');
	Route::put('/settings/make-dispatch-mode-disabled', 'ErpUser\ERPSettings\ERPSettingsController@makeDispatchModeDisabled');
    
    //Machine
	Route::get('/settings/machine', 'ErpUser\ERPSettings\ERPSettingsController@getMachine')->name('erp-machine');
	Route::post('/settings/add-machine', 'ErpUser\ERPSettings\ERPSettingsController@addMachine');
	Route::get('/settings/get-machine-by-id', 'ErpUser\ERPSettings\ERPSettingsController@getMachineById');
	Route::put('/settings/update-machine-by-id', 'ErpUser\ERPSettings\ERPSettingsController@updateMachineById');
	Route::put('/settings/make-machine-enabled', 'ErpUser\ERPSettings\ERPSettingsController@makeMachineEnabled');
	Route::put('/settings/make-machine-disabled', 'ErpUser\ERPSettings\ERPSettingsController@makeMachineDisabled');
    
    //Teeth Number
	Route::get('/settings/teethno', 'ErpUser\ERPSettings\ERPSettingsController@getTeethNo')->name('erp-teethno');
	Route::post('/settings/add-teethno', 'ErpUser\ERPSettings\ERPSettingsController@addTeethNo');
	Route::get('/settings/get-teethno-by-id', 'ErpUser\ERPSettings\ERPSettingsController@getTeethNoById');
	Route::put('/settings/update-teethno-by-id', 'ErpUser\ERPSettings\ERPSettingsController@updateTeethNoById');
	Route::put('/settings/make-teethno-enabled', 'ErpUser\ERPSettings\ERPSettingsController@makeTeethNoEnabled');
	Route::put('/settings/make-teethno-disabled', 'ErpUser\ERPSettings\ERPSettingsController@makeTeethNoDisabled');
    
    //Finish Type
	Route::get('/settings/finishtype', 'ErpUser\ERPSettings\ERPSettingsController@getFinishtype')->name('erp-finishtype');
	Route::post('/settings/add-finishtype', 'ErpUser\ERPSettings\ERPSettingsController@addFinishType');
	Route::get('/settings/get-finishtype-by-id', 'ErpUser\ERPSettings\ERPSettingsController@getFinishTypeById');
	Route::put('/settings/update-finishtype-by-id', 'ErpUser\ERPSettings\ERPSettingsController@updateFinishTypeById');
	Route::put('/settings/make-finishtype-enabled', 'ErpUser\ERPSettings\ERPSettingsController@makeFinishTypeEnabled');
	Route::put('/settings/make-finishtype-disabled', 'ErpUser\ERPSettings\ERPSettingsController@makeFinishTypeDisabled');
});

//Orchestra Order Management - customer
Route::post('customer/orchestra/order-management/orders', 'Customer\Orchestra\OrderController@Orders');
Route::post('customer/orchestra/order-management/order-status', 'Customer\Orchestra\OrderController@orderStatus');
Route::post('customer/orchestra/order-management/label-order-details', 'Customer\Orchestra\OrderController@labelOrderDetails');
Route::post('customer/orchestra/order-management/sticker-order-details', 'Customer\Orchestra\OrderController@stickerOrderDetails');
Route::post('customer/orchestra/order-management/set_hang_tag', 'Customer\Orchestra\OrderController@setHangTag');

Route::post('customer/orchestra/order-management/remove_hang_tag', 'Customer\Orchestra\OrderController@removeHangTag');

Route::post('customer/orchestra/order-management/remove_special_tag', 'Customer\Orchestra\OrderController@removeSpecialTag');
Route::post('customer/orchestra/order-management/remove_heat_transfer', 'Customer\Orchestra\OrderController@removeHeatTransfer');
Route::post('customer/orchestra/order-management/remove_main_label', 'Customer\Orchestra\OrderController@removeMainLabel');
Route::post('customer/orchestra/order-management/remove_size_label', 'Customer\Orchestra\OrderController@removeSizeLabel');

Route::post('customer/orchestra/order-management/remove_standard_stickers', 'Customer\Orchestra\OrderController@removeStandardStickers');
Route::post('customer/orchestra/order-management/remove_solid_stickers', 'Customer\Orchestra\OrderController@removeSolidStickers');


Route::post('customer/orchestra/order-management/remove_wcl', 'Customer\Orchestra\OrderController@removeWCL');


Route::post('customer/orchestra/order-management/set_heat_transfer', 'Customer\Orchestra\OrderController@setHeatTransfer');

Route::post('customer/orchestra/order-management/set_special_tag', 'Customer\Orchestra\OrderController@setSpecialTag');
Route::post('customer/orchestra/order-management/set_main_label', 'Customer\Orchestra\OrderController@setMainLabel');

Route::post('customer/orchestra/order-management/set_size_label', 'Customer\Orchestra\OrderController@setSizeLabel');

Route::post('customer/orchestra/order-management/remove_wash_label', 'Customer\Orchestra\OrderController@removeWashLabel');
Route::post('customer/orchestra/order-management/set_wash_label', 'Customer\Orchestra\OrderController@setWashLabel');
Route::post('customer/orchestra/order-management/set_wash_labelos', 'Customer\Orchestra\OrderController@setWashLabelOs');

Route::post('customer/orchestra/order-management/set_solid_stickers', 'Customer\Orchestra\OrderController@setSolidStickers');
Route::post('customer/orchestra/order-management/set_standard_stickers', 'Customer\Orchestra\OrderController@setStandardStickers');



Route::post('customer/orchestra/order-management/reorder', 'Customer\Orchestra\OrderController@reOrder');
Route::post('customer/orchestra/order-management/reorder-artwork', 'Customer\Orchestra\OrderController@reOrderArtwork');
Route::post('customer/orchestra/order-management/extra-quantity', 'Customer\Orchestra\OrderController@extraQuantity');


Route::post('customer/orchestra/order-management/po_select_wcl', 'Customer\Orchestra\OrderController@poSelectWcl');

Route::post('customer/orchestra/order-management/set_solid_stickers_os', 'Customer\Orchestra\OrderController@setSolidStickersOs');
Route::post('customer/orchestra/order-management/set_solid_poly_bag_stickers_os', 'Customer\Orchestra\OrderController@setSolidPolyBagStickersOs');
Route::post('customer/orchestra/order-management/set_standard_carton_stickers_os', 'Customer\Orchestra\OrderController@setStandardCartonStickersOs');
Route::post('customer/orchestra/order-management/set_standard_poly_bag_stickers_os', 'Customer\Orchestra\OrderController@setStandardPolyBagStickersOs');


Route::get('customer/orchestra/order-management/get-existing-label-orders-po', 'Customer\Orchestra\OrderController@getExistingLabelOrdersPo');
Route::get('customer/orchestra/order-management/get-existing-standard-sticker-orders-po', 'Customer\Orchestra\OrderController@getExistingStandardStickerOrdersPo');
Route::get('customer/orchestra/order-management/get-existing-solid-sticker-orders-po', 'Customer\Orchestra\OrderController@getExistingSolidStickerOrdersPo');
Route::get('customer/orchestra/order-management/get-existing-label-orders-status-po', 'Customer\Orchestra\OrderController@getExistingLabelOrderStatusPo');

Route::post('customer/orchestra/order-management/set-po-number', 'Customer\Orchestra\OrderController@setPONumber');
Route::post('customer/orchestra/set-po-file', 'Customer\Orchestra\OrderController@setCustomerPOFile');
Route::Post('customer/orchestra/order-management/map-label-orders-po', 'Customer\Orchestra\OrderController@mapLabelOrdersPo');
Route::Post('customer/orchestra/order-management/update-label-orders-po', 'Customer\Orchestra\OrderController@updateLabelOrdersPO');
Route::get('customer/orchestra/get-erp-status', 'Customer\Orchestra\OrderController@checkERPStatus');


Route::post('customer/orchestra/template-management/template-view', 'Customer\Orchestra\TemplateManagementCntrl@templateView');

// Routes for report generation
Route::get('reportGenerator','ErpUser\Reports\ReportController@index')->name('reportGenerator');
Route::post('level1Filter','ErpUser\Reports\ReportController@level1Filter');
Route::post('/level2Filter','ErpUser\Reports\ReportController@level2Filter');
Route::get('getTableFields','ErpUser\Reports\ReportController@getTableFields');
Route::get('setUpDatatable', 'ErpUser\Reports\ReportController@setUpDatatable' )->name('setUpDatatable');

Route::get('print-manager/get-customers-list', 'ErpUser\Printmanager\PrintManagerController@getCustomersListByBuyerId'); 
Route::get('print-manager/get-style-list', 'ErpUser\Printmanager\PrintManagerController@getStyleListByBuyerCustomerId'); 
Route::get('print-manager/get-erporder-list', 'ErpUser\Printmanager\PrintManagerController@getErpOrderListByBuyerCustomerId'); 





