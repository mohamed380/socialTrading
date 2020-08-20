<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Project;
use App\User;
use App\Tag;
use App\Program;
use App\Category;
use App\Subcategory;
use App\Message;
use App\View;
use App\Download;
use App\Image;
use App\Notifications\projectLiked;
use Intervention\Image\Facades\Image as ImageEditor;

class ProjectsController extends Controller
{

    public function proStati()
    {
        return view('admin.adminStatistics');
    }
    
    public function projects()
    {
        return view('admin.projects.adminProject');
    }

    public function proSearch()
    {
         $category  = Category::all();
        return view('admin.projects.adminProjectSearch')->with('categories',$category);
    }

    public function searchProjectsByCategory($id)
    {
        $category = Category::find($id);
        $subc = $category->Subcategory;
        $projects = $category->Project;
        return view('admin.projects.searchedProjects')->with('subcategories',$subc)->with('projects',$projects);
    }

    public function searchProjectsByToken($token) 
    {
        $projects = Project::where([['title','like', '%'. $token .'%']])->get();
        return view('admin.projects.searchedProjects')->with('subcategories',null)->with('projects',$projects);
    }

    public function directSearch($token)
    {
        $projects = Project::select('id','title')->where([['title','like', '%'. $token .'%']])->get();
        return $projects;
    }

    public function manageProjects()
    {
        $projects = Project::all();
        return view('admin.projects.manageProjects')->with('projects',$projects);
    } 

    public function subCategoryProjects($id)
    {
        $subcategory = Subcategory::Find($id);
        $projects = $subcategory->Project;
        return view('admin.projects.ProjectsTemplate')->with('projects',$projects);
    }

    public function projectInfo($id)
    {
        $project = Project::Find($id);
        return view('admin.projects.projectInfo')->with('project',$project);
    }

    public function activeProjects($num)
    {
        $projects = Project::where('state','=','approved')->skip($num)->take(10)->get();
        return view('admin.projects.ProjectsTemplate')->with('projects',$projects);
    }
    public function pendingProjects($num)
    {
        $projects = Project::where('state','pending')->skip($num)->take(10)->get();
        return view('admin.projects.ProjectsTemplate')->with('projects',$projects);
    }

    public function rejectedProjects($num)
    {
        $projects = Project::where('state','reject')->skip($num)->take(10)->get();
        return view('admin.projects.ProjectsTemplate')->with('projects',$projects);
    }

    public function addProjectView()
    {
        return view('admin.projects.adminAddProduct')->with('tags',Tag::all())->with('programs',Program::all())
        ->with('categories',Category::all())->with('subcategories',Subcategory::all());
    }

