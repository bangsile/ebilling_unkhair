<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BillingMhsController;
use App\Http\Controllers\BillingPmbController;
use App\Http\Controllers\DataImportController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\JenisBayarController;
use App\Http\Controllers\LaporanUktMahasiswaController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\RekeningKoranController;
use App\Http\Controllers\TahunPembayaranController;
use App\Http\Controllers\UserController;
use App\Models\TahunPembayaran;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::get('/dashboard', function () {
    $tahun_pembayaran = TahunPembayaran::first();
    return view('pages.dashboard', ['tahun_akademik' => $tahun_pembayaran->tahun_akademik]);
})->middleware(['auth'])->name('dashboard');


Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/' . env('SITE_NAME') . '/public/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/' . env('SITE_NAME') . '/public/livewire/livewire.js', $handle);
});

// MANAJEMEN BILLING
Route::controller(BillingController::class)->group(function () {
    Route::get('/billing-pembayaran', 'billing_pembayaran')->name('billing.pembayaran');
    Route::get('/tambah-billing', 'create_billing')->name('billing.tambah');
    Route::post('/tambah-billing', 'store_billing')->name('billing.store');

    Route::get('/billing-dosen', 'billing_dosen')->name('billing.dosen');
    Route::get('/billing-dosen/tambah', 'create_billing_dosen')->name('billing.dosen.tambah');
    Route::post('/billing-dosen/tambah', 'store_billing_dosen')->name('billing.dosen.store');
})->middleware(['auth']);


// BILLING MAHASISWA
Route::controller(BillingMhsController::class)->group(function () {
    Route::get('/billing-ukt', 'billing_ukt')->name('billing.ukt');
    Route::get('/billing-ukt/edit/{id}', 'edit_billing_ukt')->name('billing.ukt.edit');
    Route::patch('/billing-ukt/edit/{id}', 'update_billing_ukt')->name('billing.ukt.update');
    Route::post('/billing-ukt/lunas', 'set_lunas_billing')->name('billing.ukt.lunas');

    Route::get('/billing-umb', 'billing_umb')->name('billing.umb');
    Route::get('/billing-ipi', 'billing_ipi')->name('billing.ipi');
    Route::get('/billing-pemkes', 'billing_pemkes')->name('billing.pemkes');
})->middleware(['auth', 'role:admin|spp|keuangan']);

// REKENING KORAN
Route::controller(RekeningKoranController::class)->group(function () {
    Route::get('/rekening-koran', 'index')->name('rekening-koran.index');
    Route::get('/rekening-koran/show', 'tampil')->name('rekening-koran.tampil');
    Route::get('/rekening-koran/export-excel', 'excel')->name('rekening-koran.export-excel');
    Route::get('/rekening-koran/export-pdf', 'pdf')->name('rekening-koran.export-pdf');
})->middleware(['auth', 'role:developper|admin|spp|keuangan']);


// LAPORAN UKT MAHASISWA
Route::controller(LaporanUktMahasiswaController::class)->group(function () {
    Route::get('/laporan-ukt', 'index')->name('laporan.ukt');
    Route::post('/laporan-ukt/show', 'index')->name('laporan.ukt.tampil');
})->middleware(['auth', 'role:developper|admin|spp|keuangan']);

// IMPORT DATA UKT
Route::controller(DataImportController::class)->group(function () {
    Route::get('/billing-ukt/import-data', 'import_data_ukt_form')->name('ukt.import.form');
    Route::post('/billing-ukt/import-data', 'import_data_ukt')->name('ukt.import');
})->middleware(['auth', 'role:developper|admin|spp|keuangan']);


// JENIS BAYAR
Route::controller(JenisBayarController::class)->group(function () {
    Route::get('/jenis-bayar', 'index')->name('jenis-bayar');
    Route::get('/jenis-bayar/tambah', 'create')->name('jenis-bayar.tambah');
    Route::post('/jenis-bayar/tambah', 'store')->name('jenis-bayar.store');
})->middleware(['auth', 'role:developper|admin']);

// TAHUN PEMBAYARAN
Route::controller(TahunPembayaranController::class)->group(function () {
    Route::get('/tahun-pembayaran', 'index')->name('tahun-pembayaran');
    Route::post('/tahun-pembayaran', 'store')->name('tahun-pembayaran.store');
})->middleware(['auth', 'role:developper|admin|spp|keuangan']);

// FAKULTAS
Route::controller(FakultasController::class)->group(function () {
    Route::get('/fakultas', 'index')->name('fakultas.index');
    Route::get('/fakultas/import', 'import')->name('fakultas.import');
})->middleware(['auth', 'role:developper|admin']);

// PRODI
Route::controller(ProdiController::class)->group(function () {
    Route::get('/prodi', 'index')->name('prodi.index');
    Route::get('/prodi/import', 'import')->name('prodi.import');
})->middleware(['auth', 'role:developper|admin']);


// PENGGUNA
Route::controller(UserController::class)->group(function () {
    Route::get('/pengguna', 'index')->name('pengguna.index');
    Route::get('/pengguna/tambah', 'create')->name('pengguna.tambah');
    Route::get('/pengguna/import', 'import')->name('pengguna.import');
    Route::post('/pengguna/tambah', 'store')->name('pengguna.store');
    Route::get('/pengguna/{id}', 'edit')->name('pengguna.edit');
    Route::patch('/pengguna/{id}', 'update')->name('pengguna.update');
    Route::delete('/pengguna/{id}', 'destroy')->name('pengguna.destroy');
})->middleware(['auth', 'role:developper|admin']);

// LOG
Route::controller(LogController::class)->group(function () {
    Route::get('/log', 'index')->name('log.index');
    Route::get('/log/view/{nama_file}', 'lihat')->name('log.lihat');
    Route::get('/log/ecoll', 'ecoll')->name('log.ecoll');
    Route::get('/log/failed-pelunasan-ukt', 'failed_set_lunas_ukt')->name('log.failed-pelunasan-ukt');
})->middleware(['auth', 'role:developper']);


// BILLING PMB
Route::controller(BillingPmbController::class)->group(function () {
    Route::get('/billing-pmb', 'index')->name('billing-pmb.index');
})->middleware(['auth', 'role:admin|keuangan']);
