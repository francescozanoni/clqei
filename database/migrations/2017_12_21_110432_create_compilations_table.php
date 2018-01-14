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
            $table->foreign('student_id')->references('id')->on('students');
            
            $table->unsignedInteger('stage_location_id')->nullable(false);
            $table->foreign('stage_location_id')->references('id')->on('locations');
            
            $table->unsignedInteger('stage_ward_id')->nullable(false);
            $table->foreign('stage_ward_id')->references('id')->on('wards');
            
            $table->date('stage_start_date')->nullable(false);
            $table->date('stage_end_date')->nullable(false);
            
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
        Schema::dropIfExists('compilations');
    }
}
