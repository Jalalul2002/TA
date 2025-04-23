<?php

namespace App\Http\Controllers;

use App\Exports\PenggunaanExportAll;
use App\Exports\PenggunaanExportId;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Models\Assetlab;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

use function PHPSTORM_META\type;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Transaction::where('type', 'bhp')->select('user_id', 'name');
        $query = Transaction::where('type', 'bhp')->withCount('items');
        $locations = ['all' => 'Semua',  'Matematika' => 'Matematika', 'Biologi' => 'Biologi', 'Fisika' => 'Fisika', 'Kimia' => 'Kimia', 'Teknik Informatika' => 'Teknik Informatika', 'Agroteknologi' => 'Agroteknologi', 'Teknik Elektro' => 'Teknik Elektro'];
        if (Auth::user()->usertype === 'staff') {
            $query->where('location', Auth::user()->prodi);
            $user->where('location', Auth::user()->prodi);
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
        $allowedFields = ['user_id', 'name', 'prodi', 'telp', 'items_count', 'detail', 'location', 'created_by', 'created_at'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $transactions = $query->paginate(10);
        $filterUser = $user->distinct()->get();
        return view('transactions.penggunaan', compact('transactions', 'locations', 'sortField', 'sortOrder', 'filterUser'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prodi = Auth::user()->prodi;
        return view('transactions.add-penggunaan', compact('prodi'));
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
                    'name' => ucwords(strtolower($request->name)),
                    'prodi' => $request->prodi,
                    'telp' => $request->telp,
                    'detail' => $request->detail,
                    'type' => 'bhp',
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
                    $asset = Assetlab::with('latestPrice')->where('product_code', $item['product_code'])->first();
                    if ($asset) {
                        if ($item['quantity'] > $asset->stock) {
                            return back()->withErrors([
                                'quantity' => "Jumlah pemakaian untuk produk {$item['product_code']} melebihi stok yang tersedia ({$asset->stock})."
                            ])->withInput();
                        }
                        // Hitung updated stock
                        $updatedStock = max(0, $asset->stock - $item['quantity']); // Hindari stock negatif
                        $price = $request->purpose == 'penelitian' ? $asset->latestPrice->price : 0;
                        // Simpan data transaksi
                        $data->items()->create([
                            'product_code' => $item['product_code'],
                            'stock' => $asset->stock,
                            'unit_price' => $price,
                            'jumlah_pemakaian' => $item['quantity'],
                            'updated_stock' => $updatedStock,
                            'total_price' => ($item['quantity'] * $price),
                        ]);
                        $asset->update(['stock' => $updatedStock]);
                    }
                }

                return redirect()->route('detail-penggunaan', ['id' => $data->id])
                    ->with('success', 'Penggunaan berhasil dibuat.');
            });
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database.');
        } catch (\Exception $e) {
            Log::error('Unexpected Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $dataTransaksi = Transaction::findOrFail($id);
        $query = $dataTransaksi->items()->with('asset');

        $sortField = $request->get('sort_field', 'product_name');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedFields = ['product_code', 'product_name', 'product_detail', 'merk', 'product_type', 'stock', 'jumlah_pemakaian', 'updated_stock', 'product_unit'];

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if (in_array($sortField, $allowedFields)) {
            if (in_array($sortField, ['product_name', 'product_detail', 'merk', 'product_type', 'product_unit'])) {
                // Sorting berdasarkan tabel product
                $query->join('assetlabs as a', 'transaction_items.product_code', '=', 'a.product_code')
                    ->select('transaction_items.*', 'a.product_name', 'a.product_detail', 'a.merk', 'a.product_type', 'a.product_unit')
                    ->orderBy("a.$sortField", $sortOrder);
            } else {
                // Sorting berdasarkan tabel plans
                $query->orderBy($sortField, $sortOrder);
            }
        }

        $products = $query->paginate(10);

        return view('transactions.detail-penggunaan', compact('dataTransaksi', 'products', 'sortField', 'sortOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    //export
    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $location = $request->input('location');
        $purpose = $request->input('purpose');
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

        if ($purpose) {
            $filename .= "_{$purpose}";
        }

        if ($location) {
            $filename .= "_{$location}";
        }

        $filename .= ".xlsx";

        return Excel::download(new PenggunaanExportAll($startDate, $endDate, $location, $user_id, $purpose), $filename);
    }
    public function exportById($id)
    {
        $transaksi = Transaction::findOrFail($id);
        $user_id = $transaksi->user_id;
        $name = $transaksi->name;
        $tanggal = $transaksi->created_at->format('d-m-Y');
        // Susun nama file yang lebih rapi
        $filename = "Data Penggunaan_{$user_id}_{$name}_{$tanggal}.xlsx";

        return Excel::download(new PenggunaanExportId($id), $filename);
    }
    // Print Data
    public function print(Request $request)
    {
        $location = $request->input('location');
        $printDate = Carbon::now()->format('d M Y, H:i');
        $query = Transaction::where('type', 'bhp')->with(['items.asset', 'creator', 'updater']);
        $startDate = null;
        $endDate = null;
        $purpose = 'semua';
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
        $totalPrice = $data->sum('total_item_price');

        if ($request->has('user_id') && $request->user_id != 'semua') {
            return view('print.penggunaan-detail', compact('data', 'startDate', 'endDate', 'location', 'printDate', 'totalPrice'));
        }

        return view('print.penggunaan', compact('data', 'startDate', 'endDate', 'location', 'printDate', 'totalPrice', 'purpose'));
    }
    public function printById(Request $request, $id)
    {
        $printDate = Carbon::now()->format('d M Y, H:i');
        $data = Transaction::with(['items.asset', 'creator', 'updater'])->findOrFail($id);
        $totalPrice = $data->total_item_price;
        return view('print.penggunaan-detail', compact('data', 'printDate', 'totalPrice'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        try {
            DB::transaction(function () use ($transaction) {
                // Ambil semua item dalam transaksi
                foreach ($transaction->items as $item) {
                    // Ambil asset terkait
                    $asset = Assetlab::where('product_code', $item->product_code)->first();

                    if ($asset) {
                        // Kembalikan stok ke nilai sebelum transaksi terjadi
                        $asset->update([
                            'stock' => $asset->stock + $item->jumlah_pemakaian
                        ]);
                    }

                    // Hapus item transaksi
                    $item->delete();
                }

                // Hapus transaksi utama
                $transaction->delete();
            });

            return redirect()->route('penggunaan')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting transaction: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus transaksi.');
        }
    }
}
