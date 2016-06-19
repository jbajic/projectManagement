<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = array(
        	array(
        		'name' => 'WebShop',
        		'body' => 'Web shop made in Magento 2.0',
        		'client' => 'Inchoo',
        		'deadline' => '2016-08-23',
                'manager_id' => '1',
        	),
        	array(
        		'name' => 'GMS',
        		'body' => 'Gym management system made in Laravel 5, during SSA contest.',
        		'client' => 'SSA - Microsoft',
        		'deadline' => '2016-10-10',
                'manager_id' => '1',
        	),
        );

        DB::table('projects')
            ->delete();

        DB::table('projects')
        	->insert($projects);
    }
}
