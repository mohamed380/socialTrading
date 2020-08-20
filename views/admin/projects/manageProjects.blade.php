<div class="circlesDiv" style="justify-content:center;">
    <li class="pendingState">
        <p>Pending Projects</p>
    </li>    
    <li class="activeState">
        <p>In Prouction Projects</p>
    </li>
    <li class="rejectedState">
        <p>Rejected Projects</p>
    </li>

</div>

<div class="loading">
     <div class="loadingIcon"></div>
 </div>
 
<div class="projSearchResultContainer colContainer" style="width:100%">
    <h4 class="topTen">Top Ten Projects <i class="fas fa-medal"></i></h4>   
    @include('admin.projects.ProjectsTemplate',[$projects=$projects])
</div>
<div class="InfoDiv"></div>