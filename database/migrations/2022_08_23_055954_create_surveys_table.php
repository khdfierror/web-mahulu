<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->string('question');
            $table->json('options')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->ulid();
            $table->foreignId('survey_question_id')->nullable()->cascadeOnDelete();
            $table->string('answer')->nullable();
            $table->json('sender')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('survey_answers');
    }
};
