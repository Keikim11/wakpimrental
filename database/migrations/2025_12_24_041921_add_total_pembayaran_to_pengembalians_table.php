<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalPembayaranToPengembaliansTable extends Migration
{
    public function up()
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            $table->decimal('total_pembayaran', 10, 2)->default(0)->after('denda');
        });
    }

    public function down()
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            $table->dropColumn('total_pembayaran');
        });
    }
}