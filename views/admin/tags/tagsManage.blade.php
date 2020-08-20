<div class="editForm">
  <div class="fadeOut">

    <i class="fas fa-2x fa-times"></i>

  </div>
  <form method="post" id="update_tag_form" enctype="multipart/form-data">
  {{ csrf_field() }}
  <input type="hidden" name="id">
  <div class="colContainer">
      <div class="col-auto">
        <input type="text" class="form-control mb-2" name="title" id="inlineFormInput" placeholder="Title">
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-primary mb-2 ">Submit</button>
      </div>
    </div>
      </div>
    
      
    </div>
  </form>
</div>
<div class="colContainer">
<form method="post" id="add_tag_form" enctype="multipart/form-data">
{{ csrf_field() }}
  <div class="colContainer">
    <div class="col-auto">
      <input type="text" class="form-control mb-2" name="title" id="inlineFormInput" placeholder="Title">
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary mb-2 ">Submit</button>
    </div>
  </div>
</form>
</div>

<div class="rowContainer" style="justify-content:center">

<table class="table table-striped " style="width: 84%;">
  <thead>
    <tr>
     
      <th scope="col">Title</th>
      <th scope="col">Delete</th>
      <th scope="col">Update</th>
    </tr>
  </thead>
  <tbody class="subCaegoriesTable">
  @foreach($tags as $tag)
  <tr id="{{$tag->id}}">
      <td><input type="text" value="{{$tag->title}}" class="form-control" /></td>
      <td><input type='button' class='btn btn-primary update-tag' id="{{$tag->id}}" value='Edit'/></td>
      <td><input type='button' class='btn btn-danger delete-tag' id="{{$tag->id}}"  value='Delete'/></td>
    </tr>
  @endforeach
  </tbody>
</table>

</div>

