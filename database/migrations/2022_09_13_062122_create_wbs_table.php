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
        Schema::create('wbs_kategori', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->nullable();
            $table->string('nama')->nullable();
            $table->timestamps();
        });

        Schema::create('wbs_pelapor', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->nullable();
            $table->string('nama')->nullable();
            $table->string('nomor_identitas')->nullable(); // nip / nik
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('wbs_laporan', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->nullable();
            $table->foreignId('kategori_id')->nullable()->cascadeOnDelete();
            $table->foreignId('pelapor_id')->nullable()->cascadeOnDelete();
            $table->text('what')->nullable();
            $table->text('where')->nullable();
            $table->text('when')->nullable();
            $table->text('who')->nullable();
            $table->text('how')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wbs_kategori');
        Schema::dropIfExists('wbs_pelapor');
        Schema::dropIfExists('wbs_laporan');
    }
};
