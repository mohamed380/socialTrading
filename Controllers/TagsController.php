<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\tag;

class tagsController extends Controller
{
    //
    public function tags()
    {
        return view('admin.tags.tagsHome');
    }
    
    public function tagsManage()
    {
        return view('admin.tags.tagsManage')->with('tags',tag::All());
    }
    
    public function addtag(Request $r)
    {
        $response = 'false';
        $tag = new tag();
        if($r->title){
            $tag->title = $r->title;
            $tag->admin_id = Auth::guard('admin')->user()->id;
            if($tag->save()){
                $this->tagsManage();
                $response = 'true';
                echo json_encode($response);
                exit;
            }
           
        }
        echo json_encode($response);
        exit;
    }

    public function edittag($id,$title = null)
    {
        $response = 'false';
    $tag = tag::find($id);
    if($title != null ){
        $update = tag::where('id',$id)->update(["title" => $title]) ;
        if($update){
            $response = 'true';
            echo json_encode($response);
            exit;
        }else{
            echo json_encode($response);
            exit;
        }
    }else{
        echo json_encode($response);
        exit;
    }
    }

    public function deleteTag($id)
    {
        $response = 'false';
        $tag = Tag::find($id);
        if($tag->delete()){
            $response = 'true';
            echo json_encode($response);
            exit;
        }
        echo json_encode($response);
        exit;
    }
}
