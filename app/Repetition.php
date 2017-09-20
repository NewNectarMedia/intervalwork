<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repetition extends Model
{
    protected $fillable = ['when', 'user_id'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}