<?php

namespace App\Exports;

use App\Models\Assetlab;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetInvExport implements FromCollection, WithHeadings, WithMapping
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

    public function collection()
    {
        $query = AssetLab::ofType('inventaris')->with(['creator', 'updater']);

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
            'Keterangan',
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
