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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view('project.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        $users = $user->friends();

        //$users->prepend($user);

        return view('project.create', array('users' => $users, 'user' => $user ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::with(['users', 'manager', 'tasks' => function($query){
                            $query->whereNull('task_id')->with('categoryTasks');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        //gate automaticlly check if project exists
        if( Gate::allows('destroy', $project) )
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
            
            if( $category && !empty($category) )
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

}
