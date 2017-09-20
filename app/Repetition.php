<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repetition extends Model
{
    protected $fillable = ['when', 'user_id','topic_id', 'timezone'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function topic()
    {
        return $this->hasOne('App\Topic', 'id', 'topic_id');
    }
}