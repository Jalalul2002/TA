<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $totalPengajuan = Pengajuan::where('mahasiswa_id', $user->username)->count();

        return view('mahasiswa.index', compact('totalPengajuan'));
    }

    public function pengajuan()
    {
        $datas = Pengajuan::paginate(10);
        return view('mahasiswa.pengajuan', compact('datas'));
    }



    public function create()
    {
        return view('mahasiswa.create');
    }

    public function show($id)
    {
        return view('mahasiswa.show', compact('id'));
    }

    public function edit($id)
    {
        return view('mahasiswa.edit', compact('id'));
    }
    public function store(Request $request)
    {
        // Logic to store mahasiswa data
        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa created successfully.');
    }
    public function update(Request $request, $id)
    {
        // Logic to update mahasiswa data
        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa updated successfully.');
    }
    public function destroy($id)
    {
        // Logic to delete mahasiswa data
        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa deleted successfully.');
    }
}
