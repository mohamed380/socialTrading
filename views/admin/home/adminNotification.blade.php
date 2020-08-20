<div class="notiContainer rowContainer" style="justify-content: center;">
  
    @if($projects->count() == 0)
      <h2>No Notification</h2>
    @else
    @include('admin.projects.ProjectsTemplate',[$projects=$projects])
    @endif
 </div>