<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Gate;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Project;
use App\Models\User;

class ProjectController extends BaseController
{

    public function create()
    {
        $user = Auth::user();

        $users = $user->friends();

        //$users->prepend($user);

        return view('project.create', array('users' => $users, 'user' => $user ));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->input(), Project::$create_rules);

        if( $validator->passes() )
        {
            $project = new Project;
            $project->name = $request->input('name');
            $project->client = $request->input('client');
            $project->deadline = $request->input('deadline');
            $project->body = $request->input('body');
            
            $manager = User::find($request->input('manager'));
            
            if( $manager && !empty($manager) )
            {
                $manager->managerTo()->save($project);

                $project->save();

                $members = array();
                if( $request->input('members') !== "" )
                {
                    $members = array_map('intval', explode(',', $request->input('members')));
                }

                $members[] = $manager->id;
            
                $project->users()->attach($members); 

                return redirect()->route('dashboard.index');
            }
            else
            {
                return redirect()->back()->withInput()->withInfo('An error has occured');
            }
        }
        else
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }


    public function show($id)
    {
        // DB::enableQueryLog();

        $project = Project::with(['users', 'manager', 'tasks' => function($query){
                            $query
                                ->whereNull('task_id')
                                ->with(['categoryTasks' => function($query){
                                    $query->with('users');
                                }]);
                        }])
                        ->where('id', $id)
                        ->first();
        // dd(DB::getQueryLog());
        $countTasks = $project->getCountOfTasks();
        $solvedTasks = $project->getCountOfSolvedTasks();

        $permission = ( Auth::user()->id === $project->manager->id ) ? true : false;

        return view('project.showProject', array('project' => $project, 'countTasks' => $countTasks, 'solvedTasks' => $solvedTasks,
                        'activeTasks' => $countTasks - $solvedTasks, 'permission' => $permission));
    }


    public function edit($id)
    {
        $project = Project::with('users')->where('id', $id)->first();

        if( Gate::allows('kingMethod', $project) )
        {
            $notMembers = array();

            $user = Auth::user();

            $users = $user->friends();
            $users[] = $user;
            
            $users_count = count($users);
            $project_users_count = count($project->users);
            for( $i = 0; $i < $users_count; ++$i ) 
            {
                $flag = 0;
                for( $j = 0; $j < $project_users_count; ++$j )
                {
                    if( $users[$i]->id == $project->users[$j]->id )
                    {
                        $flag = 1;
                        break;
                    }
                }
                if( !$flag )
                {
                    $notMembers[] = $users[$i];
                }
            }
            
            return view('project.editProject', array('project' => $project, 'notMembers' => $notMembers, 'users' => $users));
        }
        else
        {
            return redirect()->back();
        }
    }

 
    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        
        if( Gate::allows('kingMethod', $project) )
        {
            $validator = Validator::make($request->input(), Project::$update_rules);

            if( $validator->passes() )
            {
                $project->update($request->input());

                $manager = User::find($request->input('manager'));

                $project->manager()->associate($manager);

                $members = array();
                if( $request->input('members') !== "" )
                {
                    $members = array_map('intval', explode(',', $request->input('members')));
                }

                $members[] = $manager->id;
            
                $project->users()->sync($members);

                return redirect()->route('project.show', array('id' => $id));
            }
            else
            {
                return redirect()->back()->withErrors($validator);
            }
        }
        else
        {
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $project = Project::find($id);
        //gate automaticlly check if project exists
        if( Gate::allows('kingMethod', $project) )
        {
            $project->users()->detach();
            $project->tasks()->whereNull('task_id')->delete();
            $project->tasks()->delete();
            $project->delete();
        }
        return redirect()->route('dashboard.index')->withInfo('Project has been deleted!');
    }

    public function changeStatus($id)
    {
        $project = Project::find($id);

        if( Gate::allows('kingMethod', $project) )
        {
            if( $project->completed == 1 )
            {
                $project->completed = 0;
            }
            else
            {
                $project->completed = 1;
            }
            $project->save();
            return redirect()->route('dashboard.index');
        }
        else
        {
            return redirect()->back();
        }
    }




}
