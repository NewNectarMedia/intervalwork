<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['name', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function repetitions()
    {
        return $this->hasMany('App\Repetition', 'topic_id', 'id');
    }
}