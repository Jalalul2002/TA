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
        $product_code_upper = strtoupper($request->product_code);
        $product_name_capitalized = ucwords(strtolower($request->product_name));
        $location_capitalized = ucwords(strtolower($request->location));
        $formula_upper = strtoupper($request->formula);
        $merk_capitalized = ucwords(strtolower($request->merk));
        $type_lower = strtolower($request->type);
        $product_type_capitalized = ucwords(strtolower($request->product_type));
        $location_detail_capitalized = ucwords(strtolower($request->location_detail));

        Assetlab::create([
            'product_code' => $product_code_upper,
            'product_name' => $product_name_capitalized,
            'formula' => $formula_upper,
            'merk' => $merk_capitalized,
            'type' => $type_lower,
            'product_type' => $product_type_capitalized,
            'stock' => $request->stock,
            'product_unit' => $request->product_unit,
            'location_detail' => $location_detail_capitalized,
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
