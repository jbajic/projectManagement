<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 30);
            $table->string('last_name', 30);
            $table->string('username', 30);
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('address', 60);
            $table->string('city', 60);
            $table->string('avatar')->default('no-avatar.png'); 
            $table->text('body');
            $table->string('confirmation_code', 30)->nullable();
            $table->boolean('activated')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
