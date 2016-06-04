<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';


    /*
        Relationships, Scopes, Accessors, Mutators
    */

    public function project()
    {
    	return $this->belongsTo('App\Models\Project', 'project_id');
    }
}
