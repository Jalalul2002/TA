<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataLab extends Model
{
    protected $table = 'data_labs';

    protected $fillable = [
        'lab_code',
        'name',
        'prodi',
        'capacity',
    ];

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('lab_code', 'LIKE', "%{$searchTerm}%")->orWhere('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('prodi', 'LIKE', "%{$searchTerm}%")
            ->orWhere('capacity', 'LIKE', "%{$searchTerm}%");
    }
}
