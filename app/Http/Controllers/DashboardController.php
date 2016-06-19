<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Project;
use Auth;
use App\Models\User;
use App\Models\Task;

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
    	$category = Task::find(4);
        $category = $category->users()->get();
        dd($category);
    }

}
