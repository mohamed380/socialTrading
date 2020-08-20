<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    public function Admin(){
        return $this->belongsTo('App\Admin');
    }
    public function Project(){
        return $this->belongsToMany('App\Project');
    }
}
