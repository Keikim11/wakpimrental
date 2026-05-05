<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            $table->decimal('denda', 15, 2)->default(0)->after('kondisi_mobil');
        });
    }

    public function down()
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            $table->dropColumn('denda');
        });
    }
};
