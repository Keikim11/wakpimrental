<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mobil;
use App\Models\Pelanggan;
use App\Models\Penyewaan;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin WakPim',
            'email' => 'admin@wakpim.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        // Create karyawan user
        User::create([
            'name' => 'Karyawan Rental',
            'email' => 'karyawan@wakpim.com',
            'password' => bcrypt('password123'),
            'role' => 'karyawan'
        ]);

        // Sample mobil data
        $mobil1 = Mobil::create([
            'merk' => 'Toyota',
            'model' => 'Avanza',
            'nomor_plat' => 'B 1234 ABC',
            'tarif_sewa_per_hari' => 300000,
            'status' => 'tersedia',
            'deskripsi' => 'Mobil keluarga dengan kapasitas 7 penumpang'
        ]);

        $mobil2 = Mobil::create([
            'merk' => 'Honda',
            'model' => 'Jazz',
            'nomor_plat' => 'B 5678 DEF',
            'tarif_sewa_per_hari' => 250000,
            'status' => 'tersedia',
            'deskripsi' => 'Mobil hatchback dengan desain modern'
        ]);

        $mobil3 = Mobil::create([
            'merk' => 'Mitsubishi',
            'model' => 'Pajero Sport',
            'nomor_plat' => 'B 9012 GHI',
            'tarif_sewa_per_hari' => 500000,
            'status' => 'disewa',
            'deskripsi' => 'SUV mewah dengan fitur lengkap'
        ]);

        // Sample pelanggan data
        $pelanggan1 = Pelanggan::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@email.com',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta',
            'no_ktp' => '1234567890123456'
        ]);

        $pelanggan2 = Pelanggan::create([
            'nama' => 'Siti Rahayu',
            'email' => 'siti@email.com',
            'telepon' => '081298765432',
            'alamat' => 'Jl. Sudirman No. 456, Jakarta',
            'no_ktp' => '6543210987654321'
        ]);

        // Sample penyewaan data - DURASI SEWA SUDAH DITAMBAHKAN
        Penyewaan::create([
            'pelanggan_id' => $pelanggan1->id,
            'mobil_id' => $mobil3->id,
            'tanggal_sewa' => Carbon::now()->subDays(2),
            'tanggal_kembali_rencana' => Carbon::now()->addDays(3),
            'durasi_sewa' => 5, // Kolom ini sekarang ada
            'total_biaya' => 2500000,
            'status' => 'aktif'
        ]);

        Penyewaan::create([
            'pelanggan_id' => $pelanggan2->id,
            'mobil_id' => $mobil1->id,
            'tanggal_sewa' => Carbon::now()->subDays(10),
            'tanggal_kembali_rencana' => Carbon::now()->subDays(5),
            'tanggal_kembali_aktual' => Carbon::now()->subDays(4),
            'durasi_sewa' => 5, // Kolom ini sekarang ada
            'total_biaya' => 1500000,
            'denda' => 50000,
            'status' => 'selesai'
        ]);

        // Tambahkan data penyewaan aktif untuk testing
        Penyewaan::create([
            'pelanggan_id' => $pelanggan1->id,
            'mobil_id' => $mobil2->id,
            'tanggal_sewa' => Carbon::now()->subDays(1),
            'tanggal_kembali_rencana' => Carbon::now()->addDays(2),
            'durasi_sewa' => 3,
            'total_biaya' => 750000,
            'status' => 'aktif'
        ]);
    }
}