<?php

use Illuminate\Support\Facades\Route;

/* Authentication Controller */
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;

/* Common Controller */
use App\Http\Controllers\HelpController;
use App\Http\Controllers\ProfileController;

/* User/Company Controller */
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\CompanyProfileController;
use App\Http\Controllers\User\DocLockerController;
use App\Http\Controllers\User\SubscriptionController;
use App\Http\Controllers\User\CaAssignController;
use App\Http\Controllers\User\CustomerManagement;
use App\Http\Controllers\User\VendorManagemet;
use App\Http\Controllers\User\ProductServiceController;
use App\Http\Controllers\User\ProjectManagementController;
use App\Http\Controllers\User\SalesController;
use App\Http\Controllers\User\PurchaseController;
use App\Http\Controllers\User\OtherIncomeController;
use App\Http\Controllers\User\ExpensesController;
use App\Http\Controllers\User\CustomInvoiceController;
use App\Http\Controllers\User\AssetController;
use App\Http\Controllers\User\LiabilitesController;
use App\Http\Controllers\User\InventoryController;
use App\Http\Controllers\User\EmployeeManagemnet;
use App\Http\Controllers\User\LeaveManagementController;
use App\Http\Controllers\User\EmployeePolicy;
use App\Http\Controllers\User\HRLetter;
use App\Http\Controllers\User\TaskManagementController;
use App\Http\Controllers\User\ContraController;
use App\Http\Controllers\User\GSTController;
use App\Http\Controllers\User\CompliancesController;
use App\Http\Controllers\User\ReportsController;
use App\Http\Controllers\User\InvoiceController;
use App\Http\Controllers\User\TdsPfEsiController;
use App\Http\Controllers\User\QuotationsController;
use App\Http\Controllers\User\ProformasController;
use App\Http\Controllers\User\TaxFilingController;
use App\Http\Controllers\User\BusinessHealthCheckupController;
use App\Http\Controllers\User\AgentChannelController;
use App\Http\Controllers\User\PoController;
use App\Http\Controllers\User\DirectorController;
use App\Http\Controllers\User\VendorTdsController;
use App\Http\Controllers\User\SupportTicketController;
use App\Http\Controllers\User\ProprietorshipProfileController;
use App\Http\Controllers\User\JournalController;
use App\Http\Controllers\User\CommonController;
use App\Http\Controllers\User\PaymentVoucherController;
use App\Http\Controllers\User\ChatController;
use App\Http\Controllers\User\PayController;
use App\Http\Controllers\User\SettlementController;
use App\Http\Controllers\User\Reports\ProfitLossController;
use App\Http\Controllers\User\Reports\BalanceSheetController;
use App\Http\Controllers\User\DigitalSignedController;
use App\Http\Controllers\User\MSMEBenefitHubController;
use App\Http\Controllers\PayrollReportController;


/* User/Company Employee Controller */
use App\Http\Controllers\UserEmployee\UserEmployeeController;
use App\Http\Controllers\UserEmployee\ClaimManagement;
use App\Http\Controllers\UserEmployee\UserEmployeeAttendance;
use App\Http\Controllers\UserEmployee\UserEmployerPolicy;
use App\Http\Controllers\UserEmployee\UserTaskManagement;
use App\Http\Controllers\UserEmployee\UserLeaveManagement;
use App\Http\Controllers\UserEmployee\UserHRLetter;
use App\Http\Controllers\UserEmployee\UserDashboardController;


/* CA Controller */
use App\Http\Controllers\CA\CADashboardController;
use App\Http\Controllers\CA\CaProfileController;
use App\Http\Controllers\CA\FirmInformationController;
use App\Http\Controllers\CA\PayrollManagement;
use App\Http\Controllers\CA\CompanyAssignment;
use App\Http\Controllers\CA\NotificationController; //added binay
use App\Http\Controllers\CA\CompanyController;
use App\Http\Controllers\CA\ReminderController;
use App\Http\Controllers\CA\CaCompliancesController;
use App\Http\Controllers\CA\TaskController;
use App\Http\Controllers\CA\AgentController;
use App\Http\Controllers\CA\QuoteController;
use App\Http\Controllers\CA\MessageController;
use App\Http\Controllers\CA\EarningManagementController;
use App\Http\Controllers\CA\PaymentController;

/* Admin Controller */
use App\Http\Controllers\Admin\SubscriptionManagement;
use App\Http\Controllers\Admin\CAManagementController;
use App\Http\Controllers\Admin\CustomerManagementController;
use App\Http\Controllers\Admin\BusinessEarningController;
use App\Http\Controllers\Admin\PaymentManagementController;
use App\Http\Controllers\Admin\TicketManagementController;
use App\Http\Controllers\Admin\AdminEmployeeManagement;
use App\Http\Controllers\Admin\TdstaxslabManagementController;
use App\Http\Controllers\Admin\IncomeTaxSlabController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\ComplianceReminderSetController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DeductionMasterController;
use App\Http\Controllers\Admin\TaxDeductionController;
use App\Http\Controllers\Admin\HRLetterTemplateController;
use App\Http\Controllers\Admin\DropdownValueController;

use Illuminate\Support\Facades\File;

 // ----=====================  System Function Route =====================---- //

Route::get('/storage-link', function () {
    $source = storage_path('app/public');
    $destination = public_path('storage');
    if (!File::exists($destination)) {
        File::copyDirectory($source, $destination);
        return "Storage directory copied instead of symlink!";
    }
    return "Storage link already exists!";
});


// ----===================== Authentication Routes =====================---- //

Route::get('/login', [HomeController::class, 'login'])->name('login'); // Login
Route::get('/logout', [HomeController::class, 'logout'])->name('logout'); // Logout
Route::get('/signup', [HomeController::class, 'register'])->name('signup'); // Register
Route::get('/userlogin', [HomeController::class, 'register'])->name('register');
Route::post('/login/user', [HomeController::class, 'loginUser'])->name('/login/user'); // Login User
Route::post('/register/user', [HomeController::class, 'registerUser'])->name('/register/user'); // Register User

Route::get('/verify_email/{id}/{email}', [HomeController::class, 'verify_email'])->name('/verify_email/{id}/{email}'); // Email Verification Link
Route::post('/resend-verification-email', [HomeController::class, 'resendVerificationEmail']); // Resend Email Verification Link
Route::post('/check-verification-status', [HomeController::class, 'checkVerificationStatus']); // Check Email Verification Status
Route::post('/check-email-existence', [HomeController::class, 'checkEmailExistence']); // Check Email Existence

Route::middleware('auth')->get('/dashboard', function () {return Auth::user()->id;}); // Authentication Middleware of User Type
Route::get('/', [HomeController::class, 'index'])->name('index'); // Based upon Auth Define Route to there Dashboard

Route::get('/getCity', [HomeController::class, 'getCity'])->name('getCity'); // Fetch City

Route::get('/forget-password', [HomeController::class, 'forgetPassword'])->name('forgetPassword'); // Forgot Password
Route::get('/forget-password-otp', [HomeController::class, 'forgetPasswordOTP'])->name('forgetPasswordOTP'); // Forgot Password OTP
Route::get('/forget-password-otp-destroy', [HomeController::class, 'forgetPasswordOTPDestroy'])->name('forgetPasswordOTPDestroy'); // OTP Timeout
Route::get('/reset-password', [HomeController::class, 'ResetPassword'])->name('resetPassword'); // Reset Password
Route::post('/verify-otp', [HomeController::class, 'verifyOtp'])->name('verifyOtp'); // Verify OTP
Route::post('/resend-otp', [HomeController::class, 'resendOtp'])->name('resendOtp'); // Resend OTP
Route::post('/update-password', [HomeController::class, 'updatePassword'])->name('updatePassword'); // Update Password
Route::get('/change-password', [LoginController::class, 'ChangePassword'])->name('changepassword'); // Manually Password Change
Route::get('/lock-screen', [LoginController::class, 'LockScreen'])->name('lock-screen'); // Lock Screen

// ----===================== Common Routes =====================---- //
Route::get('/help-center', [HelpController::class, 'Help'])->name('help-center'); // Help Center
Route::get('/support-mail', [HelpController::class, 'SupportMail'])->name('support-mail'); // Support Mail
Route::get('/coming-soon', [HelpController::class, 'ComingSoon'])->name('coming-soon'); // Coming Soon
Route::get('/edit-personal-profile', [ProfileController::class, 'EditProfile'])->name('editProfile'); // Edit Profile

