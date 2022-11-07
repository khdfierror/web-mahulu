<?php

use Illuminate\Database\Migrations\Migration;
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
        Schema::table('navigations', function ($table) {
            $table->after('url', function ($table) {
                $table->tinyInteger('is_group')->default(0);
                $table->string('description')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('navigations', function ($table) {
            $table->dropColumn(['is_group', 'description']);
        });
    }
};
