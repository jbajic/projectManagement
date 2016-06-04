<?php

use Illuminate\Database\Seeder;

class TaskUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $task_user = array(
        	array(
        		'task_id' => '1',
        		'user_id' => '1',
        	),
        	array(
        		'task_id' => '2',
        		'user_id' => '1',
        	),
        	array(
        		'task_id' => '3',
        		'user_id' => '1',
        	),
        	array(
        		'task_id' => '4',
        		'user_id' => '1',
        	),
        	array(
        		'task_id' => '5',
        		'user_id' => '1',
        	),
        	array(
        		'task_id' => '6',
        		'user_id' => '2',
        	),
        	array(
        		'task_id' => '7',
        		'user_id' => '2',
        	),
        	array(
        		'task_id' => '8',
        		'user_id' => '2',
        	),
        	array(
        		'task_id' => '9',
        		'user_id' => '1',
        	),
        	array(
        		'task_id' => '5',
        		'user_id' => '2',
        	),
        	array(
        		'task_id' => '9',
        		'user_id' => '2',
        	),
        );

        DB::table('task_user')
            ->delete();

        DB::table('task_user')
            ->insert($task_user);
    }
}
