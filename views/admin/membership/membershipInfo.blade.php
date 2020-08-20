<div class="fadeOut" style="color:black">
    <i class="fas fa-2x fa-times"></i>
</div>
<h3>membership Details</h3>
<div class="membershipDetialsDiv" id="{{$membership->id}}">
<div class="notificationsDiv"></div>
<form id="membershipForm"  class="FormContainer" enctype="multipart/form-data" method="post" require >
{{ csrf_field() }}
<input type="hidden" name="id" value="{{$membership->id}}">
<label class="switch">
<label class="switch">
                @if ($membership->state == 0)
                 <input type="checkbox"  name="state" value="{{$membership->state}}" class="editMembershipState">
                @else 
                 <input type="checkbox" checked="true"  name="state" value="{{$membership->state}}" class="editMembershipState">
                @endif
                <span class="slider round"></span>
            </label>
</label>
<div class="Form">
    <div>
        <input type="text" name="title" class="form-control" placeholder="Title" value="{{$membership->title}}" require>
    </div>
    <div>
        <textarea class="form-control" name="body" cols="2" rows="3" placeholder="Description" require>
            {{$membership->body}}
        </textarea>
    </div>
  
  <div>
      <input type="number" name="cost" class="form-control" placeholder="Cost $" require value="{{$membership->cost}}">
      <input type="number" name="duration" class="form-control" placeholder="Duration in months" value="{{$membership->duration}}" require>
      <input type="number" name="storage" class="form-control" placeholder="Storage in GB" value="{{$membership->storage}}" require>
  </div>
  <div>
      <button type="submit" class="btn btn-primary">Submit</button>
  </div>
  
</form>

