<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->string('id', 26)->primary();
            $table->string('ip', 36)->index();
            $table->string('device', 10)->default('desktop')->index();
            $table->string('agent')->nullable();
            $table->json('location');
            $table->timestamp('visited_at');
        });
    }
};
