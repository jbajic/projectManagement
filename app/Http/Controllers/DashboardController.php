<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Project;
use Auth;
use App\Models\User;

class DashboardController extends BaseController
{
    
    public function index()
    {
    	$projects = Auth::user()
    					->projects()
    					->where('completed', 0)
    					->take(10)
    					->get();


    	return view('dashboard.index', array('projects' => $projects));
    }

    public function test()
    {
    	$requests = Auth::user()->friendRequestPending();

        dd($requests);
    }

}
