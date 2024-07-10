<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assetlab extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_name',
        'merk',
        'type',
        'stock',
        'location',
        'created_by',
        'updated_by'
    ];

    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }

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
        return $query->where('product_name', 'LIKE', "%{$searchTerm}%");
    }
}
