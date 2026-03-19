<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PublicInvoiceController;
use App\Http\Controllers\ReportController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ─── Authenticated Routes ──────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Customers — full resource (no show page needed)
    Route::resource('customers', CustomerController::class)
        ->except(['show']);

    // Invoices — resource minus edit (status toggle replaces it)
    Route::resource('invoices', InvoiceController::class)
        ->except(['edit', 'update']);

    // Invoice extras
    Route::patch('/invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])
        ->name('invoices.updateStatus');

    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])
        ->name('invoices.pdf');
    Route::patch('/invoices/{invoice}/toggle-public', [InvoiceController::class, 'togglePublic'])
        ->name('invoices.togglePublic');
    Route::post('/invoices/{invoice}/duplicate', [InvoiceController::class, 'duplicate'])
        ->name('invoices.duplicate');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

});

Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
Route::delete('/settings/logo', [SettingController::class, 'deleteLogo'])->name('settings.deleteLogo');
// ─── Public Invoice Routes (no auth) ──────────────────────────────
Route::get('/invoice/{token}', [PublicInvoiceController::class, 'show'])
    ->name('invoices.public');

Route::get('/invoice/{token}/pdf', [PublicInvoiceController::class, 'downloadPdf'])
    ->name('invoices.public.pdf');
Route::get(
    '/landing',
    function () {
        return view('landing');
    }
);