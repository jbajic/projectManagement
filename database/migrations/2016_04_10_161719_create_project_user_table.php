<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_user', function(Blueprint $table){
            $table->integer('project_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('manager')->default(false);
            $table->string('role', 60);
        });

        Schema::table('project_user', function($table){
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('project_user');
    }
}
