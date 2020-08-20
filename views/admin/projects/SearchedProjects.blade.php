@if($subcategories)
      <div class="subCategoriesDiv">
      @foreach($subcategories as $subcategory)
         <li class="subCategory" id="{{ $subcategory->id }}">{{ $subcategory->title }}</li>
      @endforeach
      </div>
@endif
<div class="resultsContainer">
      @include('admin.projects.ProjectsTemplate',[$projects=$projects])
</div>
<div class="projectInfoDiv">
</div>