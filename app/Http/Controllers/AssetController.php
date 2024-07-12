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
        return view('dataaset', compact('assetLabs'));
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
        return view('baranghabispakai', compact('assetLabs'));
    }

    public function addInv()
    {
        $prodi = Auth::user()->prodi;
        return view('add-aset', compact('prodi'));
    }

    public function addBhp()
    {
        $prodi = Auth::user()->prodi;
        return view('add-bhp', compact('prodi'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'string', 'unique:assetlabs'],
            'product_name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255']
        ]);

        // Convert product_id to lowercase
        $product_id_lower = strtolower($request->product_id);

        Assetlab::create([
            'product_id' => $product_id_lower,
            'product_name' => $request->product_name,
            'merk' => $request->merk,
            'type' => $request->type,
            'stock' => $request->stock,
            'location' => $request->location,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        if ($request->type == 'bhp') {
            return redirect(route('data-bhp', absolute: false));
        }

        return redirect(route('data-aset', absolute: false));
    }

    public function destroy($id)
    {
        $assetlab = Assetlab::findOrFail($id);
        $type = $assetlab->type;
        $assetlab->delete();

        if ($type === 'bhp') {
            return redirect()->route('data-bhp')->with('success', 'Barang deleted successfully');
        }

        return redirect()->route('data-aset')->with('success', 'Barang deleted successfully');
    }
}
