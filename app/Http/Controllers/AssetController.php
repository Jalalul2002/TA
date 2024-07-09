<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assetlab;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function indexInv(Request $request)
    {
        $query = AssetLab::with('product')->where('type', 'inventaris');

        if (auth()->user()->usertype === 'staff') {
            $query->whereHas('product', function ($q) {
                $q->where('location', auth()->user()->prodi);
            });
        }

        if ($request->has('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('product_name', 'LIKE', "%{$request->search}%");
            });
        }

        $assetLabs = $query->paginate(10);
        return view('dataaset', compact('assetLabs'));
    }

    public function indexBhp()
    {
        $assetLabs = AssetLab::all();
        return view('baranghabispakai', compact('assetLabs'));
    }

    // public function create()
    // {
    //     return view('create-dataaset');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|string',
            'stock' => 'required|string',
            'location' => 'required|string',
            'created_by' => 'required|exists:users,id',
            'updated_by' => 'required|exists:users,id'
        ]);

        $assetLab = new AssetLab($request->all());
        $assetLab->updated_by = auth()->user()->id;
        $assetLab->save();

        return redirect()->route('data-aset')->with('success', 'AssetLab created successfully.');
    }

    public function show(AssetLab $assetLab)
    {
        return view('dataaset', compact('assetLab'));
    }

    public function edit(AssetLab $assetLab)
    {
        return view('dataaset', compact('assetLab'));
    }

    public function update(Request $request, AssetLab $assetLab)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|string',
            'stock' => 'required|string',
            'location' => 'required|string',
            'created_by' => 'required|exists:users,id',
            'updated_by' => 'required|exists:users,id'
        ]);

        $assetLab->fill($request->all());
        $assetLab->updated_by = auth()->user()->id;
        $assetLab->save();

        return redirect()->route('data-aset')->with('success', 'AssetLab updated successfully.');
    }

    public function destroy(AssetLab $assetLab)
    {
        $assetLab->delete();

        return redirect()->route('data-aset')->with('success', 'AssetLab deleted successfully.');
    }
}
