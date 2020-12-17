<?php
declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowCompilationFreeTextAnswer extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Since foreign keys are lost during ALTER TABLE of SQLite database,
        // table must be re-created with the new feature.
        // @see https://github.com/laravel/framework/issues/24876

        Schema::rename("compilation_items", "compilation_items_old");
        Schema::create("compilation_items", function (Blueprint $table) {
            $table->increments("id")->autoIncrement();
            $table->unsignedInteger("compilation_id")->nullable(false);
            $table->unsignedInteger("question_id")->nullable(false);
            // Answer ID or free text answer, according to question type.
            $table->text("answer")->nullable(true);
            $table->foreign("compilation_id")->references("id")->on("compilations");
            $table->foreign("question_id")->references("id")->on("questions");
            $table->timestamps();
            $table->softDeletes();
        });

        // Data migration.
        $data = DB::table("compilation_items_old")->get()->toArray();
        echo count($data) . " records to migrate: ";
        $counter = 0;
        $recordChunkLength = 100;
        foreach (array_chunk($data, $recordChunkLength) as $recordChunk) {
            $recordChunk = array_map(
                function (stdClass $record): array {
                    return json_decode(json_encode($record), true);
                },
                $recordChunk
            );
            DB::table("compilation_items")->insert($recordChunk);
            $counter += $recordChunkLength;
            echo $counter . "... ";
        }
        echo PHP_EOL;

        Schema::drop("compilation_items_old");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::rename("compilation_items", "compilation_items_old");
        (new CreateCompilationItemsTable())->up();
        $data = DB::table("compilation_items_old")->get();
        foreach ($data as $record) {
            $record = json_decode(json_encode($record), true);
            DB::table("compilation_items")->insert($record);
        }
        Schema::drop("compilation_items_old");
    }
}
