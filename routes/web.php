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




Route::get('admin/order-management', 'Admin\OrderManagement\OrderController@dashboard');
Route::get('admin/order-management/orders', 'Admin\OrderManagement\OrderController@orders');
Route::get('admin/order-management/order-status', 'Admin\OrderManagement\OrderController@orderStatus');
Route::get('admin/order-management/reorder', 'Admin\OrderManagement\OrderController@reorder');
Route::post('admin/orchestra/order-management/compositions-details', 'Admin\Orchestra\OrderManagementCntrl@compositionDetails');

Route::post('admin/order-management/print-pdf', 'Admin\OrderManagement\OrderController@printPdf');
Route::post('admin/order-management/print-pdf-small', 'Admin\OrderManagement\OrderController@printPdfSmall');
Route::post('admin/order-management/print-pdf-table', 'Admin\OrderManagement\OrderController@printPdfTable');
Route::post('admin/order-management/print-hang-pdf', 'Admin\OrderManagement\OrderController@printHangPdf');
Route::post('admin/order-management/print-special-pdf', 'Admin\OrderManagement\OrderController@printSpecialPdf');

Route::post('admin/order-management/print-wash-pdf', 'Admin\OrderManagement\OrderController@printWashPdf');

Route::get('admin/template-management', 'Admin\TemplateManagement\TemplateController@templateList');
//Route::get('admin/template-management/template-list', 'Admin\TemplateManagement\TemplateController@templateList');
Route::post('admin/orchestra/template-management/template-view', 'Admin\Orchestra\TemplateManagementCntrl@templateView');


Route::post('admin/tesco/template-management/template-view', 'Admin\Tesco\TemplateManagementCntrl@templateView');
Route::post('/admin/template-management/base64_to_jpeg', 'Admin\TemplateManagement\TemplateController@base64ToJpeg');
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
/** customer Management **/
Route::get('admin/customer-management', 'Admin\CustomerManagement\CustomerManagementController@index');
Route::post('admin/customer-management/customers-list', 'Admin\CustomerManagement\CustomerManagementController@customersList');
Route::post('admin/customer-management/add-customer', 'Admin\CustomerManagement\CustomerManagementController@addCustomer');
Route::put('admin/customer-management/make-customer-active', 'Admin\CustomerManagement\CustomerManagementController@makeCustomerActive');
Route::put('admin/customer-management/make-customer-inactive', 'Admin\CustomerManagement\CustomerManagementController@makeCustomerInactive');
Route::get('admin/customer-management/get-customers', 'Admin\CustomerManagement\CustomerManagementController@getCustomerById');
Route::put('admin/customer-management/update-customer', 'Admin\CustomerManagement\CustomerManagementController@updateCustomer');
Route::delete('admin/customer-management/delete-customer', 'Admin\CustomerManagement\CustomerManagementController@deleteCustomerById');
Route::get('admin/customer-management/customers', 'Admin\CustomerManagement\CustomerManagementController@customers');
Route::get('admin/customer-management/pricing-taxation','Admin\CustomerManagement\CustomerManagementController@custumerPriceList');
Route::get('admin/customer-management/get-buyers-customers','Admin\CustomerManagement\CustomerManagementController@getBuyersCustomers');
Route::post('admin/customer-management/save-price-tax', 'Admin\CustomerManagement\CustomerManagementController@savePriceTax');
Route::get('admin/customer-management/get-price-tax', 'Admin\CustomerManagement\CustomerManagementController@getPriceTax');
Route::post('admin/tesco/customer-management/template-view', 'Admin\CustomerManagement\CustomerManagementController@templateView');



