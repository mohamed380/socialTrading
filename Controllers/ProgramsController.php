<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Program;

class ProgramsController extends Controller
{
    //
    public function programs()
    {
        return view('admin.programs.programsHome');
    }
    
    public function programManage()
    {
        return view('admin.programs.programManage')->with('programs',Program::All());
    }
    public function programStatistics()
    {
        return view('admin.adminStatistics');
    }

    public function addProgram(Request $r)
    {
        $response = 'false';
        $program = new program();
        $program->title = $r->title;
        $program->admin_id = Auth::user()->id;
        if($image = $r->file('img')){
            $extension = $image->getClientOriginalExtension();
            $filename = $program->title . '_'. time(). "." . $extension;
            $image->move('uploads/Programs/',$filename);
            $program->img = $filename;
            if (!$program->save()){
                echo json_encode($response);
                exit;
            }
        }else{
            echo json_encode($response);
                exit;
        }
        $this->programManage();
        $response = 'true';
        echo json_encode($response);
        exit;
    }

    public function editProgram(Request $r)
    {
        $response = 'false';
        $program = Program::find($r->id);
        if($r->title != null){
            $program->title= $r->title;
        }
        if($image = $r->file('img')){
            File::delete('uploads/Programs/'.$program->img);
            $extension = $image->getClientOriginalExtension();
            $filename = $program->title . '_'. time(). "." . $extension;
            $image->move('uploads/Programs/',$filename);
            $program->img = $filename;
        }

        $update = program::where('id',$r->id)
        ->update([
            "title" => $program->title,
            "img" => $program->img
        ]);
        if($update){
            $response = 'true';
            echo json_encode($response);
            exit;
        }else{
            echo json_encode($response);
            exit;
        }

    }

    public function deleteProgram($id){
        $response = 'false';
        $program = Program::find($id);
       // $img = $program->img;
        if($program->delete()){
            File::delete('uploads/Programs/'.$img);
            $response = 'true';
        }
        echo json_encode($response);
        exit;

    }
}
