<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perencanaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'rencana_id', 'product_code', 'stok', 'jumlah_kebutuhan'
    ];

    public function rencana()
    {
        return $this->belongsTo(DataPerencanaan::class, 'rencana_id');
    }

    public function product()
    {
        return $this->belongsTo(Assetlab::class, 'product_code');
    }
}
