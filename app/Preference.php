<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    protected $fillable = ['language', 'pattern', 'use_calendar', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}