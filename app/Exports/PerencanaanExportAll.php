<?php

namespace App\Exports;

use App\Models\DataPerencanaan;
use App\Models\Perencanaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PerencanaanExportAll implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $startDate;
    private $endDate;
    private $location;
    private $type;
    private $totalHarga;

    public function __construct($startDate, $endDate, $location, $type)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $this->endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : null;
        $this->location = $location;
        $this->type = $type;
    }
    public function startCell(): string
    {
        return 'A5';
    }
    public function collection()
    {
        $query = DataPerencanaan::with(['plans', 'plans.product'])->where('type', $this->type);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }
        if (Auth::user()->usertype === 'staff') {
            $query->where('prodi', Auth::user()->prodi);
        } else if ($this->location && $this->location !== 'all') {
            $query->where('prodi', $this->location);
        }
        $data = $query->get();

        // Simpan total harga keseluruhan untuk dipakai di `map()`
        $this->totalHarga = $data->flatMap->plans->sum('total_price');

        return $data;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $typeText = match ($this->type) {
                    'bhp' => 'Bahan Habis Pakai',
                    'inventaris' => 'Aset Inventaris',
                    default => ucfirst($this->type), // Jika tidak ada di daftar, gunakan ucfirst
                };
                $locationText = $this->location;
                if ($this->location == 'all') {
                    $locationText = 'Semua';
                }
                // Auto-size untuk semua kolom dari A hingga L
                foreach (range('A', 'M') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $title = "Data Perencanaan {$typeText} Prodi {$locationText}";
                $periode = "Periode: " . ($this->startDate && $this->endDate
                    ? Carbon::parse($this->startDate)->format('d M Y') . " - " . Carbon::parse($this->endDate)->format('d M Y')
                    : "Semua Data");

                // Judul Laporan di Baris 1
                $sheet->mergeCells('A1:M1');
                $sheet->setCellValue('A1', $title);
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Periode di Baris 2
                $sheet->mergeCells('A2:M2');
                $sheet->setCellValue('A2', $periode);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Baris Kosong (Baris 3)
                $sheet->mergeCells('A3:M3');

                // Membuat header bold (Baris 4)
                $sheet->getStyle('A5:M5')->getFont()->setBold(true);
                $sheet->getStyle('A5:M5')->getAlignment()->setHorizontal('center');

                // Menghitung jumlah baris data (total transaksi)
                $lastRow = $sheet->getHighestRow(); // Baris terakhir dengan data
                $sheet->getStyle('K6:K' . $sheet->getHighestRow())->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');
                $sheet->getStyle('M6:M' . $sheet->getHighestRow())->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');
                $sheet->mergeCells("A$lastRow:L$lastRow");
                $sheet->setCellValue("A$lastRow", "Total Harga");
                $sheet->setCellValue("M$lastRow", $this->totalHarga);
                $sheet->getStyle("A$lastRow")->getAlignment()->setHorizontal('right');
                $sheet->getStyle("A$lastRow:L$lastRow")->getFont()->setBold(true);

                // Styling angka total harga
                $sheet->getStyle("M$lastRow")->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');
                // Tambahkan border ke seluruh tabel (A5:L(last row))
                $cellRange = "A5:M$lastRow";
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
    public function headings(): array
    {
        return [
            'Perencanaan',
            'Tanggal',
            'Prodi',
            'Produk Kode',
            'Nama Produk',
            'Keterangan/Formula',
            'Merk',
            'Jenis Produk',
            'Stok',
            'Satuan',
            'Harga Beli',
            'Jumlah Kebutuhan',
            'Sub Total',
        ];
    }

    public function map($perencanaan): array
    {
        $rows = [];
        foreach ($perencanaan->plans as $item) {
            $rows[] = [
                $perencanaan->nama_perencanaan ?: '-',
                $perencanaan->created_at ?: '-',
                $perencanaan->prodi ?: '-',
                $item->product->product_code ?: '-',
                $item->product->product_name ?: '-',
                $item->product->product_detail ?: '-',
                $item->product->merk ?: '-',
                $item->product->product_type ?: '-',
                $perencanaan->stock > 0 ? $perencanaan->stock : '0',
                $item->product->product_unit ?: '-',
                $item->purchase_price ?: '0',
                $item->jumlah_kebutuhan ?: '-',
                $item->total_price ?: '0'
            ];
        }
        return $rows;
    }
}
