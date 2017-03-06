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
        $this->call(CountriesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(FriendsTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(ProjectUserTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(TaskUserTableSeeder::class);
    }
}

