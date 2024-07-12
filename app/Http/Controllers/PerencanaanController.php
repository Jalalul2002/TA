<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assetlab;
use App\Models\Perencanaan;
use Illuminate\Http\Request;

class PerencanaanController extends Controller
{
    public function indexInv()
    {
        $perencanaans = Perencanaan::with(['rencana', 'product'])->get();
        return view('perencanaan', compact('perencanaans'));
    }


    public function indexBhp()
    {
        $assetbhps = Assetlab::where('type', 'bhp');
        $perencanaans = Perencanaan::with(['rencana', 'product'])->where('type', 'bhp')->get();
        return view('perencanaan', compact('perencanaans', 'assetbhps'));
    }

    public function show($id)
    {
        $perencanaan = Perencanaan::with(['rencana', 'product'])->findOrFail($id);
        return view('perencanaans.show', compact('perencanaan'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('add-perencanaan');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rencana_id' => 'required|exists:data_perencanaans,id',
            'prodi' => 'required|string|max:255',
            'product_id' => 'required|exists:assetlabs,id',
            'stok' => 'required|integer',
            'jumlah_kebutuhan' => 'required|integer',
            'created_by' => 'required|exists:users,id',
            'updated_by' => 'required|exists:users,id',
        ]);

        Perencanaan::create($request->all());

        return redirect()->route('perencanaans.index')->with('success', 'Perencanaan created successfully.');
    }
}
