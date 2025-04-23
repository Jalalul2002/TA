<?php

namespace App\Exports;

use App\Models\Assetlab;
use App\Models\DataPerencanaan;
use App\Models\Perencanaan;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReportExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $startDate, $endDate, $location, $type, $productType;
    public function __construct($startDate, $endDate, $location, $type, $productType)
    {
        $this->startDate = Carbon::parse($startDate)->startOfDay();
        $this->endDate = Carbon::parse($endDate)->endOfDay();
        $this->location = $location;
        $this->productType = $productType;
        $this->type = $type;
    }

    public function collection()
    {
        $query = Assetlab::where('location', $this->location)
            ->where('type', $this->type);

        if ($this->productType) {
            $query->where('product_type', $this->productType);
        }

        $assets = $query->get();

        $report = $assets->filter(function ($asset) {
            $productCode = $asset->product_code;

            $hasStock = $asset->stock > 0;

            $hasMasuk = DataPerencanaan::where('status', 'selesai')
                ->whereBetween('updated_at', [$this->startDate, $this->endDate])
                ->whereHas('plans', fn($q) => $q->where('product_code', $productCode))
                ->exists();

            $hasKeluar = TransactionItem::where('product_code', $productCode)
                ->whereHas('transaction', fn($q) => $q->whereBetween('created_at', [$this->startDate, $this->endDate]))
                ->exists();

            return $hasStock || $hasMasuk || $hasKeluar;
        })->map(function ($asset) {
            $productCode = $asset->product_code;
            $stockAwal = null;

            // Perencanaan awal
            $perencanaanPertama = Perencanaan::where('product_code', $productCode)
                ->whereHas('rencana', fn($q) => $q->where('status', 'selesai'))
                ->orderBy('updated_at', 'asc')
                ->first();

            if ($perencanaanPertama) {
                $stockAwal = $perencanaanPertama->stock ?? null;
            }

            // Transaksi awal dalam rentang
            if ($stockAwal === null) {
                $transaksiPertama = TransactionItem::where('product_code', $productCode)
                    ->whereHas('transaction', fn($q) => $q->whereBetween('created_at', [$this->startDate, $this->endDate]))
                    ->with('transaction')
                    ->get()
                    ->sortBy(fn($item) => $item->transaction->created_at)
                    ->first();

                $stockAwal = $transaksiPertama->stock ?? null;
            }

            // Transaksi sebelum startDate
            if ($stockAwal === null) {
                $transaksiPertama = TransactionItem::where('product_code', $productCode)
                    ->whereHas('transaction', fn($q) => $q->where('created_at', '<=', $this->startDate))
                    ->with('transaction')
                    ->get()
                    ->sortBy(fn($item) => $item->transaction->created_at)
                    ->first();

                $stockAwal = $transaksiPertama->stock ?? null;
            }

            if ($stockAwal === null) {
                $stockAwal = $asset->stock;
            }

            // Total masuk
            $totalMasuk = DataPerencanaan::where('status', 'selesai')
                ->whereBetween('updated_at', [$this->startDate, $this->endDate])
                ->whereHas('plans', fn($q) => $q->where('product_code', $productCode))
                ->with(['plans' => fn($q) => $q->where('product_code', $productCode)])
                ->get()
                ->flatMap->plans
                ->sum('jumlah_kebutuhan');

            // Total keluar
            $totalKeluar = TransactionItem::where('product_code', $productCode)
                ->whereHas('transaction', fn($q) => $q->whereBetween('created_at', [$this->startDate, $this->endDate]))
                ->sum('jumlah_pemakaian');

            $stockSekarang = $stockAwal + $totalMasuk - $totalKeluar;

            return [
                $productCode,
                $asset->product_name,
                $asset->product_detail ?: '-',
                $asset->merk ?: '-',
                $asset->product_type,
                $asset->product_unit,
                $stockAwal > 0 ? $stockAwal : '0',
                $totalMasuk > 0 ? $totalMasuk : '0',
                $totalKeluar > 0 ? $totalKeluar : '0',
                $stockSekarang > 0 ? $stockSekarang : '0',
            ];
        });

        return collect($report);
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function headings(): array
    {
        return [
            'Kode Barang',
            'Nama Barang',
            'Detail',
            'Merk',
            'Tipe',
            'Satuan',
            'Stok Awal',
            'Masuk',
            'Keluar',
            'Stok',
        ];
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
                foreach (range('A', 'J') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $title = "Stock Opname {$typeText} Prodi {$locationText}";
                $periode = "Periode: " . ($this->startDate && $this->endDate
                    ? Carbon::parse($this->startDate)->format('d M Y') . " - " . Carbon::parse($this->endDate)->format('d M Y')
                    : "Semua Data");

                // Judul Laporan di Baris 1
                $sheet->mergeCells('A1:J1');
                $sheet->setCellValue('A1', $title);
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Periode di Baris 2
                $sheet->mergeCells('A2:J2');
                $sheet->setCellValue('A2', $periode);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Baris Kosong (Baris 3)
                $sheet->mergeCells('A3:J3');

                // Membuat header bold (Baris 4)
                $sheet->getStyle('A5:J5')->getFont()->setBold(true);
                $sheet->getStyle('A5:J5')->getAlignment()->setHorizontal('center');

                // Menghitung jumlah baris data (total transaksi)
                $lastRow = $sheet->getHighestRow(); // Baris terakhir dengan data
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
}
