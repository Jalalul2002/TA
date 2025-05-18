<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Exports\TransactionReport;
use App\Models\Assetlab;
use App\Models\DataPerencanaan;
use App\Models\Peminjaman;
use App\Models\Perencanaan;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $location = $request->input('location');
        $type = $request->input('type');
        $productType = $request->input('productType');
        $report = collect();

        if (Auth::user()->usertype == 'staff') {
            $location = Auth::user()->prodi;
        }

        $assets = collect();
        if ($startDate && $endDate && $location && $type) {
            $query = Assetlab::where('location', $location)
                ->where('type', $type);

            if ($productType) {
                $query->where('product_type', $productType);
            }

            $assets = $query->get();

            $report = $assets->filter(function ($asset) use ($startDate, $endDate) {
                $productCode = $asset->product_code;

                // Cek stok sekarang
                $hasStock = $asset->stock > 0;

                // Cek transaksi masuk
                $hasMasuk = DataPerencanaan::where('status', 'selesai')
                    ->whereBetween('updated_at', [$startDate, $endDate])
                    ->whereHas('plans', fn($q) => $q->where('product_code', $productCode))
                    ->exists();

                // Cek transaksi keluar
                $hasKeluar = TransactionItem::where('product_code', $productCode)
                    ->whereHas('transaction', fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->exists();

                return $hasStock || $hasMasuk || $hasKeluar;
            })->map(function ($asset) use ($startDate, $endDate, $type) {
                $productCode = $asset->product_code;

                $stockAwal = null;

                // Coba ambil dari perencanaan masuk paling awal
                $perencanaanPertama = Perencanaan::where('product_code', $productCode)
                    ->whereHas('rencana', fn($q) => $q->where('status', 'selesai'))
                    ->orderBy('updated_at', 'asc')
                    ->first();

                if ($perencanaanPertama) {
                    $stockAwal = $perencanaanPertama->stock ?? null;
                }

                // Kalau belum dapat dari perencanaan, coba dari transaksi keluar paling awal
                if ($stockAwal === null) {
                    $transaksiPertama = TransactionItem::where('product_code', $productCode)
                        ->whereHas('transaction', fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                        ->with('transaction')
                        ->get()
                        ->sortBy(fn($item) => $item->transaction->created_at)
                        ->first();

                    $stockAwal = $transaksiPertama->stock ?? null;
                }

                if ($stockAwal === null) {
                    $transaksiPertama = TransactionItem::where('product_code', $productCode)
                        ->whereHas('transaction', fn($q) => $q->where('created_at', '<=', $startDate))
                        ->with('transaction')
                        ->get()
                        ->sortBy(fn($item) => $item->transaction->created_at)
                        ->first();

                    $stockAwal = $transaksiPertama->stock ?? null;
                }

                // Kalau masih null, ambil dari Assetlab
                if ($stockAwal === null) {
                    $stockAwal = $asset->stock;
                }

                // Total masuk
                $totalMasuk = DataPerencanaan::where('status', 'selesai')
                    ->whereBetween('updated_at', [$startDate, $endDate])
                    ->whereHas('plans', fn($q) => $q->where('product_code', $productCode))
                    ->with(['plans' => fn($q) => $q->where('product_code', $productCode)])
                    ->get()
                    ->flatMap->plans
                    ->sum('jumlah_kebutuhan');

                // Total keluar
                $totalPraktikum = TransactionItem::where('product_code', $productCode)
                    ->whereHas(
                        'transaction',
                        fn($q) =>
                        $q->where('purpose', 'praktikum')
                            ->whereBetween('created_at', [$startDate, $endDate])
                    )
                    ->sum('jumlah_pemakaian');

                $totalPenelitian = TransactionItem::where('product_code', $productCode)
                    ->whereHas(
                        'transaction',
                        fn($q) =>
                        $q->where('purpose', 'penelitian')
                            ->whereBetween('created_at', [$startDate, $endDate])
                    )
                    ->sum('jumlah_pemakaian');

                $totalRusak = 0;
                if ($type !== 'bhp') {
                    $totalRusak = Peminjaman::where('product_code', $productCode)
                        ->whereBetween('loan_date', [$startDate, $endDate])
                        ->sum('damaged_quantity');
                }

                $stockSekarang = $stockAwal + $totalMasuk - ($totalPraktikum + $totalPenelitian);
                if ($type !== 'bhp') {
                    $stockSekarang = $stockAwal + $totalMasuk - $totalRusak;
                }
                $stockDatabase = $asset->stock;
                $selisih = $stockDatabase - $stockSekarang;

                return [
                    'product_code'     => $productCode,
                    'product_name'     => $asset->product_name,
                    'product_detail'   => $asset->product_detail,
                    'merk'             => $asset->merk,
                    'product_type'     => $asset->product_type,
                    'product_unit'     => $asset->product_unit,
                    'stock_awal'       => $stockAwal,
                    'total_masuk'      => $totalMasuk,
                    'total_praktikum'  => $totalPraktikum,
                    'total_penelitian' => $totalPenelitian,
                    'total_rusak'      => $totalRusak,
                    'stock_terhitung'  => $stockSekarang,
                    'stock_database'   => $stockDatabase,
                    'selisih'          => $selisih,
                    'location'         => $asset->location,
                ];
            });

            $sortField = $request->input('sort_field');
            $sortOrder = $request->input('sort_order', 'asc');

            if ($sortField) {
                $report = $report->sortBy($sortField, SORT_REGULAR, $sortOrder === 'desc')->values();
            }
        }

        $locations = ['' => 'Pilih Lokasi',  'Matematika' => 'Matematika', 'Biologi' => 'Biologi', 'Fisika' => 'Fisika', 'Kimia' => 'Kimia', 'Teknik Informatika' => 'Teknik Informatika', 'Agroteknologi' => 'Agroteknologi', 'Teknik Elektro' => 'Teknik Elektro'];

        return view('pelaporan', compact('locations', 'report'));
    }

    public function print(Request $request)
    {
        $printDate = Carbon::now()->format('d M Y, H:i');
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $location = $request->input('location');
        $type = $request->input('type');
        $productType = $request->input('productType');
        $report = collect();

        if (Auth::user()->usertype == 'staff') {
            $location = Auth::user()->prodi;
        }

        $assets = collect();
        if ($startDate && $endDate && $location && $type) {
            $query = Assetlab::where('location', $location)
                ->where('type', $type);

            if ($productType) {
                $query->where('product_type', $productType);
            }

            $assets = $query->get();

            $report = $assets->filter(function ($asset) use ($startDate, $endDate) {
                $productCode = $asset->product_code;

                // Cek stok sekarang
                $hasStock = $asset->stock > 0;

                // Cek transaksi masuk
                $hasMasuk = DataPerencanaan::where('status', 'selesai')
                    ->whereBetween('updated_at', [$startDate, $endDate])
                    ->whereHas('plans', fn($q) => $q->where('product_code', $productCode))
                    ->exists();

                // Cek transaksi keluar
                $hasKeluar = TransactionItem::where('product_code', $productCode)
                    ->whereHas('transaction', fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->exists();

                return $hasStock || $hasMasuk || $hasKeluar;
            })->map(function ($asset) use ($startDate, $endDate) {
                $productCode = $asset->product_code;

                $stockAwal = null;

                // Coba ambil dari perencanaan masuk paling awal
                $perencanaanPertama = Perencanaan::where('product_code', $productCode)
                    ->whereHas('rencana', fn($q) => $q->where('status', 'selesai'))
                    ->orderBy('updated_at', 'asc')
                    ->first();

                if ($perencanaanPertama) {
                    $stockAwal = $perencanaanPertama->stock ?? null;
                }

                // Kalau belum dapat dari perencanaan, coba dari transaksi keluar paling awal
                if ($stockAwal === null) {
                    $transaksiPertama = TransactionItem::where('product_code', $productCode)
                        ->whereHas('transaction', fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                        ->with('transaction')
                        ->get()
                        ->sortBy(fn($item) => $item->transaction->created_at)
                        ->first();

                    $stockAwal = $transaksiPertama->stock ?? null;
                }

                if ($stockAwal === null) {
                    $transaksiPertama = TransactionItem::where('product_code', $productCode)
                        ->whereHas('transaction', fn($q) => $q->where('created_at', '<=', $startDate))
                        ->with('transaction')
                        ->get()
                        ->sortBy(fn($item) => $item->transaction->created_at)
                        ->first();

                    $stockAwal = $transaksiPertama->stock ?? null;
                }

                // Kalau masih null, ambil dari Assetlab
                if ($stockAwal === null) {
                    $stockAwal = $asset->stock;
                }

                // Total masuk
                $totalMasuk = DataPerencanaan::where('status', 'selesai')
                    ->whereBetween('updated_at', [$startDate, $endDate])
                    ->whereHas('plans', fn($q) => $q->where('product_code', $productCode))
                    ->with(['plans' => fn($q) => $q->where('product_code', $productCode)])
                    ->get()
                    ->flatMap->plans
                    ->sum('jumlah_kebutuhan');

                // Total keluar
                $totalPraktikum = TransactionItem::where('product_code', $productCode)
                    ->whereHas(
                        'transaction',
                        fn($q) =>
                        $q->where('purpose', 'praktikum')
                            ->whereBetween('created_at', [$startDate, $endDate])
                    )
                    ->sum('jumlah_pemakaian');

                $totalPenelitian = TransactionItem::where('product_code', $productCode)
                    ->whereHas(
                        'transaction',
                        fn($q) =>
                        $q->where('purpose', 'penelitian')
                            ->whereBetween('created_at', [$startDate, $endDate])
                    )
                    ->sum('jumlah_pemakaian');


                $stockSekarang = $stockAwal + $totalMasuk - ($totalPenelitian + $totalPraktikum);
                $stockDatabase = $asset->stock;
                $selisih = $stockDatabase - $stockSekarang;

                return [
                    'product_code'     => $productCode,
                    'product_name'     => $asset->product_name,
                    'product_detail'   => $asset->product_detail,
                    'merk'             => $asset->merk,
                    'product_type'     => $asset->product_type,
                    'product_unit'     => $asset->product_unit,
                    'stock_awal'       => $stockAwal,
                    'total_masuk'      => $totalMasuk,
                    'total_praktikum'  => $totalPraktikum,
                    'total_penelitian' => $totalPenelitian,
                    'stock_terhitung'  => $stockSekarang,
                    'stock_database'   => $stockDatabase,
                    'selisih'          => $selisih,
                    'location'         => $asset->location,
                ];
            });
        }

        return view('print.pelaporan', compact('report', 'startDate', 'endDate', 'type', 'location', 'printDate'));
    }

    public function download(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $location = $request->input('location');
        $type = $request->input('type');
        $productType = $request->input('productType');
        if (Auth::user()->usertype === 'staff') {
            $location = Auth::user()->prodi;
        }

        $filename = "Stock Opname_";

        if ($startDate || $endDate) {
            $filename .= "Periode_{$startDate}-{$endDate}";
        }

        if ($type) {
            $filename .= "_{$type}";
        }

        if ($productType) {
            $filename .= "_{$productType}";
        }

        if ($location) {
            $filename .= "_{$location}";
        }

        $filename .= ".xlsx";

        return Excel::download(new ReportExport($startDate, $endDate, $location, $type, $productType), $filename);
    }

    public function transaction(Request $request)
    {
        $authUser = Auth::user();
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $location = $request->input('location');
        $userId = $request->input('user_id');
        $purpose = $request->input('purpose');
        $report = collect();
        $filterUser = collect();
        $assets = collect();
        $query = Transaction::with(['items.asset', 'loans.asset', 'creator', 'updater']);

        if ($authUser->usertype == 'staff') {
            $location = $authUser->prodi;
        }

        if ($startDate && $endDate && $location && $userId) {
            if ($location) {
                $query->where('location', $location);
            }
            if ($userId) {
                $query->where('user_id', $request->user_id);
            }
            if ($purpose) {
                $query->where('purpose', $request->purpose);
                $purpose = $request->purpose;
            }
            if ($startDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $report = $query->paginate(10);
        }

        $locations = ['' => 'Pilih Lokasi',  'Matematika' => 'Matematika', 'Biologi' => 'Biologi', 'Fisika' => 'Fisika', 'Kimia' => 'Kimia', 'Teknik Informatika' => 'Teknik Informatika', 'Agroteknologi' => 'Agroteknologi', 'Teknik Elektro' => 'Teknik Elektro'];
        $queryUser = Transaction::select('user_id', 'name');
        if ($location) {
            $queryUser->where('location', $location);
        }
        if ($purpose) {
            $queryUser->where('purpose', $purpose);
        }

        $filterUser = $queryUser->distinct()->get();

        return view('transactions.report', compact('locations', 'filterUser', 'report'));
    }

    public function transactionPrint(Request $request)
    {
        $authUser = Auth::user();
        $printDate = Carbon::now()->format('d M Y, H:i');
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $location = $request->input('location');
        $userId = $request->input('user_id');
        $purpose = $request->input('purpose');
        $report = collect();
        $assets = collect();
        $query = Transaction::with(['items.asset', 'loans.asset', 'creator', 'updater']);

        if ($authUser->usertype == 'staff') {
            $location = $authUser->prodi;
        }

        if ($startDate && $endDate && $location && $userId) {
            if ($location) {
                $query->where('location', $location);
            }
            if ($userId) {
                $query->where('user_id', $userId);
            }
            if ($purpose) {
                $query->where('purpose', $purpose);
            }
            if ($startDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $report = $query->get();
        }

        return view('print.report', compact('report', 'printDate', 'purpose'));
    }

    public function TransactionDownload(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $location = $request->input('location');
        $user_id = $request->input('user_id');
        $purpose = $request->input('purpose');
        if (Auth::user()->usertype === 'staff') {
            $location = Auth::user()->prodi;
        }

        $filename = "Data_Transaksi";

        if ($user_id) {
            $filename .= "_{$user_id}";
        }
        if ($purpose) {
            $filename .= "_{$purpose}";
        }
        if ($startDate || $endDate) {
            $filename .= "_Periode_{$startDate}-{$endDate}";
        }
        if ($location) {
            $filename .= "_{$location}";
        }

        $filename .= ".xlsx";

        return Excel::download(new TransactionReport($startDate, $endDate, $location, $user_id, $purpose), $filename);
    }
}
