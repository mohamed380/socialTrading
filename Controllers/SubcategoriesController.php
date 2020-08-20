<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Category;
use App\Subcategory;
class SubcategoriesController extends Controller
{
   public function editSubCategories($id,$title=null)
   {
    $response = 'false';
    $subCategory = Subcategory::find($id);
    if($title != null ){
        $update = Subcategory::where('id',$id)->update(["title" => $title]) ;
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

   public function deleteSubcategory($id)
   {
    $response = 'false';
    $subcategory = Subcategory::find($id);
    if($subcategory->delete()){
        $response = 'true';
        echo json_encode($response);
        exit;
    }
    echo json_encode($response);
    exit;
   }
   public function addSubcategory(Request $r,$id)
   {
        $response['state'] = "false";
        $subcategory =new Subcategory();
        $subcategory->title = $r->title;
        $subcategory->admin_id = Auth::user()->id;
        $subcategory->category_id = $id;
        if($subcategory->save()){
            $response['state'] ="true";
            $response['id'] = $subcategory->id;
            $response['title'] = $r->title;
            echo json_encode($response);
            exit;
        }
        echo json_encode($response);
        exit;
   }
}
