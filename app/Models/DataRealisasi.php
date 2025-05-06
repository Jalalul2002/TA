<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataRealisasi extends Model
{
    use HasFactory;

    protected $table = 'data_realisasis';
    protected $appends = ['total_price'];

    protected $fillable = [
        'name',
        'prodi',
        'type',
        'status',
        'created_by',
        'updated_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function items()
    {
        return $this->hasMany(Realisasi::class, 'realisasi_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($dataRealisasi) {
            $dataRealisasi->items()->each(function ($item) {
                $item->delete();
            });
        });

        static::saving(function ($dataRealisasi) {
            if ($dataRealisasi->isDirty('updated_by') || $dataRealisasi->isDirty('updated_at')) {
                $dataRealisasi->updated_at = now();
            }
        });
    }

    public function latestUpdater()
    {
        return $this->hasOne(Realisasi::class, 'realisasi_id')
            ->latest('updated_at')
            ->with('updater');
    }

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('prodi', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
        }

        return $query;
    }
    public function getTotalPriceAttribute()
    {
        return $this->items()->sum('total_price');
    }
}
