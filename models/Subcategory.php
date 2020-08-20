<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    public function Category(){
        return $this->belongsTo('App\Category');
    }
    public function Project(){
        return $this->hasMany('App\Project');
    }
}
