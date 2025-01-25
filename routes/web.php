<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DataImportController;
use App\Http\Controllers\TahunPembayaranController;
use App\Models\JenisBayar;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/semua-billing', [BillingController::class, 'semua_billing'])->middleware(['auth', 'role:admin'])->name('billing');

Route::get('/tambah-billing',[BillingController::class, 'create_billing'] )->middleware(['auth', 'role:admin'])->name('billing.tambah');
Route::post('/tambah-billing',[BillingController::class, 'store_billing'] )->middleware(['auth', 'role:admin'])->name('billing.store');

Route::get('/billing-ukt', [BillingController::class, 'billing_ukt'])->middleware(['auth'])->name('billing.ukt');
Route::get('/billing-umb', [BillingController::class, 'billing_umb'])->middleware(['auth'])->name('billing.umb');

Route::get('/billing-dosen', [BillingController::class, 'billing_dosen'])->middleware(['auth'])->name('billing.dosen');
Route::get('/billing-dosen/tambah', [BillingController::class, 'create_billing_dosen'])->middleware(['auth'])->name('billing.dosen.tambah');
Route::post('/billing-dosen/tambah', [BillingController::class, 'store_billing_dosen'])->middleware(['auth'])->name('billing.dosen.store');

Route::get('/jenis-bayar', function () {
    $jenis_bayar = JenisBayar::all();
    return view('pages.jenis-bayar.index', ['jenis_bayar' => $jenis_bayar]);
})->middleware(['auth'])->name('jenis-bayar');

Route::get('/tahun-pembayaran', [TahunPembayaranController::class, 'index'])
    ->middleware(['auth'])->name('tahun-pembayaran');
Route::post('/tahun-pembayaran', [TahunPembayaranController::class, 'store'])
    ->middleware(['auth'])->name('tahun-pembayaran.store');


Route::get('/tes', function () {
    return view('tes');
});

Route::get('/import', [DataImportController::class, 'importForm'])->middleware(['auth'])->name('data.import.form');
Route::post('/import', [DataImportController::class, 'import'])->name('data.import');

