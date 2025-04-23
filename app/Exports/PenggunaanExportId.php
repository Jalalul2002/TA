<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PenggunaanExportId implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $id, $data;
    public function __construct($id)
    {
        $this->id = $id;
        $this->data = Transaction::with('items.asset')->where('id', $id)->first();
    }
    public function startCell(): string
    {
        return 'A8';
    }
    public function collection()
    {
        return $this->data ? collect($this->data->items) : collect([]);
    }
    public function headings(): array
    {
        return [
            'Nama Produk',
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
                foreach (range('A', 'F') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Judul Laporan di Baris 1
                $sheet->mergeCells('A1:G1');
                $sheet->setCellValue('A1', 'LAPORAN DATA PENGGUNAAN BARANG ' . strtoupper($this->data->location ?? ''));
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                $sheet->mergeCells('A2:G2');
                // **Subjudul Informasi Pengguna (Tabel Format)**
                $sheet->getStyle('A3:F6')->getAlignment()->setHorizontal('left');
                $sheet->setCellValue('A3', 'ID:');
                $sheet->setCellValue('B3', $this->data->user_id ?? '-');
                $sheet->setCellValue('F3', 'Tanggal:');
                $sheet->setCellValue('G3', $this->data->created_at ? $this->data->created_at->format('d/m/Y') : '-');

                $sheet->setCellValue('A4', 'Nama:');
                $sheet->setCellValue('B4', $this->data->name ?? '-');
                $sheet->setCellValue('F4', 'Laboran:');
                $sheet->setCellValue('G4', $this->data->creator->name ?? '-');

                $sheet->setCellValue('A5', 'Prodi:');
                $sheet->setCellValue('B5', $this->data->prodi ?? '-');
                $sheet->setCellValue('F4', 'Keterangan:');
                $sheet->setCellValue('G4', $this->data->detail ?? '-');

                $sheet->setCellValue('A6', 'Telepon:');
                $sheet->setCellValue('B6', $this->data->telp ?? '-');

                $sheet->mergeCells('A7:G7');

                // Membuat header bold (Baris 5)
                $sheet->getStyle('A8:G8')->getFont()->setBold(true);
                $sheet->getStyle('A8:G8')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('D6:D' . $sheet->getHighestRow())->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');
                $sheet->getStyle('G6:G' . $sheet->getHighestRow())->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');

                // Menghitung jumlah baris data (total transaksi)
                $lastRow = $sheet->getHighestRow() + 1; // Baris terakhir dengan data
                $sheet->mergeCells("A$lastRow:F$lastRow");
                $sheet->setCellValue("A$lastRow", "Total Harga Keseluruhan");
                $sheet->setCellValue("G$lastRow", $this->data->items->sum('total_price'));
                $sheet->getStyle("A$lastRow")->getAlignment()->setHorizontal('right');
                $sheet->getStyle("A$lastRow:G$lastRow")->getFont()->setBold(true);

                // Tambahkan border ke seluruh tabel (A5:L(last row))
                $cellRange = "A8:G$lastRow";
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
    public function map($item): array
    {
        return [
            $item->asset->product_name ?? '-',
            $item->asset->merk ?? '-',
            $item->asset->product_type ?? '-',
            $item->unit_price > 0 ? $item->unit_price : '0',
            $item->jumlah_pemakaian > 0 ? $item->jumlah_pemakaian : '0',
            $item->asset->product_unit ?? '-',
            $item->total_price > 0 ? $item->total_price : '0',
        ];
    }
}
