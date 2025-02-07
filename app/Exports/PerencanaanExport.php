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
