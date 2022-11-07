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
        Schema::create('cagar_budaya', function (Blueprint $table) {
            $table->id();
            $table->ulid('uuid')->nullable();
            $table->string('name');

            $table->string('cp_name')->nullable();
            $table->string('cp_phone')->index()->nullable();
            $table->string('cp_email')->index()->nullable();
            $table->timestamps();
        });

        Schema::create('permit_categories', function (Blueprint $table) {
            $table->id();
            $table->ulid('uuid')->nullable();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('permit_applicants', function (Blueprint $table) {
            $table->id();
            $table->ulid('uuid')->nullable();
            $table->string('name')->nullable();
            $table->string('job')->nullable(); // pekerjaan
            $table->string('agency')->nullable(); // instansi
            $table->string('phone')->index();
            $table->string('email')->index();
            $table->timestamps();
        });

        Schema::create('services_permits', function (Blueprint $table) {
            $table->id();
            $table->ulid('uuid')->nullable();
            $table->foreignId('categories_id')->nullable()->cascadeOnDelete();
            $table->foreignId('cagar_budaya_id')->nullable()->cascadeOnDelete();
            $table->foreignId('permit_applicant_id')->nullable()->cascadeOnDelete();

            $table->text('activity_concept')->nullable(); // konsep kegiatan
            // $table->text('activity_tools'); // alat yg di gunakan
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('services_reports', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->foreignId('categories_id')->nullable()->cascadeOnDelete();
            $table->foreignId('permit_applicant_id')->nullable()->cascadeOnDelete();

            $table->string('name');
            $table->text('description')->nullable();
            $table->text('additional_information')->nullable(); // keterangan tambahan

            $table->timestamp('discovery_date')->nullable();
            $table->timestamps();
        });

        Schema::create('services_data_requests', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->foreignId('categories_id')->nullable()->cascadeOnDelete();
            $table->foreignId('cagar_budaya_id')->nullable()->cascadeOnDelete();
            $table->foreignId('permit_applicant_id')->nullable()->cascadeOnDelete();

            $table->text('concept')->nullable(); // konsep penggunaan data
            $table->timestamps();
        });

        Schema::create('services_cases', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->foreignId('categories_id')->nullable()->cascadeOnDelete(); // questionable, category list unknown, for now using permit categories latter will be change with the right table
            $table->foreignId('cagar_budaya_id')->nullable()->cascadeOnDelete();
            $table->foreignId('permit_applicant_id')->nullable()->cascadeOnDelete();

            $table->text('condition')->nullable(); // cb condition
            $table->timestamp('reported_at')->nullable();
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
        Schema::dropIfExists('cagar_budaya');
        Schema::dropIfExists('permit_categories');
        Schema::dropIfExists('permit_applicants');
        Schema::dropIfExists('services_permits');
        Schema::dropIfExists('services_reports');
        Schema::dropIfExists('services_data_requests');
        Schema::dropIfExists('services_cases');
    }
};
