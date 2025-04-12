<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPerencanaan extends Model
{
    use HasFactory;

    protected $table = 'data_perencanaans';
    protected $appends = ['total_price'];

    protected $fillable = [
        'nama_perencanaan',
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

    public function plans()
    {
        return $this->hasMany(Perencanaan::class, 'rencana_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($dataPerencanaan) {
            $dataPerencanaan->plans()->each(function ($plan) {
                $plan->delete();
            });
        });

        static::saving(function ($dataPerencanaan) {
            if ($dataPerencanaan->isDirty('updated_by') || $dataPerencanaan->isDirty('updated_at')) {
                $dataPerencanaan->updated_at = now();
            }
        });
    }

    public function latestUpdater()
    {
        return $this->hasOne(Perencanaan::class, 'rencana_id')
            ->latest('updated_at')
            ->with('updater');
    }

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where('nama_perencanaan', 'like', "%{$search}%")
                ->orWhere('prodi', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
        }

        return $query;
    }
    public function getTotalPriceAttribute()
    {
        return $this->plans()->sum('total_price');
    }
}
