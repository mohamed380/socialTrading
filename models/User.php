<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $guard = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','state','membership_id','cover','img','storage'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Like()
    {
        return $this->belongsToMany('App\Project','likes')->withTimestamps();
    }

    public function Download()
    {
        return $this->belongsToMany('App\Project','downloads')->withTimestamps();
    }
    
    public function Following()
    {
       return $this->belongsToMany('App\User', 'followers', 'follow_id', 'user_id');
    }
        
    public function Follower()
    {
        return $this->belongsToMany('App\User', 'followers', 'user_id', 'follow_id');
    }

    public function Membership()
    {
        return $this->belongsTo('App\Membership');
    }
    public function Project()
    {
        return $this->hasMany('App\Project');
    }

    public function Report()
    {
        return $this->hasMany('App\Report');
    }

    public function exceedStorage($newSize)
    {
          return $this->storage + $newSize >= $this->Membership->wholeStorage();
    }

    public function Chat()
    {
        return $this->belongsToMany('App\Chat');
    }

    public function ChatNotifications(){
        return Message::where([['destinationID',$this->id],['destinationState',0]])->groupBy('chat_id')->pluck('chat_id')->toArray();
    }
}