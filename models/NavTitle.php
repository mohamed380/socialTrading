<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NavTitle extends Model
{
    //
    use Notifiable;

    protected $fillable = [
        'title',
    ];

    public function Category(){
        return $this->hasMany('App\Category');
    }

    
}
