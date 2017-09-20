<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = ['name', 'phone', 'user_id'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}