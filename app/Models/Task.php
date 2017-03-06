<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = ['name, body, estimated_time, deadline'];


    /*
        VALIDATION RULES
    */

    public static $create_rules = [
        'name' => 'required',
        'body' => 'required',
        'estimated_time' => 'required|integer',
        'category' => 'required|exists:tasks,id',
        'members' => 'array',
    ];

    public static $delete_edit_rules = [
        'category' => 'required|integer|exists:tasks,id',
        'task' => 'required|integer|exists:tasks,id',
    ];

    public static $delete_category_rules = [
        'category' => 'required|integer|exists:tasks,id',
    ];

    public static $create_category_rules = [
        'name' => 'required',
        'body' => 'required',
        'deadline' => 'required|date_format:"d.m.Y."',
        'project_id' => 'required|exists:projects,id',
    ];

    public static $get_task_rules = [
        'id' => 'required|integer|exists:tasks,id',
    ];

    public static $update_task_rules = [
        'name' => 'required',
        'body' => 'required',
        'estimated_time' => 'required|integer',
        'members' => 'array',
    ];

    public static $update_category_rules = [
        'id' => 'required|integer|exists:tasks,id',
        'name' => 'required',
        'body' => 'required',
        'deadline' => 'required|date_format:"d.m.Y."',
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


    public function getDeadlineAttribute($value)
    {
        $value = date_create($value);
        $value = date_format($value, 'd.m.Y.');
        return $value;
    }

    public function setDeadlineAttribute($value)
    {
        $this->attributes['deadline'] = date('Y-m-d', strtotime(trim($value)));
    }
}
