<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname', 'lname', 'email', 'mobile', 'country_id', 'password', 'user_role', 'company_name', 'website', 'video', 'twitter_link', 'facebook_page', 'speciality', 'description', 'status', 'userRemind', 'imagesUser', 'imagesCertificate', 'address', 'crtDL', 'crtPL', 'crtFI',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
