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
        Schema::create('navigations', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('lang', 2)->nullable()->index()->default('id');
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->unsignedBigInteger('group_id')->nullable()->index();
            $table->unsignedBigInteger('priority')->nullable()->index();
            $table->nullableMorphs('linkable');
            $table->string('name')->nullable();
            $table->text('url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('navigation_groups', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('name');
            $table->string('description');
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
        Schema::dropIfExists('navigations');
        Schema::dropIfExists('navigation_groups');
    }
};
