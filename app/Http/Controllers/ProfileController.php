<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Country;
use Image;

class ProfileController extends BaseController
{
    public function index()
    {
    	// get current user
    	$user = Auth::user();

        //get all countries
        $countries = Country::all();
    	
    	return view('dashboard.profile', array('countries' => $countries));
    }

    public function show($id)
    {
        $user = User::find($id);

        $user->countryName = $user->country->name;

        //relationship status
        $status = 0;
        if( Auth::user()->isFriendWith($user) )
        {
            $status = 1;
        }
        else if( Auth::user()->hasFriendRequestPending($user) )
        {
            $status = 2;
        }
        else if( Auth::user()->hasFriendRequestReceveid($user) )
        {
            $status = 3;
        }

        return view('dashboard.showProfile', array('user' => $user, 'status' => $status));
    }

    public function edit()
    {

    }

    public function update(Request $request)
    {
       $validator = Validator::make($request->input(), User::$edit_rules);

       if( $validator->passes() )
       {
            $user = Auth::user();
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->address = $request->input('address');
            $user->city = $request->input('city');
            $user->body = $request->input('body');

            $country_id = $request->input('country_id');

            if( isset($country_id) && !empty($country_id) )
            {
                $country = Country::find($country_id);
                $user->country()->associate($country);    
            }

            $user->save();

            return redirect()->route('dashboard.index');
       }
       else
       {
            return redirect()->back()->withErrors($validator);
       }
    }

    public function search(Request $request)
    {
        $this->string = $request->input('string');

        if( isset($this->string) )
        {
            $validator = Validator::make($request->all(), [
                'string' => 'required|alpha_dash',
            ]);

            if( $validator->passes() )
            {
                 $users = User::where( function($query){
                    $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$this->string%"])
                            ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%$this->string%"])
                            ->orWhereRaw("username LIKE ?", ["%$this->string%"]);
                })
                ->take(10)
                ->get();

                if( count($users) )
                {
                    foreach ($users as $user) 
                    {
                        $user->name = $user->name;
                    }
                    return json_encode(array('status' => 1, 'results' => $users));
                }
                else
                {
                    return json_encode(array('status' => 3));
                }
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

    public function changeAvatar(Request $request)
    {
        // $this->validate($request, )
        $this->validate($request, [
            'avatar' => 'required|image',
        ]);

        $user = Auth::user();

        if( $user->avatar != 'no-avatar.png' )
        {
            $oldImage = $user->avatar;

            unlink(sprintf(public_path() . '/img/avatars/' . $oldImage));
        }    

        $image = $request->file('avatar');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $path = public_path('img/avatars/' . $imageName);

        Image::make($image->getRealPath())->resize(270, 270)->save($path);

        $user->avatar = $imageName;
        $user->save();

        return redirect()->back();
    }

    public function addFriend(Request $request)
    {
        $id = $request->input('id');

        $user = User::find($id);

        if( $user )
        {
            if( $user->id !== Auth::user()->id )
            {
                if( !Auth::user()->hasFriendRequestPending($user) && !$user->hasFriendRequestPending(Auth::user()) )
                {
                    if( !Auth::user()->isFriendWith($user) )
                    {
                        Auth::user()->addFriend($user);

                        return json_encode(array('status' => 1));
                    }
                    else
                    {
                        return json_encode(array('status' => 4));
                    }
                }
                else
                {
                    return json_encode(array('status' => 3));
                }
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

    public function rescindInvitation(Request $request)
    {
        $id = $request->input('id');

        $user = User::find($id);

        if( $user )
        {
            if( Auth::user()->hasFriendRequestPending($user) || $user->hasFriendRequestPending(Auth::user()) )
            {
                Auth::user()->deleteFriend($user);

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

    public function removeFriend(Request $request)
    {
        $id = $request->input('id');

        $user = User::find($id);

        if( $user )
        {
            if( Auth::user()->isFriendWith($user) )
            {
                Auth::user()->deleteFriend($user);

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

    public function acceptFriend(Request $request)
    {
        $id = $request->input('id');

        $user = User::find($id);

        if( $user )
        {
            if( Auth::user()->hasFriendRequestPending($user) )
            {
                Auth::user()->acceptFriendRequest($user);

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

    public function refuseFriend(Request $request)
    {
        $id = $request->input('id');

        $user = User::find($id);

        if( $user )
        {
            if( Auth::user()->hasFriendRequestPending($user) || $user->hasFriendRequestPending(Auth::user()) )
            {
                Auth::user()->deleteFriend($user);

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
