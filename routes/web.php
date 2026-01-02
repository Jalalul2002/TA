<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RegisterStaffController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DataLabController;
use App\Http\Controllers\DataStaffController;
use App\Http\Controllers\Dosen\DosenController;
use App\Http\Controllers\ItemPricesController;
use App\Http\Controllers\Mahasiswa\MahasiswaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PerencanaanController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Staff\RegisterController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    // return redirect('/login');
    return view('landing');
})->name('landing');

Route::middleware('auth')->group(function () {
    Route::get('/assets', [AssetController::class, 'getAssets']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/data-product', [ProductController::class, 'index'])->name('product');
    Route::get('/data-aset', [AssetController::class, 'indexInv'])->name('data-aset');
    Route::get('/data-barang-habis-pakai', [AssetController::class, 'indexBhp'])->name('data-bhp');
    Route::get('/export/inv', [AssetController::class, 'exportInv'])->name('export.inv');
    Route::get('/export/bhp', [AssetController::class, 'exportBhp'])->name('export.bhp');
    Route::get('/print/bhp', [AssetController::class, 'printBhp'])->name('print.bhp');
    Route::get('/print/inv', [AssetController::class, 'printInv'])->name('print.inv');

    Route::get('/perencanaan-inv', [PerencanaanController::class, 'indexInv'])->name('perencanaan-inv');
    Route::get('/perencanaan-bhp', [PerencanaanController::class, 'indexBhp'])->name('perencanaan-bhp');
    Route::get('/detail-perencanaan/{id}', [PerencanaanController::class, 'show'])->name('detail-perencanaan');
    Route::get('/perencanaan/{id}/download', [PerencanaanController::class, 'download'])->name('perencanaan.download');
    Route::get('/perencanaan/{id}/print', [PerencanaanController::class, 'print'])->name('perencanaan.print');
    Route::get('/perencanaan/download', [PerencanaanController::class, 'export'])->name('perencanaan.export');
    Route::get('/perencanaan/print', [PerencanaanController::class, 'printAll'])->name('perencanaan.print.all');

    Route::get('/realisasi/bhp', [RealisasiController::class, 'indexBhp'])->name('realisasi.bhp');
    Route::get('/realisasi/inventaris', [RealisasiController::class, 'indexInv'])->name('realisasi.inv');
    Route::get('/realisasi/view/{id}', [RealisasiController::class, 'show'])->name('realisasi.show');
    Route::get('/realisasi/print/{id}', [RealisasiController::class, 'printId'])->name('realisasi.print.id');
    Route::get('/realisasi/export/{id}', [RealisasiController::class, 'exportId'])->name('realisasi.export.id');
    Route::get('/realisasi/print', [RealisasiController::class, 'print'])->name('realisasi.print');
    Route::get('/realisasi/export', [RealisasiController::class, 'export'])->name('realisasi.export');

    Route::get('/transaction/bhp', [TransactionController::class, 'index'])->name('penggunaan');
    Route::get('/transaction/inventaris', [PeminjamanController::class, 'index'])->name('peminjaman');
    Route::get('/detail-transaction/{id}', [TransactionController::class, 'show'])->name('detail-penggunaan');
    Route::get('/detail-transaction/inventaris/{id}', [PeminjamanController::class, 'show'])->name('detail-peminjaman');
    Route::get('/print/transaction/bhp', [TransactionController::class, 'print'])->name('print.penggunaan');
    Route::get('/print/transaction/inventaris', [PeminjamanController::class, 'print'])->name('print.peminjaman');
    Route::get('/export/transaction/bhp', [TransactionController::class, 'export'])->name('export.transaction.bhp');
    Route::get('/export/transaction/inventaris', [PeminjamanController::class, 'export'])->name('export.transaction.inv');
    Route::get('/transaction/bhp/{id}/download', [TransactionController::class, 'exportById'])->name('penggunaan.export.id');
    Route::get('/transaction/inv/{id}/download', [PeminjamanController::class, 'exportById'])->name('peminjaman.export.id');
    Route::get('/transaction/bhp/{id}/print', [TransactionController::class, 'printById'])->name('penggunaan.print.id');
    Route::get('/transaction/inv/{id}/print', [PeminjamanController::class, 'printById'])->name('peminjaman.print.id');
    Route::get('/transaction/report', [ReportController::class, 'transaction'])->name('report.transaction');
    Route::get('/transaction/report/print', [ReportController::class, 'transactionPrint'])->name('report.transaction.print');
    Route::get('/transaction/report/download', [ReportController::class, 'transactionDownload'])->name('report.transaction.download');

    Route::get('/data-harga', [ItemPricesController::class, 'index'])->name('data-harga');
    Route::get('/prediksi', [PredictionController::class, 'index'])->name('prediksi');

    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::get('/report/download', [ReportController::class, 'download'])->name('report.download');
    Route::get('/report/print', [ReportController::class, 'print'])->name('report.print');

    Route::get('/data-lab', [DataLabController::class, 'index'])->name('lab.index');
});

Route::middleware(['auth', 'orMiddleware'])->group(function () {
    Route::get('/add-product', [ProductController::class, 'add'])->name('add-product');
    Route::post('/add-product', [ProductController::class, 'store']);
    Route::delete('/data-product/{id}', [ProductController::class, 'destroy'])->name('destroy-product');

    Route::get('/add-aset-inventaris', [AssetController::class, 'addInv'])->name('add-aset-inv');
    Route::post('/add-aset-inventaris', [AssetController::class, 'store']);
    Route::get('/add-aset-bhp', [AssetController::class, 'addBhp'])->name('add-aset-bhp');
    Route::post('/add-aset-bhp', [AssetController::class, 'store']);
    Route::get('/edit-aset/{product_code}', [AssetController::class, 'edit'])->name('edit-aset');
    Route::put('/update-aset/{product_code}', [AssetController::class, 'update'])->name('update-aset');
    Route::delete('/delete-aset/{product_code}', [AssetController::class, 'destroy'])->name('destroy-aset');

    Route::get('/add-perencanaan-inv', [PerencanaanController::class, 'createInv'])->name('add-perencanaan.inv');
    Route::post('/add-perencanaan-inv', [PerencanaanController::class, 'store']);
    Route::get('/add-perencanaan-bhp', [PerencanaanController::class, 'createBhp'])->name('add-perencanaan.bhp');
    Route::post('/add-perencanaan-bhp', [PerencanaanController::class, 'store']);
    Route::delete('/delete-perencanaan/{id}', [PerencanaanController::class, 'destroy'])->name('destroy-rencana');
    Route::delete('/perencanaan-bhp/destroy-item/{id}', [PerencanaanController::class, 'destroyItem'])->name('rencana.destroy-item');
    Route::post('/perencanaan-bhp/add-item/{id}', [PerencanaanController::class, 'storeItem'])->name('rencana.store-item');
    Route::get('/edit-rencana/{id}', [PerencanaanController::class, 'edit'])->name('rencana.edit-rencana');
    Route::put('/update-rencana/{id}', [PerencanaanController::class, 'update'])->name('rencana.update-rencana');
    Route::put('/update-detail/{id}', [PerencanaanController::class, 'updateDetail'])->name('rencana.update-detail');
    Route::post('/perencanaan/{id}/complete', [PerencanaanController::class, 'complete'])->name('perencanaan.complete');

    Route::get('/realisasi/add-inv', [RealisasiController::class, 'createInv'])->name('realisasi.inv.add');
    Route::post('/realisasi/add-inv', [RealisasiController::class, 'store']);
    Route::get('/realisasi/add-bhp', [RealisasiController::class, 'createBhp'])->name('realisasi.bhp.add');
    Route::post('/realisasi/add-bhp', [RealisasiController::class, 'store']);
    Route::delete('/realisasi/delete/{id}', [RealisasiController::class, 'destroy'])->name('realisasi.destroy');
    Route::delete('/realisasi/destroy-item/{id}', [RealisasiController::class, 'destroyItem'])->name('realisasi.destroy.item');
    Route::post('/realisasi/add-item/{id}', [RealisasiController::class, 'storeItem'])->name('realisasi.store.item');
    Route::get('/realisasi/edit/{id}', [RealisasiController::class, 'edit'])->name('realisasi.edit');
    Route::put('/realisasi/update/{id}', [RealisasiController::class, 'update'])->name('realisasi.update');
    Route::put('/realisasi/update-detail/{id}', [RealisasiController::class, 'updateDetail'])->name('realisasi.update-detail');
    Route::post('/realisasi/{id}/complete', [RealisasiController::class, 'complete'])->name('realisasi.complete');

    Route::get('/add-penggunaan-bhp', [TransactionController::class, 'create'])->name('add-penggunaan');
    Route::post('/add-penggunaan-bhp', [TransactionController::class, 'store']);
    Route::delete('/delete-penggunaan/{transaction}', [TransactionController::class, 'destroy'])->name('destroy-penggunaan');
    Route::get('/add-peminjaman', [PeminjamanController::class, 'create'])->name('add-peminjaman');
    Route::post('/add-peminjaman', [PeminjamanController::class, 'store']);
    Route::put('/transaction/return/{id}', [PeminjamanController::class, 'update'])->name('transaction.return');
    Route::delete('/delete-peminjaman/{transaction}', [PeminjamanController::class, 'destroy'])->name('destroy-peminjaman');

    Route::get('/add-harga', [ItemPricesController::class, 'create'])->name('add-harga');
    Route::post('/add-harga', [ItemPricesController::class, 'store']);
    Route::delete('/delete-harga/{itemPrices}', [ItemPricesController::class, 'destroy'])->name('data-harga.destroy');

    Route::post('/prediksi', [PredictionController::class, 'sendData']);
    Route::get('/add-lab', [DataLabController::class, 'create'])->name('lab.add');
    Route::post('/add-lab', [DataLabController::class, 'store']);
    Route::delete('/delete-lab/{dataLab}', [DataLabController::class, 'destroy'])->name('lab.destroy');

    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');
});

//Staff Route
Route::middleware(['auth', 'staffMiddleware'])->group(function () {
    Route::get('dashboard', [StaffController::class, 'index'])->name('dashboard');
    Route::get('data-staff', [StaffController::class, 'user'])->name('staff');
    Route::get('add-staff', [RegisterController::class, 'create'])->name('add-staff');
    Route::post('add-staff', [RegisterController::class, 'store']);
    Route::get('edit-staff/{id}', [RegisterController::class, 'edit'])->name('edit-staff');
    Route::put('update-staff/{id}', [RegisterController::class, 'update'])->name('update-staff');
    Route::delete('data-staff/{id}', [StaffController::class, 'destroy'])->name('destroy-staff');
});

//staff Route
Route::middleware(['auth', 'userMiddleware'])->group(function () {
    Route::get('user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
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

Route::middleware(['auth', 'mahasiswaMiddleware'])->group(function () {
    Route::get('mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');
    Route::get('/mahasiswa/pengajuan', [MahasiswaController::class, 'pengajuan'])->name('mahasiswa.pengajuan');
    Route::get('/mahasiswa/pengajuan/add', [MahasiswaController::class, 'create'])->name('mahasiswa.add');
    Route::post('/mahasiswa/pengajuan/add', [MahasiswaController::class, 'store']);
    Route::delete('/mahasiswa/pengajuan/delete/{pengajuan}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
});

Route::middleware(['auth', 'dosenMiddleware'])->group(function () {
    Route::get('dosen/dashboard', [DosenController::class, 'index'])->name('dosen.dashboard');
});
