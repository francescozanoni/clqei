<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompilationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compilation_items', function (Blueprint $table) {
            $table->increments('id')->autoIncrement();
            $table->unsignedInteger('compilation_id')->nullable(false);
            $table->unsignedInteger('section_id')->nullable(false);
            $table->unsignedInteger('question_id')->nullable(false);
            $table->unsignedInteger('answer_id');
            $table->string('free_text_answer');
            $table->foreign('compilation_id')->references('id')->on('compilations');
            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('answer_id')->references('id')->on('answers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compilation_items');
    }
}
