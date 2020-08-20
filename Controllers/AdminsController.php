<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Admin;
use App\User;
use App\Category;
use App\Project;
use App\Membership;
use App\Users_Work;
use App\Program;

class AdminsController extends Controller
{

    public function index()
    {
        if(Auth::guard('user')->user() == null){
            if (Auth::guard('admin')->user()){
                $labels = ['Projects','Category','Program','Membership','Users'];
                $projects = Project::where('state','pending')->get();
                $values = [Project::count(),Category::count(),Program::count(),Membership::count(),User::count()];
                return view('admin.dashBoard')->with('labels',$labels)->with('values',$values)->with('admin',Auth::guard('admin')->user())->with('projects',$projects);
            }else{
                return redirect('adminlogin');
            }
    }
        return abort(404);
    }
    public function adminlogininvoke(Request $r)
        {
            $this->validate($r,[
                'email' => 'required', 'string', 'email', 
                'password'=>'required', 'string'
                ]);
              if(Auth::guard('admin')->attempt(['email'=> $r['email'], 'password'=> $r['password']])) 
              {
                 return redirect('/admin');
              }
               return back()->with(Input());
        }
    public function home(){
        $labels = ['Projects','Category','Program','Membership','Users'];
        $projects = Project::where('state','pending')->get();
        $values = [Project::count(),Category::count(),Program::count(),Membership::count(),Users_Work::count()];
        return view('admin.home.adminHome')->with('labels',$labels)->with('values',$values)->with('projects',$projects);
    }

    public function stati(){
        return view('admin.adminStatistics');
    }

      public function noti(){
          $projects = Project::where('state','pending')->get();
        return view('admin.home.adminNotification')->with('projects',$projects);
    }
    public function adminlogin()
    { 
        return view('admin.login');
    }


    public function manageAdmins()
    {
        return view('admin.users.manageAdmin')->with('admins', Admin::all());
    }
    public function changeAdminPP(Request $r)
    {
        $admin = Auth::guard('admin')->user();
        $response = 'false';
        $file = $r->pp;
        if($image = $file){
            $admin = Auth::guadr('admin')->user();
            $extension = $image->getClientOriginalExtension();
            if($extension == 'png' || $extension == 'jpg' || $extension == 'JPG' ||$extension == 'PNG'){
                $filename = Auth::guard('admin')->user()->id."." . $extension;
                $path = $r->file('pp')->store('users/admins');
                //$image->move('uploads/users/admins',$filename);
                $admin->img = $filename;
                $update = Auth::guard('admin')->user()->update([
                    "img" => $path
                ]);
                if($update)
                    $response = 'true';
            }
        }
        echo json_encode($response);
        exit;
     
     
    }

    public function download()
    {
                $admin = Auth::guard('admin')->user();
                $extensen =explode('.',$admin->img)[1]; 
                return Storage::download($admin->img,$admin->f_name.'.'.$extensen);

    }
    public function addAdmin(Request $request)
    {
        $response['state'] = 'false';
        $validate=$request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'string', 'min:8'],
        ]);
        if($validate){
            $admin =  Admin::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'img' => null
            ]);
            if($admin){
                $response['data'] = $admin;
                $response['state'] = 'true';
            }else{
                $response['data'] = 'Something went wrong';
            }
        }
        else{
            $response['data'] = 'Something went wrong';
        }
    }

    public function deleteAdmin($id)
    {
        $response['state'] = "false";
        $flag =true;
        $admin = Admin::find($id)->first();
        if($admin->state == 'ban'){
            $flag = false;
        }
        if($flag){
            $update = $admin->update([
                'state' => 'ban'
            ]);
        }else{
            $update = $admin->update([
                'state' => 'approve'
            ]);
        }
        if($update){
            $response['state'] = "true";
            $response['data'] = $flag? 'Approve':'Ban';         
        }
        echo json_encode($response);
        exit;
    }
    
}
