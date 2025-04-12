<?php

namespace App\Http\Controllers;

use App\Models\ItemPrices;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ItemPricesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ItemPrices::leftJoin('assetlabs', 'item_prices.product_code', '=', 'assetlabs.product_code')
            ->select(
                'item_prices.*',
                'assetlabs.product_name',
                'assetlabs.product_detail',
                'assetlabs.merk',
                'assetlabs.type',
                'assetlabs.product_type',
                'assetlabs.product_unit',
            );
        $sortField = $request->get('sort_field', 'effective_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedFields = ['product_code', 'product_name', 'product_detail', 'merk', 'product_type', 'price_type', 'product_unit', 'location', 'purchase_price', 'price', 'effective_date'];


        if (Auth::user()->usertype === 'staff') {
            $query->ofLocation(Auth::user()->prodi);
        }
        #filter search
        if ($request->has('search')) {
            $query->search($request->search);
        }
        #filter tipe
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        #filter tipe
        if ($request->has('product_type') && $request->product_type != '') {
            $query->where('product_type', $request->product_type);
        }
        #filterlokasi
        if ($request->has('location') && $request->location != '') {
            $query->where('item_prices.location', $request->location);
        }

        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $data = $query->paginate(10);
        return view('barang.dataharga', compact('data', 'sortField', 'sortOrder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prodi = Auth::user()->prodi;
        return view('barang.add-harga', compact('prodi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_code' => 'required|exists:assetlabs,product_code',
                'price_type' => 'required|in:unit,rental',
                'price' => 'required|numeric|min:0',
                'purchase_price' => 'nullable|numeric|min:0',
                'effective_date' => 'required|date',
            ]);

            ItemPrices::create([
                'product_code' => $request->product_code,
                'price_type' => $request->price_type,
                'purchase_price' => $request->purchase_price,
                'price' => $request->price,
                'effective_date' => $request->effective_date,
                'location' => $request->location,
                'created_by' => Auth::id()
            ]);

            return redirect(route('data-harga', absolute: false))->with('success', 'Produk Harga berhasil ditambahkan.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return redirect()->back()->with('error', 'Kode produk sudah ada!')->withInput();
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan yang tidak terduga.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemPrices $itemPrices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemPrices $itemPrices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemPrices $itemPrices)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemPrices $itemPrices)
    {
        try {
            $itemPrices->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
