<div class="notificationsDiv" style="display: none; height:fit-content;" >

</div>

<form method="post" id="submitCategory" class="omForm" style="width: 56%;  justify-content: center;" enctype="multipart/form-data">
{{ csrf_field() }}
  
    <div class="col-auto">
      <input type="text" class="form-control mb-2" name="title"  placeholder="Title">
    </div>
    <div class="col-auto">
      
        <input  type="file" name="img" class="form-control-file mb-2">
      
    </div>
    <div class="col-auto">
      
        <input type="number" class="form-control mb-2" name="storage" placeholder="Maximum Storage"/>
    
    </div>
    <div class="col-auto">
      
        <input type="number" class="form-control mb-2" name="cost" placeholder="Maximum Price"/>
      
    </div>

    <div>
      <select id="categorieTypeList" class="form-control" name="categoryType" require>
      <option selected value="">Category Type</option>
        @foreach($categoryTypes as $categoryType)
        <option value="{{$categoryType->id}}" >{{ $categoryType->name }}</option>
        @endforeach
      </select> 
</div>

<div>
  <select id="categorieTypeList" class="form-control" name="NavTitle" require>
  <option selected value="">Nav Title</option>
    @foreach($NavTitles as $NavTitle)
    <option value="{{$NavTitle->id}}" >{{ $NavTitle->title }}</option>
    @endforeach
  </select> 
</div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary mb-2 ">Submit</button>
    </div>

</form>

<div class="SubCategories">
  <h4></h4>
<form method="post" id="subCategory_form" enctype="multipart/form-data" >
{{ csrf_field() }}
<input type="hidden" name="id">
  <div class="form-row align-items-center" style="justify-content: center;">
    <div class="col-auto">
      <input type="text" class="form-control mb-2" name="title" id="inlineFormInput" placeholder="Title">
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary mb-2 add-subc">Add</button>
    </div>
  </div>
</form>
<table class="table table-striped table-dark" style="width: 84%;">
  <thead>
    <tr>
     
      <th scope="col">Title</th>
      <th scope="col">Delete</th>
      <th scope="col">Update</th>
    </tr>
  </thead>
  <tbody class="subCaegoriesTable">

  </tbody>
</table>
<div class="fadeOut">

<i class="fas fa-2x fa-times"></i>

</div>
</div>
<div class="editForm" style="flex-direction:row;"></div>
<div class="rowContainer" style="justify-content:center">
@foreach($categories as $category)
    <div class="squarContainer ctgory" id="{{$category->id}}" >
        <img src="{{asset('storage')}}/{{$category->img}}"/>
        <div class="content">
            <h5>{{$category->title}}</h5>
            <div class="ctgoryAction">
                {{-- <button type="button" class="btn btn-sm btn-primary editCategory">
                <i class="fas fa-pen"></i>
                </button> --}}
                <button type="button" class="btn btn-sm btn-danger delete_category">
                <i class="far fa-trash-alt"></i>
                </button>
                <button type="button" class="btn btn-sm btn-success sub_category">
                <i class="fas fa-cubes"></i>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>