// ----===================== User/ Company Routes =====================---- //
Route::middleware(['ensure.login'])->group(function () {
	/* Dashboard */
	Route::get('/get-bank-details', [DashboardController::class, 'getBankDetails'])->name('get-bank-details');
	Route::get('/get-monthly-data', [DashboardController::class, 'getMonthlyData'])->name('get-monthly-data');
	Route::get('/get-monthlyturnover-data', [DashboardController::class, 'getMonthlyturnoverData'])->name('get-monthlyturnover-data');
	Route::get('/get-receivables-data', [DashboardController::class, 'getReceivablesData'])->name('get-receivables-data');
	Route::get('/get-payables-data', [DashboardController::class, 'getPayablesData'])->name('get-payables-data');
	Route::get('/get-Statutory-data', [DashboardController::class, 'getStatutoryData'])->name('get-Statutory-data');
	Route::get('/get_attendance_details', [DashboardController::class, 'get_attendance_details'])->name('get_attendance_details');
	Route::get('/get-asset-summary', [DashboardController::class, 'getAssetSummary'])->name('get-asset-summary');
    Route::get('/get-liabilities-summary', [DashboardController::class, 'getLiabilitiesSummary'])->name('get-liabilities-summary');
	Route::post('/get-cashflow-summary', [DashboardController::class, 'getCashflowSummary'])->name('get.cashflow.summary');
	Route::get('/get-gst-summary', [DashboardController::class, 'getGstSummary'])->name('get-gst-summary');
	Route::post('/complete-tour', [DashboardController::class, 'completeTour'])->name('user.completeTour');

	/* Company Profile */
	Route::get('/company-profile', [CompanyProfileController::class, 'CompanyProfile'])->name('user.CompanyProfile');
	Route::post('/update_compdet', [CompanyProfileController::class, 'update_compdet'])->name('update_compdet');
	Route::post('/update_businessdet', [CompanyProfileController::class, 'update_businessdet'])->name('update_businessdet');
	Route::post('/update_bankdet', [CompanyProfileController::class, 'update_bankdet'])->name('update_bankdet');
	Route::post('/update_comp_attachment', [CompanyProfileController::class, 'update_comp_attachment'])->name('update_comp_attachment');
	Route::post('/upload-profile-image', [CompanyProfileController::class, 'uploadProfileImage'])->name('upload.profile.image');
	Route::post('/holidays', [CompanyProfileController::class, 'holidayStore']);
	Route::get('/holidays/{id}/edit', [CompanyProfileController::class, 'holidayEdit']);
	Route::put('/holidays/{id}', [CompanyProfileController::class, 'holidayUpdate']);
	Route::delete('/holidays/{id}', [CompanyProfileController::class, 'holidayDestroy']);
	Route::post('/save-schedule', [CompanyProfileController::class, 'scheduleStore'])->name('save.schedule');
	Route::post('/save-location', [CompanyProfileController::class, 'saveLocation'])->name('save.location');
	Route::get('/get-location/{id}', [CompanyProfileController::class, 'getLocation'])->name('get.location');
	Route::put('/update-location/{id}', [CompanyProfileController::class, 'updateLocation'])->name('update.location');
	Route::delete('/delete-location/{id}', [CompanyProfileController::class, 'deleteLocation'])->name('delete.location');

		/* Document Locker */
	Route::get('/document-locker', [DocLockerController::class, 'DocLocker'])->name('user.DocLocker');

	/* Company Subscbtion Plans */
	Route::get('/plans', [SubscriptionController::class, 'Plans'])->name('user.Plans');
	Route::post('/subscription/create-order', [SubscriptionController::class, 'createOrder'])->name('subscription.create-order');
	Route::post('/subscription/verify-payment', [SubscriptionController::class, 'verifyPayment'])->name('subscription.verify-payment');
	Route::post('/subscription/test-verify-payment', [SubscriptionController::class, 'testVerifyPayment'])->name('subscription.test-verify-payment');
	Route::get('/subscription/history', [SubscriptionController::class, 'subscriptionHistory'])->name('subscription.history');
	Route::get('/subscription/debug-config', [SubscriptionController::class, 'debugConfig'])->name('subscription.debug-config');

	/* Assign CA */
	Route::get('/assign-ca-firm', [CaAssignController::class, 'AssignCA'])->name('user.AssignCa');

	/* CA Access Control by user */
	Route::get('/ca-access-control', [CaAssignController::class, 'CaAccessControl'])->name('user.CaAccessControl');

	/* Customer & Receivables */
	Route::get('/customer-list', [CustomerManagement::class, 'CustomerList'])->name('user.CustomerList');
	Route::get('/add-customer', [CustomerManagement::class, 'AddCustomer'])->name('user.AddCustomer');
	Route::get('/view-customer/{custId}', [CustomerManagement::class, 'view_customer'])->name('view-customer');
	Route::get('/edit-customer/{custId}', [CustomerManagement::class, 'edit_customer'])->name('edit-customer');
	Route::post('/add_customer', [CustomerManagement::class, 'add_customer'])->name('add_customer');
	Route::post('/update_customer', [CustomerManagement::class, 'update_customer'])->name('update_customer');
	Route::delete('/customer-delete/{id}', [CustomerManagement::class, 'delCustomer'])->name('customer.delete');
	Route::post('/customer_status_update/{id}', [CustomerManagement::class, 'changeStatus'])->name('customer_status_update');

	/* Vendor & Payables */
	Route::get('/vendor-list', [VendorManagemet::class, 'VendorList'])->name('user.VendorList');
	Route::get('/add-vendor', [VendorManagemet::class, 'AddVendor'])->name('user.AddVendor');
	Route::post('/saveaddvendor', [VendorManagemet::class, 'saveaddvendor'])->name('saveaddvendor');
	Route::post('/update_vendor', [VendorManagemet::class, 'update_vendor'])->name('update_vendor');
	Route::get('/view_vendor/{id}', [VendorManagemet::class, 'view_vendor'])->name('view_vendor');
	Route::get('/edit-vendor/{id}', [VendorManagemet::class, 'edit_vendor'])->name('edit-vendor');
	Route::post('/vendor_status_update/{id}', [VendorManagemet::class, 'changeVendorStatus'])->name('vendor_status_update');

	/* Products & Services */
	Route::get('/product-service-list', [ProductServiceController::class, 'ProductServiceList'])->name('user.ProductServiceList');
	Route::get('/add-product-service', [ProductServiceController::class, 'AddProductService'])->name('user.AddProductService');
	Route::post('/save_product', [ProductServiceController::class, 'save_product'])->name('save_product');
	Route::get('/edit-product/{id}', [ProductServiceController::class, 'editProductService'])->name('editProductService');
	Route::get('/view-product/{id}', [ProductServiceController::class, 'viewProductService'])->name('viewProductService');
	Route::post('/update_product', [ProductServiceController::class, 'update_product'])->name('update_product');
	Route::delete('/product-delete/{id}', [ProductServiceController::class, 'delProduct'])->name('product.delete');

	/* Project & Jobs */
	Route::get('/project-list', [ProjectManagementController::class, 'ProjectList'])->name('user.ProjectList');
	Route::get('/add-project', [ProjectManagementController::class, 'AddProject'])->name('user.AddProject');
	Route::post('/save_add_project', [ProjectManagementController::class, 'save_add_project'])->name('save_add_project');
	Route::post('/update_project', [ProjectManagementController::class, 'update_project'])->name('update_project');
	Route::get('/editProject/{id}', [ProjectManagementController::class, 'edit_project'])->name('editProject');
	Route::get('/viewProject/{id}', [ProjectManagementController::class, 'view_project'])->name('viewProject');
	Route::delete('/delProject/{id}', [ProjectManagementController::class, 'delProject'])->name('delProject');
	Route::get('/search-projects', [ProjectManagementController::class, 'searchProjects'])->name('search.projects');

	/* Agent & Channel */
	Route::get('/agent-channel-list', [AgentChannelController::class, 'AgentChannelList'])->name('user.AgentChannelList');
	Route::get('/add-agent-channel', [AgentChannelController::class, 'AddAgentChannelList'])->name('user.AddAgentChannelList');


	/* Sales & Revenue */
	Route::get('/carriage-outward', [SalesController::class, 'CarriageOutwards'])->name('user.CarriageOutwards');
	Route::get('/add-carriage-outward', [SalesController::class, 'AddCarriageOutward'])->name('user.AddCarriageOutward');
	Route::get('/sale-invoices', [SalesController::class, 'salesInvoiceIndex'])->name('user.SalesInvoices');
	Route::get('/view-sales-invoice/{sId}', [SalesController::class, 'view_sales_invoice'])->name('view_sales_invoice');
	Route::get('/create-sale-invoices', [SalesController::class, 'CreateSalesInvoices'])->name('user.CreateSalesInvoices');
	Route::get('/sale-credit-debit', [SalesController::class, 'SalesCreditDebit'])->name('user.SalesCreditDebit');
	Route::get('/add-sale-credit-debit', [SalesController::class, 'AddSalesCreditDebit'])->name('user.AddSalesCreditDebit');
	Route::post('/save_sales_invoice', [SalesController::class, 'save_sales_invoice'])->name('save_sales_invoice');
	Route::get('/edit-sales-invoice/{sId}', [SalesController::class, 'edit_sales_invoice'])->name('user.edit_sales_invoice');
	Route::get('/getinvcust', [SalesController::class, 'getinvcust'])->name('getinvcust');
	Route::post('/update_sales_customer', [SalesController::class, 'update_sales_customer'])->name('update_sales_customer');
	Route::post('/getProductType', [SalesController::class, 'getProductType'])->name('getProductType');
	Route::post('/getProduct', [SalesController::class, 'getProduct'])->name('getProduct');
	Route::post('/update_sales_invoice', [SalesController::class, 'update_sales_invoice'])->name('update_sales_invoice');
	Route::post('/update_sales_item_rate', [SalesController::class, 'update_sales_item_rate'])->name('update_sales_item_rate');
	Route::post('/sales_items_display', [SalesController::class, 'sales_items_display'])->name('sales_items_display');
	Route::post('/update_sales_invoice_final', [SalesController::class, 'update_sales_invoice_final'])->name('update_sales_invoice_final');
	Route::post('/update_sales_other', [SalesController::class, 'update_sales_other'])->name('update_sales_other');
	Route::get('/invoice', [InvoiceController::class, 'Index'])->name('user.invoice');
	Route::get('/sales-invoice-pdf/{id}/{invType}', [InvoiceController::class, 'getSalesInvoice'])->name('user.getSalesInvoice');
	Route::delete('/delInvoice/{id}', [SalesController::class, 'delInvoice'])->name('sales.delInvoice');
	Route::post('/fetch-sales-details', [SalesController::class, 'fetchSalesDetails'])->name('fetchSalesDetails');
	Route::post('/savecarriageoutwards', [SalesController::class, 'savecarriageoutwards'])->name('savecarriageoutwards');
	Route::post('/update_sales_item_quantity', [SalesController::class, 'update_sales_item_quantity'])->name('update_sales_item_quantity');
	Route::post('/fetchSalesItem', [SalesController::class, 'fetchSalesItem'])->name('fetchSalesItem');
	Route::post('/delSalesItem', [SalesController::class, 'delSalesItem'])->name('delSalesItem');
	Route::get('/edit-carriageout/{cId}', [SalesController::class, 'editcarriageoutwards'])->name('user.editcarriageoutwards');
	Route::post('/update_carriageoutwards', [SalesController::class, 'update_carriageoutwards'])->name('user.update_carriageoutwards');
	Route::get('/view-carriageout/{cId}', [SalesController::class, 'viewcarriageoutwards'])->name('user.viewcarriageoutwards');
	Route::delete('/delcarrout/{id}', [SalesController::class, 'delcarrout'])->name('sales.delete');
	Route::post('/save_sales_invoice_creditdebit', [SalesController::class, 'save_sales_invoice_creditdebit'])->name('save_sales_invoice_creditdebit');
	Route::get('/edit-sales-credit-debit/{sId}', [SalesController::class, 'edit_sales_invoice_credit_debit'])->name('edit_sales_invoice_credit_debit');
	Route::post('/update_sales_invoice_creditdebit', [SalesController::class, 'update_sales_invoice_creditdebit'])->name('update_sales_invoice_creditdebit');
	Route::get('/view-sales-credit-debit/{sId}', [SalesController::class, 'view_sales_invoice_credit_debit'])->name('view_sales_invoice_credit_debit');
	Route::delete('/delInvoiceCreditDebit/{id}', [SalesController::class, 'delInvoiceCreditDebit'])->name('delInvoiceCreditDebit');
	Route::get('/payment_history/{saleId}', [SalesController::class, 'getPaymentHistory']);
	Route::post('/submit-new-payment', [SalesController::class, 'submitNewPayment']);
	Route::get('/payment_received/{id}', [SalesController::class, 'paymentReceived']);

	//Route::get('/sales-quotation', [SalesController::class, 'Quotation'])->name('user.SalesQuotation');
	//Route::get('/create-sales-quotation', [SalesController::class, 'CreateQuotation'])->name('user.CreateSalesQuotation');
	//Route::get('/proform-invoice', [SalesController::class, 'ProformaInvoice'])->name('user.ProformaInvoice');
	//Route::get('/create-proform-invoice', [SalesController::class, 'CreateProformaInvoice'])->name('user.CreateProformaInvoice');

	/* Purchase */
	Route::post('/fetch-vendor-details', [PurchaseController::class, 'fetchVendorDetails'])->name('fetchVendorDetails');
	Route::post('/fetch-purchases-details', [PurchaseController::class, 'fetchParchaseDetails'])->name('fetchParchaseDetails');
	Route::get('/carriage-inward', [PurchaseController::class, 'CarriageInwards'])->name('user.CarriageInwards');
	Route::get('/add-carriage-inward', [PurchaseController::class, 'AddCarriageInwards'])->name('user.AddCarriageInwards');
	Route::get('/purchase-invoices', [PurchaseController::class, 'PurchaseInvoices'])->name('user.PurchaseInvoices');
	Route::get('/create-purchase-invoices', [PurchaseController::class, 'CreatePurchaseInvoices'])->name('user.CreatePurchaseInvoices');
	Route::get('/purchase-credit-debit', [PurchaseController::class, 'PurchaseCreditDebit'])->name('user.PurchaseCreditDebit');
	Route::get('/add-purchase-credit-debit', [PurchaseController::class, 'AddPurchaseCreditDebit'])->name('user.AddPurchaseCreditDebit');
	Route::post('/save_purchase_invoice', [PurchaseController::class, 'save_purchase_invoice'])->name('save_purchase_invoice');
	Route::get('/edit-purchase-invoice/{sId}', [PurchaseController::class, 'edit_purchase_invoice'])->name('edit_purchase_invoice');
	Route::post('/update_purchase_invoice', [PurchaseController::class, 'update_purchase_invoice'])->name('update_purchase_invoice');
	Route::post('/update_purchase_item', [PurchaseController::class, 'update_purchase_item'])->name('update_purchase_item');
	Route::post('/update_purchase_item_quantity', [PurchaseController::class, 'update_purchase_item_quantity'])->name('update_purchase_item_quantity');
	Route::post('/update_purchase_item_rate', [PurchaseController::class, 'update_purchase_item_rate'])->name('update_purchase_item_rate');
	Route::get('getinvcust_purchase', [PurchaseController::class, 'getinvcust_purchase'])->name('getinvcust_purchase');
	Route::post('/purchase_items_display', [PurchaseController::class, 'purchase_items_display'])->name('purchase_items_display');
	Route::post('/update_seller_details',  [PurchaseController::class, 'update_seller_details'])->name('update_seller_details');
	Route::post('/update_purchase_invoice_final', [PurchaseController::class, 'update_purchase_invoice_final'])->name('update_purchase_invoice_final');
	Route::post('/update_purchase_other', [PurchaseController::class, 'update_purchase_other'])->name('update_purchase_other');
	Route::get('/view-purchase-invoice/{sId}', [PurchaseController::class, 'view_purchase_invoice'])->name('view_purchase_invoice');
	Route::delete('/delInvoicePurchase/{id}', [PurchaseController::class, 'delInvoicePurchase'])->name('delInvoicePurchase');
	Route::post('/save_purchase_invoice_creditdebit', [PurchaseController::class, 'save_purchase_invoice_creditdebit'])->name('save_purchase_invoice_creditdebit');
	Route::get('/edit-purchase-credit-debit/{sId}', [PurchaseController::class, 'edit_purchase_invoice_credit_debit'])->name('edit_purchase_invoice_credit_debit');
	Route::get('/view-purchase-credit-debit/{sId}', [PurchaseController::class, 'view_purchase_invoice_credit_debit'])->name('view_purchase_invoice_credit_debit');
	Route::post('/update_purchase_invoice_creditdebit', [PurchaseController::class, 'update_purchase_invoice_creditdebit'])->name('update_purchase_invoice_creditdebit');
	Route::delete('/delPurchaseCreditDebit/{id}', [PurchaseController::class, 'delPurchaseCreditDebit'])->name('delPurchaseCreditDebit');
	Route::post('/savecarriageinwards', [PurchaseController::class, 'savecarriageinwards'])->name('savecarriageinwards');
	Route::get('/edit-carriagein/{cId}', [PurchaseController::class, 'editcarriageinwards'])->name('user.editcarriageinwards');
	Route::post('/updatecarriageinwards ', [PurchaseController::class, 'updatecarriageinwards'])->name('updatecarriageinwards');
	Route::get('/view-carriagein/{cId}', [PurchaseController::class, 'viewcarriageinwards'])->name('viewcarriageinwards');
	Route::delete('/delcarrageIn/{id}', [PurchaseController::class, 'delcarrageIn'])->name('delcarrageIn');
	Route::post('/fetchPurchaseItem', [PurchaseController::class, 'fetchPurchaseItem'])->name('fetchPurchaseItem');
	Route::delete('/delcarrIn/{id}', [PurchaseController::class, 'delcarrIn'])->name('delcarrIn');

	//Route::get('/purchase-order', [PurchaseController::class, 'PurchaseOrder'])->name('user.PurchaseOrder');
	//Route::get('/create-purchase-order', [PurchaseController::class, 'CreatePurchaseOrder'])->name('user.CreatePurchaseOrder');

	/* Income & Other Revenue */
	Route::get('/other-income-list', [OtherIncomeController::class, 'OtherIncomeList'])->name('user.OtherIncomeList');
	Route::get('/add-other-income', [OtherIncomeController::class, 'AddOtherIncome'])->name('user.AddOtherIncome');
	Route::post('/GetIncomeData', [OtherIncomeController::class, 'getIncomeData'])->name('GetIncomeData');
	Route::post('/storeNewIncomeData', [OtherIncomeController::class, 'store'])->name('income.store');
	Route::get('/viewincome/{id}', [OtherIncomeController::class, 'getViewIncome'])->name('income.view');
	Route::get('/editincome/{id}', [OtherIncomeController::class, 'editIncome'])->name('income.edit');
	Route::post('/updateincome/{id}', [OtherIncomeController::class, 'updateIncome'])->name('income.update');
	Route::delete('/income-delete/{id}', [OtherIncomeController::class, 'deleteIncome'])->name('income.delete');

	/* Expenses Management*/
	Route::get('/expenses-list', [ExpensesController::class, 'ExpensesList'])->name('user.ExpensesList');
	Route::get('/add-expenses', [ExpensesController::class, 'AddExpenses'])->name('user.AddExpenses');
	Route::get('/view-expenses/{id}', [ExpensesController::class, 'ViewExpenses'])->name('view-expenses');
	Route::get('/edit-expenses/{id}', [ExpensesController::class, 'EditExpenses'])->name('edit-expenses');
	Route::post('/save_expenses', [ExpensesController::class, 'save_expenses'])->name('save_expenses');
	Route::post('/update_expenses', [ExpensesController::class, 'update_expenses'])->name('update_expenses');
	Route::delete('/deleteExpenses/{id}', [ExpensesController::class, 'delExpenses'])->name('deleteExpenses');
	Route::post('/get-tds-rule', [ExpensesController::class, 'getTdsRule']);

	/* Custom Invoice */
	Route::get('/custom-invoice-list', [CustomInvoiceController::class, 'CustomInvoiceList'])->name('user.CustomInvoiceList');
	Route::get('/gererate-custom-invoice', [CustomInvoiceController::class, 'GenerateCustomInvoice'])->name('user.GenerateCustomInvoice');
	Route::get('/view-invoice-details/{id}', [CustomInvoiceController::class, 'ViewCustomInvoice'])->name('ViewCustomInvoice');
	Route::post('/custom_invoice_status_update', [CustomInvoiceController::class, 'custom_invoice_status_update'])->name('custom_invoice_status_update');
	Route::post('/custom_invoice_store', [CustomInvoiceController::class, 'saveInvoice'])->name('custom_invoice_store');

	/* Asset Management */
	Route::get('/assets-list', [AssetController::class, 'AssetList'])->name('user.AssetList');
	Route::get('/add-asset', [AssetController::class, 'AddAsset'])->name('user.AddAsset');
	Route::get('/assets-voutcher-list', [AssetController::class, 'AssetVoucherList'])->name('user.AssetVoucherList');
	Route::get('/add-assets-voutcher', [AssetController::class, 'AddAssetVoucher'])->name('user.AddAssetVoucher');
	Route::post('/save-asset', [AssetController::class, 'save_add_asset'])->name('user.SaveAsset');
	Route::get('/view-asset/{id}', [AssetController::class, 'view_asset'])->name('user.ViewAsset');
	Route::get('/edit-asset/{id}', [AssetController::class, 'edit_asset'])->name('user.EditAsset');
	Route::post('/update-asset/{id}', [AssetController::class, 'update_asset'])->name('user.UpdateAsset');
	Route::delete('/delete-asset/{id}', [AssetController::class, 'delAsset'])->name('user.DeleteAsset');
	Route::post('/save_add_series_name', [AssetController::class, 'save_add_series_name'])->name('save_add_series_name');
	Route::post('/save_add_voucher', [AssetController::class, 'save_add_voucher'])->name('save_add_voucher');
	Route::post('/update_voucher', [AssetController::class, 'update_voucher'])->name('update_voucher');
	Route::get('/edit-asset-voucher/{id}', [AssetController::class, 'edit_asset_voucher'])->name('edit-asset-voucher');
	Route::get('/view-asset-voucher/{id}', [AssetController::class, 'view_asset_voucher'])->name('view-asset-voucher');
	Route::delete('/delAssetVoucher/{id}', [AssetController::class, 'delAssetVoucher'])->name('delAssetVoucher');

	/* Liabilites & Loans */
	Route::get('/liabilites-list', [LiabilitesController::class, 'Liabilites'])->name('user.Liabilites');
	Route::get('/add-liabilites', [LiabilitesController::class, 'AddLiabilites'])->name('user.AddLiabilites');
	Route::get('/view-liabilities/{id}', [LiabilitesController::class, 'ViewLiabilites'])->name('view-liabilities');
	Route::get('/edit-liabilities/{id}', [LiabilitesController::class, 'EditLiabilites'])->name('edit-liabilities');
	Route::post('/saveLiabilities', [LiabilitesController::class, 'saveLiabilities'])->name('saveLiabilities');
	Route::post('/updateLiabilities', [LiabilitesController::class, 'updateLiabilities'])->name('updateLiabilities');
	Route::delete('/delLiabilities/{id}', [LiabilitesController::class, 'delLiabilities'])->name('delLiabilities');

	Route::get('/get-current-liability-amount', [LiabilitesController::class, 'getCurrentLiabilityAmount'])->name('get-current-liability-amount');

	/*Inventory Management*/
	Route::get('/inventory-list', [InventoryController::class, 'Inventory'])->name('user.Inventory');
	Route::post('/save_stock', [InventoryController::class, 'save_stock'])->name('save_stock');
	Route::post('/save_removestock', [InventoryController::class, 'save_removestock'])->name('save_removestock');
	Route::get('/getinventoryhistory', [InventoryController::class, 'getinventoryhistory'])->name('getinventoryhistory');
	Route::get('/expenses_inventorylist', [InventoryController::class, 'expenses_inventory'])->name('user.expenses_inventory');
	Route::get('/add-inventory-expenses', [InventoryController::class, 'AddInventoryExpenses'])->name('user.AddInventoryExpenses');

	/* HR & Payroll Management */

	/* Employee Master */
	Route::get('/add-employee', [EmployeeManagemnet::class, 'AddEmployee'])->name('user.AddEmployee');
	Route::get('/employee-list', [EmployeeManagemnet::class, 'EmployeeList'])->name('user.EmployeeList');
	Route::get('/employee-details/{id}', [EmployeeManagemnet::class, 'EmployeeDetails'])->name('user.EmployeeDetails');
	Route::get('/get-departments', [EmployeeManagemnet::class, 'getDepartments']);
	Route::get('/get-designations/{dept_id}', [EmployeeManagemnet::class, 'getDesignationsByDept']);
	Route::post('/add-department', [EmployeeManagemnet::class, 'DeptStore']);
	Route::post('/add-designation', [EmployeeManagemnet::class, 'DesignationStore']);
	Route::post('/add_user_employee', [EmployeeManagemnet::class, 'add_user_employee'])->name('add_user_employee');
	Route::post('/update_user_employee', [EmployeeManagemnet::class, 'update_user_employee'])->name('update_user_employee');
	Route::get('edit_user_employee/{id}', [EmployeeManagemnet::class, 'edit_user_employee'])->name('edit_user_employee');
	Route::get('view_user_employee/{id}', [EmployeeManagemnet::class, 'view_user_employee'])->name('view_user_employee');
	Route::get('delet_user_employee/{id}', [EmployeeManagemnet::class, 'delet_user_employee'])->name('delet_user_employee');
	Route::post('/check-email', [EmployeeManagemnet::class, 'checkEmail']);
	Route::post('/update-resignation', [EmployeeManagemnet::class, 'updateResignation'])->name('update.resignation');
	Route::get('/work-from-home/{id?}', [EmployeeManagemnet::class, 'workFromHome'])->name('user.workFromHome');
	Route::post('/store-wfh-request', [EmployeeManagemnet::class, 'storeWFHRequest'])->name('user.storeWFHRequest');
	Route::post('/get-wfh-calendar-data', [EmployeeManagemnet::class, 'getWFHCalendarData'])->name('user.getWFHCalendarData');
	Route::get('/get-wfh-details/{id}', [EmployeeManagemnet::class, 'getWFHDetails'])->name('user.getWFHDetails');
	Route::post('/accept-wfh-request/{id}', [EmployeeManagemnet::class, 'acceptWFHRequest'])->name('user.acceptWFHRequest');
	Route::post('/reject-wfh-request/{id}', [EmployeeManagemnet::class, 'rejectWFHRequest'])->name('user.rejectWFHRequest');
	Route::get('/check-company-policies', [EmployeeManagemnet::class, 'checkCompanyPolicies'])->name('check.company.policies');
	Route::post('/user-calculate-tds', [EmployeeManagemnet::class, 'calculateTDSAjax'])->name('user.calculate_tds');
	Route::get('/employee_hr_letter/{id?}', [EmployeeManagemnet::class, 'employeeHrLetter'])->name('user.employee_hr_letter');
	Route::get('/employee-leaves/{id}', [EmployeeManagemnet::class, 'EmployeeLeaves'])->name('employee.leaves');
	Route::get('/performace-review/{id}', [EmployeeManagemnet::class, 'Performace'])->name('user.performace-review');
	Route::post('/employee/save-rating', [EmployeeManagemnet::class, 'saveRating'])->name('employee.saveRating');
	Route::get('/employee/rating/{id}/edit', [EmployeeManagemnet::class, 'editRating'])->name('employee.editRating');
	Route::put('/employee/rating/{id}/update', [EmployeeManagemnet::class, 'updateRating'])->name('employee.updateRating');
	Route::delete('/employee/rating/{id}/delete', [EmployeeManagemnet::class, 'deleteRating'])->name('employee.deleteRating');
	Route::get('/resignEmployee', [EmployeeManagemnet::class, 'resignEmployee'])->name('user.ResignEmployee');
	Route::get('/view_resign_user_employee/{id}', [EmployeeManagemnet::class, 'view_resign_user_employee'])->name('view_resign_user_employee');

	/* Attandance History */
	Route::get('/attendance-list', [EmployeeManagemnet::class, 'AttendanceList'])->name('user.AttendanceList');
	Route::post('/attendance-list-filter', [EmployeeManagemnet::class, 'AttendanceListFilter'])->name('user.AttendanceListFilter');
	Route::post('/get_employee_attendance_log', [EmployeeManagemnet::class, 'getEmployeeAttendanceLog'])->name('user.getEmployeeAttendanceLog');
	Route::post('get_user_attendance', [EmployeeManagemnet::class, 'getMonthlyAttendance'])->name('user.getMonthlyAttendance');
	Route::get('get_user_attendance', [EmployeeManagemnet::class, 'getMonthlyAttendance'])->name('user.getMonthlyAttendance');
	Route::post('/attendance-list-pdf', [EmployeeManagemnet::class, 'AttendanceListPDF'])->name('user.AttendanceListPDF');
	Route::post('/export_employee_attendance', [EmployeeManagemnet::class, 'exportEmployeeAttendance'])->name('user.exportEmployeeAttendance');
	Route::post('/export_employee_attendance_pdf', [EmployeeManagemnet::class, 'exportEmployeeAttendancePDF'])->name('user.exportEmployeeAttendancePDF');
	Route::post('/update_attendance', [EmployeeManagemnet::class, 'updateAttendance']);
	Route::post('/get_daily_activity', [EmployeeManagemnet::class, 'getDailyActivity'])->name('user.getDailyActivity');

	/* Leave Managment */
	Route::get('/leave-management', [LeaveManagementController::class, 'LeaveManagement'])->name('user.LeaveManagement');
	Route::post('/store-leave', [LeaveManagementController::class, 'storeLeave'])->name('user.storeLeave');
	Route::get('/get-leave/{id}', [LeaveManagementController::class, 'getLeave'])->name('user.getLeave');
	Route::put('/update-leave/{id}', [LeaveManagementController::class, 'updateLeave'])->name('user.updateLeave');
	Route::put('/update-leave-status/{id}', [LeaveManagementController::class, 'updateLeaveStatus'])->name('user.updateLeaveStatus');
	Route::delete('/delete-leave/{id}', [LeaveManagementController::class, 'deleteLeave'])->name('user.deleteLeave');
	Route::post('/calculate-leave-days', [LeaveManagementController::class, 'calculateLeaveDays'])->name('user.calculateLeaveDays');
	Route::get('/get-working-days', [LeaveManagementController::class, 'getWorkingDays'])->name('user.getWorkingDays');

	/* Payslip Management */
	Route::get('/generate-payslip', [EmployeeManagemnet::class, 'GeneratePayslip'])->name('user.GeneratePayslip');
	Route::post('/check-payslip', [EmployeeManagemnet::class, 'checkPayslip'])->name('check.payslip');
	Route::post('/save-payslip', [EmployeeManagemnet::class, 'savePayslip']);
	Route::get('/download-payslip/{id}', [EmployeeManagemnet::class, 'downloadPayslip']);




	/* Employee Policies */
	Route::get('/employee-policy/create/{subject}', [EmployeePolicy::class, 'create'])->name('employee.policy.create');
	Route::get('/employee-policy-list', [EmployeePolicy::class, 'EmployeePolicyList'])->name('user.EmployeePolicyList');
	Route::get('/add-employee-policy', [EmployeePolicy::class, 'AddEmployeePolicy'])->name('user.AddEmployeePolicy');
	Route::post('/employee-policy/store', [EmployeePolicy::class, 'store'])->name('employee.policy.store');
	Route::post('/employee-policy/delete/{id}', [EmployeePolicy::class, 'delete'])->name('employee.policy.delete');
	Route::post('/employee/policy/update', [EmployeePolicy::class, 'update'])->name('employee.policy.update');
	Route::get('/employee-policy/edit/{id}', [EmployeePolicy::class, 'edit'])->name('employee.policy.edit');

	/* Holiday List */
	Route::get('/holiday-list', [EmployeeManagemnet::class, 'HolidayList'])->name('user.holiday-list');

	/* HR Letters */
	Route::get('/hr-letter-list', [HRLetter::class, 'LetterList'])->name('user.HRLetterList');
	Route::get('/hr-letter/edit/{id}', [HRLetter::class, 'edit'])->name('hr_letter.edit');
	Route::put('/hr-letter/update/{id}', [HRLetter::class, 'update'])->name('hr_letter.update');
	Route::delete('/hr-letter/delete/{id}', [HRLetter::class, 'destroy'])->name('hr_letter.delete');
	Route::get('/hr-letter/debug/{id}', [HRLetter::class, 'debug'])->name('hr_letter.debug');
	Route::get('/api/get-employees', [HRLetter::class, 'getEmployees'])->name('api.getEmployees');
	Route::post('/hr-letter/send', [HRLetter::class, 'SendHRLetter'])->name('hr_letter.send');

	/* Hr Letter Template Management */
	Route::get('/hr-letter-master-template-list', [HRLetterTemplateController::class, 'TemplateList'])->name('admin.HRLetterMasterList');
	Route::get('/generate-hr-letter', [HRLetterTemplateController::class, 'GenerateLetter'])->name('user.GenerateHRLetter');
	Route::post('/hr-letter/store', [HRLetterTemplateController::class, 'store'])->name('hr_letter.store');
	Route::post('/admin/hr-letter/update/{id}', [HRLetterTemplateController::class, 'update'])->name('admin.hr_letter.update');
	Route::post('/admin/hr-letter/toggle-status/{id}', [HRLetterTemplateController::class, 'toggleStatus'])->name('admin.hr_letter.toggle_status');
	Route::delete('/admin/hr-letter/delete/{id}', [HRLetterTemplateController::class, 'destroy'])->name('admin.hr_letter.delete');


	/* Employee Task Management */
	Route::get('/task-management', [TaskManagementController::class, 'index'])->name('user.TaskManagement');
	Route::post('/task-management/store', [TaskManagementController::class, 'storeTask'])->name('task.store');
	Route::post('/task-management/update/{id}', [TaskManagementController::class, 'update'])->name('task.update');
	Route::delete('/task-management/delete/{id}', [TaskManagementController::class, 'destroy'])->name('task.delete');

	/* Procurement & Reimbursement */
	Route::get('/expenditure-claims', [CustomerManagement::class, 'expenditureClaims'])->name('user.ExpenditureClaims');
	Route::post('/expenditure-claims/store', [CustomerManagement::class, 'expenditureStore'])->name('expenditure.store');
	Route::post('/expenditure-claims/update/{id}', [CustomerManagement::class, 'expenditureUpdate'])->name('expenditure.update');
	Route::get('/supply-requisitions', [CustomerManagement::class, 'supplyRequisitions'])->name('user.SupplyRequisitions');
	Route::post('/supply-requisitions/store', [CustomerManagement::class, 'supplyRequisitionsStore'])->name('requisition.store');
	Route::post('/supply-requisitions/update/{id}', [CustomerManagement::class, 'supplyRequisitionsUpdate'])->name('requisition.update');

	/* Statutory Compliance Status */
	Route::get('/compliances-list', [CompliancesController::class, 'CompliancesList'])->name('user.CompliancesList');
	Route::get('/compliances-chat', [CompliancesController::class, 'CompliancesChat'])->name('user.CompliancesChat');

	/* Cash & Banking */
	Route::post('/save_bank', [ContraController::class, 'save_bank'])->name('save_bank');
	Route::post('/update_bank', [ContraController::class, 'update_bank'])->name('update_bank');
	Route::get('/bank-list', [ContraController::class, 'BankList'])->name('user.BankList');
	Route::get('/bank-details/{id}', [ContraController::class, 'BankDetails'])->name('user.BankDetails');
	Route::delete('/bank_statement_delete/{id}', [ContraController::class, 'bank_statement_delete'])->name('bank_statement_delete');
	Route::get('/loan-list', [ContraController::class, 'LoanList'])->name('user.LoanList');
	Route::get('/loan-account-details/{id}', [ContraController::class, 'LoanAccountDetails'])->name('user.LoanAccountDetails');
	Route::delete('/loan_trans_delete/{id}', [ContraController::class, 'loan_trans_delete'])->name('loan_trans_delete');
	Route::post('/save_transaction', [ContraController::class, 'save_transaction'])->name('save_transaction');
	Route::post('/update_transaction', [ContraController::class, 'update_transaction'])->name('update_transaction');
	Route::post('/save_loan', [ContraController::class, 'save_loan'])->name('save_loan');
	Route::post('/save_installment', [ContraController::class, 'save_installment'])->name('save_installment');
	Route::post('/update_installment', [ContraController::class, 'update_installment'])->name('update_installment');
	Route::get('/cash-management', [ContraController::class, 'CashManagement'])->name('user.CashManagement');
	Route::post('/save_cash_credit', [ContraController::class, 'save_cash_credit'])->name('save_cash_credit');
	Route::post('/update_cash_credit', [ContraController::class, 'update_cash_credit'])->name('update_cash_credit');
	Route::delete('/cash_trans_delete/{id}', [ContraController::class, 'cash_trans_delete'])->name('cash_trans_delete');
	Route::get('/payment-voucher-list', [ContraController::class, 'PaymentVoucherList'])->name('user.PaymentVoucherList');
	Route::get('/add-payment-voucher', [ContraController::class, 'AddPaymentVoucher'])->name('user.AddPaymentVoucher');

	/* Tax Filing & Returns */

	/* GST Management */
	Route::post('/gst_authentication', [GSTController::class, 'gst_authentication'])->name('gst_authentication');
	Route::post('/hsn_details', [GSTController::class, 'getGstRateByHsn'])->name('get.hsn_details');
	Route::post('/gst/otp-request', [GSTController::class, 'otpRequest'])->name('gst.otp.request');
	Route::post('/gst/otp-verify', [GSTController::class, 'otpVerify'])->name('gst.otp.verify');
	Route::post('/gst/hsn-details', [GSTController::class, 'getHSNDetails'])->name('gst.hsn.details');
	Route::post('/gst/whitebookOtpRequest', [GSTController::class, 'whitebookOtpRequest'])->name('whitebookOtpRequest');
	Route::post('/gst/whitebookAuthenticationRequest', [GSTController::class, 'whitebookAuthenticationRequest'])->name('whitebookAuthenticationRequest');
	Route::get('/gst-profile', [GSTController::class, 'GSTProfile'])->name('User.GSTProfile');
	Route::post('/get-gst-details', [GSTController::class, 'fetchGSTDetails'])->name('gst.fetch');
	Route::post('/gst/profile', [GstController::class, 'gstOtherProfile']);
	Route::get('/other-gst-profile', [GSTController::class, 'OtherGSTProfile'])->name('User.OtherGSTProfile');
	Route::get('/gst-returns', [GSTController::class, 'GSTReturns'])->name('User.GSTReturns');
	Route::post('/gst/return-status', [GstController::class, 'gstOtherReturnStatus']);
	Route::post('/getGstReturnsData', [GSTController::class, 'getGstReturnsData'])->name('getGstReturnsData');
	Route::post('/submit_GSTReturns', [GSTController::class, 'submit_GSTReturns'])->name('submit_GSTReturns');
	Route::post('/final_submit_GSTReturns', [GSTController::class, 'final_submit_GSTReturns'])->name('final_submit_GSTReturns');
	Route::get('/gst-reports', [GSTController::class, 'GSTReports'])->name('User.GSTReports');
	Route::post('/generate_GSTReports', [GSTController::class, 'generate_GSTReports'])->name('generate_GSTReports');
	//Route::get('/gst-compliance-support', [GSTController::class, 'GSTComplianceSupport'])->name('User.GSTComplianceSupport');
	Route::get('/gst-dashboard', [GSTController::class, 'GstDashboard'])->name('User.GstDashboard');

	/* Reports Management */
	Route::get('/ledger', [ReportsController::class, 'Ledger'])->name('user.Ledger');
	Route::get('/trail-balance', [ReportsController::class, 'TrailBalance'])->name('user.TrailBalance');
	Route::get('/trail-balance-report', [ReportsController::class, 'TrialBalance'])->name('user.TrialBalanceReport');
	Route::get('/bank-reconciliation', [ReportsController::class, 'BankReconciliation'])->name('user.BankReconciliation');
	Route::post('/fatch-trial-balance-data', [ReportsController::class, 'fatch_trial_balance_data'])->name('trial_balance_data');
	Route::get('/get-opening-balance', [ReportsController::class, 'getOpeningBalanceAjax'])->name('get.opening.balance');
	Route::get('/msme-compliance', [TaxFilingController::class, 'MSMECompliences'])->name('user.MSMECompliance');
	Route::get('/mca-roc-filing', [TaxFilingController::class, 'MCAROCFiling'])->name('user.MCAROCFiling');
	Route::get('/income-tax-filing', [TaxFilingController::class, 'IncomeTaxReturnFiling'])->name('user.IncomeTaxFiling');
	Route::get('/startup-filing', [TaxFilingController::class, 'StartupFiling'])->name('user.StartupFiling');
	Route::get('/business-health-checkup', [BusinessHealthCheckupController::class, 'BusinessHealthCheckup'])->name('user.BusinessHealthCheckup');
	Route::get('/payroll-reports', [ReportsController::class, 'PayrollReports'])->name('user.PayrollReports');

	/* MSME Benefits Hub */
	Route::get('/msme/discover-schemes', [MSMEBenefitHubController::class, 'discoverSchemes'])->name('user.msme.discover-schemes');
	Route::get('/msme/eligibility-checker', [MSMEBenefitHubController::class, 'eligibilityChecker'])->name('user.msme.eligibility-checker');
	Route::get('/msme/loan-and-subsidies', [MSMEBenefitHubController::class, 'loanAndSubsidies'])->name('user.msme.loan-and-subsidies');
	Route::get('/msme/startup-benefits', [MSMEBenefitHubController::class, 'startupBenefits'])->name('user.msme.startup-benefits');
	Route::get('/msme/govt-updates', [MSMEBenefitHubController::class, 'govtUpdates'])->name('user.msme.govt-updates');
	Route::get('/msme/consultant-assistance', [MSMEBenefitHubController::class, 'consultantAssistance'])->name('user.msme.consultant-assistance');

});

