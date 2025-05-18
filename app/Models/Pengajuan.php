<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuans';

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'lab_id',
        'name',
        'prodi',
        'detail',
        'dosen',
        'kepala_lab',
        'ketua_lab',
        'laboran',
        'status_pengajuan',
        'status',
        'keterangan',
    ];

    public function lab()
    {
        return $this->belongsTo( DataLab::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id', 'id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id', 'id');
    }
}
