<?php

use Illuminate\Database\Seeder;

class ProjectUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project_user = array(
        	array(
        		'project_id' => '1',
        		'user_id' => '1',
        		'role' => 'Developer',
        	),
        	array(
        		'project_id' => '2',
        		'user_id' => '1',
        		'role' => 'Backend developer',
        	),
        	array(
        		'project_id' => '2',
        		'user_id' => '2',
        		'role' => 'Frontend developer',
        	),
        );

        DB::table('project_user')
            ->delete();

        DB::table('project_user')
            ->insert($project_user);
    }
}
