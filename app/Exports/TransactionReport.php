<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TransactionReport implements FromCollection, WithCustomStartCell, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $startDate, $endDate, $location, $user_id, $purpose, $loans, $items;

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
        $query = Transaction::with(['loans.asset', 'items.asset', 'creator', 'updater']);

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

        $this->loans = $data->flatMap->loans;
        $this->items = $data->flatMap->items;

        return collect();
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Judul
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', 'Laporan Data Transaksi');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // === TABEL ===
                $startRow = 3;
                $sheet->mergeCells('A3:E3');
                $sheet->setCellValue("A$startRow", 'Bahan Habis Pakai');
                $sheet->getStyle("A$startRow")->getFont()->setBold(true);
                $startRow++;

                $headers1 = ['No', 'Tanggal', 'Produk', 'Harga', 'Jumlah', 'Satuan', 'Sub Total'];
                $col = 'A';
                foreach ($headers1 as $header) {
                    $sheet->setCellValue("$col$startRow", $header);
                    $sheet->getStyle("$col$startRow")->getFont()->setBold(true);
                    $col++;
                }

                $row = $startRow + 1;
                $no = 1;
                foreach ($this->items as $item) {
                    $sheet->setCellValue("A$row", $no++);
                    $sheet->setCellValue("B$row", optional($item->created_at)->format('d/m/Y'));
                    $sheet->setCellValue("C$row", $item->asset->product_name);
                    $sheet->setCellValue("D$row", $item->unit_price);
                    $sheet->setCellValue("E$row", $item->formatted_quantity);
                    $sheet->setCellValue("F$row", $item->asset->product_unit);
                    $sheet->setCellValue("G$row", $item->total_price);
                    $row++;
                }

                // === TABEL ===
                $startRow = $row + 2;
                $sheet->mergeCells("A$startRow:E$startRow");
                $sheet->setCellValue("A$startRow", 'Pemakaian Alat');
                $sheet->getStyle("A$startRow")->getFont()->setBold(true);
                $startRow++;

                $headers2 = ['No', 'Tanggal', 'Produk', 'Harga', 'Jumlah', 'Durasi', 'Satuan', 'Sub Total'];
                $col = 'A';
                foreach ($headers2 as $header) {
                    $sheet->setCellValue("$col$startRow", $header);
                    $sheet->getStyle("$col$startRow")->getFont()->setBold(true);
                    $col++;
                }

                $row = $startRow + 1;
                $no = 1;
                foreach ($this->loans as $item) {
                    $sheet->setCellValue("A$row", $no++);
                    $sheet->setCellValue("B$row", optional($item->created_at)->format('d/m/Y'));
                    $sheet->setCellValue("C$row", $item->asset->product_name);
                    $sheet->setCellValue("D$row", $item->rental_price);
                    $sheet->setCellValue("E$row", $item->quantity);
                    $sheet->setCellValue("F$row", $item->rental);
                    $sheet->setCellValue("G$row", $item->asset->product_unit);
                    $sheet->setCellValue("H$row", $item->total_price);
                    $row++;
                }

                // AutoSize kolom
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}
