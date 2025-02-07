<?php

namespace App\Http\Controllers;

use App\Exports\PerencanaanExport;
use App\Models\Assetlab;
use App\Models\Perencanaan;
use Illuminate\Http\Request;
use App\Models\DataPerencanaan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PerencanaanController extends Controller
{
    public function indexInv(Request $request)
    {
        $query = DataPerencanaan::with('plans.product', 'latestUpdater.updater')->where('type', 'inventaris');

        if (Auth::user()->usertype !== 'admin') {
            $query->where('prodi', Auth::user()->prodi);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $perencanaans = $query->paginate(10);
        return view('perencanaan.perencanaan-aset', compact('perencanaans'));
    }

    public function indexBhp(Request $request)
    {
        $query = DataPerencanaan::with('plans.product', 'latestUpdater.updater')->where('type', 'bhp');

        if (Auth::user()->usertype !== 'admin') {
            $query->where('prodi', Auth::user()->prodi);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $perencanaans = $query->paginate(10);

        return view('perencanaan.perencanaan-bhp', compact('perencanaans'));
    }

    public function show($id, Request $request)
    {
        $dataPerencanaan = DataPerencanaan::with('plans.product')->findOrFail($id);
        $query = $dataPerencanaan->plans()->with('product');

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $products = $query->paginate(10);

        if ($dataPerencanaan->type === 'bhp') {
            $assets = Assetlab::where('type', 'bhp')->get();
        } else {
            $assets = Assetlab::where('type', 'inventaris')->get();
        }

        return view('perencanaan.detail-perencanaan', compact('dataPerencanaan', 'products', 'assets'));
    }

    public function createInv()
    {
        $prodi = Auth::user()->prodi;
        $assetsinv = Assetlab::where('type', 'inventaris')->get();
        return view('perencanaan.add-perencanaan-inv', compact('assetsinv', 'prodi'));
    }

    public function createBhp()
    {
        $prodi = Auth::user()->prodi;
        $assetbhps = Assetlab::where('type', 'bhp')->get();
        return view('perencanaan.add-perencanaan', compact('assetbhps', 'prodi'));
    }

    // Store Rencana + Item
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_perencanaan' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        $data = DataPerencanaan::create([
            'nama_perencanaan' => $request->nama_perencanaan,
            'prodi' => $request->location,
            'type' => $request->type,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $items = $request->items;
        if ($items) {
            $result = [];
            $temp = [];

            foreach ($items as $item) {
                // Check if the item contains a "product_code"
                if (isset($item['product_code'])) {
                    // If $temp is not empty, push it to $result and reset $temp
                    if (!empty($temp)) {
                        $result[] = $temp;
                        $temp = [];
                    }
                    $temp['product_code'] = $item['product_code'];
                } else {
                    // Merge the other attributes into $temp
                    $temp = array_merge($temp, $item);
                }
            }

            // Push the last temp array to result
            if (!empty($temp)) {
                $result[] = $temp;
            }

            foreach ($result as $item) {
                $data->plans()->create([
                    'product_code' => $item['product_code'],
                    'stock' => $item['stock'],
                    'jumlah_kebutuhan' => $item['quantity'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route($request->type === 'bhp' ? 'perencanaan-bhp' : 'perencanaan-inv')
            ->with('success', 'Perencanaan created successfully.');
    }

    // Store Item
    public function storeItem(Request $request, $id)
    {
        Perencanaan::create([
            'rencana_id' => $id,
            'product_code' => $request->product_code,
            'stock' => $request->stock,
            'jumlah_kebutuhan' => $request->quantity,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Product added successfully.');
    }

    public function edit($id)
    {
        $item = Perencanaan::with(['rencana', 'product'])->where('id', $id)->firstOrFail();
        return view('perencanaan.edit-rencana', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $rencana = Perencanaan::where('id', $id)->firstOrFail();

        $request->validate([
            'jumlah_kebutuhan' => ['required', 'integer'],
        ]);

        $rencana->update([
            'jumlah_kebutuhan' => $request->jumlah_kebutuhan,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('detail-perencanaan', ['id' => $rencana->rencana_id])->with('success', 'Data berhasil diperbarui');
    }

    // Destroy Rencana + Item
    public function destroy($id)
    {
        $dataPerencanaan = DataPerencanaan::findOrFail($id);
        $dataPerencanaan->delete();

        return redirect()->back();
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

    public function complete($id)
    {
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
                        "Stok untuk produk {$productDetails->product_name} tidak mencukupi."
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
    }
}
