<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Project extends Model
{
    protected $table = 'projects';



    /*
        Relationships, Scopes, Accessors, Mutators
    */

    public function users()
    {
    	return $this->belongsToMany('App\Models\User', 'project_user', 'project_id', 'user_id');
    }

    public function tasks()
    {
    	return $this->hasMany('App\Models\Task', 'project_id', 'id');
    }


    //jeli moguce u istom query dva counta napraviti i razliku?!
    public function getPercentageAttribute()
    {
        // SELECT sum(estimated_time) AS value FROM `tasks` where project_id = 1 Group by project_id
    	$tasksTime = $this->tasks()->sum('estimated_time');

    	$solvedTasksTime = $this
    	    				->tasks()
    	    				->where('completed', true)
    	    				->sum('estimated_time');

	    if( $solvedTasksTime == 0 )
	    {
	    	return number_format( 0, 2 );
	    }
	    else
	    {
	    	return number_format(( $solvedTasksTime / $tasksTime ) * 100, 2 );
	    }
    }



}