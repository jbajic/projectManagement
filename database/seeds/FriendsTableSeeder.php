<?php

use Illuminate\Database\Seeder;

class FriendsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $friends = array(
        	array(
        		'user_id' => '1',
        		'friend_id' => '2',
        		'accepted' => true,
        	),
        );

        DB::table('friends')
       		->delete();

        DB::table('friends')
        	->insert($friends);
    }
}
