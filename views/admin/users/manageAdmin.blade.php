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
<form method="post" id="add_admin" enctype="multipart/form-data">
{{ csrf_field() }}
  <div class="colContainer">
    <div class="col-auto">
      <input type="text" class="form-control mb-2" name="name" id="inlineFormInput" placeholder="Name">
    </div>
    <div class="col-auto">
      <input type="email" class="form-control mb-2" name="email" id="inlineFormInput" placeholder="E-mail">
    </div>
    <div class="col-auto">
      <input type="password" class="form-control mb-2" name="password" id="inlineFormInput" placeholder="Password">
    </div>
    <div class="col-auto">
      <input type="password" class="form-control mb-2" name="confirm_password" id="inlineFormInput" placeholder="Confirm Password">
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary mb-2 ">Add Admin</button>
    </div>
  </div>
</form>
</div>

<div class="rowContainer" style="justify-content:center">

<table class="table table-striped table-dark" style="width: 84%;">
  <thead>
    <tr>
     
      <th scope="col">Title</th>
      <th scope="col">Edit</th>
      <th scope="col">Ban</th>
    </tr>
  </thead>
  <tbody>
  @foreach($admins as $admin)
  <tr id="{{$admin->id}}">
      <td><p>{{$admin->f_name}}</p></td>
      <td><input type='button' class='btn btn-sm btn-primary update-tag' id="{{$admin->actual_id}}" value='Edit'/></td>
      @if($admin->state != 'ban')
         <td><input type='button' class='btn btn-sm btn-warning ban-admin' id="{{$admin->actual_id}}"  value='Ban'/></td>
      @else
      <td><input type='button' class='btn btn-sm btn-success ban-admin' id="{{$admin->actual_id}}"  value='Approve'/></td>
     @endif
    </tr>
  @endforeach
  </tbody>
</table>

</div>

