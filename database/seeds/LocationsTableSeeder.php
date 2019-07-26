<?php
declare(strict_types = 1);

use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        DB::table(Location::getTableName())->insert([
            "name" => "Example location",
            "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
        ]);

    }
}
