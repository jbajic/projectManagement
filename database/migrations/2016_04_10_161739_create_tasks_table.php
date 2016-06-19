<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function(Blueprint $table){
            $table->increments('id');
            $table->string('name', 60);
            $table->string('body');
            $table->boolean('completed')->default(false);
            $table->integer('estimated_time')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->integer('task_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('tasks', function($table){
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('task_id')->references('id')->on('tasks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tasks');
    }
}
