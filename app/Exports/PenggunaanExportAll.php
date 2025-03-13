<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PenggunaanExportAll implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $startDate;
    protected $endDate;
    protected $location;
    protected $user_id;

    public function __construct($startDate, $endDate, $location, $user_id)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $this->endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : null;
        $this->location = $location;
        $this->user_id = $user_id;
    }
    // Menentukan mulai data dari baris 5 (agar ada space untuk judul)
    public function startCell(): string
    {
        return 'A5';
    }
    public function collection()
    {
        $query = Transaction::with(['items.asset', 'creator', 'updater'])->where('type', 'bhp');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }
        if ($this->user_id && $this->user_id != 'semua') {
            $query->where('user_id', $this->user_id);
        }
        if (Auth::user()->usertype === 'staff') {
            $query->where('location', Auth::user()->prodi);
        } else if ($this->location) {
            $query->where('location', $this->location);
        }
        return $query->get();
    }
    public function headings(): array
    {
        return [
            'ID Pengguna',
            'Nama Pengguna',
            'Prodi',
            'Telepon',
            'Nama Produk',
            'Merk',
            'Jenis',
            'Satuan',
            'Jumlah',
            'Keterangan',
            'Pembuat/Laboran',
            'Tanggal'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Auto-size untuk semua kolom dari A hingga L
                foreach (range('A', 'L') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Judul Laporan di Baris 1
                $sheet->mergeCells('A1:L1');
                $sheet->setCellValue('A1', 'LAPORAN DATA PENGGUNAAN BARANG');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Periode di Baris 2
                $periode = "Periode: " . ($this->startDate && $this->endDate
                    ? Carbon::parse($this->startDate)->format('d M Y') . " - " . Carbon::parse($this->endDate)->format('d M Y')
                    : "Semua Data");
                $lokasi = "Lokasi: " . ($this->location ?: "Semua Data");
                $sheet->mergeCells('A2:L2');
                $sheet->setCellValue('A2', "$lokasi | $periode");
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Baris Kosong (Baris 3)
                $sheet->mergeCells('A3:L3');

                // Membuat header bold (Baris 4)
                $sheet->getStyle('A5:L5')->getFont()->setBold(true);
                $sheet->getStyle('A5:L5')->getAlignment()->setHorizontal('center');

                // Menghitung jumlah baris data (total transaksi)
                $lastRow = $sheet->getHighestRow(); // Baris terakhir dengan data

                // Tambahkan border ke seluruh tabel (A5:L(last row))
                $cellRange = "A5:L$lastRow";
                $sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Warna hitam
                        ],
                    ],
                ]);
            }
        ];
    }
    public function map($transaction): array
    {
        $rows = [];
        foreach ($transaction->items as $item) {
            $rows[] = [
                $transaction->user_id ?? '-',
                $transaction->name ?? '-',
                $transaction->prodi ?? '-',
                $transaction->telp ?? '-',
                $item->asset->product_name ?? '-',
                $item->asset->merk ?? '-',
                $item->asset->product_type ?? '-',
                $item->asset->product_unit ?? '-',
                $item->jumlah_pemakaian ?? '-',
                $transaction->detail ?? '-',
                $transaction->creator->name ?? '-',
                $transaction->created_at ? $transaction->created_at->format('d/m/Y') : '-',
            ];
        }
        return $rows;
    }
}