/* Professional Services */
Route::get('valuable-services', function () {
    return redirect()->away('https://360bizservice.com/services');
})->name('valuable-services');
Route::get('health-checkup', function () {
    return redirect()->away('https://360bizservice.com/company-helth-checkup');
})->name('health-checkup');
Route::get('health-checkup', function () {
    return redirect()->away('https://360bizservice.com/company-helth-checkup');
})->name('health-checkup');
Route::get('home', function () {
    return redirect()->away('https://360bizservice.com/');
})->name('home');

/* Technology Services */
Route::get('company-branding', function () {
    return redirect()->away('https://clickngotech.com/');
})->name('company-branding');
Route::get('web-and-app', function () {
    return redirect()->away('https://clickngotech.com/services/web-development');
})->name('web-and-app');
Route::get('ui-and-ux', function () {
    return redirect()->away('https://clickngotech.com/services/social-media-post');
})->name('ui-and-ux');
Route::get('crm-erp-implementation', function () {
    return redirect()->away('https://clickngotech.com/services/web-app-development');
})->name('crm-erp-implementation');
Route::get('pwa', function () {
    return redirect()->away('https://clickngotech.com/services/progressive-web-app');
})->name('pwa');
Route::get('seo', function () {
    return redirect()->away('https://clickngotech.com/services/search-engine-optimization');
})->name('seo');
Route::get('smm', function () {
    return redirect()->away('https://clickngotech.com/services/social-media-marketing');
})->name('smm');

