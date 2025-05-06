<?php

namespace App\Http\Controllers;

use App\Exports\RealisasiExport;
use App\Exports\RealisasiExportAll;
use App\Models\Assetlab;
use App\Models\DataRealisasi;
use App\Models\Realisasi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class RealisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexInv(Request $request)
    {
        $query = DataRealisasi::with([
            'items.product',
            'latestUpdater.updater',
            'creator' => function ($query) {
                $query->select('id', 'name');
            }
        ])->where('type', 'inventaris')->withCount('items');
        $locations = ['all' => 'Semua',  'Matematika' => 'Matematika', 'Biologi' => 'Biologi', 'Fisika' => 'Fisika', 'Kimia' => 'Kimia', 'Teknik Informatika' => 'Teknik Informatika', 'Agroteknologi' => 'Agroteknologi', 'Teknik Elektro' => 'Teknik Elektro'];

        if (Auth::user()->usertype === 'staff') {
            $query->where('prodi', Auth::user()->prodi);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('location') && $request->location != 'all') {
            $query->where('prodi', $request->location);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        #Sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedFields = ['name', 'product_name', 'prodi', 'items_count', 'status', 'created_at'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $datas = $query->paginate(10);
        return view('pengadaan.index', compact('datas', 'sortField', 'sortOrder', 'locations'));
    }

    public function indexBhp(Request $request)
    {
        $query = DataRealisasi::with([
            'items.product',
            'latestUpdater.updater',
            'creator' => function ($query) {
                $query->select('id', 'name');
            }
        ])->where('type', 'bhp')->withCount('items');
        $locations = ['all' => 'Semua',  'Matematika' => 'Matematika', 'Biologi' => 'Biologi', 'Fisika' => 'Fisika', 'Kimia' => 'Kimia', 'Teknik Informatika' => 'Teknik Informatika', 'Agroteknologi' => 'Agroteknologi', 'Teknik Elektro' => 'Teknik Elektro'];

        if (Auth::user()->usertype === 'staff') {
            $query->where('prodi', Auth::user()->prodi);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('location') && $request->location != 'all') {
            $query->where('prodi', $request->location);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        #Sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedFields = ['name', 'product_name', 'prodi', 'items_count', 'status', 'created_at'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $datas = $query->paginate(10);
        return view('pengadaan.index', compact('datas', 'sortField', 'sortOrder', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createInv()
    {
        $prodi = Auth::user()->prodi;
        return view('pengadaan.add-inv', compact('prodi'));
    }

    public function createBhp()
    {
        $prodi = Auth::user()->prodi;
        return view('pengadaan.add-bhp', compact('prodi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'pengadaan' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'type' => 'required|string|max:255',
            ]);

            $location = $request->location;
            $type = $request->type;
            if (Auth::user()->usertype === 'staff') {
                $location = Auth::user()->prodi;
            }

            $location_code = [
                'Matematika' => '701',
                'Biologi' => '702',
                'Fisika' => '703',
                'Kimia' => '704',
                'Teknik Informatika' => '705',
                'Agroteknologi' => '706',
                'Teknik Elektro' => '707',
            ];
            $initial_code = $location_code[$location] ?? '700';
            // **1. Validasi apakah produk baru sudah ada di AssetLab sebelum transaksi berjalan**
            $newProducts = json_decode($request->new_products, true) ?? [];
            $productCodes = array_map(fn($p) => strtoupper("{$initial_code}-{$p['product_code']}"), $newProducts);
            // Cek apakah ada kode produk yang sudah ada di AssetLab
            $existingProductCodes = AssetLab::whereIn('product_code', $productCodes)->pluck('product_code')->toArray();
            if (!empty($existingProductCodes)) {
                // Format pesan error untuk ditampilkan
                $errorMessages = array_map(fn($code) => "Kode produk {$code} sudah ada di Asset Lab!", $existingProductCodes);
                return redirect()->route($type === 'bhp' ? 'add-perencanaan.bhp' : 'add-perencanaan.inv')->withErrors(['product_code' => implode('<br>', $errorMessages)])->withInput();
            }
            return DB::transaction(function () use ($request, $location, $type, $newProducts, $productCodes) {
                // Simpan data perencanaan
                $data = DataRealisasi::create([
                    'name' => $request->pengadaan,
                    'prodi' => $location,
                    'type' => $type,
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
                    $data->items()->create([
                        'product_code' => $item['product_code'],
                        'stock' => $item['stock'],
                        'purchase_price' => $item['purchase_price'],
                        'jumlah_kebutuhan' => $item['quantity'],
                        'total_price' => $item['total_price'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }

                foreach ($newProducts as $index => $product) {
                    if (!isset($product['product_code'], $product['product_name'], $product['product_type'], $product['stock'], $product['product_unit'])) {
                        continue;
                    }

                    $product_code = $productCodes[$index];

                    $asset = AssetLab::create([
                        'product_code' => $product_code,
                        'product_name' => $product['product_name'],
                        'type' => $type,
                        'product_type' => $product['product_type'],
                        'stock' => $product['stock'],
                        'product_unit' => $product['product_unit'],
                        'product_detail' => $product['product_detail'] ?? null,
                        'merk' => $product['merk'] ?? null,
                        'location' => $location,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);

                    $data->items()->create([
                        'product_code' => $asset->product_code,
                        'stock' => $asset->stock ?? 0,
                        'purchase_price' => $product['purchase_price'] ?? 0,
                        'jumlah_kebutuhan' => $product['quantity'] ?? 1,
                        'total_price' => $product['total_price'] ?? 0,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
                return redirect()->route($request->type === 'bhp' ? 'realisasi.bhp' : 'realisasi.inv')
                    ->with('success', 'Pengadaan berhasil dibuat.');
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

    public function storeItem(Request $request, $id)
    {
        try {
            $dataPerencanaan = DataRealisasi::findOrFail($id);
            $location_code = [
                'Matematika' => '701',
                'Biologi' => '702',
                'Fisika' => '703',
                'Kimia' => '704',
                'Teknik Informatika' => '705',
                'Agroteknologi' => '706',
                'Teknik Elektro' => '707',
            ];
            $initial_code = $location_code[$dataPerencanaan->prodi] ?? '700';
            if ($request->product_code === 'new') {
                if (empty($request->new_product_code)) {
                    return redirect()->back()->with('error', 'Kode produk baru harus diisi!');
                }
                $product_code = "{$initial_code}-{$request->new_product_code}";
            } else {
                $product_code = $request->product_code;
            }
            $product_code_upper = strtoupper($product_code);
            $product_name_capitalized = ucwords(strtolower($request->new_product_name));
            $location_capitalized = ucwords(strtolower($dataPerencanaan->prodi));
            $merk_capitalized = ucwords(strtolower($request->new_merk));
            $type_lower = strtolower($dataPerencanaan->type);
            $product_type_capitalized = ucwords(strtolower($request->new_product_type));

            $asset = Assetlab::where('product_code', $product_code_upper)->first();

            if ($request->product_code === 'new') {
                if ($asset) {
                    return redirect()->back()->with('error', 'Kode produk sudah ada di Asset Lab!');
                }
                Assetlab::create([
                    'product_code' => $product_code_upper,
                    'product_name' => $product_name_capitalized,
                    'product_detail' => $request->new_product_detail,
                    'merk' => $merk_capitalized,
                    'type' => $type_lower,
                    'product_type' => $product_type_capitalized,
                    'stock' => $request->stock ?? 0,
                    'product_unit' => $request->product_unit,
                    'location_detail' => null,
                    'location' => $location_capitalized,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            } else {
                if (!$asset) {
                    return redirect()->back()->with('error', 'Kode produk tidak ditemukan di Asset Lab!');
                }
            }

            Realisasi::create([
                'realisasi_id' => $id,
                'product_code' => $product_code_upper,
                'stock' => (int) $request->stock ?? 0,
                'purchase_price' => (int) $request->purchase_price,
                'jumlah_kebutuhan' => (int) $request->quantity,
                'total_price' => (int) $request->total_price,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'Product added successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Data Pengadaan tidak ditemukan!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan yang tidak terduga.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $dataRealisasi = DataRealisasi::with('items.product')->findOrFail($id);
        $query = $dataRealisasi->items()->with('product');

        $sortField = $request->get('sort_field', 'jumlah_kebutuhan');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedFields = ['product_code', 'product_name', 'product_detail', 'merk', 'product_type', 'stock', 'jumlah_kebutuhan', 'product_unit'];

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if (in_array($sortField, $allowedFields)) {
            if (in_array($sortField, ['product_name', 'merk', 'product_type', 'product_unit'])) {
                // Sorting berdasarkan tabel product
                $query->join('assetlabs', 'realisasis.product_code', '=', 'assetlabs.product_code')
                    ->select('realisasis.*', 'assetlabs.product_name', 'assetlabs.product_detail', 'assetlabs.merk', 'assetlabs.product_type', 'assetlabs.product_unit')
                    ->orderBy("assetlabs.$sortField", $sortOrder);
            } else {
                // Sorting berdasarkan tabel
                $query->orderBy($sortField, $sortOrder);
            }
        }

        $products = $query->paginate(10);
        $totalPriceSum = $products->sum('total_price');
        $assets = Assetlab::with('latestPrice')->where('type', $dataRealisasi->type === 'bhp' ? 'bhp' : 'inventaris')->where('location', $dataRealisasi->prodi)->get();

        return view('pengadaan.detail', compact('dataRealisasi', 'products', 'totalPriceSum', 'assets', 'sortField', 'sortOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = Realisasi::with(['data', 'product'])->where('id', $id)->firstOrFail();
        return view('pengadaan.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $data = Realisasi::with('data')->where('id', $id)->firstOrFail();
            $request->validate([
                'jumlah_kebutuhan' => ['required'],
            ]);
            $data->update([
                'purchase_price' => $request->purchase_price,
                'jumlah_kebutuhan' => $request->jumlah_kebutuhan,
                'total_price' => $request->total_price,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->route('realisasi.show', ['id' => $data->data->id, 'type' => $data->data->type])
                ->with('success', 'Data berhasil diperbarui');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->route('realisasi.show', ['id' => $id])
                ->with('error', 'Terjadi kesalahan saat memperbarui data!');
        }
    }

    public function updateDetail(Request $request, $id)
    {
        try {
            $dataRealisasi = DataRealisasi::findOrFail($id);
            $dataRealisasi->update([
                'name' => $request->name,
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('realisasi.show', ['id' => $dataRealisasi->id, 'type' => $dataRealisasi->type])
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('realisasi.show', ['id' => $id])
                ->with('error', 'Gagal memperbarui data!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dataRealisasi = DataRealisasi::findOrFail($id);
        $dataRealisasi->delete();
        return redirect()->back()->with('success', 'Data Pengadaan deleted successfully.');
    }

    public function destroyItem($id)
    {
        $realisasi = Realisasi::findOrFail($id);
        $dataRealisasi = $realisasi->rencana;
        if ($dataRealisasi) {
            $dataRealisasi->updated_at = now();
            $dataRealisasi->updated_by = Auth::id();
            $dataRealisasi->save();
        }
        $realisasi->delete();
        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    // Export
    public function exportId($id)
    {
        $realisasi = DataRealisasi::find($id);

        $type = $realisasi->type;
        $name = $realisasi->name;
        $prodi = $realisasi->prodi;

        // Susun nama file yang lebih rapi
        $filename = "Pengadaan {$type}_{$name}_{$prodi}.xlsx";

        return Excel::download(new RealisasiExport($id), $filename);
    }
    public function export(Request $request)
    {
        $type = $request->type == 'bhp' ? 'bhp' : 'inventaris';
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $location = $request->input('location');
        if (Auth::user()->usertype === 'staff') {
            $location = Auth::user()->prodi;
        }

        $filename = "Data_Pengadaan_{$type}";

        if ($location) {
            $filename .= "_{$location}";
        }

        if ($startDate || $endDate) {
            $filename .= "_Periode_{$startDate}-{$endDate}";
        }

        $filename .= ".xlsx";

        return Excel::download(new RealisasiExportAll($startDate, $endDate, $location, $type), $filename);
    }
    // Print
    public function printId($id)
    {
        $realisasi = DataRealisasi::findOrFail($id);
        $printDate = Carbon::now()->format('d M Y, H:i');

        $data = Realisasi::with(['product'])
            ->where('realisasi_id', $id)
            ->get();

        return view('print.realisasi', compact('data', 'realisasi', 'printDate'));
    }
    public function print(Request $request)
    {
        $type = $request->type == 'bhp' ? 'bhp' : 'inventaris';
        $location = $request->input('location');
        $printDate = Carbon::now()->format('d M Y, H:i');
        $query = DataRealisasi::where('type', $type)->with(['items', 'items.product']);
        $startDate = null;
        $endDate = null;
        if (Auth::user()->usertype === 'staff') {
            $location = Auth::user()->prodi;
        }
        if (!empty($location) && $location !== 'all') {
            $query->where('prodi', $location);
        }
        if ($request->has('user_id') && $request->user_id != 'semua') {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $data = $query->get();
        $grandTotal = $data->sum->total_price;

        return view('print.realisasi-all', compact('data', 'startDate', 'endDate', 'location', 'printDate', 'type', 'grandTotal'));
    }

    // Complete the realisasion data
    public function complete($id)
    {
        try {
            $dataRealisasi = DataRealisasi::with('items.product')->findOrFail($id);

            if ($dataRealisasi->status === 'selesai') {
                return redirect()->back()->with('error', 'Pengadaan ini sudah selesai.');
            }

            foreach ($dataRealisasi->items as $item) {
                $productDetails = $item->product;

                if ($productDetails) {
                    $productDetails->stock += $item->jumlah_kebutuhan;

                    if ($productDetails->stock < 0) {
                        return redirect()->back()->with(
                            'error',
                            "Stok untuk produk {$productDetails->product_name} tidak normal."
                        );
                    }

                    $productDetails->save();
                } else {
                    return redirect()->back()->with(
                        'error',
                        "Produk dengan kode {$item->product_code} tidak ditemukan."
                    );
                }
            }

            $dataRealisasi->status = 'selesai';
            $item->updated_by = Auth::id();
            $item->updated_at = now();
            $dataRealisasi->save();


            return redirect()->route($dataRealisasi->type === 'bhp' ? 'realisasi.bhp' : 'realisasi.inv')->with('success', 'Pengadaan berhasil diselesaikan.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database saat memperbarui data.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan yang tidak terduga.');
        }
    }
}
