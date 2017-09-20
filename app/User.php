<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'slug', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function phones()
    {
        return $this->hasMany('App\Phone', 'user_id', 'id');
    }

    public function topics()
    {
        return $this->hasMany('App\Topic', 'user_id', 'id');
    }

    public function preferences()
    {
        return $this->hasMany('App\Preference', 'user_id', 'id');
    }
}
