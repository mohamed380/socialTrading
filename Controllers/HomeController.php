<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Project;
use App\User;
use App\Tag;
use App\Program;
use App\Category;
use App\Subcategory;
use App\Message;
use App\View;
use App\Download;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function HomeProjects()
    {

        $projects = Project::with('User')->withCount('Like')->withCount('View')->withCount('Download')->get()
        ->sortByDesc('download_count')->sortByDesc('view_count')->sortByDesc('like_count')->take(3);
        return $projects->values();
    }
}
