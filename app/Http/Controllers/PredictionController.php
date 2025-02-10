<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DataPrediksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PredictionController extends Controller
{
    public function index(Request $request)
    {
        $query = DataPrediksi::with("asset");

        if (Auth::user()->usertype === 'staff') {
            $query->ofLocation(Auth::user()->prodi);
        }
        
        if ($request->has('search')) {
            $query->search($request->search);
        }

        $dataPrediksis = $query->paginate(10);

        return view('prediksi.prediksi', compact("dataPrediksis"));
    }

    public function sendData(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $userid = Auth::id();
        $file = $request->file('csv_file');
        $response = Http::attach(
            'csv_file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        // )->post('http://127.0.0.1:5001/predict', ['user_id' => $userid]);
        // )->post('http://103.186.1.191/predict', ['user_id' => $userid]);
        )->post('https://labsaintek.icu/predict', ['user_id' => $userid]);

        if ($response->successful()) {
            // $headers = [
            //     'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            //     'Content-Disposition' => 'attachment; filename="prediksi.xlsx"',
            // ];

            // return response()->streamDownload(function () use ($response) {
            //     echo $response->body();
            // }, 'prediksi.xlsx', $headers);
            $responseData = $response->json();
            $namaPerencanaan = $responseData['nama_perencanaan'];

            session()->flash('predicted', "Prediksi Perencanaan BHP $namaPerencanaan Berhasil");

            return redirect()->route('prediksi')->with('success', 'Prediksi berhasil diproses dan file telah tersedia.');
        } else {
            return back()->withErrors(['error' => 'Failed to send file']);
        }
    }
}
