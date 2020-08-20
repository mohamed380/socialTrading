<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project_offer extends Model
{
    public function Project()
    {
        return $this->hasMany('App\Project');
    }
}
