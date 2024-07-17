<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_description',
        'created_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeSearch(Builder $builder, $search = null): void
    {
        $builder->when(
            $search,
            fn ($builder, $search) =>
            $builder->where('product_name', 'like', "%{$search}%")
                ->orWhereHas('user', function (Builder $builder) use ($search) {
                    $builder->where('product_description', 'like', "%{$search}%");
                })
        );
    }
}
