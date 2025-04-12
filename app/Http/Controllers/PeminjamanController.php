<?php

namespace App\Http\Controllers;

use App\Models\Assetlab;
use App\Models\Peminjaman;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $query = Transaction::with(['creator'])->where('type', 'inventaris')->withCount([
            'loans',
            'loans as completed_loans_count' => function ($query) {
                $query->where('status', '!=', 'dipinjam');
            },
            'loans as pending_loans_count' => function ($query) {
                $query->where('status', 'dipinjam');
            }
        ]);

        $filterUser = Transaction::where('type', 'inventaris')->select('user_id', 'name')->when($authUser->usertype === 'staff', function ($query) use ($authUser) {
            return $query->where('location', $authUser->prodi);
        })->distinct()->get();

        if ($authUser->usertype === 'staff') {
            $query->where('location', $authUser->prodi);
        }
        if ($request->has('search')) {
            $query->search($request->search);
        }
        if ($request->has('location') && $request->location != 'all') {
            $query->where('location', $request->location);
        }
        if ($request->has('purpose') && $request->purpose != 'semua') {
            $query->where('purpose', $request->purpose);
        }
        if ($request->has('user_id') && $request->user_id != 'semua') {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        #Sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedFields = ['user_id', 'name', 'prodi', 'telp', 'items', 'detail', 'location', 'created_by', 'created_at'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $transactions = $query->paginate(10);
        $locations = ['all' => 'Semua',  'Matematika' => 'Matematika', 'Biologi' => 'Biologi', 'Fisika' => 'Fisika', 'Kimia' => 'Kimia', 'Teknik Informatika' => 'Teknik Informatika', 'Agroteknologi' => 'Agroteknologi', 'Teknik Elektro' => 'Teknik Elektro'];

        return view('transactions.peminjaman', compact('transactions', 'locations', 'sortField', 'sortOrder', 'filterUser'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prodi = Auth::user()->prodi;
        return view('transactions.add-peminjaman', compact('prodi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'purpose' => 'required',
                'user_id' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'prodi' => 'required|string|max:255',
                'telp' => 'required|string|max:16',
                'detail' => 'required|string|max:255',
                'location' => 'required|string|max:255',
            ]);

            return DB::transaction(function () use ($request) {
                $location = Auth::user()->usertype === 'staff'
                    ? Auth::user()->prodi
                    : $request->location;

                // Simpan data perencanaan
                $data = Transaction::create([
                    'purpose' => $request->purpose,
                    'user_id' => $request->user_id,
                    'name' => $request->name,
                    'prodi' => $request->prodi,
                    'telp' => $request->telp,
                    'detail' => $request->detail,
                    'type' => 'inventaris',
                    'location' => $location,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
                // **1. Simpan produk yang sudah ada**
                $items = $request->items ?? [];
                $processedItems = [];
                $temp = [];

                foreach ($items as $item) {
                    if (isset($item['product_code'])) {
                        // Jika temp tidak kosong, berarti ada produk sebelumnya yang harus disimpan
                        if (!empty($temp)) {
                            $processedItems[] = $temp;
                        }
                        // Reset temp dan simpan product_code
                        $temp = $item;
                    } else {
                        // Tambahkan atribut lainnya ke dalam produk yang sedang diproses
                        $temp = array_merge($temp, $item);
                    }
                }

                if (!empty($temp)) {
                    $processedItems[] = $temp;
                }
                foreach ($processedItems as $item) {
                    $asset = Assetlab::where('product_code', $item['product_code'])->first();
                    if ($asset) {
                        // Hitung updated stock
                        if ($item['quantity'] > $asset->stock) {
                            return back()->withErrors([
                                'quantity' => "Jumlah pemakaian untuk produk {$item['product_code']} melebihi stok yang tersedia ({$asset->stock})."
                            ])->withInput();
                        }
                        $updatedStock = max(0, $asset->stock - $item['quantity']); // Hindari stock negatif
                        $price = $request->purpose == 'penelitian' ? $asset->latestPrice->price : 0;
                        // Simpan data transaksi
                        $data->loans()->create([
                            'product_code' => $item['product_code'],
                            'stock' => $asset->stock,
                            'quantity' => $item['quantity'],
                            'rental' => $item['rental'],
                            'rental_price' => $price,
                            'total_price' => ($item['quantity'] * $item['rental'] * $price),
                            'loan_date' => now(),
                            'status' => 'dipinjam',
                            'notes' => $item['notes'] ?: '',
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]);
                        $asset->update(['stock' => $updatedStock]);
                    }
                }
                return redirect()->route('detail-peminjaman', ['id' => $data->id])
                    ->with('success', 'Penggunaan berhasil dibuat.');
            });
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database.');
        } catch (Exception $e) {
            Log::error('Unexpected Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan pada baris ' . $e->getLine() . ' di file ' . $e->getFile() . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $dataTransaksi = Transaction::with('loans')->findOrFail($id);

        $query = $dataTransaksi->loans()->with('asset');

        $sortField = $request->get('sort_field', 'product_name');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedFields = ['product_code', 'product_name', 'product_detail', 'merk', 'product_type', 'stock', 'jumlah_pemakaian', 'updated_stock', 'product_unit'];

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if (in_array($sortField, $allowedFields)) {
            if (in_array($sortField, ['product_name', 'product_detail', 'merk', 'product_type', 'product_unit'])) {
                // Sorting berdasarkan tabel product
                $query->join('assetlabs as a', 'peminjamans.product_code', '=', 'a.product_code')
                    ->select('peminjamans.*', 'a.product_name', 'a.product_detail', 'a.merk', 'a.product_type', 'a.product_unit')
                    ->orderBy("a.$sortField", $sortOrder);
            } else {
                // Sorting berdasarkan tabel plans
                $query->orderBy($sortField, $sortOrder);
            }
        }

        $products = $query->paginate(10);
        $assets = Assetlab::where('type', 'inventaris')->where('location', $dataTransaksi->location)->orderBy('product_name')->get();

        // **Menentukan Status Transaksi Berdasarkan Status Loans (Produk)**
        $isCompleted = $dataTransaksi->loans->every(fn($loan) => $loan->status == 'dikembalikan');
        $isPartiallyReturned = $dataTransaksi->loans->contains(fn($loan) => $loan->status == 'dikembalikan sebagian');

        if ($isCompleted) {
            $statusColor = 'bg-green-200 text-green-700';
            $statusText = 'Selesai';
        } elseif ($isPartiallyReturned) {
            $statusColor = 'bg-yellow-200 text-yellow-700';
            $statusText = 'Dikembalikan Sebagian';
        } else {
            $statusColor = 'bg-red-200 text-red-700';
            $statusText = 'Belum Selesai';
        }

        return view('transactions.detail-peminjaman', compact('dataTransaksi', 'products', 'assets', 'sortField', 'sortOrder', 'statusText', 'statusColor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }
    public function update(Request $request, $id)
    {
        $dataPeminjaman = Peminjaman::findOrFail($id);

        $request->validate([
            'returned_quantity' => "required|integer|min:0|max:{$dataPeminjaman->quantity}",
            'damaged_quantity' => "required|integer|min:0|max:{$dataPeminjaman->quantity}",
            'return_notes' => 'nullable|string',
        ]);

        $newReturnedQuantity = $request->returned_quantity;
        $newDamagedQuantity = $request->damaged_quantity;

        $deltaReturned = $newReturnedQuantity - $dataPeminjaman->returned_quantity;
        $deltaDamaged = $newDamagedQuantity - $dataPeminjaman->damaged_quantity;

        // Update status dataPeminjaman
        if ($newReturnedQuantity + $newDamagedQuantity == 0) {
            $status = 'belum dikembalikan';
        } elseif ($newReturnedQuantity + $newDamagedQuantity < $dataPeminjaman->quantity) {
            $status = 'dikembalikan sebagian';
        } elseif ($newReturnedQuantity == $dataPeminjaman->quantity) {
            $status = 'dikembalikan';
        } elseif ($newDamagedQuantity == $dataPeminjaman->quantity) {
            $status = 'rusak';
        } else {
            $status = 'dikembalikan sebagian';
        }

        $dataPeminjaman->update([
            'returned_quantity' => $newReturnedQuantity,
            'damaged_quantity' => $newDamagedQuantity,
            'return_date' => now(),
            'return_notes' => $request->return_notes,
            'status' => $status,
        ]);

        // Update stok di Assetlab
        $dataPeminjaman->asset->updateStock($deltaReturned, $deltaDamaged);

        return redirect()->back()->with('success', 'Barang berhasil dikembalikan.');
    }

    //export
    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $location = $request->input('location');
        $user_id = $request->input('user_id');
        if (Auth::user()->usertype === 'staff') {
            $location = Auth::user()->prodi;
        }

        $filename = "Data_Penggunaan Barang";

        if ($startDate || $endDate) {
            $filename .= "Periode_{$startDate}-{$endDate}";
        }

        if ($user_id) {
            $filename .= "_{$user_id}";
        }

        if ($location) {
            $filename .= "_{$location}";
        }

        $filename .= ".xlsx";

        // return Excel::download(new PenggunaanExportAll($startDate, $endDate, $location, $user_id), $filename);
    }
    public function exportById($id)
    {
        $transaksi = Transaction::findOrFail($id);
        $user_id = $transaksi->user_id;
        $name = $transaksi->name;
        $tanggal = $transaksi->created_at->format('d-m-Y');
        // Susun nama file yang lebih rapi
        $filename = "Data Penggunaan_{$user_id}_{$name}_{$tanggal}.xlsx";

        // return Excel::download(new PenggunaanExportId($id), $filename);
    }
    // Print Data
    public function print(Request $request)
    {
        $location = $request->input('location');
        $printDate = Carbon::now()->format('d M Y, H:i');
        $query = Transaction::where('type', 'inventaris')->with(['items.asset', 'creator', 'updater']);
        $purpose = 'semua';
        $startDate = null;
        $endDate = null;
        if (Auth::user()->usertype === 'staff') {
            $location = Auth::user()->prodi;
        }
        if (!empty($location) && $location !== 'all') {
            $query->where('location', $location);
        }
        if ($request->has('user_id') && $request->user_id != 'semua') {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('purpose') && $request->purpose != 'semua') {
            $query->where('purpose', $request->purpose);
            $purpose = $request->purpose;
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $data = $query->get();

        if ($request->has('user_id') && $request->user_id != 'semua') {
            return view('print.peminjaman-detail', compact('data', 'startDate', 'endDate', 'location', 'printDate'));
        }

        return view('print.peminjaman', compact('data', 'startDate', 'endDate', 'location', 'printDate', 'purpose'));
    }
    public function printById(Request $request, $id)
    {
        $printDate = Carbon::now()->format('d M Y, H:i');
        $data = Transaction::with(['loans.asset', 'creator', 'updater'])->findOrFail($id);
        return view('print.peminjaman-detail', compact('data', 'printDate'));
    }
    public function destroy(Transaction $transaction)
    {
        try {
            DB::transaction(function () use ($transaction) {
                // Ambil semua item dalam transaksi
                foreach ($transaction->loans as $item) {
                    // Ambil asset terkait
                    $asset = Assetlab::where('product_code', $item->product_code)->first();

                    if ($asset) {
                        if ($item->returned_quantity == 0) {
                            // Kembalikan stok ke nilai sebelum transaksi terjadi
                            $asset->update([
                                'stock' => $asset->stock + $item->quantity
                            ]);
                        } else if ($item->returned_quantity < $item->quantity) {
                            throw new Exception('Terdapat transaksi belum selesai.');
                        }
                    }

                    // Hapus item transaksi
                    $item->delete();
                }

                // Hapus transaksi utama
                $transaction->delete();
            });

            return redirect()->route('peminjaman')->with('success', 'Transaksi berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Error deleting transaction: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus transaksi.');
        }
    }
}
