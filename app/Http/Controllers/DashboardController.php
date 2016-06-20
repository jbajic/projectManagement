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
    					->take(10)
    					->get();

    	return view('dashboard.index', array('projects' => $projects));
    }

    public function test()
    {
    	$category = Task::find(15);

        if( Gate::allows('deleteCategory', $category) )
        {
            dd('success');
        }
        else
        {
            dd('nein');
        }
    }

}
