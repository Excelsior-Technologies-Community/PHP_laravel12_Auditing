<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Default Welcome Page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authentication Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    Route::resource('products', ProductController::class);

    /*
    |--------------------------------------------------------------------------
    | Product Audit History
    |--------------------------------------------------------------------------
    */

    Route::get('/products/{id}/audits', [ProductController::class, 'audits'])
        ->name('products.audits');

    /*
    |--------------------------------------------------------------------------
    | Audit Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/audit-dashboard', [AuditController::class, 'dashboard'])
        ->name('audit.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Audit Logs
    |--------------------------------------------------------------------------
    */

    Route::get('/audit-logs', [AuditController::class, 'index'])
        ->name('audit.index');

    /*
    |--------------------------------------------------------------------------
    | Audit CSV Export
    |--------------------------------------------------------------------------
    */

    Route::get('/audit-logs/export', [AuditController::class, 'export'])
        ->name('audit.export');
});

/*
|--------------------------------------------------------------------------
| Breeze Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';