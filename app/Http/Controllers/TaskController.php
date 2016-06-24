<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use Gate;
use App\Models\Task;
use App\Models\Project;


class TaskController extends Controller
{
    
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


    public function store(Request $request)
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
                if( $request->input('members') && !empty($request->input('members')) )
                {
                    $task->users()->attach($request->input('members'));
                }

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


    public function destroy($project_id, $taskId, Request $request)
    {
        $validator = Validator::make($request->input(), Task::$delete_edit_rules);

        if( $validator->passes() )
        {
            $task = Task::find($request->input('task'));

            if( $task->task_id == $request->input('category') && $task && !empty($task) )
            {
                $task->users()->detach();
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

    public function show($project_id, $taskId, Request $request)
    {
        $task = Task::with('users')->where('id', $taskId)->first();

        if( $task && !empty($task) )
        {
            return array('status' => 1, 'task' => $task);
        }
        else
        {
        return array('status' => 0);
        }
    }

    public function update($project_id, $task_id, Request $request)
    {
        $validator = Validator::make($request->input(), Task::$update_task_rules);

        if( $validator->passes() )
        {
            $task = Task::find($task_id);

            if( $task && !empty($task) )
            {
                $task->body = $request->input('body');
                $task->estimated_time = $request->input('estimated_time');
                $task->name = $request->input('name');
                $task->save();

                if( $request->input('members') && !empty($request->input('members')) )
                {
                    $task->users()->sync($request->input('members'));
                }
                else
                {
                    $task->users()->detach();   
                }

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

    public function getCategory(Request $request)
    {
        $validator = Validator::make($request->input(), Task::$get_task_rules);

        if( $validator->passes() )
        {
            $category = Task::find($request->input('id'));

            if( $category && !empty($category) )
            {
                return json_encode(array('status' => 1, 'category' => $category));
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

    public function updateCategory(Request $request)
    {
        $validator = Validator::make($request->input(), Task::$update_category_rules);

        if( $validator->passes() )
        {
            $task = Task::find($request->input('id'));

            if( $task && !empty($task) )
            {
                $task->body = $request->input('body');
                $task->deadline = date('Y-m-d', strtotime(trim($request->input('deadline'))));
                $task->name = $request->input('name');
                $task->save();

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
}
