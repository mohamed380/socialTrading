<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class View extends Model
{
    protected $fillable = ['project_id','user_id','ip','agent','sesstion_id'];
    protected $guard = ['id'];

    public function Project()
    {
        return $this->belongsTo('App\Project');
    }

    public static function createView($project) 
    {
        $view= new View();
        $view->project_id = $project->id;
        $view->session_id = request()->getSession()->getId();
        $view->user_id = (Auth::guard('user')->check())?Auth::guard('user')->user()->id:null; 
        $view->ip = request()->ip();
        $view->agent = request()->header('User-Agent');
        $view->save();
    }
}
