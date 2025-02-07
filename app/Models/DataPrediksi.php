<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPrediksi extends Model
{
    use HasFactory;

    public function asset()
    {
        return $this->belongsTo(Assetlab::class, 'product_code');
    }
    public function scopeOfLocation($query, $location)
    {
        return $query->where('location', $location);
    }
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('product_code', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('asset', function ($q) use ($searchTerm) {
                    $q->where('product_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('product_detail', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('merk', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('product_type', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('location', 'LIKE', "%{$searchTerm}%");
                });
        });
    }
}
