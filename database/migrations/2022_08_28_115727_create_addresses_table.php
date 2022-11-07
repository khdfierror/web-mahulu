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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('ulid', 26)->nullable()->index();
            $table->morphs('addressable');
            $table->string('country_code', 10)->nullable()->default('id');
            $table->string('state_code', 10)->nullable()->default('id');
            $table->string('province_code', 20)->nullable()->default('64');
            $table->string('city_code', 20)->nullable()->default('6472');
            $table->string('district_code', 20)->nullable();
            $table->string('ward_code', 20)->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->tinyInteger('is_default')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
