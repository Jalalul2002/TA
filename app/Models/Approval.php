<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $table = 'approvals';

    protected $fillable = [
        'pengajuan_id',
        'approver_id',
        'level',
        'status',
        'approved_date',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id', 'id');
    }
}
