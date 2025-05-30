<?php

namespace App\Http\Controllers;

use App\Exports\AssetBhpExport;
use App\Exports\AssetInvExport;
use App\Http\Controllers\Controller;
use App\Models\Assetlab;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class AssetController extends Controller
{
    public function getAssets(Request $request)
    {
        $query = Assetlab::with('latestPrice', 'latestPlans', 'latestRealisasi');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('location')) {
            $query->where('location', $request->location);
        }

        if ($request->has('stock_filter') && $request->stock_filter == 1) {
            $query->where('stock', '>', 0);
        }

        $assets = $query->orderBy('product_name')->get();
        return response()->json($assets);
    }

    public function indexInv(Request $request)
    {
        $productType = request('product_type');
        $query = AssetLab::with(['updater', 'latestPrice'])->ofType('inventaris');
        $sortField = $request->get('sort_field', 'product_name');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedFields = ['product_code', 'product_name', 'product_detail', 'merk', 'product_type', 'stock', 'product_unit', 'location_detail', 'location'];


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

        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $assetLabs = $query->paginate(10);
        return view('barang.dataaset', compact('assetLabs', 'sortField', 'sortOrder'));
    }

    public function indexBhp(Request $request)
    {
        $authUser = Auth::user();
        $productType = request('product_type');
        $query = AssetLab::with(['updater', 'latestPrice'])->ofType('bhp');
        #filter user
        if ($authUser->usertype === 'staff') {
            $query->ofLocation($authUser->prodi);
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
        $allowedFields = ['product_code', 'product_name', 'product_detail', 'merk', 'product_type', 'stock', 'product_unit', 'location_detail', 'location'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $assetLabs = $query->paginate(10);
        return view('barang.dataaset', compact('assetLabs', 'sortField', 'sortOrder'));
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

        if (Auth::user()->usertype === 'staff') {
            $location = Auth::user()->prodi;
        }

        $filename = "Data_Inventaris";

        if ($productType) {
            $filename .= "_{$productType}";
        }

        if ($location) {
            $filename .= "_{$location}";
        }

        $filename .= ".xlsx";

        return Excel::download(new AssetInvExport($productType, $location), $filename);
    }

    public function exportBhp(Request $request)
    {
        $productType = $request->input('product_type');
        $location = $request->input('location');
        if (Auth::user()->usertype === 'staff') {
            $location = Auth::user()->prodi;
        }

        $filename = "Data_BHP";

        if ($productType) {
            $filename .= "_{$productType}";
        }

        if ($location) {
            $filename .= "_{$location}";
        }

        $filename .= ".xlsx";


        return Excel::download(new AssetBhpExport($productType, $location), $filename);
    }

    public function printBhp(Request $request)
    {
        $productType = $request->input('product_type');
        $location = $request->input('location');
        $printDate = Carbon::now()->format('d M Y, H:i');

        if (Auth::user()->usertype === 'staff') {
            $location = Auth::user()->prodi;
        }

        $query = AssetLab::with('latestPrice')->ofType('bhp');

        if ($productType) {
            $query->where('product_type', $productType);
        }
        if ($location) {
            $query->where('location', $location);
        }

        $data = $query->get();

        return view('print.aset', compact('data', 'productType', 'location', 'printDate'));
    }

    public function printInv(Request $request)
    {
        $productType = $request->input('product_type');
        $location = $request->input('location');
        $printDate = Carbon::now()->format('d M Y, H:i');

        if (Auth::user()->usertype === 'staff') {
            $location = Auth::user()->prodi;
        }

        $query = AssetLab::with('latestPrice')->ofType('inventaris');

        if ($productType) {
            $query->where('product_type', $productType);
        }
        if ($location) {
            $query->where('location', $location);
        }

        $data = $query->get();

        return view('print.aset', compact('data', 'productType', 'location', 'printDate'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
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

            return redirect($request->type == 'bhp' ? route('data-bhp', absolute: false) : route('data-aset', absolute: false))->with('success', 'Produk berhasil ditambahkan.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return redirect()->back()->with('error', 'Kode produk sudah ada!')->withInput();
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan yang tidak terduga.');
        }
    }

    public function destroy($product_code)
    {
        $assetlab = Assetlab::where('product_code', $product_code)->firstOrFail();
        $type = $assetlab->type;
        $assetlab->delete();

        return redirect($type == 'bhp' ? route('data-bhp', absolute: false) : route('data-aset', absolute: false))->with('success', 'Barang deleted successfully');
    }

    public function edit($product_code)
    {
        $assetLab = Assetlab::where('product_code', $product_code)->firstOrFail();
        return view('barang.edit-asset', compact('assetLab'));
    }

    public function update(Request $request, $product_code)
    {
        try {
            $assetLab = Assetlab::where('product_code', $product_code)->firstOrFail();
            $request->validate([
                'product_name' => ['required', 'string', 'max:255'],
                'product_detail' => ['nullable', 'string', 'max:255'],
                'merk' => ['nullable', 'string', 'max:255'],
                'product_type' => ['required', 'string', 'max:255'],
                'stock' => ['required'],
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

            return redirect()->route(strtolower($assetLab->type) === 'bhp' ? 'data-bhp' : 'data-aset')
                ->with('success', 'Data berhasil diperbarui');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }
}
