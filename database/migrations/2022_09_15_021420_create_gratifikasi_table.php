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
        Schema::create('gratifikasi_jenis', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('nama')->nullable();
            $table->string('kode')->nullable();
            $table->timestamps();
        });

        Schema::create('gratifikasi_peristiwa', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('nama')->nullable();
            $table->string('kode')->nullable();
            $table->timestamps();
        });

        Schema::create('gratifikasi_pelapor', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('nama')->nullable();
            $table->string('nomor_identitas')->nullable(); // nip / nik
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('golongan')->nullable(); // jabatan / golongan / pangkat
            $table->string('satker')->nullable(); // satker / upt
            $table->string('unit_tugas')->nullable();
            $table->timestamps();
        });

        Schema::create('gratifikasi_penerima', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->foreignId('gratifikasi_jenis_id')->nullable()->cascadeOnDelete();
            $table->foreignId('gratifikasi_peristiwa_id')->nullable()->cascadeOnDelete();
            $table->foreignId('gratifikasi_pelapor_id')->nullable()->cascadeOnDelete();
            $table->foreignId('gratifikasi_pemberi_id')->nullable()->cascadeOnDelete();
            $table->foreignId('gratifikasi_kronologi_id')->nullable()->cascadeOnDelete();

            $table->text('uraian')->nullable(); // uraian jenis penerimaan (bentuk / merek / jenis / warna / tahun pembuatan / dll)
            $table->double('nominal')->nullable(); // harga / nilai / nominal / taksiran yg diterima
            $table->timestamp('tanggal')->nullable(); // tanggal penerimaan
            $table->string('tempat')->nullable(); // tempat penerimaan
            $table->timestamps();
        });

        Schema::create('gratifikasi_pemberi', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('nama')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('hubungan')->nullable(); // hubungan dengan penerima
            $table->timestamps();
        });

        Schema::create('gratifikasi_kronologi', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->text('alasan')->nullable(); // alasan pemberian
            $table->text('kronologi')->nullable(); // Kronologi pemberian (runtutan kejadian penerimaan)
            $table->text('catatan')->nullable(); // Catatan tambahan (bila perlu)
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
        Schema::dropIfExists('gratifikasi_jenis');
        Schema::dropIfExists('gratifikasi_peristiwa');
        Schema::dropIfExists('gratifikasi_pelapor');
        Schema::dropIfExists('gratifikasi_penerima');
        Schema::dropIfExists('gratifikasi_pemberi');
        Schema::dropIfExists('gratifikasi_kronologi');
    }
};