/** buyer Management **/
Route::get('admin/buyer-management', 'Admin\BuyerManagement\BuyerManagementController@index');
Route::get('admin/buyer-management/buyers-list', 'Admin\BuyerManagement\BuyerManagementController@buyerList');
Route::get('admin/buyer-management/get-buyers', 'Admin\BuyerManagement\BuyerManagementController@getBuyers');
Route::post('admin/buyer-management/add-jga-buyer', 'Admin\BuyerManagement\BuyerManagementController@addJgaBuyer');
Route::get('admin/buyer-management/get-buyer-details', 'Admin\BuyerManagement\BuyerManagementController@getBuyerById');
Route::put('admin/buyer-management/update-buyer-details', 'Admin\BuyerManagement\BuyerManagementController@updateBuyerById');
Route::delete('admin/buyer-management/delete-buyer', 'Admin\BuyerManagement\BuyerManagementController@deleteBuyerById');
Route::get('admin/buyer-management/buyers', 'Admin\BuyerManagement\BuyerManagementController@buyers');

Route::get('admin/buyer-management/merchandiser-list', 'Admin\BuyerManagement\BuyerManagementController@merchandiserList');
Route::post('admin/buyer-management/add-merchandiser', 'Admin\BuyerManagement\BuyerManagementController@addMerchandiser');
Route::get('admin/buyer-management/get-merchandiser-details', 'Admin\BuyerManagement\BuyerManagementController@getMerchandiserById');
Route::put('admin/buyer-management/update-merchandiser-details', 'Admin\BuyerManagement\BuyerManagementController@updateMerchandiserById');
Route::delete('admin/buyer-management/delete-merchandiser', 'Admin\BuyerManagement\BuyerManagementController@deleteMerchandiserById');
Route::get('admin/buyer-management/merchandisers', 'Admin\BuyerManagement\BuyerManagementController@merchandisers');

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
	Route::get('customer/dashboard', 'Customer\HomeController@index')->name('customer');
	Route::get('customer/order-status', 'Customer\HomeController@customerOrderStatus');
	Route::get('customer/create-order', 'Customer\HomeController@customerOrders');
	//ORCHESTRA 
	Route::post('customer/orchestra/order-status', 'Customer\Orchestra\OrderController@orchestraOrderStatus');

	//TESCO
	Route::post('customer/tesco/order-status', 'Customer\Tesco\OrderController@tescoOrderStatus');
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
	Route::get('/merchandiser/pending-orders', 'Merchandiser\HomeController@index');
	Route::get('/merchandiser/get-wcl-details', 'Merchandiser\HomeController@getWCLById');
	Route::post('/merchandiser/set-wcl-merchandiser-approve-comment', 'Merchandiser\HomeController@addWCLApproveCommentById');
	Route::post('/merchandiser/set-wcl-merchandiser-reject-comment', 'Merchandiser\HomeController@addWCLRejectCommentById');
	Route::get('/merchandiser/past-orders', 'Merchandiser\HomeController@pastOrders');

});

//Erp Module

Route::post('erp_logout', 'ErpAuth\LoginController@logout');
Route::get('erp', 'ErpAuth\LoginController@showLoginForm');
Route::post('erp', 'ErpAuth\LoginController@login');

Route::group(['middleware' => 'erp_guest'], function() {

Route::get('erp_register', 'ErpAuth\RegisterController@showRegistrationForm');
Route::post('erp_register', 'ErpAuth\RegisterController@register');
Route::get('erp', 'ErpAuth\LoginController@showLoginForm');
Route::post('erp', 'ErpAuth\LoginController@login');

});

//Only logged in sellers can access or send requests to these pages
Route::group(['middleware' => 'erp_auth'], function(){

Route::post('erp/logout', 'ErpAuth\LoginController@logout');
Route::get('/erp/dashboard', function(){
  return view('erp.home');
});

});

//Orchestra Order Management

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
Route::post('admin/orchestra/order-management/show-profoma-invoice', 'Admin\Orchestra\OrderManagementCntrl@getProfomaInvoice');
Route::post('admin/orchestra/set-po-file', 'Admin\Orchestra\OrderManagementCntrl@setCustomerPOFile');
Route::post('admin/orchestra/approve-profoma-invoice', 'Admin\Orchestra\OrderManagementCntrl@approveProfomaInvice');
Route::post('admin/orchestra/reject-profoma-invoice', 'Admin\Orchestra\OrderManagementCntrl@rejectProfomaInvice');

