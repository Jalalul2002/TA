<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DataPrediksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PredictionController extends Controller
{
    public function index()
    {
        $dataPrediksi = DataPrediksi::all();

        return view('prediksi.prediksi', compact($dataPrediksi));
    }

    public function sendData(Request $request)
    {
        // Validasi file yang diupload
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        // Mengirim file CSV ke server Flask
        $file = $request->file('csv_file');
        $response = Http::attach(
            'csv_file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post('http://127.0.0.1:5001/predict');

        // Cek respon dari server Flask
        if ($response->successful()) {
            // $data = $response->json()['data'];
            // return view('prediksi.result', ['data' => $data]);

            // // Menyimpan respons CSV sementara
            // $filename = 'prediksi.csv';
            // Storage::disk('local')->put($filename, $response->body());

            // // Membaca isi CSV dan mengonversinya ke array untuk ditampilkan
            // $csvData = array_map('str_getcsv', explode("\n", $response->body()));
            // $headers = array_shift($csvData);

            // // Menampilkan data pada view result.blade.php dalam bentuk tabel
            // return view('prediksi.result', compact('headers', 'csvData'));
            // $headers = [
            //     'Content-Type' => 'text/csv',
            //     'Content-Disposition' => 'attachment; filename="prediksi.csv"',
            // ];

            // return response()->streamDownload(function () use ($response) {
            //     echo $response->body();
            // }, 'prediksi.csv', $headers);
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="prediksi.xlsx"',
            ];
        
            return response()->streamDownload(function () use ($response) {
                echo $response->body();
            }, 'prediksi.xlsx', $headers);
        } else {
            return back()->withErrors(['error' => 'Failed to send file']);
        }
    }
}
