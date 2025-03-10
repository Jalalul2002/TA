<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_code',
        'stock',
        'jumlah_pemakaian',
        'updated_stock'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function asset()
    {
        return $this->belongsTo(Assetlab::class, 'product_code', 'product_code');
    }
}
