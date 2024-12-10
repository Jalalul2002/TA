<?php

namespace App\Exports;

use App\Models\Perencanaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PerencanaanExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $perencanaanId;

    public function __construct($perencanaanId)
    {
        $this->perencanaanId = $perencanaanId;
    }

    public function collection()
    {
        return Perencanaan::with(['rencana', 'product'])
            ->where('rencana_id', $this->perencanaanId)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Produk Kode',
            'Nama Produk',
            'Rumus Kimia',
            'Merk',
            'Jenis Produk',
            'Stok',
            'Jumlah Kebutuhan',
            'Satuan',
        ];
    }

    // public function map($perencanaan): array
    // {
    //     $rows = [];
    //     foreach ($perencanaan->product as $product) {
    //         $rows[] = [
    //             $perencanaan->product_code,
    //             $product->product_name ?? '-',
    //             $product->formula ?? '-',
    //             $product->merk ?? '-',
    //             $product->product_type ?? '-',
    //             $perencanaan->stock,
    //             $perencanaan->jumlah_kebutuhan,
    //             $product->product_unit ?? '-',
    //         ];
    //     }
    //     return $rows;
    // }
    public function map($perencanaan): array
    {
        // Mengembalikan satu baris data per produk
        return [
            $perencanaan->product_code,
            $perencanaan->product->product_name ?? '-',
            $perencanaan->product->formula ?? '-',
            $perencanaan->product->merk ?? '-',
            $perencanaan->product->product_type ?? '-',
            $perencanaan->stock ?? '-',
            $perencanaan->jumlah_kebutuhan ?? '-',
            $perencanaan->product->product_unit ?? '-',
        ];
    }
}
