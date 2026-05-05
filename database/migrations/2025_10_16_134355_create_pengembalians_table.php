<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengembaliansTable extends Migration
{
    public function up()
    {
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_kembali_aktual');
            $table->enum('kondisi_mobil', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->decimal('denda', 12, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengembalians');
    }
}