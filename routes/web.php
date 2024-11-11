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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
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
    Route::delete('/delete-aset/{product_code}', [AssetController::class, 'destroy'])->name('destroy-aset');

    Route::get('/perencanaan-inv', [PerencanaanController::class, 'indexInv'])->name('perencanaan-inv');
    Route::get('/perencanaan-bhp', [PerencanaanController::class, 'indexBhp'])->name('perencanaan-bhp');
    Route::get('/add-perencanaan-bhp', [PerencanaanController::class, 'createBhp'])->name('add-perencanaan-bhp');
    Route::post('/add-perencanaan-bhp', [PerencanaanController::class, 'store']);
    Route::get('/perencanaan-bhp/{id}', [PerencanaanController::class, 'show'])->name('detail-perencanaan-bhp');
    Route::delete('/delete-perencanaan/{id}', [PerencanaanController::class, 'destroy'])->name('destroy-rencana');
    Route::delete('/perencanaan-bhp/destroy-item/{id}', [PerencanaanController::class, 'destroyItem'])->name('rencana.destroy-item');
    Route::post('/perencanaan-bhp/add-item/{id}', [PerencanaanController::class, 'storeItem'])->name('rencana.store-item');

    Route::get('/prediksi', [PredictionController::class, 'index'])->name('prediksi');
    Route::post('/send-data', [PredictionController::class, 'sendData']);


});

require __DIR__ . '/auth.php';

//Staff Route
Route::middleware(['auth', 'staffMiddleware'])->group(function () {
    Route::get('dashboard', [StaffController::class, 'index'])->name('dashboard');
});

//Admin Route
Route::middleware(['auth', 'adminMiddleware'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/data-staff', [DataStaffController::class, 'index'])->name('admin.staff');
    Route::get('admin/add-staff', [RegisterStaffController::class, 'create'])->name('admin.add-staff');
    Route::post('admin/add-staff', [RegisterStaffController::class, 'store']);
    Route::delete('admin/data-staff/{id}', [DataStaffController::class, 'destroy'])->name('admin.destroy-staff');
});
