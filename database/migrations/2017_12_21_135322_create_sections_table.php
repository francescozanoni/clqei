<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create("sections", function (Blueprint $table) {
            $table->increments("id")->autoIncrement();
            $table->string("text")->nullable(false);
            // Position of the section within the set of section (1...N)
            $table->unsignedTinyInteger("position")->nullable(false)->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists("sections");
    }
}
