<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;


use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\LowStockController;
use App\Http\Controllers\SupplierStockPaymentController;
use App\Models\Product;

// Resource Routes
Route::resource('permissions', PermissionController::class);
Route::resource('brands', BrandController::class);
Route::resource('categories', CategoryController::class);
Route::resource('subcategories', SubcategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('suppliers', SupplierController::class);

// StockIn Management Routes
Route::resource('stock-ins', StockInController::class);

// Supplier Payments Routes
Route::get('supplier-stock-payments/create/{stockIn}', [SupplierStockPaymentController::class, 'create'])->name('supplier-stock-payments.create');
Route::post('supplier-stock-payments/store/{stockIn}', [SupplierStockPaymentController::class, 'store'])->name('supplier-stock-payments.store');
Route::get('supplier-stock-payments', [SupplierStockPaymentController::class, 'index'])->name('supplier-stock-payments.index');

// Payments Related to StockIn
Route::post('stock-ins/{stock_in}/payments', [SupplierStockPaymentController::class, 'store'])->name('payments.store');
Route::delete('payments/{payment}', [SupplierStockPaymentController::class, 'destroy'])->name('payments.destroy');
// Supplier Stock Payments
Route::get('supplier-stock-payments/{id}', [SupplierStockPaymentController::class, 'show'])->name('supplier-stock-payments.show');
Route::delete('supplier-stock-payments/{id}', [SupplierStockPaymentController::class, 'destroy'])->name('supplier-stock-payments.destroy');
// Supplier Stock Payment Routes
Route::get('supplier-stock-payments/create/{stockIn}', [SupplierStockPaymentController::class, 'create'])->name('supplier-stock-payments.create');

//pdf for supplier payments
Route::get('stock-in/{stockIn}/invoice', [StockInController::class, 'generateInvoice'])->name('stock-in.generate-invoice');

use GuzzleHttp\Middleware;


Route::group(['middleware'=>['role:Master Admin|Admin']],function()
{


// Product Units API
Route::get('/products/{id}/units', function($id) {
    $product = Product::findOrFail($id);
    return response()->json($product->getAvailableUnits());
});

Route::resource('permissions',PermissionController::class);
Route::get('permissions/{permissionId}/delete',[App\Http\Controllers\PermissionController::class,'destroy']) ;

Route::resource('roles',RoleController::class);
Route::get('roles/{roleId}/delete',[App\Http\Controllers\RoleController::class,'destroy']) 
->middleware('permission:delete role');



Route::resource('users',UserController::class);

Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionToRole'])->name('roles.addPermissions');
Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionToRole'])->name('roles.givePermissions');

});


Route::resource('brands',BrandController::class);

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
});

// Dashboard Route
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth Routes
require __DIR__.'/auth.php';
