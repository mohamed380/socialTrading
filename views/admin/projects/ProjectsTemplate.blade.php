@if($projects->count() != 0)
  <div class="cardRows">
    <div class="card-columns" style="column-count: 4;">
      @foreach($projects as $project)
        <div class="card" id="{{$project->id}}" >
        @if($project->state == 'ban')
            <div class="bannedProject state">
              <div class="loadingIcon"></div>
            </div>
        @elseif($project->state == 'reject')
            <div class="rejectedProject state">
              <div class="loadingIcon"></div>
            </div>
        @endif
          <i class="fas fa-2x fa-medal goldMedal"></i>
          <img    src="{{$project->path}}/{{$project->img}}" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">{{$project->title}}</h5>
            <p class="card-text">{{$project->breif}}</p>
            <p class="card-text"><small class="text-muted">{{$project->created_at}}</small>
            
          </div>
          <div class="cardButtons" style="padding: 4px;">
                  <button type="button" {{$project->state=='approved'? 'disabled' : ''}} class="btn btn-success approveProject"><i class="far fa-check-circle"></i></button>
                  <button type="button" class="btn btn-primary projectInof"><i class="fas fa-info-circle"></i></button>
                  <button type="button"  {{ $project->state=='ban'? 'disabled' : ' '}} class="btn btn-warning banProject" style="color:white"><i class="fas fa-ban"></i></button>
                  <button type="button" class="btn btn-danger deleteProject"><i class="far fa-trash-alt"></i></button> 
                  <button type="button" {{ $project->state=='reject'? 'disabled' : ' '}} class="btn btn-secondary rejectProject"> <i class="far fa-times-circle"></i></button> 
                </div>
        </div>
        
      @endforeach
    </div>
  </div>
@else
  <h4>No projects found!</h4>
@endif