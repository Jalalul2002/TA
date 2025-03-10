<?php

namespace App\Exports;

use App\Models\DataPerencanaan;
use App\Models\Perencanaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PerencanaanExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $perencanaanId;
    private $dataPerencanaan;

    public function __construct($perencanaanId)
    {
        $this->perencanaanId = $perencanaanId;
        $this->dataPerencanaan = DataPerencanaan::where('id', $perencanaanId)->first();
    }
    public function startCell(): string
    {
        return 'A5';
    }
    public function collection()
    {
        return Perencanaan::with(['rencana', 'product'])
            ->where('rencana_id', $this->perencanaanId)
            ->get();
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $typeText = match ($this->dataPerencanaan->type) {
                    'bhp' => 'Bahan Habis Pakai',
                    'inventaris' => 'Aset Inventaris',
                    default => ucfirst($this->dataPerencanaan->type), // Jika tidak ada di daftar, gunakan ucfirst
                };
                // Auto-size untuk semua kolom dari A hingga L
                foreach (range('A', 'H') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $title = "Data Perencanaan {$typeText} Prodi {$this->dataPerencanaan->prodi}";
                $subtitle = "Perencanaan: " . ($this->dataPerencanaan->nama_perencanaan ?? '-') .
                    " | Status: " . ($this->dataPerencanaan->status ?? '-');

                // Judul Laporan di Baris 1
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', $title);
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Periode di Baris 2
                $sheet->mergeCells('A2:H2');
                $sheet->setCellValue('A2', $subtitle);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Baris Kosong (Baris 3)
                $sheet->mergeCells('A3:H3');

                // Membuat header bold (Baris 4)
                $sheet->getStyle('A5:H5')->getFont()->setBold(true);
                $sheet->getStyle('A5:H5')->getAlignment()->setHorizontal('center');

                // Menghitung jumlah baris data (total transaksi)
                $lastRow = $sheet->getHighestRow(); // Baris terakhir dengan data

                // Tambahkan border ke seluruh tabel (A5:L(last row))
                $cellRange = "A5:H$lastRow";
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
            'Produk Kode',
            'Nama Produk',
            'Keterangan/Formula',
            'Merk',
            'Jenis Produk',
            'Stok',
            'Jumlah Kebutuhan',
            'Satuan',
        ];
    }

    public function map($perencanaan): array
    {
        return [
            $perencanaan->product_code,
            $perencanaan->product->product_name ?? '-',
            $perencanaan->product->product_detail !== null && $perencanaan->product->product_detail !== '' ? $perencanaan->product->product_detail : '-',
            $perencanaan->product->merk !== null && $perencanaan->product->merk !== '' ? $perencanaan->product->merk : '-',
            $perencanaan->product->product_type ?? '-',
            $perencanaan->stock > 0 ? $perencanaan->stock : '0',
            $perencanaan->jumlah_kebutuhan,
            $perencanaan->product->product_unit ?? '-',
        ];
    }
}
