<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Project extends Model
{
    protected $table = 'projects';

    // protected $dateFormat = 'd.m.Y. H:i:s';


    /**
    **  VALIDATION
    */

    public static $create_rules = [
        'name' => 'required|max:59',
        'body' => 'required',
        'client' => 'required|max:59',
        'deadline' => 'required|date_format:"d.m.Y."',
        'manager' => 'required|integer|exists:users,id',
        'members' => '',
    ];


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

    public function manager()
    {
        return $this->belongsTo('App\Models\User', 'manager_id', 'id');
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

    public function getCountOfTasks()
    {
        return $this->tasks()->whereNull('task_id')->count();
    }

    public function getCountOfSolvedTasks()
    {
        return $this->tasks()->whereNull('task_id')->where('completed', true)->count();   
    }



}