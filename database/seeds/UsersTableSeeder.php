<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
        	array(
	        	'first_name' => 'Jure',
	        	'last_name' => 'Bajić',
	        	'username' => 'jbajic',
	        	'email' => 'bajic@mail.com',
	        	'password' => bcrypt('123456'),
	        	'address' => 'K. Tomislava 56',
	        	'city' => 'Osijek',
                'country_id' => '55',
        	),
        	array(
	        	'first_name' => 'Luka',
	        	'last_name' => 'Patrun',
	        	'username' => 'lpatrun',
	        	'email' => 'patrun@mail.com',
	        	'password' => bcrypt('123456'),
	        	'address' => 'Sjenjak 66',
	        	'city' => 'Osijek',
                'country_id' => '55',
        	),
            array(
                'first_name' => 'Ivan',
                'last_name' => 'Ostenheimer',
                'username' => 'iostenheimer',
                'email' => 'ivan@mail.com',
                'password' => bcrypt('123456'),
                'address' => 'Đakovo 12',
                'city' => 'Osijek',
                'country_id' => '55',
            ),
        );

        DB::table('users')
            ->delete();

        DB::table('users')
        	->insert($users);
    }
}
