<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
        DB::table('locations')->insert([
            'name' => 'Example location',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        
    }
}
