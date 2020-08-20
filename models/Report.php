<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $fillable = [
        'complain', 'user_id', 'project_id',
    ];

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function Project()
    {
        return $this->belongsTo('App\Project');
    }
}
