<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    use Notifiable;

    
    protected $fillable = [
        'title', 'img','cost','storage', 'admin_id','categoryType_id','NavTitle_id',
    ];

    public function Admin(){
        return $this->belongsTo('App\Admin');
    }
    public function Project(){
        return $this->hasMany('App\Project');
    }
    public function Subcategory(){
        return $this->hasMany('App\Subcategory');
    }

    public function CategoryType(){
        return $this->belongsTo('App\CategoryType');
    }

    public function NavTitle()
    {
        return $this->belongsTo('App\NavTitle');
        
    }
}