Route::post('admin/orchestra/order-management/set_hang_tag', 'Admin\Orchestra\OrderManagementCntrl@setHangTag');
Route::post('admin/orchestra/order-management/set_special_tag', 'Admin\Orchestra\OrderManagementCntrl@setSpecialTag');
Route::post('admin/orchestra/order-management/set_main_label', 'Admin\Orchestra\OrderManagementCntrl@setMainLabel');
Route::post('admin/orchestra/order-management/remove_main_label', 'Admin\Orchestra\OrderManagementCntrl@removeMainLabel');
Route::post('admin/orchestra/order-management/remove_wash_label', 'Admin\Orchestra\OrderManagementCntrl@removeWashLabel');
Route::post('admin/orchestra/order-management/set_wash_label', 'Admin\Orchestra\OrderManagementCntrl@setWashLabel');
Route::post('admin/orchestra/order-management/set_solid_stickers', 'Admin\Orchestra\OrderManagementCntrl@setSolidStickers');
Route::post('admin/orchestra/order-management/set_standard_stickers', 'Admin\Orchestra\OrderManagementCntrl@setStandardStickers');

Route::get('admin/orchestra/order-management/get_existing_label_orders_po', 'Admin\Orchestra\OrderManagementCntrl@getExistingLabelOrdersPo');
Route::get('admin/orchestra/order-management/get_existing_standard_sticker_orders_po', 'Admin\Orchestra\OrderManagementCntrl@getExistingStandardStickerOrdersPo');
Route::get('admin/orchestra/order-management/get_existing_solid_sticker_orders_po', 'Admin\Orchestra\OrderManagementCntrl@getExistingSolidStickerOrdersPo');
Route::get('admin/orchestra/order-management/get_existing_label_orders_status_po', 'Admin\Orchestra\OrderManagementCntrl@getExistingLabelOrderStatusPo');

Route::Post('admin/orchestra/order-management/put_existing_label_orders_po', 'Admin\Orchestra\OrderManagementCntrl@putExistingLabelOrdersPo');
Route::Post('admin/orchestra/order-management/put_existing_label_orders_po_new', 'Admin\Orchestra\OrderManagementCntrl@putExistingLabelOrdersPoNew');

Route::post('/admin/order-management/order_sub_modules', 'Admin\AdminController@orderSubModules');
Route::post('/admin/template-management/sub_modules', 'Admin\AdminController@templateSubModules');
/** Tesco order-management**/
Route::get('admin/tesco/order-management', 'Admin\Tesco\OrderManagementCntrl@index');
Route::post('admin/tesco/order-management/orders', 'Admin\Tesco\OrderManagementCntrl@Orders');
Route::get('admin/tesco/template-management', 'Admin\Tesco\TemplateManagementCntrl@index');
Route::get('admin/tesco/templates-details', 'Admin\Tesco\OrderManagementCntrl@getTescoTemplateDetails');
Route::post('admin/tesco/po-upload', 'Admin\Tesco\OrderManagementCntrl@createOrderByTabula');
Route::get('admin/tesco/get-tsl-size', 'Admin\Tesco\OrderManagementCntrl@getTescoTSLSizechart');

Route::post('admin/tesco/print-div-tesco', 'Admin\Tesco\OrderManagementCntrl@printDivTesco');
Route::post('admin/tesco/print-div-tesco-small', 'Admin\Tesco\OrderManagementCntrl@printDivTescoSmall');

Route::post('admin/tesco/set-ce-ratio-template-orders', 'Admin\Tesco\OrderManagementCntrl@setCERatioOrders');
Route::post('admin/tesco/set-ce-single-template-orders', 'Admin\Tesco\OrderManagementCntrl@setCESingleOrders');

