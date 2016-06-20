<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Gate;
use Auth;
use Validator;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;

class ProjectController extends BaseController
{

    public function index()
    {
        // return view('project.index');
    }


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
            $project->deadline = date('Y-m-d', strtotime(trim($request->input('deadline'))));
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
        $project = Project::with(['users', 'manager', 'tasks' => function($query){
                            $query
                                ->whereNull('task_id')
                                ->with('categoryTasks');
                        }])
                        ->where('id', $id)
                        ->first();
        
        $project->deadline = date_create($project->deadline);
        $project->deadline = date_format($project->deadline, 'd.m.Y.');

        $countTasks = $project->getCountOfTasks();
        $solvedTasks = $project->getCountOfSolvedTasks();

        $permission = ( Auth::user()->id === $project->manager->id ) ? true : false;

        return view('project.showProject', array('project' => $project, 'countTasks' => $countTasks, 'solvedTasks' => $solvedTasks,
                        'activeTasks' => $countTasks - $solvedTasks, 'permission' => $permission));
    }


    public function edit($id)
    {
        //
    }

 
    public function update(Request $request, $id)
    {
        //
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


    public function checkTask(Request $request)
    {
        $taskId = $request->input('id');
        $checked = (bool) $request->input('checked');

        $task = Task::find($taskId);
        if( $task )
        {
            if( $task->completed === 0 && $checked == 1 )
            {
                $task->completed = 1;
                $task->save();
            }
            else
            {
                $task->completed = 0;
                $task->save();
            }
            return json_encode(array('status' => 1));
        }
        return json_encode(array('status' => 0));
    }


    public function addTask(Request $request)
    {
        $category_id = $request->input('category');

        $category = Task::find($category_id);
        $project = Project::find($category->project_id);

        if( $category && !empty($category) && $project && !empty($project) )
        {
            $validator = Validator::make($request->input(), Task::$create_rules);

            if( $validator->passes() )
            {
                $task = new Task;
                $task->name = $request->input('name');
                $task->body = $request->input('body');
                $task->estimated_time = $request->input('estimated_time');

                $task->category()->associate($category);
                $project->tasks()->save($task);

                return json_encode(array('status' => 1, 'taskId' => $task->id));
            }
            else
            {
                return json_encode(array('status' => 0, 'errors' => $validator->errors()));
            }
        }
        else
        {
            return json_encode(array('status' => 2));
        }
    }


    public function deleteTask(Request $request)
    {
        $validator = Validator::make($request->input(), Task::$delete_rules);

        if( $validator->passes() )
        {
            $task = Task::find($request->input('task'));

            if( $task->task_id == $request->input('category') && $task && !empty($task) )
            {
                $task->delete();

                return json_encode(array('status' => 1));
            }
            else
            {
                return json_encode(array('status' => 2));
            }
        }
        else
        {
            return json_encode(array('status' => 0));
        }
    }


    public function deleteCategory(Request $request)
    {
        $validator = Validator::make($request->input(), Task::$delete_category_rules);

        if( $validator->passes() )
        {
            $category = Task::find($request->input('category'));
            
            if( Gate::allows('deleteCategory', $category) )
            {
                $category->users()->detach();
                $category->categoryTasks()->delete();

                $category->delete();

                return json_encode(array('status' => 1));
            }
            else
            {
                return json_encode(array('status' => 2));
            }
        }
        else
        {
            return json_encode(array('status' => 0, 'errors' => $validator->errors()));
        }
    }


    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->input(), Task::$create_category_rules);

        if( $validator->passes() )
        {
            $project = Project::find($request->input('project_id'));

            if( Gate::allows('kingMethod', $project) )
            {
                $category = new Task;
                $category->name = $request->input('name');
                $category->deadline = date('Y-m-d', strtotime(trim($request->input('deadline'))));
                $category->body = $request->input('body');
                $project->tasks()->save($category);

                return json_encode(array('status' => 1, 'id' => $category->id));
            }
            else
            {
                return json_encode(array('status' => 2));
            }
        }
        else
        {
            return json_encode(array('stauts' => 0, 'errors' => $validator->errors()));
        }
    }

}
