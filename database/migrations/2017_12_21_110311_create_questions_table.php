<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create("questions", function (Blueprint $table) {
            $table->increments("id")->autoIncrement();
            $table->string("text")->nullable(false);
            $table->unsignedInteger("section_id")->nullable(false);
            $table->enum("type", ["single_choice", "multiple_choice", "text"])->nullable(false)->default("text");
            $table->boolean("required")->nullable(false)->default(true);
            $table->json("options")->nullable(true);
            // Position of the question within the set of questions of a section (1...N)
            $table->unsignedTinyInteger("position")->nullable(false);
            $table->foreign("section_id")->references("id")->on("sections");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists("questions");
    }
}
