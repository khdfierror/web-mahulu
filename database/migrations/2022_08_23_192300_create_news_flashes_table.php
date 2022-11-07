<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('news_flashes', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('content')->nullable();
            $table->string('link')->nullable();
            $table->date('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('news_flashes');
    }
};
