<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\NavTitle;
use App\CategoryType;
use App\Category;
use App\Subcategory;
class CategoriesController extends Controller
{
    //
    public function categories(){
        return view('admin.categories.adminCategories');
    }
    public function ctogryStatistics(){
        return view('admin.adminStatistics');
    }
    public function categoryInfo($id)
    {
        return view('admin.categories.categoryInfo')->with('category',Category::find($id));
    }
    public function manageCtgories(){
        return view('admin.categories.manageCtgories')->with('categories',Category::all())
                                                      ->with('categoryTypes',CategoryType::all())
                                                      ->with('NavTitles',NavTitle::all());
    }
    public function addCategory(Request $r){
      
        $x = $r->validate([
            'title' => 'required',
            'cost' => 'required',
            'storage' => 'required',
            'NavTitle'=> 'required',
            'categoryType'=>'required',
        ]);
        $response['state'] = 'false';
        $response['find'] = 'false';
        $category = new Category();
        if($r->id){
            $category = Category::find($r->id);
            $response['find'] = 'true';
        }
        $category->title = $r->title;
        $category->cost = $r->cost;
        $category->storage = $r->storage;
        if($r->categoryType != null){
          $category->categoryType_id = $r->categoryType;
        }
        if($r->NavTitle){
            $category->NavTitle_id = $r->NavTitle;
        }
        $category->admin_id = Auth::guard('admin')->user()->id;
        
        if($r->img){
            $path =  $r->img->store('uploads/Categories/');
            $category->img = $path;
        }
        if (!$category->save()){
           
                echo json_encode($response);
                exit;
        }
        $this->manageCtgories();
        echo json_encode($response);
        exit;

    }

    public function deleteCategory($id){
        $response = 'false';
        $category = Category::find($id);
        $img      = $category->img;
        $subCategories = $category->Subcategory;
        if($category->delete()){
            foreach($subCategories as $sc){
                $sc->delete();
            }
            File::delete('uploads/Categories/'.$img);
            $response = 'true';
        }
        echo json_encode($response);
        exit;

    }

    public function editCategory(Request $r)
    {
        $response = 'false';
        $category = Category::find($r->id);
        if($r->storage != null){
            $category->storage= $r->storage;
        }
        if($r->cost != null){
            $category->cost= $r->cost;
        }
        if($r->title != null){
            $category->title= $r->title;
        }
        if($image = $r->file('img')){
            File::delete('uploads/Categories/'.$category->img);
            $extension = $image->getClientOriginalExtension();
            $filename = $category->title . '_'. time(). "." . $extension;
            $image->move('uploads/Categories/',$filename);
            $category->img = $filename;
        }

        $update = Category::where('id',$r->id)
        ->update([
            "title" => $category->title,
            "cost" => $category->cost,
            "storage" => $category->storage,
            "img" => $category->img
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
    public function getSubCategories($id = null)
        {
            $response = 'false';
           if($id != null){
            $subCategories = Category::find($id)->Subcategory;
           // dd($subCategories[0]);
           echo $response = json_encode($subCategories);
           exit;
           }else{
            echo $response; 
            exit;
           }
           
        }
}
