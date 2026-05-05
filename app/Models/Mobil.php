<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;

    protected $fillable = [
        'merk',
        'model',
        'nomor_plat',
        'tarif_sewa_per_hari',
        'status',
        'deskripsi',
        'foto'
    ];

    protected $casts = [
        'tarif_sewa_per_hari' => 'decimal:2',
    ];

    /**
     * Relasi ke model Penyewaan
     */
    public function penyewaans()
    {
        return $this->hasMany(Penyewaan::class);
    }

    /**
     * Scope untuk mobil tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    /**
     * Scope untuk mobil disewa
     */
    public function scopeDisewa($query)
    {
        return $query->where('status', 'disewa');
    }

    /**
     * Scope untuk mobil maintenance
     */
    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    /**
     * Cek apakah mobil tersedia
     */
    public function getIsTersediaAttribute()
    {
        return $this->status === 'tersedia';
    }

    /**
     * Format tarif sewa
     */
    public function getTarifFormattedAttribute()
    {
        return 'Rp ' . number_format($this->tarif_sewa_per_hari, 0, ',', '.');
    }
}