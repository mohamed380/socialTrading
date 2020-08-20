<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use Notifiable;

    
    protected $fillable = [
        'title', 'description','state','file','img','type','price','project_offer_id','category_id',
        'subcategory_id','user_id','admin_id','size'
    ];

    public function Image()
    {
        return $this->hasMany('App\Image');
    }

    public function Category()
    {
        return $this->belongsTo('App\Category');
    }

    public function Subcategory()
    {
        return $this->belongsTo('App\Subcategory');
    }

    public function Project_offer()
    {
        return $this->belongsTo('App\Project_offer');
    }

    public function Program()
    {
        return $this->belongsToMany('App\Program');
    }

    public function Tag()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function Admin()
    {
        return $this->belongsTo('App\Admin');
    }

    public function Like()
    {
        return $this->belongsToMany('App\User','likes')->withTimestamps();
    }
    
    public function Download()
    {
        return $this->hasMany('App\Download');
    }

    public function View()
    {
        return $this->hasMany('App\View');
    }

    public function Report()
    {
        return $this->hasMany('App\Report');
    }
    public function showProject()
    {   
        if(Auth::guard('user')->user()== null){
            return $this->View()
            ->where('ip', '=',  request()->ip())->exists();
        }
        return $this->View()
        ->where(function($postViewsQuery) { $postViewsQuery
            ->where('session_id', '=', request()->getSession()->getId())
            ->orWhere('ip','=',(Auth::guard('user')->user()->id))
            ->orWhere('user_id', '=', (Auth::guard('user')->user()->id));})->exists();  
    }

    public static function boot(){
        parent::boot();
        static::deleting(function($project){
            $project->View()->delete();
            $project->Download()->delete();
            $project->Image()->delete();
            $project->Like()->delete();
            $project->Report()->delete();
            $project->Tag()->sync([]);
            $project->Program()->sync([]);
        });
    }
    // public function delete()
    // {
    //     $this->View()->delete();
    //     $this->Download()->delete();
    //     $this->Image()->delete();
    //     $this->Like()->delete();
    //     $this->Tag()->delete();
    //     $this->Program()->delete();
    //     return parent::delete();
    // }

    /*public function withRelations()
    {
       return $this->with('Category')->with('Subcategory')->with('Tag')->with('Program')->with('User')->withCount('View')->withCount('Download')
       ->withCount('Like');
    }*/

    

}
