<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuans';

    protected $fillable = [
        'mahasiswa_id',
        'type',
        'telp',
        'email',
        'lab_code',
        'start_date',
        'end_date',
        'status_pengajuan',
    ];

    public function lab()
    {
        return $this->belongsTo(DataLab::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(PengajuanDetail::class, 'pengajuan_id');
    }

    public function approval()
    {
        return $this->hasMany(Approval::class, 'pengajuan_id');
    }
}
