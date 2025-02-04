<?php

namespace App\Exports;

use App\Models\Assetlab;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetBhpExport implements FromCollection, WithHeadings
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
        $query = AssetLab::ofType('bhp');
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
            'Rumus Kimia',
            'Merk',
            'Jenis',
            'Stok',
            'Satuan',
            'Lokasi Penyimpanan',
            'Lokasi',
            'Diupdate Oleh',
        ];
    }
}
