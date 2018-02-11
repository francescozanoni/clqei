<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call(UsersTableSeeder::class);
        $this->call(StudentsTableSeeder::class);

        $this->call(SectionsTableSeeder::class);
        $this->call(SectionsTableHeaderFooterSeeder::class);
        $this->call(QuestionsTableSeeder::class);
        $this->call(AnswersTableSeeder::class);
        
        $this->call(LocationsTableSeeder::class);
        $this->call(WardsTableSeeder::class);

    }
}
