<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penyewaan;
use App\Models\Pelanggan;
use App\Models\Mobil;
use Carbon\Carbon;

class PenyewaanSeeder extends Seeder
{
    public function run()
    {
        // Ambil data yang sudah ada
        $pelanggan1 = Pelanggan::where('email', 'budi@email.com')->first();
        $pelanggan2 = Pelanggan::where('email', 'siti@email.com')->first();
        
        $mobil1 = Mobil::where('nomor_plat', 'B 1234 ABC')->first();
        $mobil3 = Mobil::where('nomor_plat', 'B 9012 GHI')->first();

        if ($pelanggan1 && $mobil3) {
            Penyewaan::create([
                'pelanggan_id' => $pelanggan1->id,
                'mobil_id' => $mobil3->id,
                'tanggal_sewa' => Carbon::now()->subDays(2),
                'tanggal_kembali_rencana' => Carbon::now()->addDays(3),
                'durasi_sewa' => 5,
                'total_biaya' => 2500000,
                'status' => 'aktif'
            ]);
        }

        if ($pelanggan2 && $mobil1) {
            Penyewaan::create([
                'pelanggan_id' => $pelanggan2->id,
                'mobil_id' => $mobil1->id,
                'tanggal_sewa' => Carbon::now()->subDays(10),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(5),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(4),
                'durasi_sewa' => 5,
                'total_biaya' => 1500000,
                'denda' => 50000,
                'status' => 'selesai'
            ]);
        }
    }
}