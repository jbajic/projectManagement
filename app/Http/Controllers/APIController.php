<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use JWTAuth;
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
            return response()->json(array('status' => 0, 'errors' => $validator->errors()));
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['result' => 'wrong email or password.']);
        }
        return response()->json(['jwt' => $token]);
    }



    public function get_user_details(Request $request)
    {
        $input = $request->all();

        if(Auth::user() != null) {
            return response()->json(['result' => Auth::user()]);
        }
        return response()->json(['result' => "nada"]);
    }
}
