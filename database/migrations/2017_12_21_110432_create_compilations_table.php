<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompilationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compilations', function (Blueprint $table) {
            $table->increments('id')->autoIncrement();
            $table->unsignedInteger('student_id')->nullable(false);
            // @todo choose table structure: compilations stored vertically or horizontally?
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compilations');
    }
}
