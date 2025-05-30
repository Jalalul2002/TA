<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assetlab extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_code';
    protected $appends = ['formatted_stock'];
    public $incrementing = false;

    protected $fillable = [
        'product_code',
        'product_name',
        'product_detail',
        'merk',
        'type',
        'product_type',
        'stock',
        'product_unit',
        'location',
        'location_detail',
        'created_by',
        'updated_by'
    ];

    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    public function plans()
    {
        return $this->hasMany(Perencanaan::class, 'product_code', 'product_code');
    }

    public function latestPlans()
    {
        return $this->hasOne(Perencanaan::class, 'product_code', 'product_code')->latest('updated_at');;
    }

    public function realisasis()
    {
        return $this->hasMany(Realisasi::class, 'product_code', 'product_code');
    }

    public function latestRealisasi()
    {
        return $this->hasOne(Realisasi::class, 'product_code', 'product_code')->latest('updated_at');;
    }

    public function prices()
    {
        return $this->hasMany(ItemPrices::class);
    }

    public function latestPrice()
    {
        return $this->hasOne(ItemPrices::class, 'product_code', 'product_code')
            ->where('effective_date', '<=', now()) // Hanya harga yang sudah berlaku
            ->latest('effective_date'); // Ambil harga terbaru berdasarkan tanggal berlaku
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class, 'product_code', 'product_code');
    }

    public function predict()
    {
        return $this->hasMany(DataPrediksi::class, 'product_code');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOfLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('product_name', 'LIKE', "%{$searchTerm}%")->orWhere('product_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('product_detail', 'LIKE', "%{$searchTerm}%")
            ->orWhere('merk', 'LIKE', "%{$searchTerm}%")
            ->orWhere('product_type', 'LIKE', "%{$searchTerm}%")
            ->orWhere('location', 'LIKE', "%{$searchTerm}%");
    }
    public function updateStock($returnedQuantity, $damagedQuantity)
    {
        $this->stock += $returnedQuantity; // Tambah stok jika barang dikembalikan dengan baik
        $this->stock -= $damagedQuantity; // Kurangi stok jika ada barang rusak
        $this->save();
    }
    public function getFormattedStockAttribute()
    {
        $formatted = number_format($this->stock, 4, ',', '.');
        return rtrim(rtrim($formatted, '0'), ',');
    }
}
