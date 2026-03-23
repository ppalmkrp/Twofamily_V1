<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    TruckController,
    DriverController,
    DashboardController,
    ProductController,
    CustomerController,
    InvoiceController,
    FuelRecordController,
    ProductTypeController,
    TransportJobController,
    QuotationController,
    TruckBrandController,
    TruckModelController,
    SettingController,
    ReceiptController
};

/*
|--------------------------------------------------------------------------
| หน้าแรก
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| หลังล็อกอิน
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */
    Route::resource('customers', CustomerController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('products', ProductController::class);
    Route::resource('product_types', ProductTypeController::class);

    Route::resource('truck_brands', TruckBrandController::class);
    Route::resource('truck_models', TruckModelController::class);
    Route::resource('trucks', TruckController::class)->except(['show']);

    /*
    |--------------------------------------------------------------------------
    | Operations
    |--------------------------------------------------------------------------
    */
    Route::resource('transport-jobs', TransportJobController::class);
    Route::resource('fuel_records', FuelRecordController::class);

    /*
    |--------------------------------------------------------------------------
    | Quotation
    |--------------------------------------------------------------------------
    */
    Route::resource('quotations', QuotationController::class);

    Route::post('/quotations/{id}/cancel', [QuotationController::class, 'cancel'])
    ->name('quotations.cancel');

    Route::post('/quotations/{id}/approve', [QuotationController::class, 'approve'])
        ->name('quotations.approve');

    Route::get('/quotation/{quotation}/pdf', [QuotationController::class, 'downloadPDF'])
        ->name('quotation.pdf');

    /*
    |--------------------------------------------------------------------------
    | Invoice
    |--------------------------------------------------------------------------
    */
    Route::resource('invoices', InvoiceController::class)->only(['index', 'show', 'destroy']);

    Route::post('/invoices/create/{id}', [InvoiceController::class, 'createFromQuotation'])
        ->name('invoices.createFromQuotation');

    Route::post('/invoices/{id}/pay', [InvoiceController::class, 'pay'])
        ->name('invoices.pay');

    Route::get('/invoice/{id}/pdf', [InvoiceController::class, 'pdf'])
        ->name('invoice.pdf');

    /*
    |--------------------------------------------------------------------------
    | Receipt
    |--------------------------------------------------------------------------
    */
    Route::post('/receipts/create/{id}', [ReceiptController::class, 'createFromInvoice'])
        ->name('receipts.createFromInvoice');

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');

    Route::get('/settings/documents', [SettingController::class, 'documents'])->name('settings.documents');

    Route::get('/settings/documents/quotation', [SettingController::class, 'quotation'])->name('settings.quotation');

    Route::post('/settings/documents/quotation', [SettingController::class, 'quotationUpdate'])
        ->name('settings.quotation.update');

    /*
    |--------------------------------------------------------------------------
    | AJAX
    |--------------------------------------------------------------------------
    */
    Route::post('/customers/ajax', [CustomerController::class, 'storeAjax'])
        ->name('customers.store.ajax');
});
