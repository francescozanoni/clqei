<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id')->autoIncrement();
            $table->string('text')->nullable(false);
            $table->unsignedInteger('question_id')->nullable(false);
            // Position of the answer within the set of answers of a question (1...N)
            $table->unsignedTinyInteger('position')->nullable(false);
            // @todo find how to create a unique index on fields question_id and position
            $table->foreign('question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
