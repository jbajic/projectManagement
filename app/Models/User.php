<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
        Validation rules
    */
    
    public static $login_rules = [
        'email' => 'required',
        'password' => 'required',
    ];

    public static $registration_rules = [
        // 'username' => 'required|alpha_dash|max:30',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ];

    public static $edit_rules = [
        'first_name' => 'alpha_dash',
        'last_name' => 'alpha_dash',
        'email' => 'required|email',
        'address' => '',
        'city' => 'alpha_dash',
        'country_id' => 'integer|exists:countries,id',
        'body' => '',
    ];

    /*
        Relationships, Scopes, Accessors, Mutators
    */

    public function getNameAttribute()
    {
        if( $this->first_name && $this->last_name )
        {
            return $this->first_name.' '.$this->last_name;
        }
        else if( $this->username )
        {
            return $this->username;
        }
        else
        {
            return $this->email;
        }
    }


    public function tasks()
    {
        return $this->belongsToMany('App\Models\Task', 'task_user', 'user_id', 'task_id');
    }

    public function projects()
    {
        return $this->belongsToMany('App\Models\Project', 'project_user', 'user_id', 'project_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id', 'id');
    }

    public function managerTo()
    {
        return $this->hasMany('App\Models\Project', 'manager_id', 'id');
    }

    /*
        FRIENDS functions
    */

    //return all friends to whom the user has send request
    public function myFriends()
    {
        return $this->belongsToMany('App\Models\User', 'friends', 'user_id', 'friend_id');
    }

    //return all friends who have sent request to user
    public function friendsOf()
    {
        return $this->belongsToMany('App\Models\User', 'friends', 'friend_id', 'user_id');
    }

    public function friends()
    {
        return $this->myFriends()->wherePivot('accepted', true)->get()
                    ->merge($this->friendsOf()->wherePivot('accepted', true)->get());
    }

    //get all request sent by me that have not been accepted
    public function friendRequests()
    {
        return $this->myFriends()->wherePivot('accepted', false)->get();
    }

    //get friends requests send to me that have not been accepted
    public function friendRequestPending()
    {
        return $this->friendsOf()->wherePivot('accepted', false)->get();
    }

    //check if user has a pending request from another user
    public function hasFriendRequestPending( User $user )
    {
        return (bool) $this->friendRequestPending()->where('id', $user->id)->count();
    }

    //check if has unaccepted friends requests
    public function hasFriendRequestReceveid( User $user )
    {
        return (bool) $this->friendRequests()->where('id', $user->id)->count();
    }

    //add a friend
    public function addFriend( User $user )
    {
        $this->myFriends()->attach($user->id);
    }

    //delete friend
    public function deleteFriend( User $user )
    {
        $this->friendsOf()->detach($user->id);
        $this->myFriends()->detach($user->id);
    }

    //accept friend request
    public function acceptFriendRequest( User $user )
    {
        $this->friendRequestPending()->where('id', $user->id)->first()->pivot
                                                                ->update(array(
                                                                    'accepted' => true));
    }

    //check if a auth is friend with a particular user
    public function isFriendWith( User $user )
    {
        return (bool) $this->friends()->where('id', $user->id)->count();
    }

}
