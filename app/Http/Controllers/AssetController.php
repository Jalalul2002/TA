<?php

namespace App\Http\Controllers;

use App\Exports\AssetBhpExport;
use App\Exports\AssetInvExport;
use App\Http\Controllers\Controller;
use App\Models\Assetlab;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AssetController extends Controller
{
    public function indexInv(Request $request)
    {
        $productType = request('product_type');
        $location = request('location');
        $query = AssetLab::ofType('inventaris');
        #filter user
        if (Auth::user()->usertype === 'staff') {
            $query->ofLocation(Auth::user()->prodi);
        }
        #filter search
        if ($request->has('search')) {
            $query->search($request->search);
        }
        #filter tipe
        if ($request->has('product_type') && $request->product_type != '') {
            $query->where('product_type', $request->product_type);
        }
        #filterlokasi
        if ($request->has('location') && $request->location != '') {
            $query->where('location', $request->location);
        }

        #Sorting
        $sortField = $request->get('sort_field', 'product_name');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedFields = ['product_code', 'product_name', 'merk', 'product_type', 'location'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $assetLabs = $query->paginate(10);
        return view('barang.dataaset', compact('assetLabs', 'sortField', 'sortOrder'));
    }

    public function indexBhp(Request $request)
    {
        $productType = request('product_type');
        $location = request('location');
        $query = AssetLab::ofType('bhp');
        #filter user
        if (Auth::user()->usertype === 'staff') {
            $query->ofLocation(Auth::user()->prodi);
        }
        #filter search
        if ($request->has('search')) {
            $query->search($request->search);
        }
        #filter tipe
        if ($request->has('product_type') && $request->product_type != '') {
            $query->where('product_type', $request->product_type);
        }
        #filterlokasi
        if ($request->has('location') && $request->location != '') {
            $query->where('location', $request->location);
        }

        #Sorting
        $sortField = $request->get('sort_field', 'product_name');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedFields = ['product_code', 'product_name', 'merk', 'product_type', 'location'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $assetLabs = $query->paginate(10);
        return view('barang.baranghabispakai', compact('assetLabs', 'sortField', 'sortOrder'));
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

    public function exportInv(Request $request)
    {
        $productType = $request->input('product_type');
        $location = $request->input('location');

        return Excel::download(new AssetInvExport($productType, $location), 'data_Inventaris.xlsx');
    }

    public function exportBhp(Request $request)
    {
        $productType = $request->input('product_type');
        $location = $request->input('location');

        return Excel::download(new AssetBhpExport($productType, $location), 'data_bhp.xlsx');
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_code' => ['required', 'string', 'unique:assetlabs'],
            'product_name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255']
        ]);

        try {
            // Convert product_id to lowercase
            $product_code_upper = strtoupper($request->product_code);
            $product_name_capitalized = ucwords(strtolower($request->product_name));
            $location_capitalized = ucwords(strtolower($request->location));
            $merk_capitalized = ucwords(strtolower($request->merk));
            $type_lower = strtolower($request->type);
            $product_type_capitalized = ucwords(strtolower($request->product_type));
            $location_detail_capitalized = ucwords(strtolower($request->location_detail));

            Assetlab::create([
                'product_code' => "{$request->initial_code}-{$product_code_upper}",
                'product_name' => $product_name_capitalized,
                'product_detail' => $request->product_detail,
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
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) { // Error kode 1062 untuk duplicate entry
                return redirect()->back()->with('duplicate_error', 'Kode produk sudah ada!');
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database.');
        }
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

    public function edit($product_code)
    {
        $assetLab = Assetlab::where('product_code', $product_code)->firstOrFail();
        return view('barang.edit-asset', compact('assetLab'));
    }

    public function update(Request $request, $product_code)
    {
        $assetLab = Assetlab::where('product_code', $product_code)->firstOrFail();

        $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'product_detail' => ['nullable', 'string', 'max:255'],
            'merk' => ['nullable', 'string', 'max:255'],
            'product_type' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer'],
            'product_unit' => ['required', 'string', 'max:255'],
            'location_detail' => ['nullable', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $assetLab->update([
            'product_name' => ucwords(strtolower($request->product_name)),
            'product_detail' => $request->product_detail,
            'merk' => ucwords(strtolower($request->merk)),
            'product_type' => ucwords(strtolower($request->product_type)),
            'stock' => $request->stock,
            'product_unit' => $request->product_unit,
            'location_detail' => ucwords(strtolower($request->location_detail)),
            'location' => ucwords(strtolower($request->location)),
            'updated_by' => Auth::id(),
        ]);

        $assetLab->refresh();

        if (strtolower($assetLab->type) === 'bhp') {
            return redirect()->route('data-bhp')->with('success', 'Data berhasil diperbarui');
        } else {
            return redirect()->route('data-aset')->with('success', 'Data berhasil diperbarui');
        }
    }
}
