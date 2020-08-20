<div class="fadeOut" style="color:black">
    <i class="fas fa-2x fa-times"></i>
</div>
<h3>Project details</h3>
<div class="projectDetialsDiv" id="{{$project->id}}">
    <h4>{{$project->title}}</h4>
    <img src="{{$project->path}}/{{$project->img}}" alt="">
    <h6>breif</h6>
    <div class="">
        <p>{{$project->breif}}</p>
    </div>
    <h6>Description</h6>
    <div class="">
        <pre>{{$project->description}}</pre>
    </div>
    <h6>Programs</h6>
    <div class=" programs">
        @foreach($project->Program as $program)
            <li>{{$program->title}}</li>
        @endforeach
    </div>
    <h6>Tags</h6>
    <div class=" tags">
        @foreach($project->Tag as $tag)
            <li>{{$tag->title}}</li>
        @endforeach
    </div>
    @if($project->type == 'premium')
    <h6>Price</h6>
    <div class="">
       
            <input type="text" class="form-control price" value="{{$project->price}}">
            <input type="button" class="btn btn-primary submitPrice" value="Submit Price">
    </div>
    @endif
</div>