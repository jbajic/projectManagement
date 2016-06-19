<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';


    /*
        VLIDATION RULES
    */

    public static $create_rules = [
        'name' => 'required',
        'body' => 'required',
        'estimated_time' => 'required|integer',
        'category' => 'required|exists:tasks,id',
    ];

    public static $delete_rules = [
        'category' => 'required|integer|exists:tasks,id',
        'task' => 'required|integer|exists:tasks,id',
    ];

    public static $delete_category_rules = [
        'category' => 'required|integer|exists:tasks,id',
    ];

    /*
        Relationships, Scopes, Accessors, Mutators
    */

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'task_user', 'task_id', 'user_id');
    }

    public function project()
    {
    	return $this->belongsTo('App\Models\Project', 'project_id');
    }

    public function categoryTasks()
    {
    	return $this->hasMany('App\Models\Task', 'task_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Task', 'task_id', 'id');
    }

    // public function getCountOfTasksFromCategory()
    // {
    // 	return $this->categoryTasks()->count();
    // }

    // public function getCountOfSolvedTasksFromCategory()
    // {
    //     return $this->categoryTasks()->where('completed', true)->count();   
    // }
}
