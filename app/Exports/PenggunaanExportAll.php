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
    protected $startDate, $endDate, $location, $user_id, $purpose, $totalHarga;

    public function __construct($startDate, $endDate, $location, $user_id, $purpose)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $this->endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : null;
        $this->location = $location;
        $this->user_id = $user_id;
        $this->purpose = $purpose;
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
        if ($this->purpose && $this->purpose != 'semua') {
            $query->where('purpose', $this->purpose);
        }
        if (Auth::user()->usertype === 'staff') {
            $query->where('location', Auth::user()->prodi);
        } else if ($this->location && $this->location != 'all') {
            $query->where('location', $this->location);
        }

        $data = $query->get();

        $this->totalHarga = $data->flatMap->items->sum('total_price');

        return $data;
    }
    public function headings(): array
    {
        return [
            'Tanggal',
            'Laboran',
            'ID',
            'Nama',
            'Prodi',
            'Telepon',
            'Produk',
            'Merk',
            'Jenis',
            'Harga',
            'Jumlah',
            'Satuan',
            'Sub Total',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Auto-size untuk semua kolom dari A hingga L
                foreach (range('A', 'M') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Judul Laporan di Baris 1
                $sheet->mergeCells('A1:M1');
                $sheet->setCellValue('A1', 'LAPORAN DATA PENGGUNAAN BARANG');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Periode di Baris 2
                $periode = "Periode: " . ($this->startDate && $this->endDate
                    ? Carbon::parse($this->startDate)->format('d M Y') . " - " . Carbon::parse($this->endDate)->format('d M Y')
                    : "Semua Data");
                $lokasi = "Lokasi: " . ($this->location ?: "Semua Data");
                $purpose = "Keperluan: " . ($this->purpose ?: "Semua Data");
                $sheet->mergeCells('A2:M2');
                $sheet->setCellValue('A2', "$lokasi | $purpose | $periode");
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Baris Kosong (Baris 3)
                $sheet->mergeCells('A3:M3');

                // Membuat header bold (Baris 4)
                $sheet->getStyle('A5:M5')->getFont()->setBold(true);
                $sheet->getStyle('A5:M5')->getAlignment()->setHorizontal('center');

                // style
                $sheet->getStyle('J6:J' . $sheet->getHighestRow())->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');
                $sheet->getStyle('M6:M' . $sheet->getHighestRow())->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');

                // Menghitung jumlah baris data (total transaksi)
                $lastRow = $sheet->getHighestRow(); // Baris terakhir dengan data
                $totalRow = $lastRow + 1;
                $sheet->mergeCells("A$totalRow:L$totalRow");
                $sheet->setCellValue("A$totalRow", "Total Harga");
                $sheet->setCellValue("M$totalRow", $this->totalHarga);
                $sheet->getStyle("A$totalRow")->getAlignment()->setHorizontal('right');
                $sheet->getStyle("A$totalRow:L$totalRow")->getFont()->setBold(true);
                $sheet->getStyle("M$totalRow")->getNumberFormat()
                ->setFormatCode('[$Rp-421] #,##0');

                $cellRange = "A5:M$totalRow";
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
                $transaction->created_at ? $transaction->created_at->format('d/m/Y') : '-',
                $transaction->creator->name ?: '-',
                $transaction->user_id ?: '-',
                $transaction->name ?: '-',
                $transaction->prodi ?: '-',
                $transaction->telp ?: '-',
                $item->asset->product_name ?: '-',
                $item->asset->merk ?: '-',
                $item->asset->product_type ?: '-',
                $item->unit_price > 0 ? $item->unit_price : '0',
                $item->jumlah_pemakaian > 0 ? $item->jumlah_pemakaian : '0',
                $item->asset->product_unit ?: '-',
                $item->total_price > 0 ? $item->total_price : '0',
            ];
        }
        return $rows;
    }
}
