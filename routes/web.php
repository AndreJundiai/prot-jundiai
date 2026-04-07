<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DentistController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TechnicalRecordController;
use App\Http\Controllers\FinancialRecordController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('dentists', DentistController::class)->except(['create', 'show', 'edit']);
Route::post('dentists/{dentist}/prices', [DentistController::class, 'updatePrices'])->name('dentists.prices.update');
Route::get('api/prices/{dentist}/{service}', [DentistController::class, 'getPrice'])->name('api.prices.get');
Route::resource('patients', PatientController::class)->except(['create', 'show', 'edit']);
Route::resource('orders', OrderController::class);

Route::get('orders/{order}/technical-record/edit', [TechnicalRecordController::class, 'edit'])->name('orders.technical_records.edit');
Route::put('orders/{order}/technical-record', [TechnicalRecordController::class, 'update'])->name('orders.technical_records.update');
Route::get('orders/{order}/os', [OrderController::class, 'os'])->name('orders.os');

Route::get('financial', [FinancialRecordController::class, 'index'])->name('financial.index');
Route::post('financial', [FinancialRecordController::class, 'store'])->name('financial.store');
Route::get('financial/extrato/{dentist}', [FinancialRecordController::class, 'extrato'])->name('financial.extrato');

Route::get('production', [ProductionController::class, 'index'])->name('production.index');

Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/production', [ReportController::class, 'production'])->name('reports.production');
Route::get('reports/dentists', [ReportController::class, 'dentists'])->name('reports.dentists');

use App\Http\Controllers\SettingsController;
Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');

use App\Http\Controllers\ServiceController;
Route::resource('products', ServiceController::class)->except(['create', 'show', 'edit']);
