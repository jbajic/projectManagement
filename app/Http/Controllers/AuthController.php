<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests;
use Auth;
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    public function __construct()
    {
    	$this->middleware('guest', ['except' => 'logout']);
    }

    //get login
    public function getLogin()
    {
    	return view('auth.login');
    }

    //get register
    public function getRegister()
    {
    	return view('auth.register');
    }

    //login user
    public function postLogin(Request $request)
    {
    	$validator = Validator::make($request->input(), User::$login_rules);

        $throttles = $this->isUsingThrottlesLoginsTrait();

        if( $throttles && $this->hasTooManyLoginAttempts($request) )
        {
             return $this->sendLockoutResponse($request);
        }
        else
        {
            if ($throttles) 
            {
                $this->incrementLoginAttempts($request);
            }

            if( $validator->passes() )
            {
                if( Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')],
                     $request->input('remember_me')) )
                {
                    return redirect()->route('dashboard.index');
                }
                else
                {
                    return redirect()->back()->withInput()->with('message', 'The email and password you entered don\'t match.');
                }
            }
            else
            {
                return route()->back()->withInput()->withErrors($validator);
            }
        }    	
    }

    //register user
    public function postRegister(Request $request)
    {
		//create validator
		$validator = Validator::make($request->input(), User::$registration_rules);

		if( $validator->passes() )
		{
				$user = new User;
				// $user->username = $request->input('username');
				$user->email = $request->input('email');
				$user->password = $request->input('password');
				// $user->confirmation_code = $confirmation_code;
				$user->save();

				// Mail::send('emails.verify', ['confirmation_code' => $confirmation_code, 'email' => Input::get('email')], function($message){

				// 	$name = Input::get('f_name')." ".Input::get('l_name');
				// 	$message->to(Input::get('email'), $name)
				// 				->subject('Potvrdite vašu email adresu');
				// });
				return redirect()->route('login.get');	
		}
		else
		{
			return redirect()->back()->withInput()->withErrors($validator)->with('state', 1);
		}
    }

    //logout user
    public function logout()
    {
        Auth::logout();

        return redirect()->route('login.get');
    }

}