// ----===================== User/Company Employee Routes =====================---- //
Route::middleware(['ensure.login'])->group(function () {
	/* Dashboard */
	Route::get('/userEmployee.Dashboard', [UserEmployeeController::class, 'dashboard'])->name('userEmployee.Dashboard');

	/* Attendance History */
	Route::get('/attendance/today', [UserEmployeeController::class, 'todayAttendance'])->name('attendance.today');

	/* Performace & Revieew */
	Route::get('/user-performace-review', [UserEmployerPolicy::class, 'performace_review'])->name('userEmployee.performance-review');
	Route::get('/useremployee/rating/{id}/view', [UserEmployerPolicy::class, 'viewRating'])->name('useremployee.rating.view');

	/* Genrate Payslip */

	/* Employer policy */
	Route::get('/employee-policy/view/{id}', [EmployeePolicy::class, 'view'])->name('employee.policy.view');
	Route::post('/employee/policy/accept', [UserEmployerPolicy::class, 'acceptPolicy'])->name('employee.policy.accept');
	Route::get('/user-employer-list', [UserEmployerPolicy::class, 'UserEmployerList'])->name('userEmployee.policy-list');

	/* Task Management */
	Route::get('/assign-task-list', [UserTaskManagement::class, 'AssignTaskList'])->name('userEmployee.assign-task-list');

	/* Procurement & Rembursement */
	Route::get('/Expenditure-list', [ClaimManagement::class, 'ExpenditureClaimsList'])->name('userEmployee.ExpenditureClaimsList');
	Route::get('/Supply-list', [ClaimManagement::class, 'SupplyRequisitionsList'])->name('userEmployee.SupplyRequisitionsList');
	Route::post('/Expenditure_store', [ClaimManagement::class, 'UserEmployeeExpenditureStore'])->name('userEmployeeExpenditure.store');
	Route::post('/User_employee_expenditure_claims/update/{id}', [ClaimManagement::class, 'UserEmployeeExpenditureUpdate'])->name('User_employee_expenditure_claims/update');
	Route::post('/user-employee-requisition/store', [ClaimManagement::class, 'UserEmployeeSupplyRequisitionsStore'])->name('UserEmployeeRequisition.store');
	Route::post('/UserEmployee-supply-requisitions/update/{id}', [ClaimManagement::class, 'UserEmployeeSupplyRequisitionsUpdate'])->name('requisition.update');

	/* Leave Management */
	Route::get('/leave-request-list', [UserLeaveManagement::class, 'LeaveRequestList'])->name('userEmployee.user-leave-request');
	Route::get('/get-working-days-userEmployee', [UserLeaveManagement::class, 'getWorkingDays'])->name('userEmployee.getWorkingDays');
	Route::post('/store-leave-userEmployee', [UserLeaveManagement::class, 'storeLeave'])->name('userEmployee.storeLeave');
	Route::get('/get-leave-userEmployee/{id}', [UserLeaveManagement::class, 'getUserEmployeeLeave'])->name('userEmployee.getUserEmployeeLeave');
	Route::put('/update-leave-userEmployee/{id}', [UserLeaveManagement::class, 'updateLeaveUserEmployee'])->name('userEmployee.updateLeave');
	Route::delete('/delete-leave-userEmployee/{id}', [UserLeaveManagement::class, 'deleteLeaveUserEmployee'])->name('userEmployee.deleteLeave');

	/* HR Letter */
	Route::get('/hr-letters', [UserHRLetter::class, 'HRLetterList'])->name('userEmployee.hr-letters');
	Route::get('/view-hr-letters', [UserHRLetter::class, 'ViewHRLetter'])->name('userEmployee.hr-letters-view');
	Route::get('/employee/hr-letters', [UserHRLetter::class, 'HRLetterList'])->name('userEmployee.hr-letters-list');
	Route::get('/employee/hr-letters/view/{id}', [UserHRLetter::class, 'HRLetterView'])->name('userEmployee.hr-letters-view');

	/* Attendance Management*/
	Route::get('/employee/tasks', [UserEmployeeController::class, 'getEmployeeTasks'])->name('employee.tasks');
	Route::post('/employee/task/update', [UserEmployeeController::class, 'updateTaskStatus'])->name('employee.task.update');

	/* User Employee Claim Management */
	Route::get('/attendance_history', [UserEmployeeAttendance::class, 'showEmployeeAttendanceDetails'])->name('userEmployee.attendance_history');
	Route::post('get_user_attendance', [UserEmployeeAttendance::class, 'getMonthlyAttendance'])->name('userEmoloyee.getMonthlyAttendanceUserEmployee');
	Route::post('/getDailyActivityUserEmployee', [UserEmployeeAttendance::class, 'getDailyActivity'])->name('userEmoloyee.getDailyActivityUserEmployee');

	/* Employee Dashboard Data */
	Route::get('/employee-dashboard-data', [UserDashboardController::class, 'getDashboardData'])->name('employee.dashboard.data');
	Route::get('/attendance-summary', [UserDashboardController::class, 'attendanceSummary'])->name('attendance.summary');
	Route::get('/tasks_counters', [UserDashboardController::class, 'getTaskCounts'])->name('tasks_counters');
	Route::get('/employee/upcoming-tasks', [UserDashboardController::class, 'getUpcomingTasks'])->name('employee.upcoming.tasks');
	Route::post('/employee/update-task-status',[UserDashboardController::class, 'updateTaskStatus']);
	Route::get('/employee/performance-review', [UserDashboardController::class, 'getPerformanceReview']);





	/* **************************************************CA Route***********************************************************************/
	Route::get('/cahome', 'CaHomeController@Index');
	//Route::get('/caprofile', 'CaProfileController@Index');
	Route::get('/caprofile', [CaProfileController::class, 'caindex'])->name('ca.caindex');
	/* **************************************************CA Route***********************************************************************/
	/* Dashboard */
	Route::get('/ca-dashboard', [CADashboardController::class, 'CADashboard'])->name('CA.Dashboard');

	/* Firm Information */
	Route::get('/firm-information', [FirmInformationController::class, 'FirmInformation'])->name('CA.FirmInformation');

	/* Payroll Management */
	Route::get('/ca-employee-list', [PayrollManagement::class, 'CAEmployeeList'])->name('CA.EmployeeList');
	Route::get('/ca-add-employee', [PayrollManagement::class, 'CAAddEmployee'])->name('CA.AddEmployee');
	Route::get('/ca-employee-details', [PayrollManagement::class, 'CAEmployeeDetails'])->name('CA.EmployeeDetails');
	Route::get('/ca-attendance-list', [PayrollManagement::class, 'CAAttendanceList'])->name('CA.AttendanceList');
	Route::get('/ca-generate-payslip', [PayrollManagement::class, 'CAGeneratePayslip'])->name('CA.GeneratePayslip');

	/* Company Assignment */
	Route::get('/company-assignment', [CompanyAssignment::class, 'CompanyAssignment'])->name('CA.CompanyAssignment');
	Route::get('/edit-company-assignment/{clientId}', [CompanyAssignment::class, 'EditCompanyAssignment'])->name('edit-company-assignment');


	Route::post('/update_comp_logo_ca', [FirmInformationController::class, 'update_comp_logo_ca'])->name('CA.update_comp_logo_ca');
	Route::post('/update_compdet_ca', [FirmInformationController::class, 'update_compdet_ca'])->name('CA.update_compdet_ca');
	Route::post('/update_ca_speclization', [FirmInformationController::class, 'update_ca_speclization'])->name('CA.update_ca_speclization');
	Route::post('/update_bankdet_ca', [FirmInformationController::class, 'update_bankdet_ca'])->name('CA.update_bankdet_ca');
	Route::post('/update_partner_ca', [FirmInformationController::class, 'update_partner_ca'])->name('CA.update_partner_ca');
	Route::post('/update_ca_attachment', [FirmInformationController::class, 'update_ca_attachment'])->name('CA.update_ca_attachment');
	//added binay on 12-03-2025
	Route::post('/viewCustomerDet', [CompanyAssignment::class, 'viewCustomerDet'])->name('CA.viewCustomerDet');
	// Route::post('/acceptCustomerStatus', [CompanyAssignment::class, 'acceptCustomerStatus'])->name('CA.acceptCustomerStatus');
	Route::post('/acceptCustomerStatus', [CompanyAssignment::class, 'acceptCustomerStatus'])->name('CA.acceptCustomerStatus');
	Route::post('/update_client', [CompanyAssignment::class, 'update_client'])->name('update_client');
	Route::get('/client-view/{custId}', [CompanyAssignment::class, 'ClientDetails'])->name('client-view');
	Route::post('/getAssignRequestChart', [CompanyAssignment::class, 'getAssignRequestChart'])->name('getAssignRequestChart');
	Route::post('/assign_ca', [CaAssignController::class, 'assign_ca'])->name('assign_ca');


	Route::post('/add_ca_request', [CaAssignController::class, 'storeCA'])->name('addCARequest');


	Route::get('/view-all-notification', [NotificationController::class, 'index'])->name('view-all-notification');
	Route::post('/clearNotification', [NotificationController::class, 'clearNotification'])->name('clearNotification');
	Route::post('/updateRequestFor', [CompanyProfileController::class, 'updateRequestFor'])->name('updateRequestFor');

	Route::post('/save_employee', [PayrollManagement::class, 'save_employee'])->name('save_employee');
	Route::get('/edit-employee/{empId}', [PayrollManagement::class, 'edit_employee'])->name('edit_employee');
	Route::post('/update_employee', [PayrollManagement::class, 'update_employee'])->name('update_employee');
	Route::get('changeEmployeeStatus', [PayrollManagement::class, 'changeEmployeeStatus'])->name('changeEmployeeStatus');
	Route::get('delEmployee', [PayrollManagement::class, 'delEmployee'])->name('delEmployee');
	Route::get('/getDesignationOptions', [PayrollManagement::class, 'getDesignationOptions'])->name('getDesignationOptions');
	Route::post('/add_depertment', [PayrollManagement::class, 'add_depertment'])->name('add_depertment');
	Route::post('/add_designation', [PayrollManagement::class, 'add_designation'])->name('add_designation');
	Route::post('/calculate-tds', [PayrollManagement::class, 'calculateTDSAjax'])->name('calculate_tds');
	Route::get('/test-tds/{salary}', [PayrollManagement::class, 'testTDS'])->name('test_tds');
	Route::get('/payslips', [PayrollManagement::class, 'payslipList'])->name('ca.payslip.list');


	/* Company */
	Route::get('/company-list', [CompanyController::class, 'Index'])->name('Ca.CompanyList');
	Route::get('/company-add', [CompanyController::class, 'ComapanyAdd'])->name('CA.CompanyAdd');
	Route::post('/save_client', [CompanyController::class, 'save_client'])->name('save_client');
	Route::get('/changeCustomerStatus', [CompanyController::class, 'changeCustomerStatus'])->name('changeCustomerStatus');

	/* Reminder */
	Route::get('/remider_from_ca', [ReminderController::class, 'remider_from_ca'])->name('CA.Reminder');
	Route::post('/userListsAccess', [ReminderController::class, 'userListsAccess'])->name('userListsAccess');
	Route::post('/sendReminderCA', [ReminderController::class, 'sendReminderCA'])->name('sendReminderCA');

	/* Compliance Management*/
	Route::get('/ca-compliances-list', [CaCompliancesController::class, 'CompliancesList'])->name('ca.CompliancesList');
	Route::get('/ca-compliances-chat', [CaCompliancesController::class, 'CompliancesChat'])->name('ca.CompliancesChat');
	Route::get('/company-details', [CompanyController::class, 'ComapanyDetails'])->name('CA.CompanyDetails');
	/* Task Management*/
	Route::get('/task-list', [TaskController::class, 'Index'])->name('ca.TaskList');
	Route::get('/add-task', [TaskController::class, 'AddTask'])->name('ca.AddTask');
	Route::post('/save_task', [TaskController::class, 'save_task'])->name('save_task');
	Route::get('/task-quoteset', [TaskController::class, 'TaskQuoteset'])->name('ca.TaskQuoteset');
	Route::post('getcat', [TaskController::class, 'getcat'])->name('getcat');
	Route::get('/edit-task/{taskId}', [TaskController::class, 'edit_task'])->name('edit_task');
	Route::get('/view-task/{taskId}', [TaskController::class, 'view_task'])->name('view_task');
	Route::post('/update_task', [TaskController::class, 'update_task'])->name('update_task');
	Route::get('/add-new-quote', [TaskController::class, 'AddNewQuote'])->name('ca.AddNewQuote');
	Route::get('/get-task-categories', [TaskController::class, 'fetchCategories']);
	Route::post('/task/delete/{id}', [TaskController::class, 'delTask']);


	/* Quote Management*/
	Route::get('/task-quoteset', [QuoteController::class, 'Index'])->name('ca.QuoteList');
	Route::get('/add-quote', [QuoteController::class, 'AddQuote'])->name('ca.AddQuote');
	Route::post('/save_quote', [QuoteController::class, 'save_quote'])->name('save_quote');
	Route::get('/edit-quote/{quoteId}', [QuoteController::class, 'edit_quote'])->name('edit_quote');
	Route::post('/update_quote', [QuoteController::class, 'update_quote'])->name('update_quote');
	Route::get('/view-quote/{quoteId}', [QuoteController::class, 'view_quote'])->name('view_quote');
	Route::get('changeQuoteStatus', [QuoteController::class, 'changeQuoteStatus'])->name('changeQuoteStatus');
	Route::post('delQuote', [QuoteController::class, 'delQuote'])->name('delQuote');
	Route::post('/task-category/store', [QuoteController::class, 'taskCategoryStore'])->name('task-category.store');


	/* Agent Management*/
	Route::get('/agent-list', [AgentController::class, 'Index'])->name('ca.AgentList');
	Route::get('/add-agent', [AgentController::class, 'AddAgent'])->name('ca.AddAgent');
	Route::post('/save_agent', [AgentController::class, 'save_agent'])->name('save_agent');
	Route::get('/edit-agent/{agentId}', [AgentController::class, 'edit_agent'])->name('edit_agent');
	Route::post('/update_agent', [AgentController::class, 'update_agent'])->name('update_agent');
	Route::get('/agent-view/{agentId}', [AgentController::class, 'AgentDetails'])->name('AgentDetails');
	Route::get('changeAgentStatus', [AgentController::class, 'changeAgentStatus'])->name('changeAgentStatus');
	Route::delete('delAgent', [AgentController::class, 'delAgent'])->name('delAgent');
	Route::get('/agent-details', [AgentController::class, 'AgentDetails'])->name('ca.AgentDetails');

	//added by binay on 22-03-2025
	Route::get('/view-employee/{empId}', [PayrollManagement::class, 'view_employee'])->name('view_employee');
	Route::get('/addstatutory', [CaCompliancesController::class, 'addstatutory'])->name('addstatutory');
	Route::get('/editstatutory/{sId}', [CaCompliancesController::class, 'editstatutory'])->name('editstatutory');
	Route::get('/viewstatutory/{sId}', [CaCompliancesController::class, 'viewstatutory'])->name('viewstatutory');
	Route::post('/save_statutory', [CaCompliancesController::class, 'save_statutory'])->name('save_statutory');
	Route::post('/update_statutory', [CaCompliancesController::class, 'update_statutory'])->name('update_statutory');
	Route::get('/chat-response/{caId}/{uid}/{id}', [CaCompliancesController::class, 'chat_response'])->name('chat_response');
	Route::post('/upload_file', [MessageController::class, 'upload_file'])->name('upload_file');
	Route::post('/insert_chat', [MessageController::class, 'insert_chat'])->name('insert_chat');
	Route::post('/fetch_user_chat_history/{from_user_id}/{to_user_id}', [MessageController::class, 'fetch_user_chat_history'])->name('fetch_user_chat_history');
	Route::post('/refresh-messages', [MessageController::class, 'refreshMessages'])->name('refresh-messages');
	//added by binay on 22-03-2025

	/* Payment History */
	Route::get('/payment-history', [PaymentController::class, 'PaymentHistory'])->name('ca.PaymentHistory');
	Route::get('/fetch-customers', [PaymentController::class, 'fetchCustomers']);
	Route::get('/fetch-agents', [PaymentController::class, 'fetchAgents']);
	Route::post('/add-payment', [PaymentController::class, 'addPayment']);
	Route::get('/ca_add_payment', [PaymentController::class, 'showAddPaymentForm'])->name('ca_add.payment');
	Route::get('/ca_edit_payment/{id}', [PaymentController::class, 'showEditPaymentForm'])->name('ca_edit.payment');
	Route::get('/ca_view_payment/{id}', [PaymentController::class, 'showViewPaymentForm'])->name('ca_view.payment');
	Route::post('/update-payment', [PaymentController::class, 'updatePayment'])->name('update.payment');
	Route::post('/save-recurring-tasks', [PaymentController::class, 'saveRecurringTasks'])->name('payment.saveRecurringTasks');
	Route::delete('/delete-recurring-task/{id}', [PaymentController::class, 'deleteRecurringTask']);



	/* Earning Management*/
	Route::get('/earning-details', [EarningManagementController::class, 'EarningDetails'])->name('ca.EarningDetails');
	Route::get('/earning-transactions', [EarningManagementController::class, 'EarningTransaction'])->name('ca.EarningTransaction');



	/**********Admin Controller********** */

	// Admin Dashboard
    Route::post('/admin/dashboard-stats', [AdminDashboardController::class, 'getDashboardStats'])->name('admin.dashboard-stats');
	// Subscription Management
	Route::get('/subscription-list', [SubscriptionManagement::class, 'SubscriptionList'])->name('admin.subscription-list');
	//Route::Post('/subscription-create', [SubscriptionManagement::class, 'SubscriptionCreate'])->name('admin.subscription-create');
	Route::get('/admin/subscription-create', [SubscriptionManagement::class, 'SubscriptionCreate'])->name('admin.subscription-create');

	Route::post('/save_plan', [SubscriptionManagement::class, 'save_plan'])->name('admin.save-plan');
	Route::post('/update-plan/{id}', [SubscriptionManagement::class, 'UpdatePlan'])->name('admin.update-plan');
	Route::get('/view-plan/{id}', [SubscriptionManagement::class, 'ViewPlan'])->name('admin.view-plan');
	Route::get('/edit-plan/{id}', [SubscriptionManagement::class, 'EditPlan'])->name('admin.edit-plan');
	Route::post('/subscription-plan/toggle-status', [SubscriptionManagement::class, 'toggleStatus'])->name('subscription.toggle-status');
	Route::get('/coupon-codes', [SubscriptionManagement::class, 'CuponCode'])->name('admin.coupon-codes');
	Route::get('/admin/coupon/edit/{id}', [SubscriptionManagement::class, 'editCoupon'])->name('admin.coupon.edit');
	Route::get('/admin/coupon/view/{id}', [SubscriptionManagement::class, 'viewCoupon'])->name('admin.coupon.view');
	Route::delete('/admin/coupon/delete/{id}', [SubscriptionManagement::class, 'deleteCoupon'])->name('admin.coupon.delete');
	Route::post('/admin/coupon/update/{id}', [SubscriptionManagement::class, 'updateCoupon'])->name('admin.coupon.update');
	Route::post('/admin/coupon/save_coupon', [SubscriptionManagement::class, 'saveCoupon'])->name('admin.coupon.save_coupon');
	Route::get('/subscription-customer-list', [SubscriptionManagement::class, 'SubscriptionCustomerList'])->name('admin.subscription-customer-list');

	/* CAManagement */
	Route::get('/ca-list', [CAManagementController::class, 'CAList'])->name('admin.ca-list');
	Route::get('/ca-details/{id}', [CAManagementController::class, 'cadetails'])->name('admin.ca-details');

	/* TDS Tax Slab Management */
	Route::get('/tds-tax-slab-list', [TdstaxslabManagementController::class, 'TdsTaxSlabList'])->name('admin.tds-tax-slab-list');
	Route::get('/tds-tax-slab-details/{id}', [TdstaxslabManagementController::class, 'TdsTaxSlabDetails'])->name('admin.tds-tax-slab-details');
	Route::get('/add-tds-tax-slab', [TdstaxslabManagementController::class, 'AddTdsTaxSlab'])->name('admin.add-tds-tax-slab');
	Route::post('/save_tds_tax_slab', [TdstaxslabManagementController::class, 'save_tds_tax_slab'])->name('admin.save-tds-tax-slab');
	Route::get('/edit-tds-tax-slab/{id}', [TdstaxslabManagementController::class, 'EditTdsTaxSlab'])->name('admin.edit-tds-tax-slab');
	Route::get('/get-categories', [TdstaxslabManagementController::class, 'getCategories'])->name('get-categories');

	/* Income Tax Slab Management */
	Route::get('/income-tax-slab-list', [IncomeTaxSlabController::class, 'incomeTaxSlabList'])->name('admin.income-tax-slab-list');
	Route::get('/income-tax-slab-create', [IncomeTaxSlabController::class, 'createIncomeTaxSlab'])->name('admin.create-income-tax-slab');
	Route::post('/income-tax-slab-store', [IncomeTaxSlabController::class, 'storeIncomeTaxSlab'])->name('admin.store-income-tax-slab');
	Route::get('/income-tax-slab-view/{id}', [IncomeTaxSlabController::class, 'viewIncomeTaxSlab'])->name('admin.view-income-tax-slab');
	Route::get('/income-tax-slab-edit/{id}', [IncomeTaxSlabController::class, 'editIncomeTaxSlab'])->name('admin.edit-income-tax-slab');
	Route::post('/income-tax-slab-update/{id}', [IncomeTaxSlabController::class, 'updateIncomeTaxSlab'])->name('admin.update-income-tax-slab');
	Route::put('/income-tax-slab-update/{id}', [IncomeTaxSlabController::class, 'updateIncomeTaxSlab']);
	Route::post('/income-tax-slab-delete/{id}', [IncomeTaxSlabController::class, 'deleteIncomeTaxSlab'])->name('admin.delete-income-tax-slab');
	Route::post('/income-tax-slab-update-status/{id}', [IncomeTaxSlabController::class, 'updateStatus'])->name('admin.update-income-tax-slab-status');

	// Deduction Master Management
	Route::get('/deduction-master', [DeductionMasterController::class, 'index'])->name('deduction-master.index');
	Route::get('/deduction-master/create', [DeductionMasterController::class, 'create'])->name('deduction-master.create');
	Route::post('/deduction-master', [DeductionMasterController::class, 'store'])->name('deduction-master.store');
	Route::get('/deduction-master/{id}', [DeductionMasterController::class, 'show'])->name('deduction-master.show');
	Route::get('/deduction-master/{id}/edit', [DeductionMasterController::class, 'edit'])->name('deduction-master.edit');
	Route::put('/deduction-master/{id}', [DeductionMasterController::class, 'update'])->name('deduction-master.update');
	Route::delete('/deduction-master/{id}', [DeductionMasterController::class, 'destroy'])->name('deduction-master.destroy');

	/*Compliance Reminder Management*/
	Route::get('/compliance-reminder-list', [ComplianceReminderSetController::class, 'ComplianceReminderSetList'])->name('admin.compliance-reminder-list');
	Route::get('/compliance-reminder-details/{id}', [ComplianceReminderSetController::class, 'ComplianceReminderSetDetails'])->name('admin.compliance-reminder-details');
	Route::get('/add-compliance-reminder', [ComplianceReminderSetController::class, 'AddComplianceReminderSet'])->name('admin.add-compliance-reminder');
	Route::post('/save_compliance_reminder', [ComplianceReminderSetController::class, 'save_compliance_reminder'])->name('admin.save-compliance-reminder');
	Route::post('/update_compliance_reminder/{id}', [ComplianceReminderSetController::class, 'update_compliance_reminder'])->name('admin.update-compliance-reminder');
	Route::delete('/delete_compliance_reminder/{id}', [ComplianceReminderSetController::class, 'delete_compliance_reminder'])->name('admin.delete-compliance-reminder');

	/* Customer Management */
	Route::get('/admin-customer-list', [CustomerManagementController::class, 'AdminCustomerList'])->name('admin.admin-customer-list');
	Route::get('/customer-details/{id}', [CustomerManagementController::class, 'CustomerDetails'])->name('admin.customer-details');

	/* Business & Earnings */
	Route::get('/business-earnings', [BusinessEarningController::class, 'BusinessEarnings'])->name('admin.business-earnings');

	/* Payment Management */
	Route::get('/payment-management', [PaymentManagementController::class, 'PaymentManagement'])->name('admin.payment-management');

	/* Ticket Management */
	Route::get('/ticket-management', [TicketManagementController::class, 'TicketManagement'])->name('admin.ticket-management');
	Route::get('/customer-ticket', [TicketManagementController::class, 'CustomerTicket'])->name('admin.customer-ticket');
	Route::get('/ca-ticket', [TicketManagementController::class, 'CATicket'])->name('admin.ca-ticket');
	Route::get('/ticket-view', [TicketManagementController::class, 'TicketView'])->name('admin.ticket-view');

	/* Admin Payroll Managment / Employee Managment */
	Route::get('/admin_employee-list', [AdminEmployeeManagement::class, 'adminEmployeeList'])->name('admin.EmployeeList');
	Route::get('/admin_add-employee', [AdminEmployeeManagement::class, 'AdminAddEmployee'])->name('admin.AdminAddEmployee');
	Route::get('/admin_attendance-list', [AdminEmployeeManagement::class, 'AttendanceList'])->name('admin.AttendanceList');
	Route::get('/admin_generate-payslip', [AdminEmployeeManagement::class, 'GeneratePayslip'])->name('admin.GeneratePayslip');
	Route::post('/add_admin_employee', [AdminEmployeeManagement::class, 'add_admin_employee'])->name('add_admin_employee');
	Route::get('edit_admin_employee/{id}', [AdminEmployeeManagement::class, 'edit_admin_employee'])->name('edit_admin_employee');
	Route::post('/update_admin_employee', [AdminEmployeeManagement::class, 'update_admin_employee'])->name('update_admin_employee');
	Route::get('view_admin_employee/{id}', [AdminEmployeeManagement::class, 'view_admin_employee'])->name('view_admin_employee');

	//added by binay 21-11-2025
	Route::get('/tds-returns-filing', [TdsPfEsiController::class, 'tds_returns_filing'])->name('user.tds_returns_filing');
	Route::get('/pf-management-list', [TdsPfEsiController::class, 'pf_management_list'])->name('user.pf_management_list');
	Route::get('/esi-management-list', [TdsPfEsiController::class, 'esi_management_list'])->name('user.esi_management_list');

	Route::get('/shop-registration', [TdsPfEsiController::class, 'shop_registration'])->name('user.shop_registration');
	Route::get('/lwf-compliance', [TdsPfEsiController::class, 'lwf_compliance'])->name('user.lwf_compliance');
	Route::get('/gratuity-compliance', [TdsPfEsiController::class, 'gratuity_compliance'])->name('user.gratuity_compliance');
	Route::post('/addPfFiling', [TdsPfEsiController::class, 'addPfFiling'])->name('addPfFiling');
	Route::get('/tdspfesi/details/{id}', [TdsPfEsiController::class, 'getTdsPfEsiDetails']);
	Route::post('/update_tds_tax_slab/{id}', [TdstaxslabManagementController::class, 'update_tds_tax_slab']);
	Route::delete('/deleteTdsTaxSlab/{id}', [TdstaxslabManagementController::class, 'deleteTdsTaxSlab'])->name('deleteTdsTaxSlab');
	Route::get('/stats', [BusinessEarningController::class, 'getStats'])->name('get.stats');
	Route::get('/subscriber-stats/{range}', [BusinessEarningController::class, 'getSubscriberStats']);
	Route::get('/tds-returns-download', [TdsPfEsiController::class, 'download_tds_returns'])->name('user.tds_returns_download');
	Route::post('/download-pf-filing', [TdsPfEsiController::class, 'download_pf_filing'])->name('download.pf.filing');
	// Route::get('/esi-management-list', [TdsPfEsiController::class, 'esi_management_list'])->name('user.esi_management_list');
    Route::post('/download-esi-filing', [TdsPfEsiController::class, 'download_esi_filing'])->name('download.esi.filing');
    Route::get('/ptax_management_list', [TdsPfEsiController::class, 'ptax_management_list'])->name('user.ptax_management_list');
    Route::post('/download-ptax-filing', [TdsPfEsiController::class, 'download_ptax_filing'])->name('download.ptax.filing');

	Route::get('/ticket-response/{caId}/{uid}/{id}', [TicketManagementController::class, 'ticket_response'])->name('ticket_response');
	Route::post('/upload_file_ticket', [TicketManagementController::class, 'upload_file_ticket'])->name('upload_file_ticket');
	Route::post('/insert_chat_ticket', [TicketManagementController::class, 'insert_chat_ticket'])->name('insert_chat_ticket');
	Route::post('/fetch_user_chat_history_ticket/{from_user_id}/{to_user_id}', [TicketManagementController::class, 'fetch_user_chat_history_ticket'])->name('fetch_user_chat_history_ticket');
	Route::post('/refresh-messages-ticket', [TicketManagementController::class, 'refreshMessagesTicket'])->name('refresh-messages-ticket');
	Route::post('/resolvedTicket', [TicketManagementController::class, 'resolvedTicket'])->name('resolvedTicket');
	Route::post('/closedTicket', [TicketManagementController::class, 'closedTicket'])->name('closedTicket');
	Route::get('/customer-ticket-stats', [TicketManagementController::class, 'getCustomerTicketStats']);
	Route::get('/ca-ticket-stats', [TicketManagementController::class, 'getCaTicketStats']);
	Route::get('/support-ticket-stats', [TicketManagementController::class, 'getSupportTicketStats']);
	Route::post('/ticket/createTicket', [TicketManagementController::class, 'createTicket'])->name('ticket.createTicket');
	/* Admin Profile */
	Route::get('/admin-profile', [AdminProfileController::class, 'AdminProfile'])->name('admin.AdminProfile');
	Route::post('/admin_update_compdet', [AdminProfileController::class, 'admin_update_compdet'])->name('admin_update_compdet');
	Route::post('/admin_update_businessdet', [AdminProfileController::class, 'admin_update_businessdet'])->name('admin_update_businessdet');
	Route::post('/admin_update_bankdet', [AdminProfileController::class, 'admin_update_bankdet'])->name('admin_update_bankdet');
	Route::post('/admin_update_comp_attachment', [AdminProfileController::class, 'admin_update_comp_attachment'])->name('admin_update_comp_attachment');
	Route::post('/admin-upload-profile-image', [AdminProfileController::class, 'adminUploadProfileImage'])->name('adminUpload.profile.image');
	Route::post('/adminHolidays', [AdminProfileController::class, 'adminHolidayStore']);
	Route::get('/adminHolidays/{id}/edit', [AdminProfileController::class, 'adminHolidayEdit']);
	Route::put('/adminHolidays/{id}', [AdminProfileController::class, 'adminHolidayUpdate']);
	Route::delete('/adminHolidays/{id}', [AdminProfileController::class, 'adminHolidayDestroy']);
	Route::post('/admin-save-schedule', [AdminProfileController::class, 'adminScheduleStore'])->name('adminsave.schedule');
	Route::post('/admin-save-location', [AdminProfileController::class, 'adminSaveLocation'])->name('adminsave.location');
	Route::get('/get-location-admin/{id}', [AdminProfileController::class, 'getLocationAdmin'])->name('get.location.admin');
	Route::put('/update-location-admin/{id}', [AdminProfileController::class, 'updateLocationAdmin'])->name('update.location.admin');
	Route::delete('/delete-location-admin/{id}', [AdminProfileController::class, 'deleteLocationAdmin'])->name('delete.location.admin');

	Route::get('/payment-details', [PaymentManagementController::class, 'paymentDetails'])->name('payment.details');
	Route::post('/uploadBank_statement', [ContraController::class, 'uploadBank_statement']);
	Route::post('/fetchBankReconciliation', [ReportsController::class, 'fetchBankReconciliation'])->name('user.fetchBankReconciliation');
	Route::get('/pdf-BankReconciliation/download', [ReportsController::class, 'downloadPdf'])->name('bank.reconciliation.download');



	// Admin Reminder Management
	Route::post('/allUserCA', [ReminderController::class, 'allUserCA']);
	Route::post('/userCaListsAccess', [ReminderController::class, 'userCaListsAccess']);

	//Quotation route
	Route::get('/sales-quotation', [QuotationsController::class, 'quotationInvoiceIndex'])->name('user.SalesQuotation');
	Route::get('/view-quotation-invoice/{sId}', [QuotationsController::class, 'view_quotation_invoice'])->name('view_sales_invoice');
	Route::get('/create-quotation-invoices', [QuotationsController::class, 'CreateQuotationInvoices'])->name('user.CreateQuotationInvoices');
	Route::post('/save_quotation_invoice', [QuotationsController::class, 'save_quotation_invoice'])->name('save_quotation_invoice');
	Route::get('/edit-quotation-invoice/{sId}', [QuotationsController::class, 'edit_quotation_invoice'])->name('user.edit_quotation_invoice');
	Route::get('/getQuotationcust', [QuotationsController::class, 'getQuotationcust'])->name('getQuotationcust');
	Route::post('/update_quotation_customer', [QuotationsController::class, 'update_quotation_customer'])->name('update_quotation_customer');
	Route::post('/update_quotation_invoice', [QuotationsController::class, 'update_quotation_invoice'])->name('update_quotation_invoice');
	Route::post('/update_quotation_item_rate', [QuotationsController::class, 'update_quotation_item_rate'])->name('update_quotation_item_rate');
	Route::post('/quotation_items_display', [QuotationsController::class, 'quotation_items_display'])->name('quotation_items_display');
	Route::post('/update_quotation_invoice_final', [QuotationsController::class, 'update_quotation_invoice_final'])->name('update_quotation_invoice_final');
	Route::post('/update_quotation_other', [QuotationsController::class, 'update_quotation_other'])->name('update_quotation_other');
	Route::delete('/delQuotationInvoice/{id}', [QuotationsController::class, 'delQuotationInvoice'])->name('quotation.delQuotationInvoice');
	Route::post('/fetch-quotation-details', [QuotationsController::class, 'fetchQuotationDetails'])->name('fetchQuotationDetails');
	Route::post('/update_quotation_item_quantity', [QuotationsController::class, 'update_quotation_item_quantity'])->name('update_quotation_item_quantity');
	Route::post('/fetchQuotationItem', [QuotationsController::class, 'fetchQuotationItem'])->name('fetchQuotationItem');
	Route::post('/delQuotationItem', [QuotationsController::class, 'delQuotationItem'])->name('delQuotationItem');
	Route::get('/quotation-invoice-pdf/{id}/{invType}', [InvoiceController::class, 'getQuotationInvoice'])->name('user.getQuotationInvoice');
	Route::post('/quotation/update-quotation-status', [QuotationsController::class, 'updateQuotationStatus'])->name('quotation.update-quotation-status');

	//Proforma route
	Route::get('/proform-invoice', [ProformasController::class, 'proformaInvoiceIndex'])->name('user.ProformaInvoice');
	Route::get('/view-proforma-invoice/{sId}', [ProformasController::class, 'view_proforma_invoice'])->name('view_proforma_invoice');
	Route::get('/create-proform-invoice', [ProformasController::class, 'CreateProformaInvoice'])->name('user.CreateProformaInvoice');
	Route::post('/save_proforma_invoice', [ProformasController::class, 'save_proforma_invoice'])->name('save_proforma_invoice');
	Route::get('/edit-proforma-invoice/{sId}', [ProformasController::class, 'edit_proforma_invoice'])->name('user.edit_proforma_invoice');
	Route::get('/getProformacust', [ProformasController::class, 'getProformacust'])->name('getProformacust');
	Route::post('/update_proforma_customer', [ProformasController::class, 'update_proforma_customer'])->name('update_proforma_customer');
	Route::post('/update_proforma_invoice', [ProformasController::class, 'update_proforma_invoice'])->name('update_proforma_invoice');
	Route::post('/update_proforma_item_rate', [ProformasController::class, 'update_proforma_item_rate'])->name('update_proforma_item_rate');
	Route::post('/proforma_items_display', [ProformasController::class, 'proforma_items_display'])->name('proforma_items_display');
	Route::post('/update_proforma_invoice_final', [ProformasController::class, 'update_proforma_invoice_final'])->name('update_proforma_invoice_final');
	Route::post('/update_proforma_other', [ProformasController::class, 'update_proforma_other'])->name('update_proforma_other');
	Route::delete('/delProformaInvoice/{id}', [ProformasController::class, 'delProformaInvoice'])->name('proforma.delProformaInvoice');
	Route::post('/fetch-proforma-details', [ProformasController::class, 'fetchProformaDetails'])->name('fetchProformaDetails');
	Route::post('/update_proforma_item_quantity', [ProformasController::class, 'update_proforma_item_quantity'])->name('update_proforma_item_quantity');
	Route::post('/fetchProformaItem', [ProformasController::class, 'fetchProformaItem'])->name('fetchProformaItem');
	Route::post('/delProformaItem', [ProformasController::class, 'delProformaItem'])->name('delProformaItem');
	Route::get('/proforma-invoice-pdf/{id}/{invType}', [InvoiceController::class, 'getProformaInvoice'])->name('user.getProformaInvoice');
	Route::post('/proforma/update-proforma-status', [ProformasController::class, 'updateProformaStatus'])->name('proforma.update-proforma-status');

	Route::post('/quotation-to-proforma', [ProformasController::class, 'quotationToProforma'])->name('quotation.to.proforma');
	Route::post('/quotation-to-sales', [SalesController::class, 'quotationToSales'])->name('quotation.to.sales');
	Route::get('/purchase-invoice-pdf/{id}/{invType}', [InvoiceController::class, 'getPurchaseInvoice'])->name('user.getPurchaseInvoice');

	/* PO route */
	Route::post('/fetch-po-vendor-details', [PoController::class, 'fetchPoVendorDetails'])->name('fetchPoVendorDetails');
	Route::post('/fetch-po-details', [PoController::class, 'fetchPoDetails'])->name('fetchPoDetails');
	Route::get('/purchase-order', [PoController::class, 'PurchaseOrder'])->name('user.PurchaseOrder');
	Route::get('/create-purchase-order', [PoController::class, 'CreatePurchaseOrder'])->name('user.CreatePurchaseOrder');
	Route::post('/save_po_invoice', [PoController::class, 'save_po_invoice'])->name('save_po_invoice');
	Route::get('/edit-po-invoice/{sId}', [PoController::class, 'edit_po_invoice'])->name('edit_po_invoice');
	Route::post('/update_po_invoice', [PoController::class, 'update_po_invoice'])->name('update_po_invoice');
	Route::post('/update_po_item', [PoController::class, 'update_po_item'])->name('update_po_item');
	Route::post('/update_po_item_quantity', [PoController::class, 'update_po_item_quantity'])->name('update_po_item_quantity');
	Route::post('/update_po_item_rate', [PoController::class, 'update_po_item_rate'])->name('update_po_item_rate');
	Route::get('/getinvcust_po', [PoController::class, 'getinvcust_po'])->name('getinvcust_po');
	Route::post('/po_items_display', [PoController::class, 'po_items_display'])->name('po_items_display');
	Route::post('/update_po_seller_details',  [PoController::class, 'update_po_seller_details'])->name('update_po_seller_details');
	Route::post('/update_po_invoice_final', [PoController::class, 'update_po_invoice_final'])->name('update_po_invoice_final');
	Route::post('/update_po_other', [PoController::class, 'update_po_other'])->name('update_po_other');
	Route::get('/view-po-invoice/{sId}', [PoController::class, 'view_po_invoice'])->name('view_po_invoice');
	Route::delete('/delInvoicePo/{id}', [PoController::class, 'delInvoicePo'])->name('delInvoicePo');
	Route::post('/fetchPoItem', [PoController::class, 'fetchPoItem'])->name('fetchPoItem');
	Route::get('/po-invoice-pdf/{id}/{invType}', [InvoiceController::class, 'getPoInvoice'])->name('user.getPoInvoice');
	Route::post('/po/update-po-status', [PoController::class, 'updatePoStatus'])->name('po.update-po-status');
	Route::post('/delPoItem', [PoController::class, 'delPoItem'])->name('delPoItem');
	Route::post('/po-to-purchase', [PurchaseController::class, 'poToPurchase'])->name('po.to.purchase');

	Route::post('/update_contactDetails', [CompanyProfileController::class, 'update_contactDetails'])->name('update_contactDetails');
	Route::get('/directors', [DirectorController::class, 'index'])->name('directors.index');
	Route::post('/directors/store', [DirectorController::class, 'store'])->name('directors.store');
	Route::get('/directors/{id}', [DirectorController::class, 'show'])->name('directors.show');
	Route::post('/directors/update/{id}', [DirectorController::class, 'update'])->name('directors.update');
	Route::delete('/director/delete/{id}', [DirectorController::class, 'delete'])->name('director.delete');
	Route::post('/ledger/ajax', [ReportsController::class, 'ajaxLedgerData']);
	Route::post('/download-trial-balance-pdf', [ReportsController::class, 'downloadTrialBalanceSheetPdf'])->name('trialbalancesheet.download.pdf');
	Route::get('/company-profile-check/fetch', [BusinessHealthCheckupController::class, 'fetch']);
    Route::post('/company-profile-check/save', [BusinessHealthCheckupController::class, 'save']);
	Route::get('/cashflow', [ReportsController::class, 'cashflow'])->name('user.cashflow');
	Route::post('/cashflow/ajax', [ReportsController::class, 'ajaxCashFlowData']);
	Route::get('/user/audit-logs', [AuditController::class, 'auditIndex'])->name('user.audit.logs');
	Route::post('/ca/payment/delete', [PaymentController::class, 'deletePayment'])->name('ca.payment.delete');
	Route::post('/agent-status-change', [AgentController::class, 'changeStatus'])->name('agent.status.change');
	Route::get('/tds-slabs', [EmployeeManagemnet::class, 'getTdsSlabs']);
	Route::get('/tds/vendor-purchase', [VendorTdsController::class, 'index']);
	Route::post('/tds/vendor-purchase/filter', [VendorTdsController::class, 'filter']);
	Route::post('/tds/vendor-purchase/pdf', [VendorTdsController::class, 'downloadPdf']);

	Route::post('/msme/apply', [TaxFilingController::class, 'applyMsmeApplication']);
	Route::get('/msme/list', [TaxFilingController::class, 'msmeApplcationListing']);
	Route::post('/msme/delete', [TaxFilingController::class, 'deleteMsmeApplcation']);
	Route::post('/mca-roc/apply', [TaxFilingController::class, 'addMCAROCFiling']);
	Route::get('/mca-roc/list', [TaxFilingController::class, 'MCAROCFilingListing']);
	Route::post('/mca-roc/delete', [TaxFilingController::class, 'deleteMCAROCFiling']);
	Route::get('/mca-roc/view/{id}', [TaxFilingController::class, 'viewMCAROCFiling'])->name('mca.roc.view');

	Route::post('/startup-filing/store', [TaxFilingController::class,'startupFilingApply']);
	Route::get('/startup-filing/list', [TaxFilingController::class,'startupFilingListing']);
	Route::get('/startup-filing/view/{id}', [TaxFilingController::class,'startupFilingView']);
	Route::post('/startup-filing/delete', [TaxFilingController::class,'startupFilingDelete']);

    Route::post('/itr/apply', [TaxFilingController::class, 'incomeTaxReturnApply'])->name('itr.store');
    Route::get('/itr/list', [TaxFilingController::class, 'incomeTaxReturnListing'])->name('itr.list');
    Route::get('/itr/view/{id}', [TaxFilingController::class, 'incomeTaxReturnShow'])->name('itr.view');
	Route::delete('/itr/delete/{id}', [TaxFilingController::class, 'incomeTaxReturnDelete'])->name('itr.delete');

	Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
	Route::post('/change-email', [ProfileController::class, 'changeEmail']);
	Route::post('/password-change', [ProfileController::class, 'passwordChange']);

	Route::delete('/product-image/{id}', [ProductServiceController::class,'deleteImage']);

	Route::get('/gst-compliance-support', [SupportTicketController::class, 'GSTComplianceSupport'])->name('User.GSTComplianceSupport');
	Route::post('/support-ticket/store', [SupportTicketController::class, 'store']);
    Route::get('/support-ticket/{id}/messages', [SupportTicketController::class, 'messages']);
    Route::post('/support-ticket/send-message', [SupportTicketController::class, 'sendMessage']);
	Route::post('/support-ticket/{ticket}/resolve', [SupportTicketController::class, 'resolve']);
	Route::post('/support-ticket/{ticket}/close', [SupportTicketController::class, 'close']);

	Route::post('/document-locker/set-passcode', [DocLockerController::class, 'setPasscode']);
    Route::post('/document-locker/verify-passcode', [DocLockerController::class, 'verifyPasscode']);
	Route::post('/documents/upload', [DocLockerController::class, 'upload'])->name('documents.upload');
    Route::get('/documents/{id}/view', [DocLockerController::class, 'view']);
    Route::get('/documents/{id}/download', [DocLockerController::class, 'download']);
    Route::delete('/documents/{id}', [DocLockerController::class, 'destroy']);
	Route::get('/cas/list', [DocLockerController::class, 'ca_list']);
	Route::post('/documents/give-access', [DocLockerController::class, 'giveAccess']);

	Route::post('/admin/ca/status-update', [CAManagementController::class, 'statusUpdate'])->name('admin.ca.status');
	Route::post('/admin/ca/send-message', [CAManagementController::class, 'sendCaMessage'])->name('admin.ca.message');
	Route::post('/support-send', [HelpController::class, 'send'])->name('support.send');
	Route::post('/check-email', [EmployeeManagemnet::class, 'checkEmail']);

	Route::get('/liabilities/{type}', [LiabilitesController::class, 'fetchDetails'])->name('liabilities.details');
	Route::get('/company-profile-checks', [BusinessHealthCheckupController::class, 'listingProfile'])->name('admin.company.checks');
    Route::get('/company-profile-checks/{id}', [BusinessHealthCheckupController::class, 'showProfile'])->name('admin.company.checks.view');
    Route::post('/company-profile-checks/{id}/status', [BusinessHealthCheckupController::class, 'updateProfileStatus'])->name('admin.company.checks.status');
	Route::post('/company-profile-check/saveByAdmin', [BusinessHealthCheckupController::class, 'saveByAdmin']);
	Route::post('/admin/update-ca-subscription', [CAManagementController::class, 'updateCaSubscription']);

	Route::post('/calculate-tds-income', [OtherIncomeController::class, 'calculateTdsIncome']);
	Route::get('/ca/monthly-payments', [CADashboardController::class, 'monthlyPayments']);
	Route::get('/api/customer-payment-stats', [CADashboardController::class, 'getCustomerPaymentStats']);
	Route::get('/dashboard/task-stats', [CADashboardController::class, 'taskStatusSummary']);

	Route::post('/save-product-and-add-sales', [SalesController::class, 'saveProductAndSales']);
	Route::post('/save-product-and-add-purchase', [PurchaseController::class, 'saveProductAndPurchase']);
	Route::post('/save-product-and-add-quotation', [QuotationsController::class, 'saveProductAndQuotation']);
	Route::post('/save-product-and-add-proforma', [ProformasController::class, 'saveProductAndProforma']);

	Route::post('/save_inventory_expenses', [ExpensesController::class, 'addInventoryExpenses']);
	Route::post('/update_inventory_expenses', [ExpensesController::class, 'updateInventoryExpenses']);
	Route::get('/edit_inventory_expenses/{id}', [ExpensesController::class, 'editInventoryExpenses']);
	Route::delete('/delete_inventory_expenses/{id}', [ExpensesController::class, 'deleteInventoryExpenses']);
	Route::get('/get-invoice-total/{id}', [SalesController::class, 'getInvoiceTotal']);
	Route::get('/get-loan/{id}', [ContraController::class, 'getLoan']);
	Route::post('/update_loan', [ContraController::class, 'update_loan'])->name('update_loan');
	Route::delete('/delete-loan/{id}', [ContraController::class, 'deleteLoan']);
	Route::get('/dashboard/task-wise-clients', [CADashboardController::class, 'taskWiseClients']);
	Route::get('/dashboard/monthwise-onboard', [CADashboardController::class, 'monthwiseOnboardClients']);

	Route::post('/check-proprietorship-company',[ProprietorshipProfileController::class,'checkProprietorshipCompany'])->name('check.proprietorship.company');
	Route::get('/proprietorship-list', [ProprietorshipProfileController::class,'proprietorship_lists'])->name('proprietorship.list');
	Route::get('/proprietorship-edit/{id}', [ProprietorshipProfileController::class,'proprietorship_edit'])->name('proprietorship.edit');
	Route::get('/proprietorship-delete/{id}', [ProprietorshipProfileController::class,'proprietorship_delete'])->name('proprietorship.delete');
	Route::post('/update_compdet_proprietorship', [ProprietorshipProfileController::class, 'update_compdet_proprietorship'])->name('update_compdet_proprietorship');
	Route::post('/update_businessdet_proprietorship', [ProprietorshipProfileController::class, 'update_businessdet_proprietorship'])->name('update_businessdet_proprietorship');
	Route::post('/update_contactDetails_proprietorship', [ProprietorshipProfileController::class, 'update_contactDetails_proprietorship'])->name('update_contactDetails_proprietorship');
	Route::post('/update_comp_attachment_proprietorship', [ProprietorshipProfileController::class, 'update_comp_attachment_proprietorship'])->name('update_comp_attachment_proprietorship');
	Route::post('/upload-profile-image-proprietorship', [ProprietorshipProfileController::class, 'uploadProfileImage_Proprietorship'])->name('upload.profile.image.proprietorship');

	Route::post('/directors/proprietorship/store', [DirectorController::class, 'storeProprietorship'])->name('directors.proprietorship.store');
	Route::get('/directors/proprietorship/{id}', [DirectorController::class, 'showProprietorship'])->name('directors.proprietorship.show');
	Route::post('/directors/proprietorship/update/{id}', [DirectorController::class, 'updateProprietorship'])->name('directors.proprietorship.update');
	Route::delete('/director/proprietorship/delete/{id}', [DirectorController::class, 'deleteProprietorship'])->name('director.proprietorship.delete');
	Route::post('/update_bankdet_proprietorship', [ProprietorshipProfileController::class, 'update_bankdet_proprietorship'])->name('update_bankdet_proprietorship');

	Route::post('/delPurchaseItem', [PurchaseController::class, 'delPurchaseItem'])->name('delPurchaseItem');
	Route::delete('/bank_delete/{id}', [ContraController::class, 'deleteBank'])->name('bank.delete');

	Route::get('/msme-reply/{id}/messages', [TaxFilingController::class, 'messages']);
    Route::post('/msme-reply/send-message', [TaxFilingController::class, 'sendMessage']);

	Route::post('/unlock-user', [LoginController::class, 'unlockUser'])->name('unlock.user');

	Route::post('/mca-roc/status-update', [TaxFilingController::class, 'updateStatus']);
	Route::get('/mcaroc-reply/{id}/messages', [TaxFilingController::class, 'messagesMCAROC']);
    Route::post('/mcaroc-reply/send-message', [TaxFilingController::class, 'sendMessageMCAROC']);

	Route::post('/itr-filing/status-update', [TaxFilingController::class, 'updateItrFilingStatus']);
	Route::get('/itrfiling-reply/{id}/messages', [TaxFilingController::class, 'messagesItrFiling']);
    Route::post('/itrfiling-reply/send-message', [TaxFilingController::class, 'sendMessageItrFiling']);

	Route::get('journal-list', [JournalController::class, 'JournalList'])->name('user.JournalList'); // Journal List
	Route::get('add-journal', [JournalController::class, 'AddJournal'])->name('user.AddJournal'); // Add Journal
	//Route::get('view-journal', [JournalController::class, 'ViewJournal'])->name('user.ViewJournal'); // View Journal
	Route::post('save-journal', [JournalController::class, 'save'])->name('user.saveJournal');
	Route::get('view-journal/{id}', [JournalController::class, 'viewJournal'])->name('user.viewJournal');
	Route::get('edit-journal/{id}', [JournalController::class, 'editJournal'])->name('user.editJournal');
	Route::post('update-journal/{id}', [JournalController::class, 'update'])->name('user.updateJournal');
	Route::get('delete-journal-file/{id}', [JournalController::class, 'deleteFile']);
	Route::delete('delete-journal/{id}', [JournalController::class, 'delete']);
	Route::post('/journal-reverse', [JournalController::class, 'reverseJournal'])->name('journal.reverse');
	Route::get('/journal-export', [JournalController::class, 'export'])->name('journal.export');

	Route::get('/get-employees', [ExpensesController::class, 'getEmployees']);

	//added by binay
	Route::get('/get-cash-in-hand', [CommonController::class, 'getCashInHand']);
	Route::get('/bank-accounts', [CommonController::class, 'getBankAccounts']);
	Route::get('/get-employee-advance', [CommonController::class, 'getEmployeeAdvance']);
	Route::get('/get-prepaid-expense', [CommonController::class, 'getPrepaidExpense']);
	Route::get('/calculate-monthly-tds', [CommonController::class, 'calculateMonthlyTDS']);
	Route::get('/calculate-gross-profit', [CommonController::class, 'calculateGrossProfit']);
	Route::get('/export-assets', [CommonController::class, 'exportAssets']);
	Route::get('/get-trade-receivable-amount', [CommonController::class, 'getTradeReceivableAmount']);
	Route::get('/get-advance-vendor-amount', [CommonController::class, 'getAdvanceVendorAmount']);
	Route::get('/get-itc', [CommonController::class, 'getGSTSummary']);
	Route::post('/calculate-tds', [AssetController::class, 'calculateTdsAjax'])->name('calculate.tds');

	Route::post('/calculate-tds-invexp', [ExpensesController::class, 'calculateTdsInvexp']);
	Route::get('/common/get-status/{table}/{id}', [TaxFilingController::class, 'getAppStatus']);
	Route::post('/common/update-status', [TaxFilingController::class, 'updateAppStatus']);

	Route::post('/payment-voucher/store', [PaymentVoucherController::class, 'store'])->name('payment.store');
	Route::post('/payment-voucher/update/{id}', [PaymentVoucherController::class, 'update']);
	Route::get('/payment-voucher/edit/{id}', [PaymentVoucherController::class, 'edit']);
	Route::get('/get-party-list', [PaymentVoucherController::class, 'getPartyList']);
	Route::get('/get-invoice-list', [PaymentVoucherController::class, 'getInvoiceList']);
	Route::get('/get-invoice-amount', [PaymentVoucherController::class, 'getInvoiceAmount']);
	Route::get('/generate-payment-voucher-no', [PaymentVoucherController::class, 'generatePaymentVoucherNo']);
	Route::get('/task-management/get-task/{id}', [TaskManagementController::class, 'getTask']);
	Route::get('/get-bank-list', [PaymentVoucherController::class, 'getBankList']);
	Route::post('/payment-voucher/quick-update', [PaymentVoucherController::class, 'quickUpdate']);
	Route::delete('/payment-voucher/delete/{id}', [PaymentVoucherController::class, 'voucherDelete'])->name('payment.delete');
	Route::post('/save-ca-permissions', [CompanyProfileController::class, 'saveCaPermissions'])->name('save.ca.permissions');

	//chat CA to company
	Route::post('/chat/start', [ChatController::class, 'startChat']);
	Route::post('/chat/send', [ChatController::class, 'sendMessage']);
	Route::get('/chat/messages/{conversationId}', [ChatController::class, 'getMessages']);
	Route::post('/chat/read/{conversationId}', [ChatController::class, 'markAsRead']);
	Route::get('/chat/unread-count', [ChatController::class, 'getUnreadCount']);

	Route::get('/payment-invoice/{type}/{id}', [PayController::class,'getPayments']);
	Route::post('/payment-invoice/store', [PayController::class,'storePayments']);
	Route::delete('/payment-invoice/{id}',[PayController::class,'deletePayment']);

	Route::get('/profit-loss-report', [ProfitLossController::class, 'ProfitLoss'])->name('user.ProfitLossReport');
	Route::post('/fetch-profit-loss-data', [ProfitLossController::class, 'profit_loss_data'])->name('profit_loss_data');
	Route::post('/download-pl-sheet-pdf', [ProfitLossController::class, 'downloadPLSheetPdf'])->name('plsheet.download.pdf');


	//---------- Admin Deductions Management ----------//
	Route::resource('deduction-master', DeductionMasterController::class);

	Route::get('/balance-sheet-report', [BalanceSheetController::class, 'BalanceSheet'])->name('user.BalanceSheetReport');
	Route::post('/fetch_balance_sheet_data', [BalanceSheetController::class, 'fetch_balance_sheet_data'])->name('balance_sheet_data');
	Route::post('/download-balance-sheet-pdf', [BalanceSheetController::class, 'downloadBalanceSheetPdf'])->name('balancesheet.download.pdf');

	Route::get('/add-previous-balance-sheet', [BalanceSheetController::class, 'addPreviousBalanceSheet'])->name('addPreviousBalanceSheet');
	Route::post('/save-previous-balance-sheet', [BalanceSheetController::class, 'savePreviousBalanceSheet'])->name('savePreviousBalanceSheet');

	//Admin Tax Deduction Master
	Route::get('/tax-deduction-master', [TaxDeductionController::class, 'index'])->name('tax.index');
    Route::get('/tax-deduction-master/create', [TaxDeductionController::class, 'create'])->name('tax.create');
    Route::post('/tax-deduction-master/store', [TaxDeductionController::class, 'store'])->name('tax.store');
    Route::get('/tax-deduction-master/show/{id}', [TaxDeductionController::class, 'show'])->name('tax.show');
    Route::get('/tax-deduction-master/edit/{id}', [TaxDeductionController::class, 'edit'])->name('tax.edit');
    Route::post('/tax-deduction-master/update/{id}', [TaxDeductionController::class, 'update'])->name('tax.update');
    Route::delete('/tax-deduction-master/delete/{id}', [TaxDeductionController::class, 'delete'])->name('tax.delete');
	Route::get('/expense-heads', [TaxDeductionController::class, 'getExpenseHead'])->name('expense.heads');

	Route::post('/upload-signed-pdf',[DigitalSignedController::class,'uploadSignedPdf'])->name('upload.signed.pdf');
	Route::get('/download-signed-pdf/{type}/{id}',[DigitalSignedController::class,'downloadSignedPdf'])->name('download.signed.pdf');

	Route::post('/get-dropdown-types', [CommonController::class, 'getDropdownTypes'])->name('getDropdownTypes');
	Route::post('/get-tax-rule',[CommonController::class,'getTaxRule'])->name('getTaxRule');

	Route::get('/dropdown-values', [DropdownValueController::class, 'index'])->name('dropdown.index');
	Route::post('/dropdown-values/store', [DropdownValueController::class, 'store'])->name('dropdown.store');
	Route::post('/dropdown-values/update/{id}', [DropdownValueController::class, 'update'])->name('dropdown.update');
	Route::get('/dropdown-values/edit/{id}', [DropdownValueController::class, 'edit'])->name('dropdown.edit');
	Route::get('/dropdown-values/view/{id}', [DropdownValueController::class, 'show'])->name('dropdown.show');
	Route::delete('/dropdown-values/delete/{id}', [DropdownValueController::class, 'destroy'])->name('dropdown.delete');

	Route::post('/purchase_shipping_cost',[PurchaseController::class,'purchaseShippingCost']);

	Route::get('/payment-voucher/export',[PaymentVoucherController::class, 'exportPaymentVoucher'])->name('paymentVoucher.export');

	Route::get('/get-banks', [CommonController::class, 'getBankList']);

	//-------- payroll report routes --------//
	Route::get('/payslip/update', [EmployeeManagemnet::class, 'updatePayslip'])->name('payroll.payslip_update');

	Route::get('/payroll-report/summary', [PayrollReportController::class, 'summary'])->name('payroll.report.summary');
	Route::get('/payroll-report/register', [PayrollReportController::class, 'payrollRegister'])->name('payroll.report.register');
	Route::get('/payroll/report/attendance', [PayrollReportController::class, 'attendanceRegister'])->name('payroll.report.attendance');
	Route::get('/payroll/payslip/list', [PayrollReportController::class, 'getPayslipList'])->name('payroll.payslip.list');
	Route::post('/payroll/payslip/update', [PayrollReportController::class, 'updatePayslips'])->name('payroll.payslip.update');
	Route::get('/payroll/tds/list', [PayrollReportController::class, 'getTdsList'])->name('payroll.tds.list');
	Route::post('/payroll/tds/update', [PayrollReportController::class, 'updateTds'])->name('payroll.tds.update');
	Route::get('/payroll/pf/list', [PayrollReportController::class, 'getPfList'])->name('payroll.pf.list');
	Route::post('/payroll/pf/update', [PayrollReportController::class, 'updatePf'])->name('payroll.pf.update');
	Route::get('/payroll/esi/list', [PayrollReportController::class, 'getEsiList'])->name('payroll.esi.list');
	Route::post('/payroll/esi/update', [PayrollReportController::class, 'updateEsi'])->name('payroll.esi.update');
	Route::get('/payroll/ptax/list', [PayrollReportController::class, 'getPtaxList'])->name('payroll.ptax.list');
	Route::get('/payroll/ptax/summary', [PayrollReportController::class, 'getPtaxSummary'])->name('payroll.ptax.summary');
	Route::post('/payroll/ptax/update', [PayrollReportController::class, 'updatePtax'])->name('payroll.ptax.update');
	Route::get('/payroll/salary-sheet', [PayrollReportController::class, 'getSalarySheetData'])->name('payroll.salary.sheet');
	Route::get('/payroll/lwf/list', [PayrollReportController::class, 'getLwfList'])->name('payroll.lwf.list');
	Route::get('/payroll/lwf/full-list', [PayrollReportController::class, 'getLwfFullList'])->name('payroll.lwf.fullList');
	Route::post('/payroll/lwf/update', [PayrollReportController::class, 'updateLwf'])->name('payroll.lwf.update');
	Route::get('/payroll/gratuity/list', [PayrollReportController::class, 'getGratuityList'])->name('payroll.gratuity.list');

	Route::post('/get-tds-rule-liab', [CommonController::class, 'getTdsRuleLiability'])->name('get.tds.rule');
	Route::post('/settlement/store',[SettlementController::class, 'store'])->name('settlement.store');
	Route::get('/settlement/ledgers',[SettlementController::class, 'getSettlementLedgers'])->name('settlement.ledgers');
	Route::get('/settlement/amount',[SettlementController::class, 'getSettlementAmount'])->name('settlement.amount');

});
