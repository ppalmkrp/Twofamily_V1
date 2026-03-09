<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\FuelRecordController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\TransportJobController;
use App\Http\Controllers\QuotationController;

/**
 * ถ้า "ยังไม่ล็อกอิน" -> แสดงหน้า welcome
 * ถ้า "ล็อกอินแล้ว"     -> ส่งไปหน้า dashboard
 */
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
})->name('welcome');

/**
 * เส้นทางหลังล็อกอิน + ใช้ session ของ Jetstream + ยืนยันอีเมลแล้ว
 */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::resource('trucks', TruckController::class)->except(['show']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('customers', CustomerController::class);

    Route::resource('drivers', DriverController::class);

    Route::resource('transport-jobs', TransportJobController::class);

    Route::resource('fuel_records', FuelRecordController::class);

    Route::resource('products', ProductController::class);



    Route::resource('product_types', ProductTypeController::class);


    Route::get(
        'invoices/{invoice}/word',
        [InvoiceController::class, 'downloadWord']
    )->name('invoices.word');

    Route::get(
        'quotations/{quotation}/word',
        [QuotationController::class, 'downloadWord']
    )->name('quotations.word');


    Route::resource('invoices', InvoiceController::class);
    Route::resource('quotations', QuotationController::class);
});
