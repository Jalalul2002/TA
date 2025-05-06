<?php

namespace App\Exports;

use App\Models\DataRealisasi;
use App\Models\Realisasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RealisasiExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $realisasiId, $dataRealisasi;
    public function __construct($realisasiId)
    {
        $this->realisasiId = $realisasiId;
        $this->dataRealisasi = DataRealisasi::where('id', $realisasiId)->first();
    }
    public function startCell(): string
    {
        return 'A5';
    }
    public function collection()
    {
        $data = Realisasi::with(['data', 'product'])
            ->where('realisasi_id', $this->realisasiId)
            ->get();

        // Hitung total harga keseluruhan
        $totalPrice = $data->sum('total_price');

        // Tambahkan data kosong untuk subtotal di baris terakhir
        $totalRow = new Realisasi([
            'product_code' => '',
            'product' => (object) ['product_name' => 'Total Harga Keseluruhan', 'product_detail' => '', 'merk' => '', 'product_type' => '', 'product_unit' => ''],
            'stock' => '',
            'purchase_price' => '',
            'jumlah_kebutuhan' => '',
            'total_price' => $totalPrice
        ]);

        return $data->push($totalRow);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $typeText = match ($this->dataRealisasi->type) {
                    'bhp' => 'Bahan Habis Pakai',
                    'inventaris' => 'Aset Inventaris',
                    default => ucfirst($this->dataRealisasi->type), // Jika tidak ada di daftar, gunakan ucfirst
                };
                // Auto-size untuk semua kolom dari A hingga L
                foreach (range('A', 'J') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $title = "Data Pengadaan {$typeText} Prodi {$this->dataRealisasi->prodi}";
                $subtitle = "Pengadaan: " . ($this->dataRealisasi->name ?? '-') .
                    " | Status: " . ($this->dataRealisasi->status ?? '-') . " | Tanggal: " . ($this->dataRealisasi->created_at->format('d/m/Y') ?? '-');

                // Judul Laporan di Baris 1
                $sheet->mergeCells('A1:J1');
                $sheet->setCellValue('A1', $title);
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Periode di Baris 2
                $sheet->mergeCells('A2:J2');
                $sheet->setCellValue('A2', $subtitle);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Baris Kosong (Baris 3)
                $sheet->mergeCells('A3:J3');

                // Membuat header bold (Baris 4)
                $sheet->getStyle('A5:J5')->getFont()->setBold(true);
                $sheet->getStyle('A5:J5')->getAlignment()->setHorizontal('center');
                
                // Menghitung jumlah baris data (total transaksi)
                $lastRow = $sheet->getHighestRow(); // Baris terakhir dengan data
                $sheet->getStyle('H6:H' . $sheet->getHighestRow())->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');
                $sheet->getStyle('J6:J' . $sheet->getHighestRow())->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');
                // Merge cell dari kolom A-J untuk teks "Total Harga Keseluruhan"
                $sheet->mergeCells("A$lastRow:I$lastRow");
                $sheet->setCellValue("A$lastRow", "Total Harga Keseluruhan");
                $sheet->getStyle("A$lastRow")->getAlignment()->setHorizontal('right');
                $sheet->getStyle("A$lastRow:J$lastRow")->getFont()->setBold(true);

                // Styling angka total harga
                $sheet->getStyle("J$lastRow")->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');

                // Tambahkan border ke seluruh tabel (A5:L(last row))
                $cellRange = "A5:J$lastRow";
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
            'Satuan',
            'Harga Beli',
            'Jumlah',
            'Sub Total',
        ];
    }

    public function map($item): array
    {
        if ($item->product_code === '') {
            return [
                '',
                '', // Teks untuk total harga
                '',
                '',
                '',
                '',
                '',
                '',
                'Total Harga',
                'Rp. ' . number_format($item->total_price, 0, ',', '.') // Format harga di kolom terakhir
            ];
        }

        return [
            $item->product_code,
            optional($item->product)->product_name ?? '-',
            optional($item->product)->product_detail ?: '-',
            optional($item->product)->merk ?: '-',
            optional($item->product)->product_type ?? '-',
            $item->stock > 0 ? $item->stock : '0',
            optional($item->product)->product_unit ?? '-',
            $item->purchase_price ?: '0',
            $item->jumlah_kebutuhan,
            'Rp. ' . number_format($item->total_price, 0, ',', '.')
        ];
    }
}
