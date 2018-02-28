<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySectionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->renameColumn('text', 'title');
        });
        // Renaming and addition operations performed together seem to make the migration fail.
        Schema::table('sections', function (Blueprint $table) {
            $table->text('header')->nullable(true);
            $table->text('footer')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn(['header', 'footer']);
        });
        Schema::table('sections', function (Blueprint $table) {
            $table->renameColumn('title', 'text');
        });
    }
}
