<?php

namespace App\Http\Controllers;

use App\Exports\PerencanaanExport;
use App\Models\Assetlab;
use App\Models\Perencanaan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\DataPerencanaan;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class PerencanaanController extends Controller
{
    public function indexInv(Request $request)
    {
        $query = DataPerencanaan::with([
            'plans.product',
            'latestUpdater.updater',
            'creator' => function ($query) {
                $query->select('id', 'name');
            }
        ])->where('type', 'inventaris')->withCount('plans');
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
        #Sorting
        $sortField = $request->get('sort_field', 'nama_perencanaan');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedFields = ['nama_perencanaan', 'product_name', 'prodi', 'plans_count', 'status'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $perencanaans = $query->paginate(10);
        return view('perencanaan.perencanaan-aset', compact('perencanaans', 'sortField', 'sortOrder', 'locations'));
    }

    public function indexBhp(Request $request)
    {
        $query = DataPerencanaan::with('plans.product', 'latestUpdater.updater')->where('type', 'bhp')->withCount('plans');
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
        #Sorting
        $sortField = $request->get('sort_field', 'nama_perencanaan');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedFields = ['nama_perencanaan', 'product_name', 'prodi', 'plans_count', 'status'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $perencanaans = $query->paginate(10);

        return view('perencanaan.perencanaan-aset', compact('perencanaans', 'sortField', 'sortOrder', 'locations'));
    }

    public function show($id, Request $request)
    {
        $dataPerencanaan = DataPerencanaan::with('plans.product')->findOrFail($id);
        $query = $dataPerencanaan->plans()->with('product');

        $sortField = $request->get('sort_field', 'jumlah_kebutuhan');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedFields = ['product_code', 'product_name', 'product_detail', 'merk', 'product_type', 'stock', 'jumlah_kebutuhan', 'product_unit'];

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if (in_array($sortField, $allowedFields)) {
            if (in_array($sortField, ['product_name', 'merk', 'product_type', 'product_unit'])) {
                // Sorting berdasarkan tabel product
                $query->join('assetlabs', 'perencanaans.product_code', '=', 'assetlabs.product_code')
                    ->select('perencanaans.*', 'assetlabs.product_name', 'assetlabs.product_detail', 'assetlabs.merk', 'assetlabs.product_type', 'assetlabs.product_unit')
                    ->orderBy("assetlabs.$sortField", $sortOrder);
            } else {
                // Sorting berdasarkan tabel plans
                $query->orderBy($sortField, $sortOrder);
            }
        }

        $products = $query->paginate(10);
        $assets = Assetlab::where('type', $dataPerencanaan->type === 'bhp' ? 'bhp' : 'inventaris')->where('location', $dataPerencanaan->prodi)->get();

        return view('perencanaan.detail-perencanaan', compact('dataPerencanaan', 'products', 'assets', 'sortField', 'sortOrder'));
    }

    public function createInv()
    {
        $prodi = Auth::user()->prodi;
        $query = Assetlab::where('type', 'inventaris')->orderBy('product_name');

        if (Auth::user()->usertype === 'staff') {
            $query->where('location', $prodi);
        }
        $assetsinv = $query->get();

        return view('perencanaan.add-perencanaan-inv', compact('assetsinv', 'prodi'));
    }

    public function createBhp()
    {
        $prodi = Auth::user()->prodi;
        $query = Assetlab::where('type', 'bhp')->orderBy('product_name');

        if (Auth::user()->usertype === 'staff') {
            $query->where('location', $prodi);
        }
        $assetbhps = $query->get();
        return view('perencanaan.add-perencanaan', compact('assetbhps', 'prodi'));
    }

    // Store Rencana + Item
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_perencanaan' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'type' => 'required|string|max:255',
            ]);

            $location = $request->location;
            $type = $request->type;
            if (Auth::user()->usertype === 'staff') {
                $location = Auth::user()->prodi;
            }

            // **1. Validasi apakah produk baru sudah ada di AssetLab sebelum transaksi berjalan**
            $newProducts = json_decode($request->new_products, true) ?? [];
            $productCodes = array_map(fn($p) => strtoupper("{$request->initial_code}-{$p['product_code']}"), $newProducts);
            // Cek apakah ada kode produk yang sudah ada di AssetLab
            $existingProductCodes = AssetLab::whereIn('product_code', $productCodes)->pluck('product_code')->toArray();
            if (!empty($existingProductCodes)) {
                // Format pesan error untuk ditampilkan
                $errorMessages = array_map(fn($code) => "Kode produk {$code} sudah ada di Asset Lab!", $existingProductCodes);
                return redirect()->route($type === 'bhp' ? 'add-perencanaan.bhp' : 'add-perencanaan.inv')->withErrors(['product_code' => implode('<br>', $errorMessages)])->withInput();
            }
            return DB::transaction(function () use ($request, $location, $type, $newProducts, $productCodes) {
                // Simpan data perencanaan
                $data = DataPerencanaan::create([
                    'nama_perencanaan' => $request->nama_perencanaan,
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
                    $data->plans()->create([
                        'product_code' => $item['product_code'],
                        'stock' => $item['stock'],
                        'jumlah_kebutuhan' => $item['quantity'],
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

                    $data->plans()->create([
                        'product_code' => $asset->product_code,
                        'stock' => $asset->stock,
                        'jumlah_kebutuhan' => $product['quantity'] ?? 1,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
                return redirect()->route($request->type === 'bhp' ? 'perencanaan-bhp' : 'perencanaan-inv')
                    ->with('success', 'Perencanaan berhasil dibuat.');
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

    // Store Item
    public function storeItem(Request $request, $id)
    {
        try {
            $dataPerencanaan = DataPerencanaan::findOrFail($id);
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

            Perencanaan::create([
                'rencana_id' => $id,
                'product_code' => $product_code_upper,
                'stock' => $request->stock ?? 0,
                'jumlah_kebutuhan' => $request->quantity,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'Product added successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Data perencanaan tidak ditemukan!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan yang tidak terduga.');
        }
    }

    public function edit($id)
    {
        $item = Perencanaan::with(['rencana', 'product'])->where('id', $id)->firstOrFail();
        return view('perencanaan.edit-rencana', compact('item'));
    }

    public function update(Request $request, $id)
    {
        try {
            $rencana = Perencanaan::where('id', $id)->firstOrFail();
            $request->validate([
                'jumlah_kebutuhan' => ['required', 'integer'],
            ]);
            $rencana->update([
                'jumlah_kebutuhan' => $request->jumlah_kebutuhan,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->route('detail-perencanaan', ['id' => $rencana->rencana_id])
                ->with('success', 'Data berhasil diperbarui');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->route('detail-perencanaan', ['id' => $id])
                ->with('error', 'Terjadi kesalahan saat memperbarui data!');
        }
    }

    public function updateDetail(Request $request, $id)
    {
        try {
            $dataPerencanaan = DataPerencanaan::findOrFail($id);
            $dataPerencanaan->update([
                'nama_perencanaan' => $request->nama_perencanaan,
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('detail-perencanaan', ['id' => $id])
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('detail-perencanaan', ['id' => $id])
                ->with('error', 'Gagal memperbarui data!');
        }
    }

    // Destroy Rencana + Item
    public function destroy($id)
    {
        $dataPerencanaan = DataPerencanaan::findOrFail($id);
        $dataPerencanaan->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    // Destroy Item
    public function destroyItem($id)
    {
        $perencanaan = Perencanaan::findOrFail($id);
        $dataPerencanaan = $perencanaan->rencana;
        if ($dataPerencanaan) {
            $dataPerencanaan->updated_at = now();
            $dataPerencanaan->updated_by = Auth::id();
            $dataPerencanaan->save();
        }
        $perencanaan->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function download($id)
    {
        $perencanaan = DataPerencanaan::find($id);

        $type = $perencanaan->type;
        $namaPerencanaan = $perencanaan->nama_perencanaan;
        $prodi = $perencanaan->prodi;

        // Susun nama file yang lebih rapi
        $filename = "Perencanaan_{$type}_{$namaPerencanaan}_{$prodi}.xlsx";

        return Excel::download(new PerencanaanExport($id), $filename);
    }

    public function print($id)
    {
        $perencanaan = DataPerencanaan::findOrFail($id);
        $printDate = Carbon::now()->format('d M Y, H:i');

        $data = Perencanaan::with(['product'])
            ->where('rencana_id', $id)
            ->get();

        return view('print.perencanaan', compact('data', 'perencanaan', 'printDate'));
    }

    public function complete($id)
    {
        try {
            $dataPerencanaan = DataPerencanaan::with('plans.product')->findOrFail($id);

            if ($dataPerencanaan->status === 'selesai') {
                return redirect()->back()->with('error', 'Perencanaan ini sudah selesai.');
            }

            foreach ($dataPerencanaan->plans as $perencanaan) {
                $productDetails = $perencanaan->product;

                if ($productDetails) {
                    $productDetails->stock += $perencanaan->jumlah_kebutuhan;

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
                        "Produk dengan kode {$perencanaan->product_code} tidak ditemukan."
                    );
                }
            }

            $dataPerencanaan->status = 'selesai';
            $perencanaan->updated_by = Auth::id();
            $perencanaan->updated_at = now();
            $dataPerencanaan->save();


            return redirect()->route($dataPerencanaan->type === 'bhp' ? 'perencanaan-bhp' : 'perencanaan-inv')->with('success', 'Perencanaan berhasil diselesaikan.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database saat memperbarui data.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan yang tidak terduga.');
        }
    }
}
