<?php
declare(strict_types = 1);

use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        DB::table(Ward::getTableName())->insert([
            "name" => "Example ward",
            "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
        ]);

    }
}
