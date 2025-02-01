<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DataImportController;
use App\Http\Controllers\JenisBayarController;
use App\Http\Controllers\TahunPembayaranController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware(['auth'])->name('dashboard');

// BILLING
Route::get('/billing-pembayaran', [BillingController::class, 'billing_pembayaran'])->middleware(['auth', 'role:admin'])->name('billing.pembayaran');

Route::get('/tambah-billing', [BillingController::class, 'create_billing'])->middleware(['auth', 'role:admin'])->name('billing.tambah');
Route::post('/tambah-billing', [BillingController::class, 'store_billing'])->middleware(['auth', 'role:admin'])->name('billing.store');

Route::get('/billing-ukt', [BillingController::class, 'billing_ukt'])->middleware(['auth', "role:admin|spp|keuangan"])->name('billing.ukt');
Route::get('/billing-ukt/edit/{id}', [BillingController::class, 'edit_billing_ukt'])->middleware(['auth', "role:admin|spp|keuangan"])->name('billing.ukt.edit');
Route::patch('/billing-ukt/edit/{id}', [BillingController::class, 'update_billing_ukt'])->middleware(['auth', "role:admin|spp|keuangan"])->name('billing.ukt.update');
Route::post('/billing-ukt/lunas', [BillingController::class, 'set_lunas_billing'])->middleware(['auth', "role:admin|spp|keuangan"])->name('billing.ukt.lunas');
Route::get('/billing-ukt/import-data', [DataImportController::class, 'import_data_ukt_form'])->middleware(['auth', "role:admin|spp|keuangan"])->name('ukt.import.form');
Route::post('/billing-ukt/import-data', [DataImportController::class, 'import_data_ukt'])->middleware(['auth', "role:admin|spp|keuangan"])->name('ukt.import');

Route::get('/billing-umb', [BillingController::class, 'billing_umb'])->middleware(['auth', 'role:admin|spp|keuangan'])->name('billing.umb');

Route::get('/billing-dosen', [BillingController::class, 'billing_dosen'])->middleware(['auth'])->name('billing.dosen');
Route::get('/billing-dosen/tambah', [BillingController::class, 'create_billing_dosen'])->middleware(['auth'])->name('billing.dosen.tambah');
Route::post('/billing-dosen/tambah', [BillingController::class, 'store_billing_dosen'])->middleware(['auth'])->name('billing.dosen.store');

// JENIS BAYAR
Route::get('/jenis-bayar', [JenisBayarController::class, 'index'])->middleware(['auth', 'role:developper|admin'])->name('jenis-bayar');
Route::get('/jenis-bayar/tambah', [JenisBayarController::class, 'create'])->middleware(['auth', 'role:developper|admin'])->name('jenis-bayar.tambah');
Route::post('/jenis-bayar/tambah', [JenisBayarController::class, 'store'])->middleware(['auth', 'role:developper|admin'])->name('jenis-bayar.store');

// TAHUN PEMBAYARAN
Route::get('/tahun-pembayaran', [TahunPembayaranController::class, 'index'])
    ->middleware(['auth', 'role:developper|admin'])->name('tahun-pembayaran');
Route::post('/tahun-pembayaran', [TahunPembayaranController::class, 'store'])
    ->middleware(['auth', 'role:developper|admin'])->name('tahun-pembayaran.store');

// PENGGUNA
Route::get('/pengguna', [UserController::class, 'index'])->middleware(['auth', 'role:developper|admin'])->name('pengguna.index');
Route::get('/pengguna/tambah', [UserController::class, 'create'])->middleware(['auth', 'role:developper|admin'])->name('pengguna.tambah');
Route::post('/pengguna/tambah', [UserController::class, 'store'])->middleware(['auth', 'role:developper|admin'])->name('pengguna.store');
Route::delete('/pengguna/{id}', [UserController::class, 'destroy'])->middleware(['auth', 'role:developper|admin'])->name('pengguna.destroy');




Route::get('/tes', function () {
    return view('tes');
});
