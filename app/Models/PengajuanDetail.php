<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanDetail extends Model
{
    protected $table = 'pengajuan_details';

    protected $fillable = [
        'pengajuan_id',
        'item_type',
        'item_id',
        'item_name',
        'quantity',
        'detail',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
