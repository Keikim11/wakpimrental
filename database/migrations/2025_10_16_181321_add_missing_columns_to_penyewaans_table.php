<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToPenyewaansTable extends Migration
{
    public function up()
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            // Cek dan tambahkan kolom durasi_sewa jika belum ada
            if (!Schema::hasColumn('penyewaans', 'durasi_sewa')) {
                $table->integer('durasi_sewa')->after('tanggal_kembali_aktual')->default(0);
            }
            
            // Cek dan tambahkan kolom catatan jika belum ada
            if (!Schema::hasColumn('penyewaans', 'catatan')) {
                $table->text('catatan')->nullable()->after('denda');
            }
        });
    }

    public function down()
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            // Optional: Hapus kolom jika rollback (jika diperlukan)
            // $table->dropColumn(['durasi_sewa', 'catatan']);
        });
    }
}