Route::post('admin/tesco/set-uk-ratio-template-orders', 'Admin\Tesco\OrderManagementCntrl@setUKRatioOrders');
Route::post('admin/tesco/set-uk-single-template-orders', 'Admin\Tesco\OrderManagementCntrl@setUKSingleOrders');
Route::post('admin/tesco/upload-tesco-single-po-file', 'Admin\Tesco\OrderManagementCntrl@uploadSingleOrdersPO');

//Template Management
Route::get('admin/orchestra/template-management', 'Admin\Orchestra\TemplateManagementCntrl@index');
Route::post('admin/orchestra/template-management/create-template-view', 'Admin\Orchestra\TemplateManagementCntrl@orchestraCreateTemplateView');

//Reorder

Route::post('admin/tesco/order-management/reorder', 'Admin\Tesco\OrderManagementCntrl@reOrder');
Route::post('admin/tesco/order-management/reorder-artwork', 'Admin\Tesco\OrderManagementCntrl@reOrderArtwork');
Route::post('admin/tesco/order-management/extra-quantity', 'Admin\Tesco\OrderManagementCntrl@extraQuantity');
Route::post('admin/tesco/order-management/set-tesco-reorder', 'Admin\Tesco\OrderManagementCntrl@setTescoReorder');
Route::post('admin/tesco/order-management/set-tesco-repeat-order', 'Admin\Tesco\OrderManagementCntrl@setTescoRepeatorder');


// Route::get('upload', 'UploadController@index');
// Route::post('admin/order-management/upload', ['as'=>'dropzone.store','uses'=>'UploadController@csvUpload']);
// Route::post('dropzone/store', ['as'=>'dropzone.store','uses'=>'HomeController@dropzoneStore']);

Route::post('admin/orchestra/template-management/template-list', 'Admin\Orchestra\TemplateManagementCntrl@templateList');

Route::post('admin/tesco/template-management/template-list', 'Admin\Tesco\TemplateManagementCntrl@templateList');

/** upload csv **/

Route::get('admin/upload-csv', 'Admin\UploadCSV\UploadCsvController@index');
Route::post('admin/upload-label-details','Admin\UploadCSV\UploadCsvController@uploadLabelDetails');
Route::post('admin/upload-parcel-details-file-one','Admin\UploadCSV\UploadCsvController@uploadParcelDetailsFileOne');
Route::post('admin/upload-parcel-details-file-two','Admin\UploadCSV\UploadCsvController@uploadParcelDetailsFileTwo');
Route::get('admin/csv-updates', 'Admin\UploadCSV\UploadCsvController@index');


