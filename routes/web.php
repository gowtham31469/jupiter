<?php

use Illuminate\Http\Request;
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

$this->get('admin', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('admin', 'Auth\LoginController@login');
$this->post('admin/logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes for Admin...
$this->get('admin/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('admin/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('admin/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('admin/password/reset', 'Auth\ResetPasswordController@reset');

// Email related routes
Route::post('feedback-mail/send', 'Mail\MailController@sendFeedback');


// Update financial year in session
Route::post('jga/set-financial-year-session', function (Request $request) {
	Session::put('fiscal-year', $request['year']);
	return response()->json(['message' => 'success'], 200);
});

Route::prefix('admin')->group(function () {
	Route::get('/dashboard', 'HomeController@index')->name('admin');
	Route::get('/non-artwork-template-management', 'Admin\TemplateManagement\NonArtworkTemplateController@templateList');
	/** upload csv **/
	Route::get('/upload-csv', 'Admin\UploadCSV\UploadCsvController@index');
	Route::get('/upload-translation', 'Admin\UploadCSV\UploadCsvController@uploadTranslation');
	Route::post('/upload-translation-csv', 'Admin\UploadCSV\UploadCsvController@uploadTranslationCsv');
	Route::get('/upload-size-chart', 'Admin\UploadCSV\UploadCsvController@uploadSizeChart');
	Route::post('/upload-size-chart-csv', 'Admin\UploadCSV\UploadCsvController@uploadSizeChartCsv');
	Route::post('/upload-orchestra-order-csv-files', 'Admin\UploadCSV\UploadOrchestraCSVController@uploadOrchestraOrderCSVFile');
	Route::get('/csv-conflicts', 'Admin\UploadCSV\CSVDataHandlingController@conflictsCSV');
	Route::get('/csv-conflicts-data', 'Admin\UploadCSV\CSVDataHandlingController@getConflictsCSVData')->name('csv-conflicts-data');
	Route::get('/partial-csv', 'Admin\UploadCSV\CSVDataHandlingController@partialCSV');
	Route::get('/partial-csv-data', 'Admin\UploadCSV\CSVDataHandlingController@getPartialCSVData')->name('partial-csv-data');
	Route::get('/get-uvc-data', 'Admin\UploadCSV\CSVDataHandlingController@getDataByUVC');
	Route::post('/confirm-uvc-data', 'Admin\UploadCSV\CSVDataHandlingController@confirmUVCOrders');
	Route::post('/cancel-uvc-data', 'Admin\UploadCSV\CSVDataHandlingController@cancelUVCOrders');
	Route::get('/csv-conflicts-page', 'Admin\UploadCSV\CSVDataHandlingController@orderChangesPage');
	Route::get('/get-order-changes-data', 'Admin\UploadCSV\CSVDataHandlingController@getOrderChangesData');
	Route::get('/common-settings', 'Admin\Settings\SettingsController@index');
	Route::post('/settings/orchestra/add-color-code', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraColorCode');
	/**orchestra Settings Page**/
	Route::get('/settings', 'Admin\Settings\Orchestra\OrchestraSettings@index');
	/** admin role Management **/
	Route::get('/admin-role-management', 'Admin\AdminRoleManagement\AdminRoleManagementController@index');
	Route::get('/admin-role-management/admin-list', 'Admin\AdminRoleManagement\AdminRoleManagementController@adminList');
	Route::post('/admin-role-management/add-new-admin', 'Admin\AdminRoleManagement\AdminRoleManagementController@addNewAdmin');
	Route::put('/admin-role-management/make-admin-active', 'Admin\AdminRoleManagement\AdminRoleManagementController@makeAdminActive');
	Route::put('/admin-role-management/make-admin-inactive', 'Admin\AdminRoleManagement\AdminRoleManagementController@makeAdminInactive');
	/** Admin profile related routes */
	Route::get('/get-profile-details', 'Admin\AdminProfile\AdminProfileController@getAdminDetailsById');
	Route::put('/update-admin-profile-details', 'Admin\AdminProfile\AdminProfileController@updateAdminById');
	Route::put('/update-admin-password', 'Admin\AdminProfile\AdminProfileController@updateAdminPassword');
	/** admin role Management **/
	Route::get('/audit-trail', 'Admin\AuditTrail\AuditTrailController@index');
	Route::get('/audit-trail/get-comparison-details', 'Admin\AuditTrail\AuditTrailController@getComparisonDataById');

	Route::prefix('/order-management')->group(function () {
		Route::get('/', 'Admin\OrderManagement\OrderController@dashboard');
		Route::post('/new_orders', 'Admin\OrderManagement\DashboardController@newOrders');
		Route::post('/aowp', 'Admin\OrderManagement\DashboardController@aowp');
		Route::post('/approved_orders', 'Admin\OrderManagement\DashboardController@approvedOrders');
		Route::post('/pending_orders', 'Admin\OrderManagement\DashboardController@pendingOrders');
		Route::post('/viewed_pen_production', 'Admin\OrderManagement\DashboardController@viewedPenProduction');
		Route::post('/viewed_pen_production_all', 'Admin\OrderManagement\DashboardController@viewedPenProductionAll');
		Route::post('/viewedneworders', 'Admin\OrderManagement\DashboardController@viewedNewOrders');
		Route::post('/viewedneworders_all', 'Admin\OrderManagement\DashboardController@viewedNewOrdersAll');
		Route::post('/viewedpenorders', 'Admin\OrderManagement\DashboardController@viewedPenOrders');
		Route::post('/viewedpenorders_all', 'Admin\OrderManagement\DashboardController@viewedPenOrdersAll');
		Route::post('/viewedapporders_all', 'Admin\OrderManagement\DashboardController@viewedAppOrdersAll');
		Route::post('/viewedapporders', 'Admin\OrderManagement\DashboardController@viewedAppOrders');
		Route::post('/viewedaowp', 'Admin\OrderManagement\DashboardController@viewedAOWP');
		Route::post('/viewedaowp_all', 'Admin\OrderManagement\DashboardController@viewedAOWPAll');
		Route::get('/orders', 'Admin\OrderManagement\OrderController@orders');
		Route::get('/order-status', 'Admin\OrderManagement\OrderController@orderStatus');
		Route::get('/non-artwork-orders', 'Admin\OrderManagement\OrderController@nonArtworkOrders');
		Route::get('/reorder', 'Admin\OrderManagement\OrderController@reorder');
		Route::post('/print-pdf', 'Admin\OrderManagement\OrderController@printPdf');
		Route::post('/print-pdf-arr', 'Admin\OrderManagement\OrderController@printPdfArr');
		Route::post('/print-pdf-small', 'Admin\OrderManagement\OrderController@printPdfSmall');
		Route::post('/print-pdf-table', 'Admin\OrderManagement\OrderController@printPdfTable');
		Route::post('/proof-pdf', 'Admin\OrderManagement\OrderController@proofPdf');
		Route::post('/proof-pdf-arr', 'Admin\OrderManagement\OrderController@proofPdfArr');
		Route::post('/proof-pdf-small', 'Admin\OrderManagement\OrderController@proofPdfSmall');
		Route::post('/proof-pdf-table', 'Admin\OrderManagement\OrderController@proofPdfTable');
		Route::post('/print-hang-pdf', 'Admin\OrderManagement\OrderController@printHangPdf');
		Route::post('/print-special-pdf', 'Admin\OrderManagement\OrderController@printSpecialPdf');
		Route::get('/get-tsl-size', 'Admin\OrderManagement\OrderController@getTescoTSLSizechart');
		Route::post('/print-wash-pdf', 'Admin\OrderManagement\OrderController@printWashPdf');
	});
	Route::prefix('/template-management')->group(function () {
		Route::get('/', 'Admin\TemplateManagement\TemplateController@templateList');
		Route::post('/template-hierarchy', 'Admin\TemplateManagement\TemplateController@templateHierarchy');
		Route::post('/save-taxation', 'Admin\TemplateManagement\TemplateController@saveTax');
		Route::post('/add-template', 'Admin\TemplateManagement\TemplateController@addTemplate');
		Route::post('/template-view', 'Admin\TemplateManagement\TemplateController@templateView');
		Route::get('/get-company-list', 'Admin\TemplateManagement\TemplateController@getCompanyList');
		Route::get('/get-spectrra-company-metadata', 'Admin\TemplateManagement\SpectrraMetadataController@getSpectrraTemplateDetails');
		Route::post('/submit-spectrra-metadata', 'Admin\TemplateManagement\SpectrraMetadataController@storeSpectrraTemplateDetails');
		Route::post('/base64_to_jpeg', 'Admin\TemplateManagement\TemplateController@base64ToJpeg');
	});
	/** customer Management **/
	Route::prefix('/customer-management')->group(function () {
		Route::get('/', 'Admin\CustomerManagement\CustomerManagementController@index');
		Route::post('/customers-list', 'Admin\CustomerManagement\CustomerManagementController@customersList');
		Route::post('/add-customer', 'Admin\CustomerManagement\CustomerManagementController@addCustomer');
		Route::put('/make-customer-active', 'Admin\CustomerManagement\CustomerManagementController@makeCustomerActive');
		Route::put('/make-customer-inactive', 'Admin\CustomerManagement\CustomerManagementController@makeCustomerInactive');
		Route::get('/get-customers', 'Admin\CustomerManagement\CustomerManagementController@getCustomerById');
		Route::put('/update-customer', 'Admin\CustomerManagement\CustomerManagementController@updateCustomer');
		Route::get('/company', 'Admin\CustomerManagement\CustomerManagementController@customers');
		Route::get('/pricing-taxation', 'Admin\CustomerManagement\CustomerManagementController@custumerPriceList');
		Route::get('/get-buyers-customers', 'Admin\CustomerManagement\CustomerManagementController@getBuyersCustomers');
		Route::post('/save-price-tax', 'Admin\CustomerManagement\CustomerManagementController@savePriceTax');
		Route::get('/get-price-tax', 'Admin\CustomerManagement\CustomerManagementController@getPriceTax');
		Route::get('/get-customer-details', 'Admin\CustomerManagement\CustomerManagementController@getCustomerDetailsById');
		Route::get('/customers', 'Admin\CustomerManagement\CustomerManagementController@customerProfile');
		Route::put('/make-customer-profile-active', 'Admin\CustomerManagement\CustomerManagementController@makeCustomerProfileActive');
		Route::put('/make-customer-profile-inactive', 'Admin\CustomerManagement\CustomerManagementController@makeCustomerProfileInactive');
		Route::get('/get-customers-profile', 'Admin\CustomerManagement\CustomerManagementController@getCustomerProfileById');
		Route::put('/update-customerProfile', 'Admin\CustomerManagement\CustomerManagementController@updateCustomerProfile');
		Route::post('/add-customerProfile', 'Admin\CustomerManagement\CustomerManagementController@addCustomerProfile');
		Route::post('/customer-company-buyer', 'Admin\CustomerManagement\CustomerManagementController@customerCompanyBuyer');
	});
	/** buyer Management **/
	Route::prefix('/buyer-management')->group(function () {
		Route::get('/', 'Admin\BuyerManagement\BuyerManagementController@index');
		Route::get('/buyers-list', 'Admin\BuyerManagement\BuyerManagementController@buyerList');
		Route::post('/add-jga-buyer', 'Admin\BuyerManagement\BuyerManagementController@addJgaBuyer');
		Route::get('/get-buyer-details', 'Admin\BuyerManagement\BuyerManagementController@getBuyerById');
		Route::post('/update-buyer-details', 'Admin\BuyerManagement\BuyerManagementController@updateBuyerById');
		Route::put('/make-buyer-active', 'Admin\BuyerManagement\BuyerManagementController@makeBuyerActive');
		Route::put('/make-buyer-inactive', 'Admin\BuyerManagement\BuyerManagementController@makeBuyerInactive');
		Route::get('/buyers', 'Admin\BuyerManagement\BuyerManagementController@buyers');
		Route::get('/merchandiser-list', 'Admin\BuyerManagement\BuyerManagementController@merchandiserList');
		Route::post('/add-merchandiser', 'Admin\BuyerManagement\BuyerManagementController@addMerchandiser');
		Route::get('/get-merchandiser-details', 'Admin\BuyerManagement\BuyerManagementController@getMerchandiserById');
		Route::put('/update-merchandiser-details', 'Admin\BuyerManagement\BuyerManagementController@updateMerchandiserById');
		Route::put('/make-merchandiser-active', 'Admin\BuyerManagement\BuyerManagementController@makeMerchandiserActive');
		Route::put('/make-merchandiser-inactive', 'Admin\BuyerManagement\BuyerManagementController@makeMerchandiserInactive');
		Route::get('/merchandisers', 'Admin\BuyerManagement\BuyerManagementController@merchandisers');
	});
	Route::prefix('/non-artwork-template')->group(function () {
		Route::post('/add-new-template', 'Admin\TemplateManagement\NonArtworkTemplateController@addTemplate');
		Route::post('/template-view', 'Admin\TemplateManagement\NonArtworkTemplateController@templateView');
		Route::get('/get-select-list', 'Admin\TemplateManagement\NonArtworkTemplateController@getSelectList');
		Route::post('/update-template', 'Admin\TemplateManagement\NonArtworkTemplateController@updateTemplate');
		Route::post('/template-hierarchy', 'Admin\TemplateManagement\NonArtworkTemplateController@templateHierarchy');
		Route::post('/rename-template', 'Admin\TemplateManagement\NonArtworkTemplateController@renameTemplate');
	});
	//Orchestra Order Management - Admin
	Route::prefix('/orchestra')->group(function () {
		Route::prefix('/order-management')->group(function () {
			Route::get('/', 'Admin\Orchestra\OrderManagementCntrl@index');
			Route::post('/label-orders', 'Admin\Orchestra\OrderManagementCntrl@labelOrders');
			Route::post('/reorder', 'Admin\Orchestra\OrderManagementCntrl@reOrder');
			Route::post('/reorder-artwork', 'Admin\Orchestra\OrderManagementCntrl@reOrderArtwork');
			Route::post('/extra-quantity', 'Admin\Orchestra\OrderManagementCntrl@extraQuantity');
			Route::post('/back-label-orders', 'Admin\Orchestra\OrderManagementCntrl@backLabelOrders');
			Route::post('/back-sticker-orders', 'Admin\Orchestra\OrderManagementCntrl@backStickerOrders');
			Route::post('/sticker-orders', 'Admin\Orchestra\OrderManagementCntrl@stickerOrders');
			Route::post('/label-order-details', 'Admin\Orchestra\OrderManagementCntrl@labelOrderDetails');
			Route::post('/sticker-order-details', 'Admin\Orchestra\OrderManagementCntrl@stickerOrderDetails');
			Route::post('/season_code_filter', 'Admin\Orchestra\OrderManagementCntrl@seasonCodeFilter');
			Route::post('/orders', 'Admin\Orchestra\OrderManagementCntrl@Orders');
			Route::post('/set-po-number', 'Admin\Orchestra\OrderManagementCntrl@setPONumber');
			Route::post('/show-orchestra-pi', 'Admin\Orchestra\OrderManagementCntrl@getOrchestraPI');
			Route::post('/set_hang_tag', 'Admin\Orchestra\OrderManagementCntrl@setHangTag');
			Route::post('/remove_hang_tag', 'Admin\Orchestra\OrderManagementCntrl@removeHangTag');
			Route::post('/remove_special_tag', 'Admin\Orchestra\OrderManagementCntrl@removeSpecialTag');
			Route::post('/remove_heat_transfer', 'Admin\Orchestra\OrderManagementCntrl@removeHeatTransfer');
			Route::post('/remove_main_label', 'Admin\Orchestra\OrderManagementCntrl@removeMainLabel');
			Route::post('/remove_size_label', 'Admin\Orchestra\OrderManagementCntrl@removeSizeLabel');
			Route::post('/remove_standard_stickers', 'Admin\Orchestra\OrderManagementCntrl@removeStandardStickers');
			Route::post('/remove_solid_stickers', 'Admin\Orchestra\OrderManagementCntrl@removeSolidStickers');
			Route::post('/remove_wcl', 'Admin\Orchestra\OrderManagementCntrl@removeWCL');
			Route::post('/set_heat_transfer', 'Admin\Orchestra\OrderManagementCntrl@setHeatTransfer');
			Route::post('/set_special_tag', 'Admin\Orchestra\OrderManagementCntrl@setSpecialTag');
			Route::post('/set_main_label', 'Admin\Orchestra\OrderManagementCntrl@setMainLabel');
			Route::post('/set_size_label', 'Admin\Orchestra\OrderManagementCntrl@setSizeLabel');
			Route::post('/remove_wash_label', 'Admin\Orchestra\OrderManagementCntrl@removeWashLabel');
			Route::post('/set_wash_label', 'Admin\Orchestra\OrderManagementCntrl@setWashLabel');
			Route::post('/set_wash_labelos', 'Admin\Orchestra\OrderManagementCntrl@setWashLabelOs');
			Route::post('/set_solid_stickers', 'Admin\Orchestra\OrderManagementCntrl@setSolidStickers');
			Route::post('/set_solid_stickers_os', 'Admin\Orchestra\OrderManagementCntrl@setSolidStickersOs');
			Route::post('/set_solid_poly_bag_stickers_os', 'Admin\Orchestra\OrderManagementCntrl@setSolidPolyBagStickersOs');
			Route::post('/set_standard_carton_stickers_os', 'Admin\Orchestra\OrderManagementCntrl@setStandardCartonStickersOs');
			Route::post('/set_standard_poly_bag_stickers_os', 'Admin\Orchestra\OrderManagementCntrl@setStandardPolyBagStickersOs');
			Route::post('/set_standard_stickers', 'Admin\Orchestra\OrderManagementCntrl@setStandardStickers');
			Route::get('/get-existing-label-orders-po', 'Admin\Orchestra\OrderManagementCntrl@getExistingLabelOrdersPo');
			Route::get('/get-existing-standard-sticker-orders-po', 'Admin\Orchestra\OrderManagementCntrl@getExistingStandardStickerOrdersPo');
			Route::get('/get-existing-solid-sticker-orders-po', 'Admin\Orchestra\OrderManagementCntrl@getExistingSolidStickerOrdersPo');
			Route::get('/get-existing-label-orders-status-po', 'Admin\Orchestra\OrderManagementCntrl@getExistingLabelOrderStatusPo');
			Route::Post('/map-label-orders-po', 'Admin\Orchestra\OrderManagementCntrl@mapLabelOrdersPo');
			Route::Post('/update-label-orders-po', 'Admin\Orchestra\OrderManagementCntrl@updateLabelOrdersPO');
		});
		Route::post('/set-po-file', 'Admin\Orchestra\OrderManagementCntrl@setCustomerPOFile');
		Route::post('/approve-orchestra-pi', 'Admin\Orchestra\OrderManagementCntrl@approveOrchestraPI');
		Route::post('/reject-orchestra-pi', 'Admin\Orchestra\OrderManagementCntrl@rejectOrchestraPI');
		Route::post('/download-orchestra-pi', 'Admin\Orchestra\OrderManagementCntrl@downloadOrchestraPI');
		Route::get('/get-erp-status', 'Admin\Orchestra\OrderManagementCntrl@checkERPStatus');
		Route::post('/orchestra-orders-filter', 'Admin\Orchestra\OrderStatusFilters@orchestraOrdersFilter');
		Route::post('/orchestra-orders-filter-by-daterange', 'Admin\Orchestra\OrderStatusFilters@orchestraOrdersFilterByDateRange');
		Route::post('/get-orchestra-orders', 'Admin\Orchestra\OrderStatusFilters@getOrchestraOrders');
		Route::post('/toggle-orchestra-edit-permission', 'Admin\Orchestra\OrderStatusCntrl@toggleArtworkEditPermission');

		Route::post('/get-order-details', 'Admin\Orchestra\OrderStatusCntrl@getOrderDetails');
		Route::post('/edit-artwork', 'Admin\Orchestra\OrderStatusCntrl@editArtwork');
		Route::post('/settings/garments', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraGarments');
		Route::post('/add-orchestra-garments', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraGarments');
		Route::get('/get-orchestra-garments-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraGarmentsById');
		Route::put('/update-orchestra-garments', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraGarmentsById');
		Route::put('/make-garment-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeGarmentEnabled');
		Route::put('/make-garment-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeGarmentDisabled');

		Route::post('/settings/age-group', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraAgeGroup');
		Route::post('/add-orchestra-agegroup', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraAgeGroup');
		Route::get('/get-orchestra-agegroup-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraAgeGroupById');
		Route::put('/update-orchestra-agegroup', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraAgeGroupById');
		Route::put('/make-agegroup-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeAgeGroupEnabled');
		Route::put('/make-agegroup-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeAgeGroupDisabled');

		Route::post('/settings/countries', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraCountries');
		Route::post('/add-orchestra-country', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraCountry');
		Route::get('/get-orchestra-country-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraCountryById');
		Route::put('/update-orchestra-country', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraCountryById');
		Route::put('/make-country-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeCountryEnabled');
		Route::put('/make-country-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeCountryDisabled');

		Route::post('/settings/productfamily', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraProductFamily');
		Route::post('/add-orchestra-productfamily', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraProductFamily');
		Route::get('/get-orchestra-productfamily-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraProductFamilyById');
		Route::put('/update-orchestra-productfamily', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraProductFamilyById');
		Route::put('/make-productfamily-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeProductFamilyEnabled');
		Route::put('/make-productfamily-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeProductFamilyDisabled');

		Route::post('/settings/special-instructions', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraSpecialInstructions');
		Route::post('/add-orchestra-special-instructions', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraSpecialInstructions');
		Route::get('/get-orchestra-special-instruction-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraSpecialInstructionById');
		Route::put('/update-orchestra-special-instruction', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraSpecialInstructionById');
		Route::put('/make-special-instruction-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeSpecialInstructionEnabled');
		Route::put('/make-special-instruction-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeSpecialInstructionDisabled');

		Route::post('/settings/compositions', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraCompositions');
		Route::post('/add-orchestra-compositions', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraCompositions');
		Route::get('/get-orchestra-composition-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraCompositionById');
		Route::put('/update-orchestra-composition', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraCompositionById');
		Route::put('/make-composition-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeCompositionEnabled');
		Route::put('/make-composition-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeCompositionDisabled');

		Route::post('/settings/parts', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraParts');
		Route::post('/add-orchestra-parts', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraParts');
		Route::get('/get-orchestra-part-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraPartById');
		Route::put('/update-orchestra-part', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraPartById');
		Route::put('/make-part-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makePartEnabled');
		Route::put('/make-part-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makePartDisabled');

		Route::post('/settings/color', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraColorCode');
		Route::get('/get-orchestra-color-code-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraColorCodeById');
		Route::put('/make-color-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeColorEnabled');
		Route::put('/make-color-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeColorDisabled');
		Route::put('/update-orchestra-color-code', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraColorCodeById');

		Route::post('/settings/size-chart', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraSizeCharts');
		Route::post('/add-orchestra-size-chart', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraSizeCharts');
		Route::get('/get-orchestra-size-chart-details', 'Admin\Settings\Orchestra\OrchestraSettings@getOrchestraSizeChartById');
		Route::put('/update-orchestra-size-chart', 'Admin\Settings\Orchestra\OrchestraSettings@updateOrchestraSizeChartById');
		Route::put('/make-size-chart-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeSizeChartEnabled');
		Route::put('/make-size-chart-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeSizeChartDisabled');

		Route::post('/settings/symbols', 'Admin\Settings\Orchestra\OrchestraSettings@orchestraSymbols');
		Route::post('/settings/addOrchestraSymbols', 'Admin\Settings\Orchestra\OrchestraSettings@addOrchestraSymbols');
		Route::post('/settings/editOrchestraSymbols', 'Admin\Settings\Orchestra\OrchestraSettings@editOrchestraSymbols');
		Route::put('/make-symbol-enabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeSymbolEnabled');
		Route::put('/make-symbol-disabled', 'Admin\Settings\Orchestra\OrchestraSettings@makeSymbolDisabled');

		Route::post('/order-management/compositions-details', 'Admin\Orchestra\OrderManagementCntrl@compositionDetails');
		Route::post('/order-management/po_select_wcl', 'Admin\Orchestra\OrderManagementCntrl@poSelectWcl');
		//Template Management
		Route::get('/template-management', 'Admin\Orchestra\TemplateManagementCntrl@index');
		Route::post('/template-management/template-list', 'Admin\Orchestra\TemplateManagementCntrl@templateList');
		Route::post('/template-management/create-template-view', 'Admin\Orchestra\TemplateManagementCntrl@orchestraCreateTemplateView');
		Route::get('/template-management/get-company-list', 'Admin\Orchestra\TemplateManagementCntrl@getCompanyList');
		Route::post('/template-management/template-list-view', 'Admin\Orchestra\TemplateManagementCntrl@templateListView');
		Route::post('/template-management/template-view', 'Admin\Orchestra\TemplateManagementCntrl@templateView');
	});
	/** Tesco order-management**/
	Route::prefix('/tesco')->group(function () {
		Route::prefix('/order-management')->group(function () {
			Route::get('/', 'Admin\Tesco\OrderManagementCntrl@index');
			//Repeat Order
			Route::post('/orders', 'Admin\Tesco\OrderManagementCntrl@Orders');
			Route::post('/extra-quantity-label-tesco-repeatOrder', 'Admin\Tesco\OrderManagementCntrl@extraQuantityLabelTescoRepeatOrder');
			//Reorder
			Route::post('/reorder', 'Admin\Tesco\OrderManagementCntrl@reOrder');
			Route::post('/reorder-artwork', 'Admin\Tesco\OrderManagementCntrl@reOrderArtwork');
			Route::post('/repeatorder-artwork', 'Admin\Tesco\OrderManagementCntrl@repeatOrderArtwork');
			Route::post('/extra-quantity', 'Admin\Tesco\OrderManagementCntrl@extraQuantity');
			Route::post('/extra-quantity-label-tesco', 'Admin\Tesco\OrderManagementCntrl@extraQuantityLabelTesco');
			Route::post('/extra-quantity-st-tesco', 'Admin\Tesco\OrderManagementCntrl@extraQuantityStTesco');
			Route::post('/extra-quantity-reorder', 'Admin\Tesco\OrderManagementCntrl@extraQuantityReorder');
			Route::post('/set-tesco-reorder', 'Admin\Tesco\OrderManagementCntrl@setTescoReorder');
			Route::post('/set-tesco-repeat-order', 'Admin\Tesco\OrderManagementCntrl@setTescoRepeatorder');
		});
		Route::post('/template-management/template-view', 'Admin\Tesco\TemplateManagementCntrl@templateView');
		Route::get('/template-management/get-company', 'Admin\Tesco\TemplateManagementCntrl@getCompany');
		Route::get('/template-management', 'Admin\Tesco\TemplateManagementCntrl@index');
		Route::get('/templates-details', 'Admin\Tesco\OrderManagementCntrl@getTescoTemplateDetails');
		Route::post('/po-upload', 'Admin\Tesco\OrderManagementCntrl@createStickerOrders');
		Route::post('/view-order', 'Admin\Tesco\OrderManagementCntrl@viewOrders');
		Route::get('/get-tsl-size', 'Admin\Tesco\OrderManagementCntrl@getTescoTSLSizechart');
		Route::get('/get-moq-value', 'Admin\Tesco\OrderManagementCntrl@getTescoTemplateMoq');
		Route::post('/print-div-tesco', 'Admin\Tesco\OrderManagementCntrl@printDivTesco');
		Route::post('/print-div-tesco-small', 'Admin\Tesco\OrderManagementCntrl@printDivTescoSmall');
		Route::post('/generate-div-tesco', 'Admin\Tesco\OrderManagementCntrl@generateDivTesco');
		Route::post('/generate-div-tesco-small', 'Admin\Tesco\OrderManagementCntrl@generateDivTescoSmall');
		Route::post('/pdfbox-div-tesco-small', 'Admin\Tesco\OrderManagementCntrl@pdfboxDivTescoSmall');
		Route::post('/set-ce-ratio-template-orders', 'Admin\Tesco\OrderManagementCntrl@setCERatioOrders');
		Route::post('/set-ce-single-template-orders', 'Admin\Tesco\OrderManagementCntrl@setCESingleOrders');
		Route::post('/set-uk-ratio-template-orders', 'Admin\Tesco\OrderManagementCntrl@setUKRatioOrders');
		Route::post('/set-uk-single-template-orders', 'Admin\Tesco\OrderManagementCntrl@setUKSingleOrders');
		Route::post('/upload-tesco-combined-po-file', 'Admin\Tesco\OrderManagementCntrl@uploadCombinedOrdersPO');
		Route::put('/save-ce-ratio-template-orders', 'Admin\Tesco\OrderManagementCntrl@saveCERatioOrders');
		Route::put('/save-ce-single-template-orders', 'Admin\Tesco\OrderManagementCntrl@saveCESingleOrders');
		Route::put('/save-uk-ratio-template-orders', 'Admin\Tesco\OrderManagementCntrl@saveUKRatioOrders');
		Route::put('/save-uk-single-template-orders', 'Admin\Tesco\OrderManagementCntrl@saveUKSingleOrders');
		Route::put('/cancel-tesco-order', 'Admin\Tesco\OrderManagementCntrl@cancelTescoOrder');

		Route::post('/generatebarcodetsl1', 'Admin\Tesco\OrderManagementCntrl@generateBarcodeTsl1');
		Route::post('/generatebarcodetsl3', 'Admin\Tesco\OrderManagementCntrl@generateBarcodeTsl3');
		Route::post('/generatebarcodeeanbig', 'Admin\Tesco\OrderManagementCntrl@generateBarcodeEanBig');
		Route::post('/generatebarcodeitfbig', 'Admin\Tesco\OrderManagementCntrl@generateBarcodeItfBig');
		Route::post('/template-management/template-list', 'Admin\Tesco\TemplateManagementCntrl@templateList');

		#Tesco Wash care Symbols
		Route::post('/settings/symbols', 'Admin\Settings\Tesco\TescoSettings@tescoSymbols');
		Route::post('/settings/addTescoSymbols', 'Admin\Settings\Tesco\TescoSettings@addTescoSymbols');
		Route::put('/enable-tesco-symbol', 'Admin\Settings\Tesco\TescoSettings@enableTescoSymbol');
		Route::put('/disable-tesco-symbol', 'Admin\Settings\Tesco\TescoSettings@disableTescoSymbol');

		#Tesco Countries
		Route::post('/settings/countries', 'Admin\Settings\Tesco\TescoSettings@tescoCountries');
		Route::post('/add-tesco-country', 'Admin\Settings\Tesco\TescoSettings@addTescoCountry');
		Route::get('/get-tesco-country-details', 'Admin\Settings\Tesco\TescoSettings@getTescoCountryById');
		Route::put('/update-tesco-country', 'Admin\Settings\Tesco\TescoSettings@updateTescoCountryById');
		Route::put('/enable-tesco-country', 'Admin\Settings\Tesco\TescoSettings@enableTescoCountry');
		Route::put('/disable-tesco-country', 'Admin\Settings\Tesco\TescoSettings@disableTescoCountry');

		#Tesco Components
		Route::post('/settings/components', 'Admin\Settings\Tesco\TescoSettings@tescoComponents');
		Route::post('/add-tesco-components', 'Admin\Settings\Tesco\TescoSettings@addTescoComponents');
		Route::get('/get-tesco-components-details', 'Admin\Settings\Tesco\TescoSettings@getTescoComponentsById');
		Route::put('/update-tesco-components', 'Admin\Settings\Tesco\TescoSettings@updateTescoComponentsById');
		Route::put('/enable-tesco-components', 'Admin\Settings\Tesco\TescoSettings@enableTescoComponents');
		Route::put('/disable-tesco-components', 'Admin\Settings\Tesco\TescoSettings@disableTescoComponents');

		#Tesco Fabric list
		Route::post('/settings/fabric-lists', 'Admin\Settings\Tesco\TescoSettings@tescoFabricList');
		Route::post('/add-tesco-fabric', 'Admin\Settings\Tesco\TescoSettings@addTescoFabric');
		Route::get('/get-tesco-fabric-details', 'Admin\Settings\Tesco\TescoSettings@getTescoFabricById');
		Route::put('/update-tesco-fabric-details', 'Admin\Settings\Tesco\TescoSettings@updateTescoFabricById');
		Route::put('/enable-tesco-fabric', 'Admin\Settings\Tesco\TescoSettings@enableTescoFabric');
		Route::put('/disable-tesco-fabric', 'Admin\Settings\Tesco\TescoSettings@disableTescoFabric');

		#Tesco Kids Warning Statement
		Route::post('/settings/get-warn-stmts-lists', 'Admin\Settings\Tesco\TescoSettings@tescoKidsWarnLists');
		Route::post('/add-tesco-warn-stmt', 'Admin\Settings\Tesco\TescoSettings@addTescoWarnStmt');
		Route::get('/get-tesco-warn-stmt-details', 'Admin\Settings\Tesco\TescoSettings@getTescoWarningStmtById');
		Route::put('/update-tesco-warn-stmt-details', 'Admin\Settings\Tesco\TescoSettings@updateTescoWarningStmtById');
		Route::put('/enable-tesco-warn-stmt', 'Admin\Settings\Tesco\TescoSettings@enableTescoWarningStmt');
		Route::put('/disable-tesco-warn-stmt', 'Admin\Settings\Tesco\TescoSettings@disableTescoWarningStmt');

		#Tesco Translation
		Route::post('/settings/get-tesco-translation-lists', 'Admin\Settings\Tesco\TescoSettings@tescoTranslationLists');
		Route::post('/add-tesco-translation', 'Admin\Settings\Tesco\TescoSettings@addTescoTranslation');
		Route::get('/get-tesco-translation-details', 'Admin\Settings\Tesco\TescoSettings@getTescoTranslationById');
		Route::put('/update-tesco-translation-details', 'Admin\Settings\Tesco\TescoSettings@updateTescoTranslationById');
		Route::put('/enable-tesco-translation', 'Admin\Settings\Tesco\TescoSettings@enableTescoTranslation');
		Route::put('/disable-tesco-translation', 'Admin\Settings\Tesco\TescoSettings@disableTescoTranslation');

		#Tesco Label Size Chart
		Route::post('/settings/get-tesco-label-size-chart', 'Admin\Settings\Tesco\TescoSettings@tescoLabelSizeChartLists');
		Route::get('/settings/get-label-category-data', 'Admin\Settings\Tesco\TescoSettings@getLabelSizeCategories');
		Route::post('/settings/add-tesco-label-size-chart', 'Admin\Settings\Tesco\TescoSettings@addTescoLabelSizeChart');
		Route::post('/settings/update-tesco-label-size-chart', 'Admin\Settings\Tesco\TescoSettings@updateTescoLabelSizeChart');
		Route::put('/enable-tesco-size-chart', 'Admin\Settings\Tesco\TescoSettings@enableTescoPrimarySizeChart');
		Route::put('/disable-tesco-size-chart', 'Admin\Settings\Tesco\TescoSettings@disableTescoPrimarySizeChart');
		Route::get('/get-tesco-primary-size-details', 'Admin\Settings\Tesco\TescoSettings@getTescoPrimarySizeById');
		Route::get('/updateStatusFlag', ['as' => 'updateStatusFlag', 'uses' => 'Admin\Settings\Tesco\TescoSettings@updateStatusFlag']);

		/**tesco Settings Page**/
		Route::post('/settings/season-codes', 'Admin\Settings\Tesco\TescoSettings@tescoSeasonCodes');
		Route::post('/add-season-codes', 'Admin\Settings\Tesco\TescoSettings@addTescoSeasonCodes');
		Route::put('/make-season-code-enabled', 'Admin\Settings\Tesco\TescoSettings@makeSeasonCodeEnabled');
		Route::put('/make-season-code-disabled', 'Admin\Settings\Tesco\TescoSettings@makeSeasonCodeDisabled');
		Route::get('/get-tesco-season-code-details', 'Admin\Settings\Tesco\TescoSettings@getTescoSeasonCodeDetails');
		Route::put('/update-tesco-season-code', 'Admin\Settings\Tesco\TescoSettings@updateTescoSeasonCode');
		Route::post('/settings/tesco-product-description', 'Admin\Settings\Tesco\ProductDescriptionController@tescoProductDescriptions');
		Route::post('/add-tesco-product-description', 'Admin\Settings\Tesco\ProductDescriptionController@addTescoProductDescription');
		Route::put('/make-tesco-product-description-enabled', 'Admin\Settings\Tesco\ProductDescriptionController@enableProductDescription');
		Route::put('/make-tesco-product-description-disabled', 'Admin\Settings\Tesco\ProductDescriptionController@disableProductDescription');
		Route::get('/get-tesco-product-description', 'Admin\Settings\Tesco\ProductDescriptionController@getTescoProductDescription');
		Route::put('/update-tesco-product-description', 'Admin\Settings\Tesco\ProductDescriptionController@updateTescoProductDescription');
		Route::post('/settings/tesco-product-benfits', 'Admin\Settings\Tesco\ProductBenefitsController@tescoProductBenefits');
		Route::post('/add-tesco-product-benfits', 'Admin\Settings\Tesco\ProductBenefitsController@addTescoProductBenefits');
		Route::put('/make-tesco-product-benfits-enabled', 'Admin\Settings\Tesco\ProductBenefitsController@enableProductBenefits');
		Route::put('/make-tesco-product-benfits-disabled', 'Admin\Settings\Tesco\ProductBenefitsController@disableProductBenefits');
		Route::get('/get-tesco-product-benfits', 'Admin\Settings\Tesco\ProductBenefitsController@getTescoProductBenefits');
		Route::put('/update-tesco-product-benfits', 'Admin\Settings\Tesco\ProductBenefitsController@updateTescoProductBenefits');
		Route::post('/upload-product-benfits', 'Admin\Settings\Tesco\ProductBenefitsController@uploadTescoProductBenefits');
		Route::post('/settings/tesco-mm-product-benfits', 'Admin\Settings\Tesco\MMProductBenefitsController@tescoMMProductBenefits');
		Route::post('/add-tesco-mm-product-benfits', 'Admin\Settings\Tesco\MMProductBenefitsController@addTescoMMProductBenefits');
		Route::put('/make-tesco-mm-product-benfits-enabled', 'Admin\Settings\Tesco\MMProductBenefitsController@enableMMProductBenefits');
		Route::put('/make-tesco-mm-product-benfits-disabled', 'Admin\Settings\Tesco\MMProductBenefitsController@disableMMProductBenefits');
		Route::get('/get-tesco-mm-product-benfits', 'Admin\Settings\Tesco\MMProductBenefitsController@getTescoMMProductBenefits');
		Route::put('/update-tesco-mm-product-benfits', 'Admin\Settings\Tesco\MMProductBenefitsController@updateTescoMMProductBenefits');
		Route::post('/settings/tesco-wee-symbols', 'Admin\Settings\Tesco\WeeSymbolsController@tescoWeeSymbols');
		Route::post('/settings/add-tesco-wee-symbols', 'Admin\Settings\Tesco\WeeSymbolsController@addTescoWeeSymbols');
		Route::put('/settings/make-tesco-wee-symbol-enabled', 'Admin\Settings\Tesco\WeeSymbolsController@enableWeeSymbol');
		Route::put('/settings/make-tesco-wee-symbol-disabled', 'Admin\Settings\Tesco\WeeSymbolsController@disableWeeSymbol');
		Route::get('/settings/get-tesco-wee-symbol', 'Admin\Settings\Tesco\WeeSymbolsController@getTescoWeeSymbol');
		Route::post('/settings/update-tesco-wee-symbol', 'Admin\Settings\Tesco\WeeSymbolsController@updateTescoWeeSymbol');
		Route::post('/settings/tesco-woolmark-logos', 'Admin\Settings\Tesco\WoolMarkLogoController@tescoWoolmarkLogo');
		Route::post('/settings/add-tesco-woolmark-logo', 'Admin\Settings\Tesco\WoolMarkLogoController@addTescoWoolmarkLogo');
		Route::put('/settings/make-tesco-woolmark-logo-enabled', 'Admin\Settings\Tesco\WoolMarkLogoController@enableTescoWoolmarkLogo');
		Route::put('/settings/make-tesco-woolmark-logo-disabled', 'Admin\Settings\Tesco\WoolMarkLogoController@disableTescoWoolmarkLogo');
		Route::get('/settings/get-tesco-woolmark-logo', 'Admin\Settings\Tesco\WoolMarkLogoController@getTescoWoolmarkLogo');
		Route::post('/settings/update-tesco-woolmark-logo', 'Admin\Settings\Tesco\WoolMarkLogoController@updateTescoWoolmarkLogo');
		Route::post('/settings/brands', 'Admin\Settings\Tesco\TescoSettings@tescoBrands');
		Route::post('/add-tesco-brands', 'Admin\Settings\Tesco\TescoSettings@addTescoBrands');
		Route::get('/get-tesco-brands-details', 'Admin\Settings\Tesco\TescoSettings@getTescoBrandsById');
		Route::put('/update-tesco-brands', 'Admin\Settings\Tesco\TescoSettings@updateTescoBrandsById');
		Route::put('/make-brand-enabled', 'Admin\Settings\Tesco\TescoSettings@makeBrandEnabled');
		Route::put('/make-brand-disabled', 'Admin\Settings\Tesco\TescoSettings@makeBrandDisabled');
		Route::post('/settings/departments', 'Admin\Settings\Tesco\TescoSettings@tescoDepartments');
		Route::post('/add-tesco-departments', 'Admin\Settings\Tesco\TescoSettings@addTescoDepartment');
		Route::get('/get-tesco-departments-details', 'Admin\Settings\Tesco\TescoSettings@getTescoDepartmentsById');
		Route::put('/update-tesco-departments', 'Admin\Settings\Tesco\TescoSettings@updateTescoDepartmentsById');
		Route::put('/make-departments-enabled', 'Admin\Settings\Tesco\TescoSettings@makeDepartmentEnabled');
		Route::put('/make-departments-disabled', 'Admin\Settings\Tesco\TescoSettings@makeDepartmentDisabled');
		Route::post('/settings/suppliers', 'Admin\Settings\Tesco\TescoSettings@tescoSuppliers');
		Route::post('/add-tesco-suppliers', 'Admin\Settings\Tesco\TescoSettings@addTescoSuppliers');
		Route::get('/get-tesco-suppliers-details', 'Admin\Settings\Tesco\TescoSettings@getTescoSuppliersById');
		Route::get('/get-tesco-symbol-details', 'Admin\Settings\Tesco\TescoSettings@getTescoSymbolDetails');
		Route::put('/update-tesco-suppliers', 'Admin\Settings\Tesco\TescoSettings@updateTescoSuppliersById');
		Route::post('/update-tesco-symbols', 'Admin\Settings\Tesco\TescoSettings@updateTescoSymbols');
		Route::put('/make-supplier-enabled', 'Admin\Settings\Tesco\TescoSettings@makeSupplierEnabled');
		Route::put('/make-supplier-disabled', 'Admin\Settings\Tesco\TescoSettings@makeSupplierDisabled');
		Route::post('/settings/sizechart', 'Admin\Settings\Tesco\TescoSettings@tescoSizeChart');
		Route::post('/add-tesco-size-charts', 'Admin\Settings\Tesco\TescoSettings@addTescoSizeCharts');
		Route::get('/get-tesco-Sizecharts-details', 'Admin\Settings\Tesco\TescoSettings@getTescoSizeChartsById');
		Route::put('/update-tesco-sizecharts', 'Admin\Settings\Tesco\TescoSettings@updateTescoSizechartsById');
		Route::put('/make-sizechart-enabled', 'Admin\Settings\Tesco\TescoSettings@makeSizechartEnabled');
		Route::put('/make-sizechart-disabled', 'Admin\Settings\Tesco\TescoSettings@makeSizechartDisabled');
		Route::post('/settings/tax-code', 'Admin\Settings\Tesco\TescoSettings@taxCode');
		Route::post('/add-tesco-tax-code', 'Admin\Settings\Tesco\TescoSettings@addTaxCodes');
		Route::get('/get-tesco-tax-code-details', 'Admin\Settings\Tesco\TescoSettings@getTaxCodesById');
		Route::put('/update-tesco-tax-code', 'Admin\Settings\Tesco\TescoSettings@updateTaxCodesById');
		Route::post('/settings/na-size-chart', 'Admin\Settings\Tesco\TescoSettings@naSizeChart');
		Route::post('/add-tesco-na-size-chart', 'Admin\Settings\Tesco\TescoSettings@addnaSizeChart');
		Route::get('/get-tesco-na-size-chart-details', 'Admin\Settings\Tesco\TescoSettings@getnaSizeChartById');
		Route::put('/update-tesco-na-size-chart', 'Admin\Settings\Tesco\TescoSettings@updatenaSizeChartById');
		Route::put('/make-na-size-chart-enabled', 'Admin\Settings\Tesco\TescoSettings@makeNaSizeChartEnabled');
		Route::put('/make-na-size-chart-disabled', 'Admin\Settings\Tesco\TescoSettings@makeNaSizeChartDisabled');
		Route::post('/settings/na-uom', 'Admin\Settings\Tesco\TescoSettings@naUOM');
		Route::post('/add-tesco-na-uom', 'Admin\Settings\Tesco\TescoSettings@addnaUOM');
		Route::get('/get-tesco-na-uom-details', 'Admin\Settings\Tesco\TescoSettings@getnaUOMById');
		Route::put('/update-tesco-na-uom', 'Admin\Settings\Tesco\TescoSettings@updatenaUOMById');
		Route::put('/make-na-uom-enabled', 'Admin\Settings\Tesco\TescoSettings@makeNaUomEnabled');
		Route::put('/make-na-uom-disabled', 'Admin\Settings\Tesco\TescoSettings@makeNaUomDisabled');
		Route::post('/settings/material', 'Admin\Settings\Tesco\TescoSettings@rawMaterials');
		Route::post('/add-raw-material', 'Admin\Settings\Tesco\TescoSettings@addRawMaterial');
		Route::get('/get-raw-material-details', 'Admin\Settings\Tesco\TescoSettings@getRawMaterialById');
		Route::put('/update-raw-material', 'Admin\Settings\Tesco\TescoSettings@updateRawMaterialById');
		Route::put('/make-material-enabled', 'Admin\Settings\Tesco\TescoSettings@makeMaterialEnabled');
		Route::put('/make-material-disabled', 'Admin\Settings\Tesco\TescoSettings@makeMaterialDisabled');
		Route::post('/settings/error-report-emails', 'Admin\Settings\Tesco\TescoSettings@tescoErrorReportEmails');
		Route::post('/add-tesco-error-report-email', 'Admin\Settings\Tesco\TescoSettings@addTescoErrorReportEmail');
		Route::get('/get-tesco-error-report-email-details', 'Admin\Settings\Tesco\TescoSettings@getTescoErrorReportEmailById');
		Route::put('/update-tesco-error-report-email', 'Admin\Settings\Tesco\TescoSettings@updateTescoErrorReportEmailById');
		Route::put('/make-error-report-email-enabled', 'Admin\Settings\Tesco\TescoSettings@makeErrorReportEmailEnabled');
		Route::put('/make-error-report-email-disabled', 'Admin\Settings\Tesco\TescoSettings@makeErrorReportEmailDisabled');
		/** Tesco order-status**/
		Route::post('/tesco-orders', 'Admin\Tesco\OrderStatusCntrl@index');
		Route::post('/tesco-orders-filter', 'Admin\Tesco\OrderStatusCntrl@tescoFilter');
		Route::post('/tesco-orders-submit-filter', 'Admin\Tesco\OrderStatusCntrl@tescoOrdersSubmitFilter');
		Route::post('/get-order-details', 'Admin\Tesco\OrderStatusCntrl@getOrderDetails');
		Route::post('/print-div-tescoos', 'Admin\Tesco\OrderStatusCntrl@printDivTesco');
		Route::post('/print-div-tesco-smallos', 'Admin\Tesco\OrderStatusCntrl@printDivTescoSmall');
		Route::post('/generate-div-tescoos', 'Admin\Tesco\OrderStatusCntrl@generateDivTesco');
		Route::post('/generate-div-tescolabelos', 'Admin\Tesco\OrderStatusCntrl@generateDivTescoLabelos');
		Route::post('/proof-div-tescolabelos', 'Admin\Tesco\OrderStatusCntrl@proofDivTescoLabelos');
		Route::post('/proof-div-tescoticketos', 'Admin\Tesco\OrderStatusCntrl@proofDivTescoTicketos');
		Route::post('/generate-div-tescoticketos', 'Admin\Tesco\OrderStatusCntrl@generateDivTescoTicketos');
		Route::post('/generate-div-tesco-smallos', 'Admin\Tesco\OrderStatusCntrl@generateDivTescoSmall');
		Route::post('/generatebarcodetsl1', 'Admin\Tesco\OrderStatusCntrl@generateBarcodeTsl1');
		Route::post('/generatebarcodetsl3', 'Admin\Tesco\OrderStatusCntrl@generateBarcodeTsl3');
		Route::post('/generatebarcodeeanbig', 'Admin\Tesco\OrderStatusCntrl@generateBarcodeEanBig');
		Route::post('/generatebarcodeitfbig', 'Admin\Tesco\OrderStatusCntrl@generateBarcodeItfBig');
		Route::post('/tesco-submit-os', 'Admin\Tesco\OrderStatusCntrl@tescoSubmitOs');
		Route::post('/order-management/set-po-number', 'Admin\Tesco\OrderStatusCntrl@setPONumber');
		Route::post('/set-po-file', 'Admin\Tesco\OrderStatusCntrl@setCustomerPOFile');
		Route::post('/order-management/show-tesco-pi', 'Admin\Tesco\OrderStatusCntrl@getTescoPI');
		Route::post('/approve-tesco-pi', 'Admin\Tesco\OrderStatusCntrl@approveTescoPI');
		Route::post('/reject-tesco-pi', 'Admin\Tesco\OrderStatusCntrl@rejectTescoPI');
		Route::post('/edit-artwork', 'Admin\Tesco\OrderStatusCntrl@editArtwork');
		Route::get('/get-artworks', 'Admin\Tesco\OrderStatusCntrl@getArtwork');
		Route::post('/approve-artworks-bulk', 'Admin\Tesco\OrderStatusCntrl@approveArtworkBulk');
		Route::get('/get-approve-artworks', 'Admin\Tesco\OrderStatusCntrl@getApproveArtwork');
		Route::post('/update-po', 'Admin\Tesco\OrderStatusCntrl@updateArtworkPO');
		Route::post('/copy-po', 'Admin\Tesco\OrderStatusCntrl@copyArtworkPO');
		Route::post('/get-po-file', 'Admin\Tesco\OrderStatusCntrl@getPOFile');
		Route::post('/download-tesco-pi', 'Admin\Tesco\OrderStatusCntrl@downloadTescoPI');
		Route::post('/update-delivery-date', 'Admin\Tesco\OrderStatusCntrl@updateDeliveryDate');
		Route::post('/set-tesco-order-status', 'Admin\Tesco\OrderStatusCntrl@setTescoOrderStatus');
		Route::post('/enable-artwork-edit', 'Admin\Tesco\OrderStatusCntrl@enableArtworkEdit');
		Route::post('/disable-artwork-edit', 'Admin\Tesco\OrderStatusCntrl@disableArtworkEdit');
		Route::post('/edit-check-exist-po-repeat', 'Admin\Tesco\OrderStatusCntrl@editCheckExistPoRepeat');
		Route::get('/get-erp-status', 'Admin\Tesco\OrderStatusCntrl@checkERPStatus');
		Route::put('/approve-tesco-artwork', 'Admin\Tesco\OrderStatusCntrl@approveTescoArtwork');

		Route::get('/template-details', 'Admin\TemplateManagement\TemplateController@getTemplateNotes');
		Route::post('/update-notes', 'Admin\TemplateManagement\TemplateController@setTemplateNotes');
		Route::post('/customer-management/template-view', 'Admin\CustomerManagement\CustomerManagementController@templateView');

		// Tesco care label orders admin routes
		Route::get('/care-label-orders', 'Admin\Tesco\CareLabelOrdersController@index')->name('tesco-care-label-order');
		Route::get('/care-label-fiber-components', 'Admin\Tesco\CareLabelOrdersController@getFiberComponentsMeta')->name('tesco-care-label-order.components');
		Route::get('/care-size-lists', 'Admin\Tesco\CareLabelOrdersController@getSecondarySizeListsBySizeCategoryID')->name('tesco-care-label-order.care-size-lists');
		Route::post('/submit-care-label-order',  'Admin\Tesco\CareLabelOrdersController@submitCareLabelOrder')->name('tesco-care-label-order.submit');
		Route::get('/get-care-label-order-details', 'Admin\Tesco\CareLabelOrdersController@getCareLabelOrderDetail');
		Route::put('/update-care-label-order',  'Admin\Tesco\CareLabelOrdersController@updateCareLabelOrder')->name('tesco-care-label-order.update');
		Route::post('/po-upload-labels', 'Admin\Tesco\CareLabelOrdersController@createLabelOrders');
		Route::post('/po-upload-swing-tickets', 'Admin\Tesco\SwingTicketOrdersController@createSwingTicketOrders');
		Route::get('/swing-size-chart', 'Admin\Tesco\SwingTicketOrdersController@getSwingSizeChart');

		// Tesco TCL5 and TSL2 orders admin routes
		Route::post('/store-single-sticker-orders', 'Admin\Tesco\SingleStickerOrdersController@submitSingleStickerOrder');
		Route::post('/save-single-sticker-orders', 'Admin\Tesco\SingleStickerOrdersController@saveSingleStickerOrder');

		// Tesco care label orders admin routes
		Route::get('/swing-ticket-orders', 'Admin\Tesco\SwingTicketOrdersController@index')->name('tesco-swing-ticket-order');
		Route::post('/submit-swing-ticket-order',  'Admin\Tesco\SwingTicketOrdersController@submitSwingTicketOrder')->name('tesco-swing-ticket-order.submit');
		Route::get('/get-swing-ticket-order-details', 'Admin\Tesco\SwingTicketOrdersController@getSwingTicketOrderDetail');
		Route::put('/update-swing-ticket-order',  'Admin\Tesco\SwingTicketOrdersController@updateSwingTicketOrder');
	});
});

Route::group(['middleware' => 'customer_guest', 'prefix' => 'customer'], function () {
	Route::get('/', 'CustomerAuth\LoginController@showLoginForm')->name('customer.login');
	Route::post('/', 'CustomerAuth\LoginController@login');
	// Password Reset Routes for customer...
	Route::prefix('/password')->group(function () {
		Route::get('/reset', 'CustomerAuth\ForgotPasswordController@showLinkRequestForm')->name('customer.password.request');
		Route::post('/email', 'CustomerAuth\ForgotPasswordController@sendResetLinkEmail')->name('customer.password.email');
		Route::get('/reset/{token}', 'CustomerAuth\ResetPasswordController@showResetForm')->name('customer.password.reset');
		Route::post('/reset', 'CustomerAuth\ResetPasswordController@reset');
	});
});

//Only logged in Customers can access or send requests to these pages
Route::group(['middleware' => 'customer_auth', 'prefix' => 'customer'], function () {
	Route::post('/logout', 'CustomerAuth\LoginController@logout')->name('customer.logout');
	Route::get('/dashboard', 'Customer\HomeController@index')->name('customer');
	Route::get('/order-status', 'Customer\HomeController@customerOrderStatus');
	Route::get('/create-order', 'Customer\HomeController@customerOrders');
	/** customer profile related routes */
	Route::put('/update-customer-password', 'Customer\CustomerProfile\CustomerProfileController@updateCustomerPassword');
	Route::get('/get-profile-details', 'Customer\CustomerProfile\CustomerProfileController@getCustomerDetailsById');
	Route::put('/update-customer-profile-details', 'Customer\CustomerProfile\CustomerProfileController@updateCustomerDetailsById');
	Route::get('/customers', 'Customer\CustomerController@customers');
	Route::get('/reorder', 'Customer\HomeController@reorder');

	//ORCHESTRA STATUS
	Route::prefix('/orchestra')->group(function () {
		Route::post('/order-management/show-orchestra-pi', 'Customer\Orchestra\OrderController@getOrchestraPI');
		Route::post('/approve-orchestra-pi', 'Customer\Orchestra\OrderController@approveOrchestraPI');
		Route::post('/reject-orchestra-pi', 'Customer\Orchestra\OrderController@rejectOrchestraPI');
		Route::post('/download-orchestra-pi', 'Customer\Orchestra\OrderController@downloadOrchestraPI');
		Route::post('/orchestra-orders-filter', 'Customer\Orchestra\CustomerOrderStatusFilter@orchestraOrdersFilter');
		Route::post('/orchestra-orders-filter-by-daterange', 'Customer\Orchestra\CustomerOrderStatusFilter@orchestraOrdersFilterByDateRange');
		Route::post('/get-orchestra-orders', 'Customer\Orchestra\CustomerOrderStatusFilter@getOrchestraOrders');

		Route::post('/edit-artwork', 'Customer\Orchestra\OrderStatusCntrl@editArtwork');
		Route::post('/get-order-details', 'Customer\Orchestra\OrderStatusCntrl@getOrderDetails');

		Route::post('/order-management/orders', 'Customer\Orchestra\OrderController@Orders');
		Route::post('/order-management/season_code_filter', 'Customer\Orchestra\OrderController@seasonCodeFilter');
		Route::post('/order-management/label-order-details', 'Customer\Orchestra\OrderController@labelOrderDetails');
		Route::post('/order-management/sticker-order-details', 'Customer\Orchestra\OrderController@stickerOrderDetails');
		Route::post('/order-management/set_hang_tag', 'Customer\Orchestra\OrderController@setHangTag');
		Route::post('/order-management/remove_hang_tag', 'Customer\Orchestra\OrderController@removeHangTag');
		Route::post('/order-management/remove_special_tag', 'Customer\Orchestra\OrderController@removeSpecialTag');
		Route::post('/order-management/remove_heat_transfer', 'Customer\Orchestra\OrderController@removeHeatTransfer');
		Route::post('/order-management/remove_main_label', 'Customer\Orchestra\OrderController@removeMainLabel');
		Route::post('/order-management/remove_size_label', 'Customer\Orchestra\OrderController@removeSizeLabel');
		Route::post('/order-management/remove_standard_stickers', 'Customer\Orchestra\OrderController@removeStandardStickers');
		Route::post('/order-management/remove_solid_stickers', 'Customer\Orchestra\OrderController@removeSolidStickers');
		Route::post('/order-management/remove_wcl', 'Customer\Orchestra\OrderController@removeWCL');
		Route::post('/order-management/set_heat_transfer', 'Customer\Orchestra\OrderController@setHeatTransfer');
		Route::post('/order-management/set_special_tag', 'Customer\Orchestra\OrderController@setSpecialTag');
		Route::post('/order-management/set_main_label', 'Customer\Orchestra\OrderController@setMainLabel');
		Route::post('/order-management/set_size_label', 'Customer\Orchestra\OrderController@setSizeLabel');
		Route::post('/order-management/remove_wash_label', 'Customer\Orchestra\OrderController@removeWashLabel');
		Route::post('/order-management/set_wash_label', 'Customer\Orchestra\OrderController@setWashLabel');
		Route::post('/order-management/set_wash_labelos', 'Customer\Orchestra\OrderController@setWashLabelOs');
		Route::post('/order-management/set_solid_stickers', 'Customer\Orchestra\OrderController@setSolidStickers');
		Route::post('/order-management/set_standard_stickers', 'Customer\Orchestra\OrderController@setStandardStickers');
		Route::post('/order-management/reorder', 'Customer\Orchestra\OrderController@reOrder');
		Route::post('/order-management/reorder-artwork', 'Customer\Orchestra\OrderController@reOrderArtwork');
		Route::post('/order-management/extra-quantity', 'Customer\Orchestra\OrderController@extraQuantity');
		Route::post('/order-management/po_select_wcl', 'Customer\Orchestra\OrderController@poSelectWcl');
		Route::post('/order-management/set_solid_stickers_os', 'Customer\Orchestra\OrderController@setSolidStickersOs');
		Route::post('/order-management/set_solid_poly_bag_stickers_os', 'Customer\Orchestra\OrderController@setSolidPolyBagStickersOs');
		Route::post('/order-management/set_standard_carton_stickers_os', 'Customer\Orchestra\OrderController@setStandardCartonStickersOs');
		Route::post('/order-management/set_standard_poly_bag_stickers_os', 'Customer\Orchestra\OrderController@setStandardPolyBagStickersOs');
		Route::get('/order-management/get-existing-label-orders-po', 'Customer\Orchestra\OrderController@getExistingLabelOrdersPo');
		Route::get('/order-management/get-existing-standard-sticker-orders-po', 'Customer\Orchestra\OrderController@getExistingStandardStickerOrdersPo');
		Route::get('/order-management/get-existing-solid-sticker-orders-po', 'Customer\Orchestra\OrderController@getExistingSolidStickerOrdersPo');
		Route::get('/order-management/get-existing-label-orders-status-po', 'Customer\Orchestra\OrderController@getExistingLabelOrderStatusPo');
		Route::post('/order-management/set-po-number', 'Customer\Orchestra\OrderController@setPONumber');
		Route::post('/set-po-file', 'Customer\Orchestra\OrderController@setCustomerPOFile');
		Route::Post('/order-management/map-label-orders-po', 'Customer\Orchestra\OrderController@mapLabelOrdersPo');
		Route::Post('/order-management/update-label-orders-po', 'Customer\Orchestra\OrderController@updateLabelOrdersPO');
		Route::get('/get-erp-status', 'Customer\Orchestra\OrderController@checkERPStatus');
		Route::post('/template-management/template-view', 'Customer\Orchestra\TemplateManagementCntrl@templateView');
	});

	Route::prefix('/customer-management')->group(function () {
		Route::put('/make-customer-profile-active', 'Customer\CustomerController@makeCustomerProfileActive');
		Route::put('/make-customer-profile-inactive', 'Customer\CustomerController@makeCustomerProfileInactive');
		Route::get('/get-customers-profile', 'Customer\CustomerController@getCustomerProfileById');
		Route::put('/update-customerProfile', 'Customer\CustomerController@updateCustomerProfile');
		Route::post('/add-customerProfile', 'Customer\CustomerController@addCustomerProfile');
		Route::post('/customer-company-buyer', 'Customer\CustomerController@customerCompanyBuyer');
	});

	Route::prefix('/tesco')->group(function () {
		//TESCO VIEW ORDER
		Route::post('/view-order', 'Customer\Tesco\OrderController@viewOrders');
		//TESCO ORDER STATUS
		Route::post('/tesco-orders', 'Customer\Tesco\OrderStatusController@index');
		Route::put('/approve-tesco-artwork', 'Customer\Tesco\OrderStatusController@approveTescoArtwork');
		Route::post('/tesco-orders-filter', 'Customer\Tesco\OrderStatusController@tescoFilter');
		Route::post('/tesco-orders-submit-filter', 'Customer\Tesco\OrderStatusController@tescoOrdersSubmitFilter');
		Route::post('/tesco-submit-os', 'Customer\Tesco\OrderStatusController@tescoSubmitOs');
		Route::post('/print-div-tescoos', 'Customer\Tesco\OrderStatusController@printDivTesco');
		Route::post('/print-div-tesco-smallos', 'Customer\Tesco\OrderStatusController@printDivTescoSmall');
		Route::post('/approve-tesco-pi', 'Customer\Tesco\OrderStatusController@approveTescoPI');
		Route::post('/reject-tesco-pi', 'Customer\Tesco\OrderStatusController@rejectTescoPI');
		Route::post('/edit-artwork', 'Customer\Tesco\OrderStatusController@editArtwork');
		Route::get('/get-artworks', 'Customer\Tesco\OrderStatusController@getArtwork');
		Route::post('/approve-artworks-bulk', 'Customer\Tesco\OrderStatusController@approveArtworkBulk');
		Route::get('/get-approve-artworks', 'Customer\Tesco\OrderStatusController@getApproveArtwork');
		Route::post('/update-po', 'Customer\Tesco\OrderStatusController@updateArtworkPO');
		Route::post('/copy-po', 'Customer\Tesco\OrderStatusController@copyArtworkPO');
		Route::post('/get-po-file', 'Customer\Tesco\OrderStatusController@getPOFile');
		Route::post('/download-tesco-pi', 'Customer\Tesco\OrderStatusController@downloadTescoPI');
		Route::post('/update-delivery-date', 'Customer\Tesco\OrderStatusController@updateDeliveryDate');
		Route::post('/set-tesco-order-status', 'Customer\Tesco\OrderStatusController@setTescoOrderStatus');
		Route::post('/set-po-file', 'Customer\Tesco\OrderStatusController@setCustomerPOFile');
		Route::post('/get-order-details', 'Customer\Tesco\OrderStatusController@getOrderDetails');
		Route::get('/get-erp-status', 'Customer\Tesco\OrderStatusController@checkERPStatus');
		Route::get('/templates-details', 'Customer\Tesco\OrderController@getTescoTemplateDetails');
		Route::post('/template-view', 'Customer\Tesco\OrderController@templateView');
		Route::post('/po-upload', 'Customer\Tesco\OrderController@createStickerOrders');
		Route::get('/get-tsl-size', 'Customer\Tesco\OrderController@getTescoTSLSizechart');
		Route::post('/set-ce-ratio-template-orders', 'Customer\Tesco\OrderController@setCERatioOrders');
		Route::post('/set-ce-single-template-orders', 'Customer\Tesco\OrderController@setCESingleOrders');
		Route::post('/set-uk-ratio-template-orders', 'Customer\Tesco\OrderController@setUKRatioOrders');
		Route::post('/set-uk-single-template-orders', 'Customer\Tesco\OrderController@setUKSingleOrders');
		Route::post('/upload-tesco-combined-po-file', 'Customer\Tesco\OrderController@uploadCombinedOrdersPO');
		Route::put('/save-ce-ratio-template-orders', 'Customer\Tesco\OrderController@saveCERatioOrders');
		Route::put('/save-ce-single-template-orders', 'Customer\Tesco\OrderController@saveCESingleOrders');
		Route::put('/save-uk-ratio-template-orders', 'Customer\Tesco\OrderController@saveUKRatioOrders');
		Route::put('/save-uk-single-template-orders', 'Customer\Tesco\OrderController@saveUKSingleOrders');
		Route::put('/cancel-tesco-order', 'Customer\Tesco\OrderController@cancelTescoOrder');
		Route::get('/create-order', 'Customer\Tesco\OrderController@createOrder');
		Route::post('/reorder', 'Customer\Tesco\ReorderController@reOrder');
		Route::post('/reorder-artwork', 'Customer\Tesco\ReorderController@reOrderArtwork');
		Route::post('/repeatorder-artwork', 'Customer\Tesco\ReorderController@repeatOrderArtwork');
		Route::post('/extra-quantity', 'Customer\Tesco\ReorderController@extraQuantity');
		Route::post('/extra-quantity-label-tesco', 'Customer\Tesco\ReorderController@extraQuantityLabelTesco');
		Route::post('/extra-quantity-reorder', 'Customer\Tesco\ReorderController@extraQuantityReorder');
		Route::post('/extra-quantity-st-tesco', 'Customer\Tesco\ReorderController@extraQuantityStTesco');
		Route::post('/set-tesco-reorder', 'Customer\Tesco\ReorderController@setTescoReorder');
		Route::post('/set-tesco-repeat-order', 'Customer\Tesco\ReorderController@setTescoRepeatorder');
		Route::post('/edit-check-exist-po-repeat', 'Customer\Tesco\ReorderController@editCheckExistPoRepeat');
		Route::get('/get-tsl-size', 'Customer\Tesco\ReorderController@getTescoTSLSizechart');
		//Customer Print Option
		Route::post('/print-div-tesco', 'Customer\Tesco\PrintController@printDivTesco');
		Route::post('/print-div-tesco-small', 'Customer\Tesco\PrintController@printDivTescoSmall');
		Route::post('/proof-div-tescolabelos', 'Customer\Tesco\PrintController@proofDivTescoLabelos');
		Route::post('/proof-div-tescoticketos', 'Customer\Tesco\PrintController@proofDivTescoTicketos');

		Route::prefix('/order-management')->group(function () {
			Route::post('/approved-orders', 'Customer\HomeController@getApprovedOrdersList');
			Route::post('/viewed-approved', 'Customer\HomeController@viewedApprovedOrder');
			Route::post('/viewed-selected-approved', 'Customer\HomeController@viewedSelectedApprovedOrder');
			Route::post('/pending-orders', 'Customer\HomeController@getPendingOrdersList');
			Route::post('/viewed-pending', 'Customer\HomeController@viewedPendingOrder');
			Route::post('/viewed-selected-pending', 'Customer\HomeController@viewedSelectedPendingOrder');
			Route::post('/cancelled-orders', 'Customer\HomeController@getCancelledOrdersList');
			Route::post('/viewed-cancelled', 'Customer\HomeController@viewedCancelledOrder');
			Route::post('/viewed-selected-cancelled', 'Customer\HomeController@viewedSelectedCancelledOrder');
			Route::post('/set-po-number', 'Customer\Tesco\OrderStatusController@setPONumber');
			Route::post('/show-tesco-pi', 'Customer\Tesco\OrderStatusController@getTescoPI');
			Route::post('/orders', 'Customer\Tesco\OrderController@Orders');
			//Customer Repeat Order
			Route::post('/extra-quantity-label-tesco-repeatOrder', 'Customer\Tesco\RepeatOrderController@extraQuantityLabelTescoRepeatOrder');
		});

		// Tesco care label orders customer routes
		Route::get('/care-label-orders', 'Customer\Tesco\CareLabelOrdersCustomerController@index');
		Route::get('/care-label-fiber-components', 'Customer\Tesco\CareLabelOrdersCustomerController@getFiberComponentsMeta');
		Route::get('/care-size-lists', 'Customer\Tesco\CareLabelOrdersCustomerController@getSecondarySizeListsBySizeCategoryID');
		Route::post('/submit-care-label-order',  'Customer\Tesco\CareLabelOrdersCustomerController@submitCareLabelOrder');
		Route::get('/get-moq-value', 'Customer\Tesco\CareLabelOrdersCustomerController@getTescoTemplateMoq');
		Route::get('/get-care-label-order-details', 'Customer\Tesco\CareLabelOrdersCustomerController@getCareLabelOrderDetail');
		Route::put('/update-care-label-order',  'Customer\Tesco\CareLabelOrdersCustomerController@updateCareLabelOrder')->name('tesco-care-label-order.update');
		Route::post('/po-upload-labels', 'Customer\Tesco\CareLabelOrdersCustomerController@createLabelOrders');

		// Tesco TCL5 and TSL2 orders admin routes
		Route::post('/store-single-sticker-orders', 'Customer\Tesco\SingleStickerOrdersCustomerController@submitSingleStickerOrder');
		Route::post('/save-single-sticker-orders', 'Customer\Tesco\SingleStickerOrdersCustomerController@saveSingleStickerOrder');

		// Tesco care label orders admin routes
		Route::get('/swing-ticket-orders', 'Customer\Tesco\SwingTicketOrdersCustomerController@index');
		Route::post('/submit-swing-ticket-order',  'Customer\Tesco\SwingTicketOrdersCustomerController@submitSwingTicketOrder');
		Route::get('/get-swing-ticket-order-details', 'Customer\Tesco\SwingTicketOrdersCustomerController@getSwingTicketOrderDetail');
		Route::put('/update-swing-ticket-order',  'Customer\Tesco\SwingTicketOrdersCustomerController@updateSwingTicketOrder');
		Route::post('/po-upload-swing-tickets', 'Customer\Tesco\SwingTicketOrdersCustomerController@createSwingTicketOrders');
		Route::get('/swing-size-chart', 'Customer\Tesco\SwingTicketOrdersCustomerController@getSwingSizeChart');
	});

	Route::prefix('/order-management')->group(function () {
		Route::post('/print-pdf', 'Customer\OrderManagement\OrderController@printPdf');
		Route::post('/print-pdf-arr', 'Customer\OrderManagement\OrderController@printPdfArr');
		Route::post('/print-pdf-small', 'Customer\OrderManagement\OrderController@printPdfSmall');
		Route::post('/print-pdf-table', 'Customer\OrderManagement\OrderController@printPdfTable');
		Route::post('/print-hang-pdf', 'Customer\OrderManagement\OrderController@printHangPdf');
		Route::post('/print-special-pdf', 'Customer\OrderManagement\OrderController@printSpecialPdf');
		Route::post('/print-wash-pdf', 'Customer\OrderManagement\OrderController@printWashPdf');
		Route::get('/get-tsl-size', 'Customer\OrderManagement\OrderController@getTescoTSLSizechart');
	});
});

Route::group(['middleware' => 'merchandiser_guest', 'prefix' => 'merchandiser'], function () {
	Route::get('/', 'MerchandiserAuth\LoginController@showLoginForm')->name('merchandiser.login');
	Route::post('/', 'MerchandiserAuth\LoginController@login');
	// Password Reset Routes for merchandiser...
	Route::get('/password/reset', 'MerchandiserAuth\ForgotPasswordController@showLinkRequestForm')->name('merchandiser.password.request');
	Route::post('/password/email', 'MerchandiserAuth\ForgotPasswordController@sendResetLinkEmail')->name('merchandiser.password.email');
	Route::get('/password/reset/{token}', 'MerchandiserAuth\ResetPasswordController@showResetForm')->name('merchandiser.password.reset');
	Route::post('/password/reset', 'MerchandiserAuth\ResetPasswordController@reset');
});

Route::group(['middleware' => 'merchandiser_auth', 'prefix' => 'merchandiser'], function () {
	Route::post('/logout', 'MerchandiserAuth\LoginController@logout')->name('merchandiser.logout');
	Route::get('/pending-orders', 'Merchandiser\HomeController@index');
	Route::get('/get-wcl-details', 'Merchandiser\HomeController@getWCLById');
	Route::post('/set-wcl-merchandiser-approve-comment', 'Merchandiser\HomeController@addWCLApproveCommentById');
	Route::post('/set-wcl-merchandiser-reject-comment', 'Merchandiser\HomeController@addWCLRejectCommentById');
	Route::get('/past-orders', 'Merchandiser\HomeController@pastOrders');
	/** Merchandiser profile related routes */
	Route::get('/get-profile-details', 'Merchandiser\MerchandiserProfileController@getMerchandiserDetailsById');
	Route::put('/update-merchandiser-profile-details', 'Merchandiser\MerchandiserProfileController@updateMerchandiserById');
	Route::put('/update-merchandiser-password', 'Merchandiser\MerchandiserProfileController@updateMerchandiserPassword');
});

/* ERP Related Routes */
Route::group(['prefix' => 'erp', 'middleware' => 'erpuser'], function () {
	Route::get('report-generator', 'ErpUser\Reports\ReportController@index')->name('report-generator');
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
	Route::get('/erp-po-list', 'ErpUser\Invoice\InvoiceController@erpPOList')->name('erp-po');
	Route::get('/erp-order-list', 'ErpUser\Invoice\InvoiceController@erpOrderList')->name('erp-orders');
	Route::post('/save-invoice', 'ErpUser\Invoice\InvoiceController@setInvoice')->name('save-invoice');
	Route::get('/get-invoice-details', 'ErpUser\Invoice\InvoiceController@getInvoiceDetails')->name('get-invoice-details');
	Route::get('/get-invoice', 'ErpUser\Invoice\InvoiceController@getInvoice')->name('get-invoice');
	Route::post('/download-invoice', 'ErpUser\Invoice\InvoiceController@downloadInvoice')->name('download-invoice');
	Route::get('/po-orders-list', 'ErpUser\Pomanager\PoManagerController@poOrdersList')->name('po-orders-list');
	Route::post('/set-erp-company-session', 'ErpUser\ProfileController@setCompanySession');
	
	Route::prefix('/print-manager')->group(function () {
		Route::get('/', 'ErpUser\Printmanager\PrintManagerController@index')->name('print-manager');
		Route::get('/get-customers-list', 'ErpUser\Printmanager\PrintManagerController@getCustomersListByBuyerId');
		Route::get('/get-style-list', 'ErpUser\Printmanager\PrintManagerController@getStyleListByBuyerCustomerId');
		Route::get('/get-erporder-list', 'ErpUser\Printmanager\PrintManagerController@getErpOrderListByBuyerCustomerId');
	});

	Route::prefix('/po-manager')->group(function () {
		Route::get('/', 'ErpUser\Pomanager\PoManagerController@index')->name('po-manager');
		Route::Post('/get-order-details', 'ErpUser\Pomanager\PoManagerController@getOrderDetails');
		Route::post('/order-details', 'ErpUser\Pomanager\PoManagerController@orderDetails');
		Route::post('/submit-po-verify', 'ErpUser\Pomanager\PoManagerController@submitPoVerify');
		Route::post('/change_po_updated_status', 'ErpUser\Pomanager\PoManagerController@changePoUpdatedStatus');
	});

	Route::prefix('job-manager')->group(function () {
		/* Routes for job-manager/orchestra/proof-pdf-smallrd Manager Module */
		Route::get('/', 'ErpUser\Jobmanager\JobManagerController@index')->name('jobcard-manager');
		Route::post('/get-order-details', 'ErpUser\Jobmanager\JobManagerController@getOrderDetails');
		Route::get('/jga/get-jobcard-order-details', 'ErpUser\Jobmanager\JobManagerController@getOrderDetailsByIds');
		Route::post('/store-jobcard-details', 'ErpUser\Jobmanager\JobManagerController@storeJobcardData');
		Route::post('/tesco/print-div-tesco', 'ErpUser\Jobmanager\JobManagerController@printDivTesco');
		Route::post('/tesco/print-div-tesco-small', 'ErpUser\Jobmanager\JobManagerController@printDivTescoSmall');
		Route::post('/tesco/proof-div-tescoticketos', 'ErpUser\Jobmanager\JobManagerController@proofDivTescoTicketos');
		Route::post('/tesco/generate-div-tescoticketos', 'ErpUser\Jobmanager\JobManagerController@generateDivTescoTicketos');
		Route::post('/tesco/proof-div-tescolabelos', 'ErpUser\Jobmanager\JobManagerController@proofDivTescoLabelos');
		Route::post('/tesco/print-div-tescolabelos', 'ErpUser\Jobmanager\JobManagerController@printDivTescoLabelos');
		Route::post('/orchestra/proof-pdf', 'ErpUser\Jobmanager\JobManagerController@proofPdf');
		Route::post('/orchestra/proof-pdf-arr', 'ErpUser\Jobmanager\JobManagerController@proofPdfArr');
		Route::post('/orchestra/proof-pdf-small', 'ErpUser\Jobmanager\JobManagerController@proofPdfSmall');
		Route::post('/orchestra/proof-pdf-table', 'ErpUser\Jobmanager\JobManagerController@proofPdfTable');
		Route::post('/tesco/generate-div-tesco', 'ErpUser\Jobmanager\JobManagerController@generateDivTesco');
		Route::post('/tesco/generate-div-tesco-small', 'ErpUser\Jobmanager\JobManagerController@generateDivTescoSmall');
		Route::post('/generatebarcodetsl1', 'ErpUser\Jobmanager\JobManagerController@generateBarcodeTsl1');
		Route::post('/generatebarcodetsl3', 'ErpUser\Jobmanager\JobManagerController@generateBarcodeTsl3');
		Route::post('/generatebarcodeeanbig', 'ErpUser\Jobmanager\JobManagerController@generateBarcodeEanBig');
		Route::post('/generatebarcodeitfbig', 'ErpUser\Jobmanager\JobManagerController@generateBarcodeItfBig');
		Route::get('/tesco/get-tsl-size', 'ErpUser\Jobmanager\JobManagerController@getTescoTSLSizechart');
		Route::get('/script-check-orders', 'ErpUser\Jobmanager\JobManagerController@scriptCheckOrder');
		Route::get('/jobcards-list', 'ErpUser\Jobmanager\JobManagerController@getJobcards')->name('jobcards-list');
		Route::get('/jobcard-details', 'ErpUser\Jobmanager\JobManagerController@getJobcardDetailsById');
		Route::put('/update-jobcard-details', 'ErpUser\Jobmanager\JobManagerController@updateJobcardById');
		Route::get('/get-customers-list', 'ErpUser\Jobmanager\JobManagerController@getCustomersListByBuyerId');
		Route::get('/get-jobcard-id-list', 'ErpUser\Jobmanager\JobManagerController@getJobcardIdListByBuyerCustomerId');
		Route::get('/get-jobcards-list', 'ErpUser\Jobmanager\JobManagerController@getJobcardsListByBuyerCustomerJobcardId');
		Route::post('/pdf-merge', 'ErpUser\Jobmanager\JobManagerController@pdfMerge');
		Route::get('/script-update-uuid', 'ErpUser\Jobmanager\JobManagerController@scriptUpdateUUID');
		/* Orchestra Print Manager*/
		Route::post('/orchestra/print-pdf', 'ErpUser\Jobmanager\JobManagerController@printPdf');
		Route::post('/orchestra/print-pdf-arr', 'ErpUser\Jobmanager\JobManagerController@printPdfArr');
		Route::post('/orchestra/print-pdf-small', 'ErpUser\Jobmanager\JobManagerController@printPdfSmall');
		Route::post('/orchestra/print-pdf-table', 'ErpUser\Jobmanager\JobManagerController@printPdfTable');
		Route::post('/orchestra/print-wash-pdf', 'ErpUser\Jobmanager\JobManagerController@printWashPdf');
		Route::post('/orchestra/print-heat-transfer', 'ErpUser\Jobmanager\JobManagerController@printHeatTransfer');
		Route::post('/orchestra/print-special-tag', 'ErpUser\Jobmanager\JobManagerController@printSpecialTag');
		Route::post('/orchestra/print-main-label', 'ErpUser\Jobmanager\JobManagerController@printMainLabel');
		Route::post('/orchestra/print-size-label', 'ErpUser\Jobmanager\JobManagerController@printSizeLabel');
		Route::post('/print-hang-pdf', 'ErpUser\Jobmanager\JobManagerController@printHangPdf');
	});

	Route::prefix('non-artwork-job-manager')->group(function () {
		/* Routes for Non artwork jobcards */
		Route::get('/', 'ErpUser\NonArtworkJobmanager\NonArtworkJobCardFiltersController@index')->name('non-artwork-jobcard-manager');
		Route::post('/get-customers-filter', 'ErpUser\NonArtworkJobmanager\NonArtworkJobCardFiltersController@getCustomersByBuyerId');
		Route::post('/get-item-names-filter', 'ErpUser\NonArtworkJobmanager\NonArtworkJobCardFiltersController@getItemNamesByCustomerId');
		Route::post('/get-references-filter', 'ErpUser\NonArtworkJobmanager\NonArtworkJobCardFiltersController@getReferencesByItemName');
		Route::post('/get-style-ocn-po-filter', 'ErpUser\NonArtworkJobmanager\NonArtworkJobCardFiltersController@getStyleOcnBuyerPoByItemName');
		Route::post('/get-nat-filtered-orders', 'ErpUser\NonArtworkJobmanager\NonArtworkJobCardFiltersController@getFilteredNatOrders')->name('nat-filtered-orders');
		Route::get('/get-nat-jc-details', 'ErpUser\NonArtworkJobmanager\NonArtworkJobCardFiltersController@getNatJcOrderDetails');
		Route::post('/store-nat-jobcard-details', 'ErpUser\NonArtworkJobmanager\NonArtworkJobCardFiltersController@storeNatJcOrderDetails');
		Route::get('/get-machine-excess-data', 'ErpUser\NonArtworkJobmanager\NonArtworkJobCardFiltersController@getMachineExcessDetailsById');

		Route::get('/nat-jobcards-list', 'ErpUser\NonArtworkJobmanager\UpdateNATJobcardController@getNonArtworkJobcardList')->name('nat-jobcards-list');
		Route::get('/get-nat-jobcard-ids', 'ErpUser\NonArtworkJobmanager\UpdateNATJobcardController@getNatJobcardIdListByBuyerCustomerId');
		Route::get('/get-nat-jobcard-list', 'ErpUser\NonArtworkJobmanager\UpdateNATJobcardController@getNatJobcardsListByBuyerCustomerJobcardId');
		Route::get('/get-nat-jobcard-details', 'ErpUser\NonArtworkJobmanager\UpdateNATJobcardController@getNatJobcardDetailsById');
	});

	/* Routes for ERP-Settings Management */
	Route::prefix('/settings')->group(function () {
		Route::get('/', 'ErpUser\ERPSettings\ERPSettingsController@home')->name('erp-settings');
		Route::get('/dispatch-mode', 'ErpUser\ERPSettings\ERPSettingsController@getDispatchMode')->name('erp-dispatch-mode');
		Route::post('/add-dispatch-mode', 'ErpUser\ERPSettings\ERPSettingsController@addDispatchMode');
		Route::get('/get-dispatch-mode-by-id', 'ErpUser\ERPSettings\ERPSettingsController@getDispatchModeById');
		Route::put('/update-dispatch-mode-by-id', 'ErpUser\ERPSettings\ERPSettingsController@updateDispatchModeById');
		Route::put('/make-dispatch-mode-enabled', 'ErpUser\ERPSettings\ERPSettingsController@makeDispatchModeEnabled');
		Route::put('/make-dispatch-mode-disabled', 'ErpUser\ERPSettings\ERPSettingsController@makeDispatchModeDisabled');
		//Machine
		Route::get('/machine', 'ErpUser\ERPSettings\ERPSettingsController@getMachine')->name('erp-machine');
		Route::post('/add-machine', 'ErpUser\ERPSettings\ERPSettingsController@addMachine');
		Route::get('/get-machine-by-id', 'ErpUser\ERPSettings\ERPSettingsController@getMachineById');
		Route::put('/update-machine-by-id', 'ErpUser\ERPSettings\ERPSettingsController@updateMachineById');
		Route::put('/make-machine-enabled', 'ErpUser\ERPSettings\ERPSettingsController@makeMachineEnabled');
		Route::put('/make-machine-disabled', 'ErpUser\ERPSettings\ERPSettingsController@makeMachineDisabled');
		//Teeth Number
		Route::get('/teethno', 'ErpUser\ERPSettings\ERPSettingsController@getTeethNo')->name('erp-teethno');
		Route::post('/add-teethno', 'ErpUser\ERPSettings\ERPSettingsController@addTeethNo');
		Route::get('/get-teethno-by-id', 'ErpUser\ERPSettings\ERPSettingsController@getTeethNoById');
		Route::put('/update-teethno-by-id', 'ErpUser\ERPSettings\ERPSettingsController@updateTeethNoById');
		Route::put('/make-teethno-enabled', 'ErpUser\ERPSettings\ERPSettingsController@makeTeethNoEnabled');
		Route::put('/make-teethno-disabled', 'ErpUser\ERPSettings\ERPSettingsController@makeTeethNoDisabled');
		//Finish Type
		Route::get('/finishtype', 'ErpUser\ERPSettings\ERPSettingsController@getFinishtype')->name('erp-finishtype');
		Route::post('/add-finishtype', 'ErpUser\ERPSettings\ERPSettingsController@addFinishType');
		Route::get('/get-finishtype-by-id', 'ErpUser\ERPSettings\ERPSettingsController@getFinishTypeById');
		Route::put('/update-finishtype-by-id', 'ErpUser\ERPSettings\ERPSettingsController@updateFinishTypeById');
		Route::put('/make-finishtype-enabled', 'ErpUser\ERPSettings\ERPSettingsController@makeFinishTypeEnabled');
		Route::put('/make-finishtype-disabled', 'ErpUser\ERPSettings\ERPSettingsController@makeFinishTypeDisabled');
		//Financial Year
		Route::get('/financial-year', 'ErpUser\ERPSettings\ERPSettingsController@getFinancialYear')->name('erp-financialyear');
		Route::put('/update-financial-year-date', 'ErpUser\ERPSettings\ERPSettingsController@updateFinancialYearDateById');
	});

	Route::get('non-artwork-orders', 'ErpUser\NonArtworkTemplate\NonArtworkTemplateController@nonArtworkOrders')->name('non-artwork-orders');
	Route::post('save-non-artwork', 'ErpUser\NonArtworkTemplate\NonArtworkTemplateController@saveNonArtworkOrders')->name('save-non-artwork');
	Route::post('get-non-artwork-order-details', 'ErpUser\NonArtworkTemplate\NonArtworkTemplateController@getNonArtworkOrders')->name('get-non-artwork');
	Route::post('get-item-nat', 'ErpUser\NonArtworkTemplate\NonArtworkTemplateController@getItem')->name('get-item-nat');
	Route::post('get-reference-nat', 'ErpUser\NonArtworkTemplate\NonArtworkTemplateController@getReference')->name('get-reference-nat');
	Route::post('get-hsn-tax', 'ErpUser\NonArtworkTemplate\NonArtworkTemplateController@getHsn')->name('get-hsn-tax');
	Route::post('calculate-nat-rate', 'ErpUser\NonArtworkTemplate\NonArtworkTemplateController@calculateRate')->name('calculate-nat-rate');
	Route::post('cancel-order', 'ErpUser\NonArtworkTemplate\NonArtworkTemplateController@cancelOrder')->name('cancel-order');
	Route::get('truncate-data', 'ErpUser\NonArtworkTemplate\NonArtworkTemplateController@truncate')->name('truncate-data');
});

// Routes for report generation
Route::get('reportGenerator', 'ErpUser\Reports\ReportController@index')->name('reportGenerator');
Route::post('level1Filter', 'ErpUser\Reports\ReportController@level1Filter');
Route::post('/level2Filter', 'ErpUser\Reports\ReportController@level2Filter');
Route::get('getTableFields', 'ErpUser\Reports\ReportController@getTableFields');
Route::post('setUpDatatable', 'ErpUser\Reports\ReportController@setUpDatatable')->name('setUpDatatable');

// Routes for admin report generation
Route::get('testQuery', 'Admin\Report\ReportController@testQuery')->name('testQuery');
Route::get('admin/generate-report', 'Admin\Report\ReportController@index')->name('generate-report');
Route::post('filter_level1', 'Admin\Report\ReportController@level1Filter')->name('filter_level1');
Route::post('admin_level2Filter', 'Admin\Report\ReportController@level2Filter')->name('filter_level2');
Route::post('admin_getTableFields', 'Admin\Report\ReportController@admin_getTableFields')->name('admin_getTableFields');
Route::post('admin_setUpDatatable', 'Admin\Report\ReportController@admin_setUpDatatable')
	->name('admin_setUpDatatable');

// Routes for customer report generation
Route::get('customer/generate-report', 'Customer\Report\ReportController@index');
Route::post('customer_filter_level1', 'Customer\Report\ReportController@level1Filter')->name('customer_filter_level1');
Route::post('customer_filter_level2', 'Customer\Report\ReportController@level2Filter')->name('customer_filter_level2');
Route::post('customer_getTableFields', 'Customer\Report\ReportController@customer_getTableFields')->name('customer_getTableFields');
Route::post('customer_setUpDatatable', 'Customer\Report\ReportController@customer_setUpDatatable')->name('customer_setUpDatatable');
