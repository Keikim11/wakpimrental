<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'tanggal_kembali_aktual',
        'kondisi_mobil',
        'denda',
        //'total_pembayaran',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_kembali_aktual' => 'date',
        'denda' => 'decimal:2',
    ];

    /**
     * Relationship dengan Penyewaan
     */
    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class);
    }

    /**
     * Get formatted denda
     */
    public function getFormattedDendaAttribute()
    {
        return 'Rp ' . number_format($this->denda, 0, ',', '.');
    }

    /**
     * Get kondisi mobil label
     */
    public function getKondisiMobilLabelAttribute()
    {
        $labels = [
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat'
        ];

        return $labels[$this->kondisi_mobil] ?? $this->kondisi_mobil;
    }

    /**
     * Get kondisi mobil badge color
     */
    public function getKondisiMobilBadgeAttribute()
    {
        $colors = [
            'baik' => 'success',
            'rusak_ringan' => 'warning',
            'rusak_berat' => 'danger'
        ];

        return $colors[$this->kondisi_mobil] ?? 'secondary';
    }
}