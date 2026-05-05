<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyewaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'mobil_id',
        'tanggal_sewa',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'durasi_sewa',
        'total_biaya',
        'denda',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal_sewa' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
        'total_biaya' => 'decimal:2',
        'denda' => 'decimal:2',
    ];

    /**
     * Relasi ke model Pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    /**
     * Relasi ke model Mobil
     */
    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    /**
     * Relasi ke model Pengembalian
     */
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    /**
     * Scope untuk penyewaan aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk penyewaan selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope untuk penyewaan batal
     */
    public function scopeBatal($query)
    {
        return $query->where('status', 'batal');
    }

    /**
     * Hitung denda otomatis
     */
    public function hitungDenda()
    {
        if ($this->status == 'selesai' && $this->tanggal_kembali_aktual) {
            $tanggalKembaliRencana = \Carbon\Carbon::parse($this->tanggal_kembali_rencana);
            $tanggalKembaliAktual = \Carbon\Carbon::parse($this->tanggal_kembali_aktual);
            
            if ($tanggalKembaliAktual->gt($tanggalKembaliRencana)) {
                $hariTerlambat = $tanggalKembaliAktual->diffInDays($tanggalKembaliRencana);
                $denda = $hariTerlambat * 50000; // Denda 50rb per hari
                
                $this->update(['denda' => $denda]);
                return $denda;
            }
        }
        return 0;
    }

    /**
     * Get total pembayaran (biaya + denda)
     */
    public function getTotalPembayaranAttribute()
    {
        return $this->total_biaya + $this->denda;
    }

    /**
     * Cek apakah penyewaan sudah melewati batas waktu
     */
    public function getIsTerlambatAttribute()
    {
        if ($this->status == 'aktif') {
            return now()->gt($this->tanggal_kembali_rencana);
        }
        return false;
    }
}