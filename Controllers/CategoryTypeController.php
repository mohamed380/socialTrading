<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\NavTitle;
use App\CategoryType;

class CategoryTypeController extends Controller
{
    //
    public function categoryTypeStati()
    {
        return view('admin.categoryType.categoryTypeStatistics');

    }

    public function categoryType()
    {
        return view('admin.categoryType.categoryTypeHome');
    }
    
    public function categoryTypeManage()
    {
        return view('admin.categoryType.categoryTypeManage')->with('categoryTypes',categoryType::All());
    }
    
    public function addcategoryType(Request $r)
    {
        
        $response = 'false';
        $tag = new CategoryType();
        if($r->name){
            $tag->name = $r->name;
           // $tag->admin_id = Auth::guard('admin')->user()->id;
            if($tag->save()){
                $this->categoryTypeManage();
                $response = 'true';
                echo json_encode($response);
                exit;
            }
           
        }
        echo json_encode($response);
        exit;
    }

    public function editcategoryType($id,$title = null)
    {
        $response = 'false';
        $CategoryType = CategoryType::find($id);
        if($title != null ){
            $update = CategoryType::where('id',$id)->update(["name" => $title]) ;
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

    public function deletecategoryType($id)
    {
        $response = 'false';
        $CategoryType = CategoryType::find($id);
        if($CategoryType->delete()){
            $response = 'true';
            echo json_encode($response);
            exit;
        }
        echo json_encode($response);
        exit;
    }

    public function NavTitlesManage()
    {
        return view('admin.categoryType.NavTitlesManage')->with('NavTitles',NavTitle::All());

    }

    public function addNavTitle(Request $r)
    {
        $response = 'false';
        $NavTitle = new NavTitle();
        if($r->name){
            $NavTitle->title = $r->name;
           // $tag->admin_id = Auth::guard('admin')->user()->id;
            if($NavTitle->save()){
                $this->NavTitlesManage();
                $response = 'true';
                echo json_encode($response);
                exit;
            }
           
        }
    }

    public function editNavTitle($id,$title = null)
    {
        $response = 'false';
        $NavTitle = NavTitle::find($id);
        if($title != null ){
            $update = NavTitle::where('id',$id)->update(["title" => $title]) ;
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

    public function deleteNavTitle($id)
    {
        $response = 'false';
        $NavTitle = NavTitle::find($id);
        if($NavTitle->delete()){
            $response = 'true';
            echo json_encode($response);
            exit;
        }
        echo json_encode($response);
        exit;
    }
    
}

