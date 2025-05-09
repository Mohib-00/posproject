<?php
use App\Http\Controllers\AboutServiceController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\Emplyeescontroller;
use App\Http\Controllers\grnController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\saleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserAuthcontroller;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

//User Page    
Route::get('/', [UserAuthController::class, 'home']);
//to open register page
//Route::get("register", [RegisterController::class, "register"]);
//to open login page
Route::get("login", [RegisterController::class, "login"]);
//register
Route::post("registerrr",[UserAuthcontroller::class,"register"]);
//Login
Route::post("login",[UserAuthcontroller::class,"login"])->name('login');

Route::group([
    "middleware" => ["auth:sanctum"]
],function(){

//Logout
Route::post("logout",[UserAuthcontroller::class,"logout"]);
//to logout normal user
Route::post('logoutuser', [UserAuthcontroller::class, 'logoutuser']);
//to change password
Route::post("changePassword",[UserAuthcontroller::class,"changePassword"]);
});

Route::group(['middleware' => ['admin.auth'], 'prefix' => 'admin'], function() {
    Route::get("", [UserAuthcontroller::class, "admin"]);
    Route::get("users", [UserAuthcontroller::class, "users"]);
    Route::get("format", [UserAuthcontroller::class, "format"]);
    Route::get("admin_profile", [SettingsController::class, "adminprofile"]);
    Route::get("add_user", [UserAuthcontroller::class, "adduser"]);
    Route::get("add_vendor", [VendorController::class, "addvendor"]);
    Route::get("area", [AreaController::class, "areas"]);
    Route::get("customer_list", [CustomerController::class, "customer"]);
    Route::get("add_customer", [CustomerController::class, "addcustomer"]);
    Route::get("blocked_client_list", [CustomerController::class, "blockedclientlist"]);
    Route::get("employees_list", [Emplyeescontroller::class, "employeeslist"]);
    Route::get("add_employee", [Emplyeescontroller::class, "addemployee"]);
    Route::get("employees_leave", [LeaveController::class, "employeesleave"]);
    Route::get("designation", [DesignationController::class, "adddesignation"]);
    Route::get("employee_attendance", [AttendanceController::class, "employeeattendance"]);
    Route::get("employee_attendance_report", [AttendanceController::class, "attendancereport"]);
    Route::get("company_list", [CompanyController::class, "addcompany"]);
    Route::get("category_list", [CategoryController::class, "addcategory"]);
    Route::get("subcategory_list", [SubCategoryController::class, "addsubcategory"]);
    Route::get("products_list", [ProductsController::class, "addproduct"]);
    Route::get("product_price_list", [ProductsController::class, "productpricelist"]);
    Route::get("product_import", [ProductsController::class, "productimport"]);
    Route::get("purchase_list", [PurchaseController::class, "addpurchase"]);
    Route::get("purchase", [PurchaseController::class, "purchases"]);
    Route::get('edit_purchase_list/{id}', [PurchaseController::class, 'editpurchases'])->name('edit.purchase');
    Route::get('purchase_invoice/{id}', [PurchaseController::class, 'purchaseinvoice'])->name('purchase.invoice');
    Route::get("GRN", [grnController::class, "openGRN"]);
    Route::get("chart_of_account", [AccountController::class, "chartofaccount"]);
    Route::get("add_account", [AccountController::class, "addaccount"]);
    Route::get('assets_child/{head_name}', [AccountController::class, 'showByHeadName'])->name('assets.child');
    Route::get('liability_child/{head_name}', [AccountController::class, 'liabilitychild'])->name('liability.child');
    Route::get('revenue_child/{head_name}', [AccountController::class, 'revenuechild'])->name('revenue.child');
    Route::get('equity_child/{head_name}', [AccountController::class, 'equitychild'])->name('equity.child');
    Route::get('expense_child/{head_name}', [AccountController::class, 'expensechild'])->name('expense.child');
    Route::get('customers_account/{head_name}', [AccountController::class, 'customersaccount'])->name('customers.child');
    Route::get('/get_account/{id}', [AccountController::class, 'getAccount'])->name('get.account');
    Route::get('vendor_account/{head_name}', [AccountController::class, 'vendoraccountssss'])->name('vendor.child');
    Route::get("payment", [PaymentController::class, "pay"]);
    Route::get("POS", [saleController::class, "pos"]);
    Route::get("sale_list", [saleController::class, "salelist"]);
    Route::get('/edit_sale_list/{id}', [SaleController::class, 'edit'])->name('admin.edit_sale_list');
    Route::get('sale_invoice/{id}', [SaleController::class, 'saleinvoice'])->name('sale.invoice');
    Route::get('sale_print_invoice/{id}', [SaleController::class, 'saleprintinvoice'])->name('saleprint.invoice');
    Route::get('add_voucher', [VoucherController::class, 'addvoucher'])->name('voucher');
    Route::get('voucher', [VoucherController::class, 'voucher'])->name('showvoucher');
});
//to open forgot password page
Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
//to send reset link
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
//to open reset password page
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
//to reset password
Route::post('reset-password', [ResetPasswordController::class, 'updatePassword'])->name('password.update');
//to update profile
Route::post('/update-profile', [SettingsController::class, 'updateProfile'])->name('update.profile');
//to get user data
Route::post('/get-user-data', [UserAuthcontroller::class, 'getUserData'])->name('user.getData');
//to edit user
Route::post('/users/{id}/edit', [UserAuthController::class, 'editUser']);
//to delet user
Route::post('/delete-user', [UserAuthcontroller::class, 'deleteUser'])->name('delete.user');
//to add vendor data
Route::post('/vendor/store', [VendorController::class, 'store'])->name('vendor.store');
//to get sectionssssssssblog data
Route::get('/vendor/{id}', [VendorController::class, 'show'])->name('vendor.show');
// Update vendor data
Route::post('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');
//to delet blog
Route::post('/delete-vendor', [VendorController::class, 'deletevendor'])->name('delete.vendor');
//to add area data
Route::post('/area/store', [AreaController::class, 'store'])->name('area.store');
//to get area data
Route::get('/area/{id}', [AreaController::class, 'show'])->name('area.show');
// Update area data
Route::post('/area/{id}', [AreaController::class, 'update'])->name('area.update');
//to delet blog
Route::post('/delete-area', [AreaController::class, 'deletearea'])->name('delete.area');
//to add customer data
Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
//to get customer data
Route::get('/customer/{id}', [CustomerController::class, 'show'])->name('customer.show');
// Update customer data
Route::post('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
//to delet customer
Route::post('/delete-customer', [CustomerController::class, 'deletecustomer'])->name('delete.customer');
//to block customer
Route::post('/customer/block/{id}', [CustomerController::class, 'blockCustomer'])->name('customer.block');
//to ublok customer
Route::post('/customer/unblock/{id}', [CustomerController::class, 'unblock'])->name('customer.unblock');
//to add employee data
Route::post('/employee/store', [Emplyeescontroller::class, 'store'])->name('employee.store');
//to get employee data
Route::get('/employee/{id}', [Emplyeescontroller::class, 'show'])->name('employee.show');
// Update employee data
Route::post('/employee/{id}', [Emplyeescontroller::class, 'update'])->name('employee.update');
//to delet employee
Route::post('/delete-employee', [Emplyeescontroller::class, 'deleteemployee'])->name('delete.employee');
//to add leave data
Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');
//to get leave data
Route::get('/leave/{id}', [LeaveController::class, 'show'])->name('leave.show');
// Update leave data
Route::post('/leave/{id}', [LeaveController::class, 'update'])->name('leave.update');
//to delet leave
Route::post('/delete-leave', [LeaveController::class, 'deleteleave'])->name('delete.leave');
//to add designation data
Route::post('/designation/store', [DesignationController::class, 'store'])->name('designation.store');
//to get designation data
Route::get('/designation/{id}', [DesignationController::class, 'show'])->name('designation.show');
// Update designation data
Route::post('/designation/{id}', [DesignationController::class, 'update'])->name('designation.update');
//to delet designation
Route::post('/delete-designation', [DesignationController::class, 'deletedesignation'])->name('designation.leave');
//to mark attendance
Route::post('/mark-attendance', [AttendanceController::class, 'markAttendance']);
//search attendance report
Route::get('/attendance-report', [AttendanceController::class, 'report'])->name('attendance.report');
//to add company data
Route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');
//to get company data
Route::get('/company/{id}', [CompanyController::class, 'show'])->name('company.show');
// Update company data
Route::post('/company/{id}', [CompanyController::class, 'update'])->name('company.update');
//to delet company
Route::post('/delete-company', [CompanyController::class, 'deletecompany'])->name('company.leave');
//to add category data
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
//to get category data
Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');
// Update category data
Route::post('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
//to delet category
Route::post('/delete-category', [CategoryController::class, 'deletecategory'])->name('category.leave');
//to add sub data
Route::post('/sub/store', [SubCategoryController::class, 'store'])->name('sub.store');
//to get sub data
Route::get('/sub/{id}', [SubCategoryController::class, 'show'])->name('sub.show');
// Update sub data
Route::post('/sub/{id}', [SubCategoryController::class, 'update'])->name('sub.update');
//to delet sub
Route::post('/delete-sub', [SubCategoryController::class, 'deletedeletesub'])->name('sub.leave');
//to add product data
Route::post('/product/store', [ProductsController::class, 'store'])->name('product.store');
//to get product data
Route::get('/product/{id}', [ProductsController::class, 'show'])->name('product.show');
// Update product data
Route::post('/product/{id}', [ProductsController::class, 'update'])->name('product.update');
//to delet product
Route::post('/delete-product', [ProductsController::class, 'deleteproduct'])->name('product.leave');
//to add opening qty
Route::post('/products/add-opening-quantity', [ProductsController::class, 'addOpeningQuantity'])->name('products.addOpeningQuantity');
//to edit opening qty
Route::post('/products/update-opening-quantity', [ProductsController::class, 'updateOpeningQuantity'])->name('products.updateOpeningQuantity');
//to import product
Route::post('/import-csv', [ProductsController::class, 'importCSV']);
//to add purchase list
Route::post('/purchase/save', [PurchaseController::class, 'store'])->name('purchase.store');
//to serch purchase list
Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.indexx');
//to del purchase
Route::delete('/purchases/{id}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');
//to get product price
Route::get('/products/{productId}/price', [ProductsController::class, 'getUpdatedPrice']);
//to get product data
Route::get('/products/{id}/data', [ProductsController::class, 'getProductData'])->name('products.getData');
//to edit purchase
Route::post('/api/edit-purchase/{id}', [PurchaseController::class, 'update']);
//to get PO for grn 
Route::get('/get-purchase-details/{id}', [grnController::class, 'getPurchaseDetails']);
//to get account data
Route::get('/account/{id}', [AccountController::class, 'show'])->name('account.show');
// Update account data
Route::post('/account/{id}', [AccountController::class, 'update'])->name('account.update');
//to add account child
Route::post('/add-account', [AccountController::class, 'store'])->name('add.account');
//to get account with child
Route::get('/get-sub-heads-by-head-name/{accountName}', [AccountController::class, 'getSubHeadsByHeadName']);
//to save child child
Route::post('/save-sub-head-name', [AccountController::class, 'storeSubHead']);
//to del account
Route::delete('/delete-account/{id}', [AccountController::class, 'deleteAccount']);
//to save edit account
Route::put('/save-account/{id}', [AccountController::class, 'saveAccount'])->name('save.account');
//same
Route::put('/edit-account/{id}', [AccountController::class, 'saveacccount'])->name('save.account');
//to add opening for accounts
Route::post('/update-opening', [AccountController::class, 'updateOpening']);
//to mark mutliple attendance
Route::post('/mark-attendance', [AttendanceController::class, 'mark']);
//to edit product in price list
Route::post('/update-product-inline', [ProductsController::class, 'updateInline'])->name('product.updateInline');
//to get produts for purchase
Route::get('/get-product/{id}', [ProductsController::class, 'getProduct'])->name('products.getProduct');
//to grn purchase
Route::post('/update-purchase-stock', [grnController::class, 'updatePurchaseStock']);
//to submit payment
Route::post('/submit-payment', [PaymentController::class, 'storePayment'])->name('submit.payment');
//to get products for sale
Route::get('/get-product-details/{id}', [SaleController::class, 'getProductDetails']);
//to get customer related to user
Route::get('/get-customers-by-username/{username}', [SaleController::class, 'getCustomersByUsername']);
//to get customer discount
Route::get('/get-customer-discount/{customerId}', [SaleController::class, 'getCustomerDiscount']);
//to save sale
Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
//to edit sale
Route::put('/submit-sale-form/{saleId}', [SaleController::class, 'updateSale'])->name('sale.update');
//to del sale
Route::delete('/saledelete/{saleId}', [SaleController::class, 'deleteSale'])->name('sale.delete');
//to complete sale
Route::post('/complete-sale', [SaleController::class, 'completeSale'])->name('complete.sale');
//to get stock value 
Route::get('/get-product-quantity', [SaleController::class, 'getProductQuantity']);
//to serch sale list
Route::get('/sales-list', [SaleController::class, 'salelistsearch'])->name('sales.list');
//to get csh in ahnd
Route::post('/get-cash-in-hand', [VoucherController::class, 'getCashInHand']);
//to get account balance
Route::get('/get-account-balance', [VoucherController::class, 'getAccountBalance']);
//to add voucher
Route::post('/save-voucher', [VoucherController::class, 'store'])->name('voucher.store');

