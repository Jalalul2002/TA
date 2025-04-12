<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = [
        'transaction_id',
        'product_code',
        'stock',
        'quantity',
        'rental',
        'rental_price',
        'total_price',
        'returned_quantity',
        'damaged_quantity',
        'loan_date',
        'return_date',
        'status',
        'notes',
        'return_notes',
        'created_by',
        'updated_by',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    public function asset()
    {
        return $this->belongsTo(Assetlab::class, 'product_code', 'product_code');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function isReturned()
    {
        return $this->status === 'dikembalikan';
    }
}
