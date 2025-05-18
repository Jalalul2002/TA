<?php

namespace App\Http\Controllers;

use App\Models\DataLab;
use Illuminate\Http\Request;

class DataLabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DataLab::query();

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('location') && $request->location != 'all') {
            $query->where('prodi', $request->location);
        }
        $datas = $query->paginate(10);
        $locations = ['all' => 'Semua',  'Matematika' => 'Matematika', 'Biologi' => 'Biologi', 'Fisika' => 'Fisika', 'Kimia' => 'Kimia', 'Teknik Informatika' => 'Teknik Informatika', 'Agroteknologi' => 'Agroteknologi', 'Teknik Elektro' => 'Teknik Elektro'];
        return view('lab.index', compact('datas', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lab.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lab_code' => 'required',
            'name' => 'required',
        ]);

        // Check if lab_code already exists
        $existingLab = DataLab::where('lab_code', $request->lab_code)->first();
        if ($existingLab) {
            return redirect()->back()->with('error', 'Lab code already exists.');
        }

        $data = new DataLab();
        $data->lab_code = $request->lab_code;
        $data->name = $request->name;
        $data->prodi = $request->prodi;
        $data->capacity = $request->capacity;

        $data->save();

        return redirect()->route('lab.index')->with('success', 'Data Lab created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DataLab $dataLab)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataLab $dataLab)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataLab $dataLab)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataLab $dataLab)
    {
        $dataLab->delete();
        return redirect()->route('lab.index')->with('success', 'Data Lab deleted successfully.');
    }
}
