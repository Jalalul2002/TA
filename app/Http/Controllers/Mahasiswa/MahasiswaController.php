<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\DataLab;
use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $dataLabs = DataLab::all();
        $dataApprover = User::where('usertype', '!=',  'mahasiswa')->get();
        return view('mahasiswa.create', compact('dataLabs', 'dataApprover'));
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
        dd($request->all());
        DB::beginTransaction();

        try {
            // 1. Buat data pengajuan
            $submission = Pengajuan::create([
                'mahasiswa_id' => Auth::id(),
                'type' => $request->purpose,
                'telp' => $request->telp,
                'email' => $request->email,
                'lab_code' => $request->lab_code,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status_pengajuan' => 'pending',
            ]);

            // 2. Cek apakah ada item detail (misalnya $request->items)
            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    PengajuanDetail::create([
                        'pengajuan_id' => $submission->id,
                        'item_type' => $item['type'],
                        'item_id' => $item['id'],
                        'item_name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'detail' => $item['detail'] ?? null,
                        'hari' => $item['hari'] ?? null,
                        'jam_mulai' => $item['jam_mulai'] ?? null,
                        'jam_selesai' => $item['jam_selesai'] ?? null,
                    ]);
                }
            }

            // 3. Buat approval berdasarkan level dan data approver
            foreach ($request->approver as $approver) {
                Approval::create([
                    'pengajuan_id' => $submission->id,
                    'approver_id' => $approver['id'],
                    'level' => $approver['level'],
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            return redirect()->route('mahasiswa.pengajuan')->with('success', 'Pengajuan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
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
