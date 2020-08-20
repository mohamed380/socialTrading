<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Chat extends Model
{
    protected $fillable = [
        
    ];

    public function Message()
    {
        return $this->hasMany('App\Message');
    }

    public function User()
    {
        return $this->belongsToMany('App\User')->where('users.id','<>',Auth::id());
    }

}
