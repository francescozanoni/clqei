<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create("students", function (Blueprint $table) {
            $table->increments("id")->autoIncrement();
            $table->unsignedInteger("user_id")->nullable(false);
            $table->char("identification_number", 8)->nullable(false)->unique();
            $table->enum("gender", ["male", "female"])->nullable(false);
            // Nationality consists of the ISO country code.
            $table->char("nationality", 2)->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("user_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists("students");
    }
}
