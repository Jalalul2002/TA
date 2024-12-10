<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPerencanaan extends Model
{
    use HasFactory;

    protected $table = 'data_perencanaans';

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
            ->latest('updated_at') // Ambil data berdasarkan timestamp terbaru
            ->with('updater');     // Pastikan juga memuat data user (updater)
    }
}
