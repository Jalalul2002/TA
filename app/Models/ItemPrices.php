<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPrices extends Model
{
    use HasFactory;
    protected $casts = [
        'effective_date' => 'date',
    ];
    protected $fillable = ['product_code', 'price_type', 'price', 'purchase_price', 'effective_date', 'location', 'created_by'];
    public function item()
    {
        return $this->belongsTo(Assetlab::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
