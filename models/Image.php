<?php
namespace App;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageEditor;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use Notifiable;

    protected $fillable = [
        'path', 'size','project_id',
    ];

    public function Project()
    {
        return $this->belongsTo('App\Project');
    }

    static function upload(Request $request, $project, $directory)
    {
        if($request->file('imgs')){
            $imagesSize = 0;
            foreach($request->file('imgs') as $formImg){
                $imageObject = new Image();
                $extension =  $formImg->getClientOriginalExtension();
                if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg'){   
                    $img = ImageEditor::make($formImg);
                    $logoSize = round( $img->width() *((100-80)/100),2);
                    $logo = ImageEditor::make(public_path('storage/logo.jpeg'))->resize($logoSize,null,function($constrain){
                        $constrain->aspectRatio();
                    });
                    $img->insert($logo,'bottom-right',0,0);
                    $id = time();
                    if($img->save(public_path('storage/'. $directory.'/'.$id .'.'.$extension))){
                        if($imagesSize == 0){
                            $project->img = $directory.'/'.$id .'.'.$extension;
                            $project->save();
                        }
                        $imageObject->path =  $directory.'/'.$id .'.'.$extension;
                        $imageObject->size =  Storage::size($directory.'/'.$id .'.'.$extension)/1073741824;
                        $imagesSize += $imageObject->size;
                        $imageObject->project_id = $project->id;
                        $imageObject->save();
                    }
                    //throw error about that image colud not be uploaded
                }
                    //throw error about that image colud not be uploaded
            }
            return $imagesSize;
        }
        return false;
    }

}
