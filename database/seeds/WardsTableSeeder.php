<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
        DB::table('wards')->insert([
            'name' => 'Example ward',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        
    }
}
