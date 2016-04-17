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
        'username' => 'required',
        'password' => 'required',
    ];

    public static $registration_rules = [
        'username' => 'required|alpha_dash|max:30',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ];

}
