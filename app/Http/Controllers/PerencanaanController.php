<?php

namespace App\Http\Controllers;

use App\Models\Assetlab;
use App\Models\Perencanaan;
use Illuminate\Http\Request;
use App\Models\DataPerencanaan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PerencanaanController extends Controller
{
    public function indexInv()
    {
        $perencanaans = Perencanaan::with(['rencana', 'product'])->get();
        return view('perencanaan.perencanaan-bhp', compact('perencanaans'));
    }

    public function indexBhp()
    {
        $perencanaans = DataPerencanaan::with('plans.product')->where('type', 'bhp')->paginate(10);
        return view('perencanaan.perencanaan-bhp', compact('perencanaans'));
    }

    public function show($id)
    {
        $dataPerencanaan = DataPerencanaan::with('plans.product')->findOrFail($id);
        $products = $dataPerencanaan->plans()->with('product')->paginate(10);
        $assetBhps = Assetlab::where('type', 'bhp')->get();

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

    /**
     * Store a newly created resource in storage.
     */
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
                'stok' => $item['stock'],
                'jumlah_kebutuhan' => $item['quantity'],
            ]);
        }

        return redirect()->route('perencanaan-bhp')->with('success', 'Perencanaan created successfully.');
    }

    public function destroy($id)
    {
        $dataPerencanaan = DataPerencanaan::findOrFail($id);
        $dataPerencanaan->delete();

        return redirect()->back();
    }

    public function destroyItem($id)
    {
        $perencanaan = Perencanaan::findOrFail($id);
        $perencanaan->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
