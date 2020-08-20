<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'title', 'body','cost','dowloadsTimes','duration','storage','admin_id','membership_offer_id','admin_id',
    ];

    public function User()
    {
        return $this->hasMany('App\Users_Work');
    }

    public function Membership_offer()
    {
        return $this->belongsTo('App\Membership_offer');
    }
    public function wholeStorage()
    {
        if($this->Membership_offer_id){
            return ($this->Membership_offer()->storage + $this->storage);
        }
            return  $this->storage;
    }
}
