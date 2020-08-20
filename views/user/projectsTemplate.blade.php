<div class="card-columns projects-container" >
    @foreach ($projects as $project)
        <div class="card project" id={{$project->id}}>
          <img src="{{$project->path}}/{{$project->img}}" class="card-img-top" >
          <div class="card-body">
            <button class="like_project">
              <i class="fas fa-heart"
              @if (in_array($project->id,$likes))
               style="color:#ff7c7c;"
              @endif
               ></i>
            </button>
            <p class="card-title project-title">{{$project->title}}</p>
            <div class="projectInfo">
                <div class="projectOwner">
                  @if ($project->User != null)
                    <img src="{{asset('storage')}}/{{$project->User->img}}" alt="">
                    <p>{{$project->User->username}}</p>
                  @else
                    <img src="{{asset('storage')}}/admins/aKjlncq72CDcT738Du0VPudkLNlRhOs7X6eM143D.jpeg" alt="">
                    <p>wtp</p>
                  @endif
                </div>
                <div class="icons">
                <span> <i class="fas fa-download"></i><p>{{$project->Download->count()}}</p></span>
                  <span> <i class="fas fa-heart"></i><p>{{$project->Like->count()}}</p></span>
                  <span><i class="fas fa-eye"></i> <p>555</p></span>
                </div>
            </div>
          </div>
        </div>
   
    @endforeach
</div>