/**orchestra Settings Page**/
Route::get('admin/settings', 'Admin\Settings\Orchestra\OrchestraSettings@index');
Route::post('admin/orchestra/settings/garments', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraGarments');
Route::post('admin/orchestra/add-orchestra-garments', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraGarments');
Route::get('admin/orchestra/get-orchestra-garments-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraGarmentsById');
Route::put('admin/orchestra/update-orchestra-garments', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraGarmentsById');
Route::delete('admin/orchestra/delete-orchestra-garment', 'Admin\Settings\Orchestra\OrchestraSettings@deleteOrchestraGarmentsById');
Route::post('admin/orchestra/settings/age-group', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraAgeGroup');
Route::post('admin/orchestra/add-orchestra-agegroup', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraAgeGroup');
Route::get('admin/orchestra/get-orchestra-agegroup-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraAgeGroupById');
Route::put('admin/orchestra/update-orchestra-agegroup', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraAgeGroupById');
Route::delete('admin/orchestra/delete-orchestra-agegroup', 'Admin\Settings\Orchestra\OrchestraSettings@deleteOrchestraAgeGroupsById');
Route::post('admin/orchestra/settings/countries', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraCountries');
Route::post('admin/orchestra/add-orchestra-country', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraCountry');
Route::get('admin/orchestra/get-orchestra-country-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraCountryById');
Route::put('admin/orchestra/update-orchestra-country', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraCountryById');
Route::delete('admin/orchestra/delete-orchestra-country', 'Admin\Settings\Orchestra\OrchestraSettings@deleteOrchestraCountryById');
Route::post('admin/orchestra/settings/productfamily', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraProductFamily');
Route::post('admin/orchestra/add-orchestra-productfamily', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraProductFamily');
Route::get('admin/orchestra/get-orchestra-productfamily-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraProductFamilyById');
Route::put('admin/orchestra/update-orchestra-productfamily', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraProductFamilyById');
Route::delete('admin/orchestra/delete-orchestra-productfamily', 'Admin\Settings\Orchestra\OrchestraSettings@deleteOrchestraProductFamilyById');
Route::post('admin/orchestra/settings/special-instructions', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraSpecialInstructions');
Route::post('admin/orchestra/add-orchestra-special-instructions', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraSpecialInstructions');
Route::get('admin/orchestra/get-orchestra-special-instruction-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraSpecialInstructionById');
Route::put('admin/orchestra/update-orchestra-special-instruction', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraSpecialInstructionById');
Route::delete('admin/orchestra/delete-orchestra-special-instruction', 'Admin\Settings\Orchestra\OrchestraSettings@deleteOrchestraSpecialInstructionById');
Route::post('admin/orchestra/settings/compositions', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraCompositions');
Route::post('admin/orchestra/add-orchestra-compositions', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraCompositions');
Route::get('admin/orchestra/get-orchestra-composition-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraCompositionById');
Route::put('admin/orchestra/update-orchestra-composition', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraCompositionById');
Route::delete('admin/orchestra/delete-orchestra-composition', 'Admin\Settings\Orchestra\OrchestraSettings@deleteOrchestraCompositionById');
Route::post('admin/orchestra/settings/parts', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraParts');
Route::post('admin/orchestra/add-orchestra-parts', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraParts');
Route::get('admin/orchestra/get-orchestra-part-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraPartById');
Route::put('admin/orchestra/update-orchestra-part', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraPartById');
Route::delete('admin/orchestra/delete-orchestra-part', 'Admin\Settings\Orchestra\OrchestraSettings@deleteOrchestraPartById');
Route::post('admin/orchestra/settings/size-chart', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraSizeCharts');
Route::post('admin/orchestra/add-orchestra-size-chart', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraSizeCharts');
Route::get('admin/orchestra/get-orchestra-size-chart-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraSizeChartById');
Route::put('admin/orchestra/update-orchestra-size-chart', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraSizeChartById');
Route::delete('admin/orchestra/delete-orchestra-size-chart', 'Admin\Settings\Orchestra\OrchestraSettings@deleteOrchestraSizeChartById');
Route::post('admin/orchestra/settings/symbols', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraSymbols');
Route::post('admin/orchestra/settings/addOrchestraSymbols', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraSymbols');
Route::post('admin/orchestra/settings/editOrchestraSymbols', 'Admin\Settings\Orchestra\OrchestraSettings@editOrchestraSymbols');
Route::delete('admin/orchestra/settings/deleteOrchestraSymbol', 'Admin\Settings\Orchestra\OrchestraSettings@deleteOrchestraSymbolById');

/**tesco Settings Page**/
Route::post('admin/tesco/settings/brands', 'Admin\Settings\Tesco\TescoSettings@tescoBrands');
Route::post('admin/tesco/add-tesco-brands', 'Admin\Settings\Tesco\TescoSettings@addTescoBrands');
Route::get('admin/tesco/get-tesco-brands-details', 'Admin\Settings\Tesco\TescoSettings@getTescoBrandsById');
Route::put('admin/tesco/update-tesco-brands', 'Admin\Settings\Tesco\TescoSettings@updateTescoBrandsById');
Route::delete('admin/tesco/delete-tesco-brand', 'Admin\Settings\Tesco\TescoSettings@deleteTescoBrandsById');
Route::put('admin/tesco/make-brand-enabled', 'Admin\Settings\Tesco\TescoSettings@makeBrandEnabled');
Route::put('admin/tesco/make-brand-disabled', 'Admin\Settings\Tesco\TescoSettings@makeBrandDisabled');
Route::post('admin/tesco/settings/departments', 'Admin\Settings\Tesco\TescoSettings@tescoDepartments');
Route::post('admin/tesco/add-tesco-departments', 'Admin\Settings\Tesco\TescoSettings@addTescoDepartment');
Route::get('admin/tesco/get-tesco-departments-details', 'Admin\Settings\Tesco\TescoSettings@getTescoDepartmentsById');
Route::put('admin/tesco/update-tesco-departments', 'Admin\Settings\Tesco\TescoSettings@updateTescoDepartmentsById');
Route::delete('admin/tesco/delete-tesco-department', 'Admin\Settings\Tesco\TescoSettings@deleteTescoDepartmentsById');
Route::post('admin/tesco/settings/suppliers', 'Admin\Settings\Tesco\TescoSettings@tescoSuppliers');
Route::post('admin/tesco/add-tesco-suppliers', 'Admin\Settings\Tesco\TescoSettings@addTescoSuppliers');
Route::get('/admin/tesco/get-tesco-suppliers-details', 'Admin\Settings\Tesco\TescoSettings@getTescoSuppliersById');
Route::put('/admin/tesco/update-tesco-suppliers', 'Admin\Settings\Tesco\TescoSettings@updateTescoSuppliersById');
Route::delete('admin/tesco/delete-tesco-supplier', 'Admin\Settings\Tesco\TescoSettings@deleteTescoSuppliersById');
Route::put('/admin/tesco/make-supplier-enabled', 'Admin\Settings\Tesco\TescoSettings@makeSupplierEnabled');
Route::put('/admin/tesco/make-supplier-disabled', 'Admin\Settings\Tesco\TescoSettings@makeSupplierDisabled');
Route::post('admin/tesco/settings/sizechart', 'Admin\Settings\Tesco\TescoSettings@tescoSizeChart');
Route::post('admin/tesco/add-tesco-size-charts', 'Admin\Settings\Tesco\TescoSettings@addTescoSizeCharts');
Route::get('admin/tesco/get-tesco-Sizecharts-details', 'Admin\Settings\Tesco\TescoSettings@getTescoSizeChartsById');
Route::put('admin/tesco/update-tesco-sizecharts', 'Admin\Settings\Tesco\TescoSettings@updateTescoSizechartsById');
Route::delete('admin/tesco/delete-tesco-sizecharts', 'Admin\Settings\Tesco\TescoSettings@deleteTescosizechartsById');

/** Tesco order-status**/
Route::post('admin/tesco/tesco-orders', 'Admin\Tesco\OrderStatusCntrl@index');
Route::post('admin/tesco/order-management/set-po-number', 'Admin\Tesco\OrderStatusCntrl@setPONumber');
Route::post('admin/tesco/order-management/show-tesco-pi', 'Admin\Tesco\OrderStatusCntrl@getTescoPI');
Route::post('admin/tesco/approve-tesco-pi', 'Admin\Tesco\OrderStatusCntrl@approveTescoPI');
Route::post('admin/tesco/reject-tesco-pi', 'Admin\Tesco\OrderStatusCntrl@rejectTescoPI');
Route::post('admin/tesco/edit-artwork', 'Admin\Tesco\OrderStatusCntrl@editArtwork');
Route::get('admin/tesco/get-artworks', 'Admin\Tesco\OrderStatusCntrl@getArtwork');
Route::post('admin/tesco/update-po', 'Admin\Tesco\OrderStatusCntrl@updateArtworkPO');
Route::post('admin/tesco/copy-po', 'Admin\Tesco\OrderStatusCntrl@copyArtworkPO');
//Route::get('admin/tesco/PDFMiner', 'Admin\Tesco\OrderManagementCntrl@createOrderByPDFMiner');