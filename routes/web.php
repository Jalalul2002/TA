<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RegisterStaffController;
use App\Http\Controllers\DataStaffController;
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
});

require __DIR__.'/auth.php';

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