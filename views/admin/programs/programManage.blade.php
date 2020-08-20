<div class="editForm">
  <div class="fadeOut">

    <i class="fas fa-2x fa-times"></i>

  </div>
  <form method="post" id="update_program_form" enctype="multipart/form-data">
  {{ csrf_field() }}
  <input type="hidden" name="id">
  <div class="colContainer">
      <div class="col-auto">
        <input type="text" class="form-control mb-2" name="title" id="inlineFormInput" placeholder="Title">
      </div>
      <div class="col-auto">
        <div style="display:flex;width:203px;">
          <input style="" type="file" name="img" class="form-control-file mb-2">
        </div>
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
<form method="post" id="add_program_form" enctype="multipart/form-data">
{{ csrf_field() }}
  <div class="colContainer">
    <div class="col-auto">
      <input type="text" class="form-control mb-2" name="title" id="inlineFormInput" placeholder="Title">
    </div>
    <div class="col-auto">
      <div style="display:flex;width:203px;">
        <input style="" type="file" name="img" class="form-control-file mb-2">
      </div>
    </div>
    
    <div class="col-auto">
      <button type="submit" class="btn btn-primary mb-2 ">Submit</button>
    </div>
  </div>
</form>
</div>

<div class="rowContainer" style="justify-content:center">
@foreach($programs as $program)
    <div class="squarContainer program" id="{{$program->id}}">
        <img src="{{asset('uploads')}}/Programs/{{$program->img}}"/>
        <div class="content">
            <h5>{{$program->title}}</h5>
            <div class="ctgoryAction">
                <button type="button" class="btn btn-sm btn-primary edit_program">
                <i class="fas fa-pen"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger delete_program">
                <i class="far fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

