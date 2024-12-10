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
    public function indexInv()
    {
        $perencanaans = Perencanaan::with(['rencana', 'product'])->get();
        return view('perencanaan.perencanaan-bhp', compact('perencanaans'));
    }

    public function indexBhp()
    {
        $query = DataPerencanaan::with('plans.product', 'latestUpdater.updater')->where('type', 'bhp');

        if (Auth::user()->usertype !== 'admin') {
            $query->where('prodi', Auth::user()->prodi);
        }

        $perencanaans = $query->paginate(10);

        return view('perencanaan.perencanaan-bhp', compact('perencanaans'));
    }

    public function show($id, Request $request)
    {
        $assetBhps = Assetlab::where('type', 'bhp')->get();
        $dataPerencanaan = DataPerencanaan::with('plans.product')->findOrFail($id);
        $query = $dataPerencanaan->plans()->with('product');

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $products = $query->paginate(10);

        return view('perencanaan.detail-perencanaan', compact('dataPerencanaan', 'products', 'assetBhps'));
    }
    /**
     * Show the form for creating a new resource.
     */
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

        return redirect()->route('perencanaan-bhp')->with('success', 'Perencanaan created successfully.');
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

        return redirect()->route('detail-perencanaan-bhp', ['id' => $rencana->rencana_id])->with('success', 'Data berhasil diperbarui');
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
        $perencanaan->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function download($id)
    {
        $perencanaan = Perencanaan::findOrFail($id);

        $filename = 'Perencanaan_' . $perencanaan->nama_perencanaan . '.xlsx';

        return Excel::download(new PerencanaanExport($id), $filename);
    }
}
