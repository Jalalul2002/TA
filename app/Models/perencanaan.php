<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perencanaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'rencana_id',
        'product_code',
        'stock',
        'jumlah_kebutuhan',
        'created_by',
        'updated_by'
    ];

    public function rencana()
    {
        return $this->belongsTo(DataPerencanaan::class, 'rencana_id');
    }

    public function product()
    {
        return $this->belongsTo(Assetlab::class, 'product_code');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('product_code', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('product', function ($q) use ($searchTerm) {
                    $q->where('product_name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('formula', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('merk', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('product_type', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('location', 'LIKE', "%{$searchTerm}%");
                });
        });
    }

    protected static function boot()
    {
        parent::boot();

        // Event untuk memperbarui updated_at DataPerencanaan
        static::updated(function ($perencanaan) {
            $dataPerencanaan = $perencanaan->rencana;

            if ($dataPerencanaan) {
                $dataPerencanaan->updated_at = $perencanaan->updated_at; // Sinkronkan waktu update
                $dataPerencanaan->updated_by = $perencanaan->updated_by; // Sinkronkan pengguna yang terakhir mengupdate
                $dataPerencanaan->save();
            }
        });
    }
}
