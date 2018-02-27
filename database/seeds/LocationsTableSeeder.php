<?php
declare(strict_types = 1);

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

        DB::table('locations')->insert([
            'name' => 'Example location',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

    }
}
