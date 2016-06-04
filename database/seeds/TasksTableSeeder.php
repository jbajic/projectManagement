<?php

use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = array(
        	array(
        		'name' => 'Autorizacija',
        		'body' => 'Napraviti autorizaciju  za admina i za korisnike',
        		'estimated_time' => '5',
        		'project_id' => '1',
                'task_id' => NULL,
        	),
        	array(
        		'name' => 'Proizvodi',
        		'body' => 'Dodati klijentove proizvode bazu',
        		'estimated_time' => '10',
        		'project_id' => '1',
                'task_id' => NULL,
        	),
        	array(
        		'name' => 'Autorizacija',
        		'body' => 'Napraviti autorizaciju za korisnike',
        		'estimated_time' => '5',
        		'project_id' => '2',
                'task_id' => NULL,
        	),
        	array(
        		'name' => 'GMS',
        		'body' => 'Cijeli sustav za vođenje teretane',
        		'estimated_time' => '100',
        		'project_id' => '2',
                'task_id' => NULL,
        	),
        	array(
        		'name' => 'Pregled',
        		'body' => 'Kartica pregleda',
        		'estimated_time' => '10',
        		'project_id' => '2',
        		'task_id' => '4',
        	),
        	array(
        		'name' => 'Članovi',
        		'body' => 'Kartica članova',
        		'estimated_time' => '20',
        		'project_id' => '2',
        		'task_id' => '4',
        	),
        	array(
        		'name' => 'Raspored',
        		'body' => 'Kartica raspored',
        		'estimated_time' => '20',
        		'project_id' => '2',
        		'task_id' => '4',
        	),
        	array(
        		'name' => 'eTrener',
        		'body' => 'Kartica eTrenera',
        		'estimated_time' => '35',
        		'project_id' => '2',
        		'task_id' => '4',
        	),
        	array(
        		'name' => 'Palestar za klijente',
        		'body' => 'Strana za klijente',
        		'estimated_time' => '50',
        		'project_id' => '2',
                'task_id' => NULL,
        	),
        );
    
        DB::table('tasks')
            ->delete();

		DB::table('tasks')
			->insert($tasks);

    }
}
