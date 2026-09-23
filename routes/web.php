<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('products.index');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
|
| IMPORTANT:
| Bulk routes MUST come BEFORE Route::resource().
|
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Bulk Product Status
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/products/bulk-status',
        [ProductController::class, 'bulkStatus']
    )->name('products.bulk-status');


    /*
    |--------------------------------------------------------------------------
    | Bulk Product Delete
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/products/bulk-delete',
        [ProductController::class, 'bulkDelete']
    )->name('products.bulk-delete');


    /*
    |--------------------------------------------------------------------------
    | Duplicate Product
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/products/{id}/duplicate',
        [ProductController::class, 'duplicate']
    )->name('products.duplicate');


    /*
    |--------------------------------------------------------------------------
    | Product Audit History
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/products/{id}/audits',
        [ProductController::class, 'audits']
    )->name('products.audits');


    /*
    |--------------------------------------------------------------------------
    | Product CRUD
    |--------------------------------------------------------------------------
    */

    Route::resource('products', ProductController::class);


    /*
    |--------------------------------------------------------------------------
    | Audit Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/audit-dashboard',
        [AuditController::class, 'dashboard']
    )->name('audit.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Audit Logs
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/audit-logs',
        [AuditController::class, 'index']
    )->name('audit.index');


    /*
    |--------------------------------------------------------------------------
    | Audit Logs Export
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/audit-logs/export',
        [AuditController::class, 'export']
    )->name('audit.export');


    /*
    |--------------------------------------------------------------------------
    | Audit Detail
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/audit-logs/{id}',
        [AuditController::class, 'show']
    )->name('audit.show');

    /*
    |--------------------------------------------------------------------------
    | Audit Rollback
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/audit-logs/{id}/rollback',
        [AuditController::class, 'rollback']
    )->name('audit.rollback');

    /*
    |--------------------------------------------------------------------------
    | User Profile Routes
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
