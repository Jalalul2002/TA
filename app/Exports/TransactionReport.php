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
                $setTableBorder = function ($sheet, $range) {
                    $sheet->getStyle($range)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ]);
                };
                $sheet = $event->sheet->getDelegate();

                // Judul
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', 'Laporan Data Transaksi');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // === INFORMASI PEMINJAM ===
                $sheet->mergeCells('A3:B3');
                $sheet->setCellValue('A3', 'Informasi Pengguna');
                $sheet->getStyle('A3')->getFont()->setBold(true);

                $borrower = $this->items->first()?->transaction ?? $this->loans->first()?->transaction;
                $sheet->setCellValue('A4', 'ID');
                $sheet->setCellValue('B4', $borrower->user_id ?? '-');
                $sheet->setCellValue('A5', 'Nama');
                $sheet->setCellValue('B5', $borrower->name ?? '-');
                $sheet->setCellValue('A6', 'Prodi');
                $sheet->setCellValue('B6', $borrower->prodi ?? '-');
                $sheet->setCellValue('A7', 'Telepon');
                $sheet->setCellValue('B7', $borrower->telp ?? '-');

                // === TABEL ===
                $startRow = 9;
                $sheet->mergeCells("A$startRow:B$startRow");
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
                $totalBhp = 0;
                foreach ($this->items as $item) {
                    $sheet->setCellValue("A$row", $no++);
                    $sheet->setCellValue("B$row", optional($item->created_at)->format('d/m/Y'));
                    $sheet->setCellValue("C$row", $item->asset->product_name);
                    $sheet->setCellValue("D$row", $item->unit_price);
                    $sheet->setCellValue("E$row", $item->formatted_quantity);
                    $sheet->setCellValue("F$row", $item->asset->product_unit);
                    $sheet->setCellValue("G$row", $item->total_price);
                    $totalBhp += $item->total_price;
                    $row++;
                }

                // TOTAL BHP
                $sheet->mergeCells("A$row:F$row");
                $sheet->setCellValue("A$row", 'TOTAL BHP');
                $sheet->getStyle("A$row")->getFont()->setBold(true);
                $sheet->setCellValue("G$row", $totalBhp);
                $sheet->getStyle("G$row")->getFont()->setBold(true);
                $startBhp = $startRow;
                $setTableBorder($sheet, "A$startBhp:G$row");
                $row += 2;

                // === TABEL ===
                $sheet->mergeCells("A$row:E$row");
                $sheet->setCellValue("A$row", 'Pemakaian Alat');
                $sheet->getStyle("A$row")->getFont()->setBold(true);
                $row++;

                $headers2 = ['No', 'Tanggal', 'Produk', 'Harga', 'Jumlah', 'Durasi', 'Satuan', 'Sub Total'];
                $col = 'A';
                foreach ($headers2 as $header) {
                    $sheet->setCellValue("$col$row", $header);
                    $sheet->getStyle("$col$row")->getFont()->setBold(true);
                    $col++;
                }

                $row++;
                $no = 1;
                $totalAlat = 0;
                foreach ($this->loans as $item) {
                    $sheet->setCellValue("A$row", $no++);
                    $sheet->setCellValue("B$row", optional($item->created_at)->format('d/m/Y'));
                    $sheet->setCellValue("C$row", $item->asset->product_name);
                    $sheet->setCellValue("D$row", $item->rental_price);
                    $sheet->setCellValue("E$row", $item->quantity);
                    $sheet->setCellValue("F$row", $item->rental);
                    $sheet->setCellValue("G$row", $item->asset->product_unit);
                    $sheet->setCellValue("H$row", $item->total_price);
                    $totalAlat += $item->total_price;
                    $row++;
                }

                // TOTAL ALAT
                $sheet->mergeCells("A$row:G$row");
                $sheet->setCellValue("A$row", 'TOTAL ALAT');
                $sheet->getStyle("A$row")->getFont()->setBold(true);
                $sheet->setCellValue("H$row", $totalAlat);
                $sheet->getStyle("H$row")->getFont()->setBold(true);
                $alatStart = $row - (count($this->loans) + 1);
                $alatEnd = $row;
                $setTableBorder($sheet, "A$alatStart:H$alatEnd");
                $row += 2;

                // === TOTAL KESELURUHAN ===
                $sheet->setCellValue("A$row", 'TOTAL KESELURUHAN');
                $sheet->getStyle("A$row")->getFont()->setBold(true)->setSize(12);
                $sheet->setCellValue("B$row", $totalBhp + $totalAlat);
                $sheet->getStyle("B$row")->getFont()->setBold(true)->setSize(12);
                $row++;
                $sheet->setCellValue("A$row", 'KETERANGAN');
                $sheet->getStyle("A$row")->getFont()->setBold(true)->setSize(12);
                $sheet->setCellValue("B$row", '');
                $sheet->getStyle("B$row")->getFont()->setBold(true)->setSize(12);
                $start3 = $row - 1;
                $setTableBorder($sheet, "A$start3:B$row");


                // AutoSize kolom
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}
