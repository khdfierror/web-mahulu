<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cagar_budaya', function (Blueprint $table) {
            $table->string('no_inventaris')->nullable();
            $table->string('periode')->nullable();
            $table->string('bahan')->nullable();
            $table->string('ukuran')->nullable();
            $table->text('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cagar_budaya', function (Blueprint $table) {
            $table->dropColumn([
                'no_inventaris',
                'periode',
                'bahan',
                'ukuran',
                'keterangan',
            ]);
        });
    }
};
