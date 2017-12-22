<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id')->autoIncrement();
            $table->string('text')->nullable(false);
            $table->unsignedInteger('section_id')->nullable(false);
            // Position of the question within the set of questions of a section (1...N)
            $table->unsignedTinyInteger('position')->nullable(false);
            // @todo find how to create a unique index on fields section_id and position
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
