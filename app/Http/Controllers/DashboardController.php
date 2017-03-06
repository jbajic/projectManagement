<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Gate;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;

class DashboardController extends BaseController
{
    
    public function index()
    {
    	$projects = Auth::user()
    					->projects()
                        ->with('manager')
    					->where('completed', 0)
                        ->orWhere( function($query){
                            $query->where('completed', 1)
                                    ->where('manager_id', Auth::user()->id);
                        })
    					->paginate(10);

    	return view('dashboard.index', array('projects' => $projects));
    }

    public function test()
    {
    	$task = Project::where('id', 1)->first();

        dd($task->deadline);
    }

}
