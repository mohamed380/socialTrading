<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //

    protected $fillable = [
        'title', 'img','admin_id',
    ];

    public function Admin(){
        return $this->belongsTo('App\Admin');
    }

    public function Project(){
        return $this->belongsToMany('App\Project');
    }
}
