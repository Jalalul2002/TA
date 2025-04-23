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

class PeminjamanExportId implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
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
        return $this->data ? collect($this->data->loans) : collect([]);
    }
    public function headings(): array
    {
        return [
            'Produk',
            'Detail',
            'Merk',
            'Harga',
            'Banyaknya',
            'Durasi',
            'Sub Total',
            'Dikembalikan',
            'Rusak',
            'Status',
            'Tanggal Kembali',
            'Laboran',
            'Catatan'
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
                $sheet->setCellValue('A1', 'LAPORAN DATA PEMINJAMAN BARANG ' . strtoupper($this->data->location ?? ''));
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                $sheet->mergeCells('A2:M2');
                // **Subjudul Informasi Pengguna (Tabel Format)**
                $sheet->getStyle('A3:M6')->getAlignment()->setHorizontal('left');
                $sheet->setCellValue('A3', 'ID:');
                $sheet->setCellValue('B3', $this->data->user_id ?? '-');
                $sheet->setCellValue('L3', 'Tanggal:');
                $sheet->setCellValue('M3', $this->data->created_at ? $this->data->created_at->format('d/m/Y') : '-');

                $sheet->setCellValue('A4', 'Nama:');
                $sheet->setCellValue('B4', $this->data->name ?? '-');
                $sheet->setCellValue('L4', 'Laboran:');
                $sheet->setCellValue('M4', $this->data->creator->name ?? '-');

                $sheet->setCellValue('A5', 'Prodi:');
                $sheet->setCellValue('B5', $this->data->prodi ?? '-');
                $sheet->setCellValue('L4', 'Keterangan:');
                $sheet->setCellValue('M4', $this->data->detail ?? '-');

                $sheet->setCellValue('A6', 'Telepon:');
                $sheet->setCellValue('B6', $this->data->telp ?? '-');

                $sheet->mergeCells('A7:M7');

                // Membuat header bold (Baris 5)
                $sheet->getStyle('A8:M8')->getFont()->setBold(true);
                $sheet->getStyle('A8:M8')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('D6:D' . $sheet->getHighestRow())->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');
                $sheet->getStyle('G6:G' . $sheet->getHighestRow())->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');
                // Menghitung jumlah baris data (total transaksi)
                $lastRow = $sheet->getHighestRow() + 1; // Baris terakhir dengan data
                $sheet->mergeCells("A$lastRow:F$lastRow");
                $sheet->mergeCells("G$lastRow:M$lastRow");
                $sheet->setCellValue("A$lastRow", "Total Harga Keseluruhan");
                $sheet->setCellValue("G$lastRow", $this->data->loans->sum('total_price'));
                $sheet->getStyle("G$lastRow")->getNumberFormat()
                    ->setFormatCode('"Rp"#,##0');
                $sheet->getStyle("A$lastRow")->getAlignment()->setHorizontal('right');
                $sheet->getStyle("A$lastRow:G$lastRow")->getFont()->setBold(true);

                // Tambahkan border ke seluruh tabel (A5:L(last row))
                $cellRange = "A8:M$lastRow";
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
            $item->asset->product_name ?: '-',
            $item->asset->product_detail ?: '-',
            $item->asset->merk ?: '-',
            $item->rental_price > 0 ? $item->rental_price : '0',
            $item->quantity ? (int)$item->quantity . ' ' . $item->asset->product_unit : '0',
            $item->asset->latestPrice->price_type == 'unit' ? '-' : rtrim(rtrim(number_format( $item->rental, 4, ',', ''), '0'), ',') . " Jam",
            $item->total_price > 0 ? $item->total_price : '0',
            ($item->quantity > 0 ? (int)$item->quantity : '0') . ' / ' . ($item->returned_quantity > 0 ? (int)$item->returned_quantity : '0'),
            (int)$item->damaged_quantity,
            $item->status,
            $item->return_date ?: '-',
            $item->status != 'dipinjam' ? $item->updater->name : '-',
            $item->return_notes ?: ($item->notes ?: '-')
        ];
    }
}
