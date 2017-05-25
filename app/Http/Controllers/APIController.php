<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\User;
use JWTAuth;
use Mockery\Exception;
use Validator;
use Auth;


class APIController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, array(
            'password' => 'required',
            'email' => 'required|unique:users'
        ));

        if ($validator->passes()) {
            $user = User::create($input);
            return response()->json(['status' => 1, 'userId' => $user->id]);
        } else {
            return response()->json(array('status' => 0, 'errors' => $validator->errors()->first()));
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['status' => 0, 'error' => 'wrong email or password.']);
        }
        return response()->json(['jwt' => $token]);
    }


    public function synchronizeUsers(Request $request)
    {
        $users = Auth::user()->friends();
        $usersArray = array();
        foreach ($users as $user) {
            $usersArray[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'address' => $user->address,
                'city' => $user->city
            ];
        }
        return response()->json(['status' => 1, 'users' => $usersArray]);

    }

    public function synchronizeProjects(Request $request)
    {
        try {
            $projects = Auth::user()
                ->projects()
                ->with('tasks')
                ->get();
            $projectsArray = array();
            foreach ($projects as $project) {
                $projectsArray[] = [
                    'id' => $project->id,
                    'name' => $project->name,
                    'body' => $project->body,
                    'client' => $project->client,
                    'deadline' => $project->deadline,
                    'manager_id' => $project->manager_id,
                    'completed' => $project->completed,
                    'tasks' => $project['tasks']
                ];
            }
            return response()->json(['status' => 1, 'projects' => $projectsArray]);
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'error' => $e->getMessage()]);
        }
    }

    public function createProject(Request $request){
        try {
            $input = $request->all();
            $validator = Validator::make($input, array(
                'name' => 'required',
                'body' => 'required',
                'client' => 'required',
                'deadline' => 'required',
                'manager' => 'required|exists:users,id',
            ));
            if($validator->passes()) {
                $project = new Project();
                $project->name = $input['name'];
                $project->client = $input['client'];
                $project->deadline = $input['deadline'];
                $project->body = $input['body'];

                $manager = User::find($input['manager']);
                $manager->managerTo()->save($project);

                $project->save();
                $members = array();
                if(!empty($input['members'])) {
                    foreach ($input['members'] as $memberId) {
                        $members [] = $memberId;
                    }
                }

                $project->users()->sync($members);
                return response()->json(array('status' => 1));
            } else {
                return response()->json(array('status' => 0, 'errors' => $validator->errors()->first()));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'error' => $e->getMessage()]);
        }
    }

    public function createTask(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, array(
                'name' => 'required',
                'body' => 'required',
                'completed' => 'required',
                'project_id' => 'required|exists:projects,id',
                'estimated_time' => 'required|numeric',
            ));
            if($validator->passes()) {
                $task = new Task;
                $task->name = $request->input('name');
                $task->body = $request->input('body');
                $task->estimated_time = $request->input('estimated_time');
            }
        }catch (Exception $e) {
            return response()->json(['status' => 0, 'error' => $e->getMessage()]);
        }
    }
}
