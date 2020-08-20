
<div class="notificationsDiv">

</div>

<form  id="projectFormContainer" class="formContainer" enctype="multipart/form-data" method="post" require >
{{ csrf_field() }}
<div class="form">
  <div>
      <input type="text" name="title" class="form-control" placeholder="Title" require>
  </div>
  <div>
      <input type="text" class="form-control" name="breif" placeholder="Brief" require>
  </div>
  <div>
    <textarea class="form-control" name="description" cols="2" rows="3" placeholder="Description" require></textarea>
  </div>
  <div>
      <select id="categoryList" class="form-control" name="category" require>
      <option selected value="">Category</option>
        @foreach($categories as $category)
        <option value="{{$category->id}}" >{{ $category->title }}</option>
        @endforeach
      </select> 
      <select id="subCategoryList" class="form-control" style="margin-left:3px;" name="subcategory">
        <option selected>Subcategory</option>
      </select>
</div>
 
  <div style="display:flex;justify-content: space-between;">
    <label>Choose Image </label>
    <input name="img" style="width: 56%;padding-left: 15px;" type="file" class="form-control-file" require>
  </div>
  <div style="display:flex;justify-content: space-between;">
    <label >Choose compresied project file</label>
    <input style="width: 56%;padding-left: 15px;" name="file" type="file" class="form-control-file" require>
  </div>

  <div style="display:flex;padding:5px;">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="type" value="free" checked>
          <label class="form-check-label">
           Free
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input premiumProject" name="type" type="radio"  value="premium">
          <label class="form-check-label">
           Premium
          </label>
        </div>
  </div>
  <div>
      <input type="number" name="price" class="form-control" id="projectPrice" placeholder="Price $" disabled>
  </div>
  <div>
      <button type="submit" class="btn btn-primary">Add</button>
  </div>
  </div>
  <div class="selectionDiv">
    <div>
      
        <h6 style="margin-bottom: 0px;">Tags</h6>
        <div style="position:relative;margin: 0px;padding: 0px 0px;">
        <input class="form-control form-control-sm" placeholder="Search.."/> <i class="fas fa-search searchTag" style="position: absolute;top: 7px;right: 10px;cursor:pointer;"></i>
      </div>
        <ul>
          @foreach($tags as $tag)
        <li class=""><input name="tags[]" type="checkbox" value="{{$tag->id}}"><p>{{$tag->title}}</p></li>
        @endforeach
        </ul>
    </div>
    <div>
        <h6 >Programs</h6>
        <ul>
          @foreach($programs as $program)
        <li><input name="programs[]" type="checkbox" value="{{$program->id}}"> <p>{{$program->title}}</p></li>
       @endforeach
        </ul>
    </div>
</div>
</form>

