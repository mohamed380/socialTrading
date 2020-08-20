<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Download extends Model
{
    protected $fillable = ['project_id','user_id','ip','agent','sesstion_id'];
    protected $guard = ['id'];

    public function Project()
    {
        return $this->belongsTo('App\Project');
    }

    
    public static function createDownload($project) 
    {
        $download= new Download();
        $download->project_id = $project->id;
        $download->session_id = request()->getSession()->getId();
        $download->user_id = (Auth::guard('user')->check())?Auth::guard('user')->user()->id:null; 
        $download->ip = request()->ip();
        $download->agent = request()->header('User-Agent');
        $download->save();
    }
}
