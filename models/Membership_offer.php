<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership_offer extends Model
{
    //

    public function Membership()
    {
        return $this->hasMany('App\Membership');
    }
}
