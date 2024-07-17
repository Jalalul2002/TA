<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assetlab;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    public function indexInv(Request $request)
    {
        $query = AssetLab::ofType('inventaris');

        if (Auth::user()->usertype === 'staff') {
            $query->ofLocation(Auth::user()->prodi);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $assetLabs = $query->paginate(10);
        return view('barang.dataaset', compact('assetLabs'));
    }

    public function indexBhp(Request $request)
    {
        $query = AssetLab::ofType('bhp');

        if (Auth::user()->usertype === 'staff') {
            $query->ofLocation(Auth::user()->prodi);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $assetLabs = $query->paginate(10);
        return view('barang.baranghabispakai', compact('assetLabs'));
    }

    public function addInv()
    {
        $prodi = Auth::user()->prodi;
        return view('barang.add-aset', compact('prodi'));
    }

    public function addBhp()
    {
        $prodi = Auth::user()->prodi;
        return view('barang.add-bhp', compact('prodi'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_code' => ['required', 'string', 'unique:assetlabs'],
            'product_name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255']
        ]);

        // Convert product_id to lowercase
        $product_capitalyzed = ucwords(strtolower($request->product_name));
        $product_code_lower = strtoupper($request->product_code);
        $location_capitalized = ucwords(strtolower($request->location));

        Assetlab::create([
            'product_code' => $product_code_lower,
            'product_name' => $product_capitalyzed,
            'merk' => $request->merk,
            'type' => $request->type,
            'stock' => $request->stock,
            'location' => $location_capitalized,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        if ($request->type == 'bhp') {
            return redirect(route('data-bhp', absolute: false));
        }

        return redirect(route('data-aset', absolute: false));
    }

    public function destroy($product_code)
    {
        $assetlab = Assetlab::where('product_code', $product_code)->firstOrFail();
        $type = $assetlab->type;
        $assetlab->delete();

        if ($type === 'bhp') {
            return redirect()->route('data-bhp')->with('success', 'Barang deleted successfully');
        }

        return redirect()->route('data-aset')->with('success', 'Barang deleted successfully');
    }
}
