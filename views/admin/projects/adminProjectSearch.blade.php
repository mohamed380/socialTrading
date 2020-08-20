<div class="searchBox">
    <div class="input-group mb-3">
      <input type="text" class="form-control projectTitle" placeholder="Project Title" aria-label="Recipient's username" aria-describedby="button-addon2">
      <div class="input-group-append">
        <button class="btn btn-outline-secondary searchProjects" type="button" id="button-addon2">Search</button>
      </div>
    </div>
</div>
<div class="circlesDiv">
@foreach($categories as $category)
    <li class="category" id="{{$category->id}}">
        <img src="{{asset('uploads')}}/Categories/{{$category->img}}"/>
        <p>{{$category->title}}</p>
    </li>    
  @endforeach
</div>

<div class="loading">
     <div class="loadingIcon"></div>
 </div>
<div class="projSearchResultContainer colContainer" style="width:100%;">
    
</div>