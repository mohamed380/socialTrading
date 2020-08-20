<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class CategoryType extends Model
{
    //
    use Notifiable;

    protected $fillable = [
        'name',
    ];

    public function Category(){
        return $this->hasMany('App\Category');
    }

}
