<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_code',
        'stock',
        'unit_price',
        'jumlah_pemakaian',
        'updated_stock',
        'total_price',
    ];
    protected $appends = ['formatted_stock', 'formatted_quantity', 'formatted_updated_stock'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function asset()
    {
        return $this->belongsTo(Assetlab::class, 'product_code', 'product_code');
    }

    public function getFormattedStockAttribute()
    {
        $formatted = number_format($this->stock, 4, ',', '.');
        return rtrim(rtrim($formatted, '0'), ',');
    }
    public function getFormattedQuantityAttribute()
    {
        $formatted = number_format($this->jumlah_pemakaian, 4, ',', '.');
        return rtrim(rtrim($formatted, '0'), ',');
    }
    public function getFormattedUpdatedStockAttribute()
    {
        $formatted = number_format($this->updated_stock, 4, ',', '.');
        return rtrim(rtrim($formatted, '0'), ',');
    }
}
