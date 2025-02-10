<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RegisterStaffController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DataStaffController;
use App\Http\Controllers\PerencanaanController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return redirect('/login');
    return view('landing');
});

Route::middleware(['auth', 'orMiddleware'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/data-product', [ProductController::class, 'index'])->name('product');
    Route::get('/add-product', [ProductController::class, 'add'])->name('add-product');
    Route::post('/add-product', [ProductController::class, 'store']);
    Route::delete('/data-product/{id}', [ProductController::class, 'destroy'])->name('destroy-product');

    Route::get('/data-aset', [AssetController::class, 'indexInv'])->name('data-aset');
    Route::get('/add-aset-inventaris', [AssetController::class, 'addInv'])->name('add-aset-inv');
    Route::post('/add-aset-inventaris', [AssetController::class, 'store']);
    Route::get('/data-barang-habis-pakai', [AssetController::class, 'indexBhp'])->name('data-bhp');
    Route::get('/add-aset-bhp', [AssetController::class, 'addBhp'])->name('add-aset-bhp');
    Route::post('/add-aset-bhp', [AssetController::class, 'store']);
    Route::get('/edit-aset/{product_code}', [AssetController::class, 'edit'])->name('edit-aset');
    Route::put('/update-aset/{product_code}', [AssetController::class, 'update'])->name('update-aset');
    Route::delete('/delete-aset/{product_code}', [AssetController::class, 'destroy'])->name('destroy-aset');
    Route::get('/export-inv', [AssetController::class, 'exportInv'])->name('export.inv');
    Route::get('/export-bhp', [AssetController::class, 'exportBhp'])->name('export.bhp');

    Route::get('/perencanaan-inv', [PerencanaanController::class, 'indexInv'])->name('perencanaan-inv');
    Route::get('/perencanaan-bhp', [PerencanaanController::class, 'indexBhp'])->name('perencanaan-bhp');
    Route::get('/add-perencanaan-inv', [PerencanaanController::class, 'createInv'])->name('add-perencanaan.inv');
    Route::post('/add-perencanaan-inv', [PerencanaanController::class, 'store']);
    Route::get('/add-perencanaan-bhp', [PerencanaanController::class, 'createBhp'])->name('add-perencanaan.bhp');
    Route::post('/add-perencanaan-bhp', [PerencanaanController::class, 'store']);
    Route::get('/detail-perencanaan/{id}', [PerencanaanController::class, 'show'])->name('detail-perencanaan');
    Route::delete('/delete-perencanaan/{id}', [PerencanaanController::class, 'destroy'])->name('destroy-rencana');
    Route::delete('/perencanaan-bhp/destroy-item/{id}', [PerencanaanController::class, 'destroyItem'])->name('rencana.destroy-item');
    Route::post('/perencanaan-bhp/add-item/{id}', [PerencanaanController::class, 'storeItem'])->name('rencana.store-item');
    Route::get('/edit-rencana/{id}', [PerencanaanController::class, 'edit'])->name('rencana.edit-rencana');
    Route::put('/update-rencana/{id}', [PerencanaanController::class, 'update'])->name('rencana.update-rencana');
    Route::get('/perencanaan/{id}/download', [PerencanaanController::class, 'download'])->name('perencanaan.download');
    Route::post('/perencanaan/{id}/complete', [PerencanaanController::class, 'complete'])->name('perencanaan.complete');

    Route::post('/transaction-bhp', [TransactionController::class, 'index']);
    Route::post('/add-transaction-bhp', [TransactionController::class, 'create'])->name('add-transaction.bhp');

    Route::get('/prediksi', [PredictionController::class, 'index'])->name('prediksi');
    Route::post('/send-data', [PredictionController::class, 'sendData']);
});

require __DIR__ . '/auth.php';

//Staff Route
Route::middleware(['auth', 'staffMiddleware'])->group(function () {
    Route::get('dashboard', [StaffController::class, 'index'])->name('dashboard');
});

//staff Route
Route::middleware(['auth', 'userMiddleware'])->group(function () {
    Route::get('dashboard', [UserController::class, 'index'])->name('dashboard');
});

//Admin Route
Route::middleware(['auth', 'adminMiddleware'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/data-staff', [DataStaffController::class, 'index'])->name('admin.staff');
    Route::get('admin/add-staff', [RegisterStaffController::class, 'create'])->name('admin.add-staff');
    Route::post('admin/add-staff', [RegisterStaffController::class, 'store']);
    Route::get('admin/edit-staff/{id}', [RegisterStaffController::class, 'edit'])->name('admin.edit-staff');
    Route::put('admin/update-staff/{id}', [RegisterStaffController::class, 'update'])->name('admin.update-staff');
    Route::delete('admin/data-staff/{id}', [DataStaffController::class, 'destroy'])->name('admin.destroy-staff');
});
