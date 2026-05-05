<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurasiSewaToPenyewaansTable extends Migration
{
    public function up()
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            // Tambahkan kolom durasi_sewa jika belum ada
            if (!Schema::hasColumn('penyewaans', 'durasi_sewa')) {
                $table->integer('durasi_sewa')->after('tanggal_kembali_aktual');
            }

            // Tambahkan kolom lainnya yang mungkin belum ada
            if (!Schema::hasColumn('penyewaans', 'catatan')) {
                $table->text('catatan')->nullable()->after('denda');
            }
        });
    }

    public function down()
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            // Hapus kolom jika rollback
            if (Schema::hasColumn('penyewaans', 'durasi_sewa')) {
                $table->dropColumn('durasi_sewa');
            }
            
            if (Schema::hasColumn('penyewaans', 'catatan')) {
                $table->dropColumn('catatan');
            }
        });
    }
}