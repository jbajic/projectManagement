<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function(Blueprint $table){
            $table->increments('id');
            $table->string('name', 60);
            $table->text('body');
            $table->string('client', 60);
            $table->timestamp('deadline');
            $table->integer('manager_id')->unsigned();
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });

        Schema::table('projects', function($table){
            $table->foreign('manager_id')->references('id')->on('user_id');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('projects');
    }
}
