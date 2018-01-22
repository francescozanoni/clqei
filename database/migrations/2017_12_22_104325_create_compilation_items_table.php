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
            $table->unsignedInteger('question_id')->nullable(false);
            // Answer ID or free text answer, according to question type.
            $table->string('answer')->nullable(true);
            $table->foreign('compilation_id')->references('id')->on('compilations');
            $table->foreign('question_id')->references('id')->on('questions');
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
        Schema::dropIfExists('compilation_items');
    }
}
