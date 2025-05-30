<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'usertype',
        'prodi',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('usertype', 'like', "%{$search}%")
                ->orWhere('prodi', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        return $query;
    }
    public function signature()
{
    return $this->hasOne(UserSign::class);
}
    public function products()
    {
        return $this->hasMany(Product::class, 'created_by');
    }
    public function aset()
    {
        return $this->hasMany(Assetlab::class, 'created_by');
    }
    public function transctions()
    {
        return $this->hasMany(Transaction::class, 'created_by');
    }
    public function perencanaans()
    {
        return $this->hasMany(Perencanaan::class, 'created_by');
    }
    public function dataPerencanaans()
    {
        return $this->hasMany(DataPerencanaan::class, 'created_by');
    }
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'created_by');
    }
}