    public function addProject(Request $request)
    {
        $x = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'imgs' => 'required',
            'file' => 'required',
            'type' => 'required',
            'category' => 'required',
            'subcategory' => 'required',
            'tags' => 'required',
            'programs' => 'required',
        ]);

        $project = new Project();
        $project->state = Auth::guard('admin')->user() ?  "approved" : "pending";
        $project->user_id = Auth::guard('user')->user() ?  Auth::guard('user')->user()->id : null ;
        $project->admin_id = Auth::guard('admin')->user() ?  Auth::guard('admin')->user()->id : null ;
        if(!($project->admin_id ||  $project->user_id)){
            return json_encode(false);
        }
        if($project->user()){
            $imgsSize = 0;
            foreach($request->imgs as $img){
                $imgsSize += $img->getSize();
            }
            $folderSize = ($request->file('file')->getSize()+$imgsSize)/1073741824;
            if($project->user->exceedStorage($folderSize)){
                return json_encode(false);
            }
        }
        $category = new Category();
        $category = Category::find($request->category);
        $subcategory = new Subcategory();
        $subcategory = Subcategory::find($request->subcategory);
        if(!$category || !$subcategory){
            return json_encode(false);
        }
        $project->category_id = $request->category;
        $project->subcategory_id = $request->subcategory;
        $tags = $request->input('tags');
        $programs = $request->input('programs');
        $project->title = $request->input('title');
        if($request->input('type') == "true"){
            if($request->validate(['price' => 'required'])){
                $project->price = $request->input('price');
        }
       }
       $project->type = $request->input('type'); 
       $project->description = $request->input('description');
       if($project->save()){
            $directory = 'Projects/'.$project->id.time();
            mkdir(public_path('storage/'.$directory));
            if( $request->file('imgs') && $request->file('file')){
                $extension = $request->file('file')->getClientOriginalExtension();
                if($extension == 'zip' || $extension == 'rar' || $extension == 'arj' || $extension == 'tgz'  ){
                    $project->file  = $request->file('file')->store($directory);
                }
                if($imagesSize = Image::upload($request, $project, $directory)){
                    $project->size = ($request->file('file')->getSize()/1073741824) + $imagesSize;
                    $project->save();
                    if($project->user){
                        $project->user->storage +=  $project->size;
                        $project->user->save();
                    }
                }else{
                    return json_encode(false);
                }
            }else{
                $project->delete();
                return json_encode(false);
            }
            $project->Tag()->attach($tags);
            $project->Program()->attach($programs);
            $response = true;
            if(Auth::guard('user')->user()){
                $response = Project::where('id',$project->id)->with('Category')->with('Subcategory')->with('Tag')->with('Program')->with('User')->withCount('View')->withCount('Download')
                                                                                  ->withCount('Like')->first();
            }
            echo json_encode($response);
            exit;
            }
    }

    public function EditProject(Request $request)
    {
        if($request->id!=null && ($request->path() == "editAdminProject" || $request->path() == "editUserProject")){
           $project =  Project::find($request->id);
           if($request->path() == "editUserProject"){
                if(!(Auth::guard('user')->user()->id == $project->user_id)){
                    return false;
                }
           }elseif($request->path() == "editAdminProject"){
                if(!(Auth::guard('admin')->user()->id == $project->admin_id)){
                    return false;
                }
            }   
        }else{
            return false;
        }
        $x = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'type' => 'required',
            'category' => 'required',
            'subcategory' => 'required',
            'tags' => 'required',
            'programs' => 'required',
        ]);
        $category = $project->category_id == $request->category ? $project->category_id:Category::find($request->category)->id;
        $subcategory = $project->subcategory == $request->subcategory ? $project->subcategory:Subcategory::find($request->subcategory)->id;
        $project->category_id = $category;
        $project->subcategory_id = $subcategory;
        $tags = $request->input('tags');
        $programs = $request->input('programs');
        $project->description = $request->input('description');
        $project->title = $request->input('title');
        $project->type = $request->input('type');
        /*    $project->path = 'uploads/Projects/'.$category->title.'/'.$subcategory->title.'/'.$project->title.'-'.time();
            if($image = $request->file('img'))
            {
                $extension = $image->getClientOriginalExtension();
                
                if($extension == 'png' || $extension == 'jpg' )
                {
                    $filename = $project->title."." . $extension;
                    $image->move($project->path,$filename);
                    $project->img = $filename;
                }
            }

            if($file = $request->file('file')){
                $extension = $file->getClientOriginalExtension();
                if($extension == 'zip' || $extension == 'rar' || $extension == 'arj' || $extension == 'tgz'  ){
                    $filename = $project->title.".". $extension;
                    $file->move($project->path,$filename);
                    $project->file = $filename;
                }else{
                    
                }
            }
        */
       if($project->type == "premium"){
            if($request->validate(['price' => 'required'])){
                $project->price = $request->input('price');
                $project->state = 'pending';
            }
       }   
       $attach =[];
       $detach = [];
       $old = $project->Tag->pluck('id')->toArray();
            foreach($tags as $tag){
                if(!in_array($tag,$old)){
                    array_push($attach,$tag);
                }
            }
            foreach($old as $tag){
                if(!in_array($tag,$tags)){
                    array_push($detach,$tag);
                }
            }
            $project->Tag()->attach($attach);
            $project->Tag()->detach($detach);
        $attach = [];
        $detach = [];
        $old = $project->Program->pluck('id')->toArray();
        foreach($programs as $program){
            if(!in_array($program,$old)){
                array_push($attach,$program);
            }
        }
        foreach($old as $program){
            if(!in_array($program,$programs)){
                array_push($detach,$program);
            }
        }
         $project->Program()->attach($attach);
         $project->Program()->detach($detach);
         $project->save();
            $response = Project::where('id',$project->id)->with('Tag')->with('Category')->with('Program')->with('Subcategory')->first();
            echo json_encode($response);
            exit;
        
    }

   public function deleteProject($id)
   {
    $response = false;
    $project = Project::find($id);
    if(Auth::guard('user')->user() && Auth::guard('user')->user()->id == $project->user_id){
        $user = User::find($project->user_id);
        $xx = $user->storage;
        $user->storage = $user->storage - $project->size;
    }
    $file = $project->file;
    if($project->delete()){
        $projectDirectory = public_path('storage/').dirname($file);
        array_map('unlink',glob("$projectDirectory/*.*"));
        // rmdir($projectDirectory);
        if(Auth::guard('user')->user()){
                $user->save();
            $response = $user->storage+1;
        }
        else{
            $response = true;
        }
    }
    echo json_encode($response);
    exit;
   }

   public function approveProject($id)
   { 
    $response['state'] = "false";
    $response['mail'] = "false";
    try{
        $project  = Project::find($id);
        $mail = new SendMailController();
        $response['mail'] = $mail->mailsend($project,'Approve Message');
        $project->state = "approved";
        if($project->save()){
            $response['state'] = "true";
        }
        echo json_encode($response);
        exit;
        
    }catch(\Exception $e){
         echo json_encode($response);
         exit;
    }
   }

   public function banProject($id)
   {
    $response['state'] = "false";
    $response['mail'] = "false";
    try{
        $project  = Project::find($id);
        $mail = new SendMailController();
        $response['mail'] = $mail->mailsend($project,'Ban Message');
        $project->state = "ban";
        if($project->save()){
            $response['state'] = "true";
        }
        echo json_encode($response);
        exit;
        
    }catch(\Exception $e){
         echo json_encode($response);
         exit;
    }
       
   }

   public function rejectProject($id)
   {
    $response['state'] = "false";
    $response['mail'] = "false";
    try{
        $project  = Project::find($id);
        $mail = new SendMailController();
        $response['mail'] = $mail->mailsend($project,'Reject Message');
        $project->state = "reject";
        if($project->save()){
            $response['state'] = "true";
        }
        echo json_encode($response);
        exit;
        
    }catch(\Exception $e){
         echo json_encode($response);
         exit;
    }
   }

   public function submitPrice($id,$price)
   {
         $response = "false";
         $project  = Project::find($id);
         $project->price = $price;
         if($project->save()){
            $response = "true";
            echo json_encode($response);
            exit;
        }else{
        echo json_encode($response);
        exit;
        }
   }

   public function searchProject($name)
   {
       $response['state'] = "false";
       $projects = Project::where([['title','like', '%'. $name .'%'],['type','premium']])->get();
       if($projects->count()>0){
            $response['projects'] = $projects;
            $response['state'] = "true";
       }else{
        $response['state'] = "false";
       }
       echo json_encode($response);
            exit;
   }

   public function addOfferToProject($pid,$oid)
   {
       $respose = 'false';
       $project = Project::find($pid);
       $project->project_offer_id = $oid;
       if($project->save()){
            $response = 'true';
       }
       echo json_encode($response);
            exit;
   }

   public function removeOfferToProject($pid)
   {
       $respose = 'false';
       $project = Project::find($pid);
       $project->project_offer_id = null;
       if($project->save()){
            $response = 'true';
       }
       echo json_encode($response);
            exit;
   }

   public function likeProject($id)
   {
       $response = null;
           $query = DB::table('likes')->where([['user_id',Auth::guard('user')->user()->id],['project_id',$id]])->first();
        
           if(Auth::guard('user')->user()){
            $user = Auth::guard('user')->user();
            if($query){
                $user->Like()->detach($id);
                $response = 'unliked';
            }else{
                $user->Like()->attach($id);
                $project = Project::find($id);
                User::find($project->user->id)->notify(new projectLiked($project,$user));
                $response = 'liked';
            }  
        }else{
            $response = null;
        }
        echo json_encode($response);
        exit;
       
      
      
   }

   public function searchProjects($query = null, $length = null)
   {
        $query = json_decode($query,true);
        $projects = collect();
        foreach($query['categories'] as $categoryTitle){
            if($categoryTitle!=null){
                $category= Category::where('title',$categoryTitle)->first(); 
                if($category){
                    $result = Project::where('category_id',$category->id)->withCount('Download')->withCount('Like')->withCount('View')
                                                                            ->with('Tag')->with('Subcategory')->with('Category')->with('User')->get();
                    $projects = $projects->merge($result);
                }
            }
        }
        foreach ($query['subcategories'] as $subTitle){
            if($subTitle!=null){
                $subc = Subcategory::where('title',$subTitle)->first();
                if($subc){
                    $result = Project::where('subcategory_id',$subc->id)->withCount('Download')->withCount('Like')
                                    ->withCount('View')
                                    ->with('Tag')->with('Subcategory')->with('Category')->with('User')->get();
                    $projects = $projects->merge($result);
                }
            }
        }
        foreach ($query['programs'] as $programTitle){
            if($programTitle!=null){
                $resultPrograms = Program::where('title',$programTitle)->with('Project')->first();
                if($resultPrograms){
                    $projectsResult = Project::whereIn('id',($resultPrograms->Project->pluck('id'))->toArray())
                                            ->withCount('Download')->withCount('Like')->withCount('View')
                                            ->with('Tag')->with('Subcategory')->with('Category')->with('User')->get();
                    $projects = $projects->merge($projectsResult);
                }
            }
        }
        foreach($query['tags'] as $tag){
            if($tag){
                $resultTag = Tag::where('title',$tag)->with('Project')->first();
                if($resultTag != null){
                    $projectsResult = Project::whereIn('id',($resultTag->Project->pluck('id'))->toArray())
                                            ->withCount('Download')->withCount('Like')->withCount('View')
                                            ->with('Tag')->with('Subcategory')->with('Category')->with('User')->get();
                    $projects = $projects->merge($projectsResult);
                }
            }
        }

       $projects = $projects->unique('id');
       $skip=0;
       if($length==null || $length <=1){
        $skip = 0;
       }else{
        $skip = $length;
       }
       $projects = $projects->slice($skip*3,6);
       $result = [];
       foreach($projects->chunk(3) as $porjectChunk)
       {
          $chunk = ['projects' => $porjectChunk->toArray()];
          array_push($result,$chunk);
       }
        return $result;
   }

   public function getProjects($number=null)
   {
       $number = $number==null? 0 : $number;
       $projects =collect();
       $projects = Project::latest()->withCount('Download')->withCount('Like')->withCount('View')->with('Tag')->with('Subcategory')->with('Category')->get();
       $projects = $projects->unique();
       $currentUserLikes = [];
       if(Auth::guard('user')->user()){
        $currentUserLikes =DB::Table('likes')->select('project_id')->where('user_id',Auth::guard('user')->user()->id)->get();
       }
    $result = [
        "projects" => $projects,
        "likes"    => $currentUserLikes,
    ];
    return $result;
   }

   public function followingProjects($length = 0)
    {
      if($user = Auth::guard('user')->user())
        {
          $following = $user->Following->pluck('id')->toArray();
          $projects = Project::whereIn('user_id',$following)->latest()->withCount('Download')->withCount('Like')->withCount('View')
                                                       ->with('Tag')->with('Subcategory')->with('Category')->with('User')->get()->skip($length*2)->take(6);
            $projects = $projects->unique('id');
            $result = [];
          foreach($projects->chunk(2) as $porjectChunk)
          {
             $chunk = ['projects' => $porjectChunk->toArray(), 'category' => Category::inRandomOrder()->get()->random()];
             array_push($result,$chunk);
          }
           return $result;
      }
   }

   public function explore($length = 0)
   {
    //    foreach(Project::all() as $project)
    //    {
    //        $project->size = Storage::size(public_path().'/'.$project->path.'/'.$project->file);
    //        $project->save();
    //    }
       $projects = Project::latest()->skip($length*3)->take(6)->with('User')->withCount('Download')->withCount('Like')->withCount('View')->with('Tag')->with('Subcategory')->with('Category')->get();
       $result = [];
       $projects = $projects->unique('id');
       foreach($projects->chunk(3) as $porjectChunk)
        {
           $chunk = ['projects' => $porjectChunk->toArray(), 'category' => Category::inRandomOrder()->get()->random()];
           array_push($result,$chunk);
        }
        return $result;
   }

   public function getProject($id = null)
   {
        if($id){
            $project = Project::where('id',$id)->with('User')->withCount('Download')->withCount('Like')->withCount('View')->with('Tag')->with('Subcategory')->with('Category')->with('Image')->first();
            $state = false;
            if(Auth::guard('user')->user()){
                $query =DB::Table('likes')->where([['user_id',Auth::guard('user')->user()->id],['project_id',$id]])->first();
                if($query){
                    $state = true;
                }
            }
            $respose = [
                'project' => $project,
                'like' => $state,
            ];
            if($project->showProject()){ //models' function to check if user viewed that project
                //if user viewed that project return it 
                return $respose;
            }
                View::createView($project);
                $respose['project'] = Project::where('id',$id)->with('User')->withCount('Download')->withCount('Like')->withCount('View')->with('Tag')->with('Subcategory')->with('Category')->with('Image')->first();
            return $respose;
        }
        return false;
   }

   public function getRelatedProjects($id = null, $length = 0)
   {
       if($id){
            $project = Project::where('id',$id)->first();
            $category = Category::where('id',$project->Category->id)->first();
            $subcategory = Subcategory::where('id',$project->Subcategory->id)->first();
            $relatedProjects = collect();
            foreach($subcategory->Project() as $rproject){
                $projectData =  Project::where([['id','!=' ,$id],['id',$rproject->id]])->with('User')->withCount('Download')->withCount('Like')->withCount('View')->with('Tag')->with('Subcategory')->with('Category')->first();
                if($projectData){
                    $relatedProjects->push($projectData);
                }
            }
            foreach($category->project as $rproject){
                $projectData =  Project::where([['id','!=' ,$id],['id',$rproject->id]])->with('User')->withCount('Download')->withCount('Like')->withCount('View')->with('Tag')->with('Subcategory')->with('Category')->first();
                if($projectData){
                    $relatedProjects->push($projectData);
                }
            }
             $result = [];
             $relatedProjects = $relatedProjects->slice($length*3,6);
             $relatedProjects = $relatedProjects->unique('id');
            foreach($relatedProjects->chunk(3) as $porjectChunk)
            {
               $chunk = ['projects' => $porjectChunk->toArray()];
               array_push($result,$chunk);
            }
            return $result;
       }
       return false;
   }

   public function downloadProject($id,$title)
   {
       if($id)
       {
           ob_end_clean();
           $project = Project::find($id);
           if($this->approveDownload()){
            Download::createDownload($project);
            return Storage::download($project->file,preg_replace('/[[:space:]]+/', '-', $project->title).'.'.substr($project->file,strpos($project->file,'.')+1));
           }
           return json_encode('exceed');
       }
       return json_encode(false);
   }

   public function approveDownload()
   {   
       $countNumber = -1;
       if(Auth::guard('user')->user()==null){
           $countNumber = Download::where('ip', '=',  request()->ip())
           ->where('created_at','>=',date('Y-m-d H:i:s',strtotime('-24 hours',time())))->count();
           return json_encode(!($countNumber >= 25)); 
       }
       $countNumber = Download::where(function($postDownloadsQuery) { $postDownloadsQuery
           ->where('session_id', '=', request()->getSession()->getId())
           ->orWhere('ip','=',(Auth::guard('user')->user()->id))
           ->orWhere('user_id', '=', (Auth::guard('user')->user()->id))
           ->where('created_at','>=',date('Y-m-d H:i:s',strtotime('-24 hours',time())));})->count();  
         return json_encode(!($countNumber >= Auth::guard('user')->user()->Membership->downlaodTimes));
   }


}