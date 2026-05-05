<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'alamat',
        'no_ktp'
    ];

    /**
     * Relasi ke model Penyewaan
     */
    public function penyewaans()
    {
        return $this->hasMany(Penyewaan::class);
    }

    /**
     * Get total penyewaan
     */
    public function getTotalPenyewaanAttribute()
    {
        return $this->penyewaans()->count();
    }

    /**
     * Get status pelanggan
     */
    public function getStatusAttribute()
    {
        return $this->total_penyewaan > 0 ? 'Aktif' : 'Baru';
    }
}