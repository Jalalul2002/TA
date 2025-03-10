<?php

namespace App\Exports;

use App\Models\Assetlab;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AssetBhpExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $productType;
    protected $location;

    public function __construct($productType, $location)
    {
        $this->productType = $productType;
        $this->location = $location;
    }
    public function startCell(): string
    {
        return 'A5';
    }
    public function collection()
    {
        $query = AssetLab::ofType('bhp')->with(['creator', 'updater']);

        if ($this->productType) {
            $query->where('product_type', $this->productType);
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
            'Kode Barang',
            'Nama Barang',
            'Keterangan/Formula',
            'Merk',
            'Tipe',
            'Jenis',
            'Stok',
            'Satuan',
            'Lokasi Penyimpanan',
            'Lokasi',
            'Tanggal Diupdate',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Auto-size untuk semua kolom dari A hingga L
                foreach (range('A', 'K') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Judul Laporan di Baris 1
                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', 'LAPORAN DATA BAHAN HABIS PAKAI');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Periode di Baris 2
                $prodi = "Lokasi/Prodi: " . ($this->location ?: "Semua Data");
                $jenis = " | Tipe Produk: " . ($this->productType ?: "Semua Jenis");
                $sheet->mergeCells('A2:K2');
                $sheet->setCellValue('A2', $prodi . $jenis);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Baris Kosong (Baris 3)
                $sheet->mergeCells('A3:K3');

                // Membuat header bold (Baris 4)
                $sheet->getStyle('A5:K5')->getFont()->setBold(true);
                $sheet->getStyle('A5:K5')->getAlignment()->setHorizontal('center');

                // Menghitung jumlah baris data (total transaksi)
                $lastRow = $sheet->getHighestRow(); // Baris terakhir dengan data

                // Tambahkan border ke seluruh tabel (A5:L(last row))
                $cellRange = "A5:K$lastRow";
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
    public function map($row): array
    {
        return [
            $row->product_code,
            $row->product_name,
            $row->product_detail,
            $row->merk,
            $row->type,
            $row->product_type,
            $row->stock > 0 ? $row->stock : '0',
            $row->product_unit,
            $row->location_detail,
            $row->location,
            optional($row->updated_at)->format('Y-m-d H:i') ?? 'N/A', // Format tanggal
        ];
    }
}
