<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'purpose',
        'user_id',
        'name',
        'prodi',
        'telp',
        'detail',
        'type',
        'location',
        'created_by',
        'updated_by'
    ];
    protected $appends = ['total_item_price', 'total_loan_price'];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function loans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function getTotalLoanQuantityAttribute()
    {
        return $this->loans()->sum('quantity');
    }

    public function getTotalReturnedQuantityAttribute()
    {
        return $this->loans()->sum('returned_quantity');
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('user_id', 'LIKE', "%{$searchTerm}%")
            ->orWhere('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('prodi', 'LIKE', "%{$searchTerm}%")
            ->orWhere('location', 'LIKE', "%{$searchTerm}%")
            ->orWhere('detail', 'LIKE', "%{$searchTerm}%")
            ->orWhere('telp', 'LIKE', "%{$searchTerm}%");
    }
    public function getTotalItemPriceAttribute()
    {
        return $this->items()->sum('total_price');
    }
    public function getTotalLoanPriceAttribute()
    {
        return $this->loans()->sum('total_price');
    }
}
