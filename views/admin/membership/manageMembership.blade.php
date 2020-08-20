
<div class="notificationsDiv"></div>
<form id="membershipForm"  class="FormContainer" enctype="multipart/form-data" method="post" require >
{{ csrf_field() }}
<input type="hidden" name="id">
<label class="switch">
  <input type="checkbox" name="state" value="0" class="MembershipState">
  <span class="slider round"></span>
</label>
<div class="Form">
    <div>
        <input type="text" name="title" class="form-control" placeholder="Title" require>
    </div>
    <div>
        <textarea class="form-control" name="body" cols="2" rows="3" placeholder="Description" require></textarea>
    </div>
    <div>
      <input type="number" name="cost" class="form-control" placeholder="Cost $" require>
      <input type="number" name="duration" class="form-control" placeholder="Duration in months" require>
      <input type="number" name="storage" class="form-control" placeholder="Storage in GB" require>
    </div>
  <div>
      <button type="submit" class="btn btn-primary">Add</button>
  </div>
</div>
</form>

<div class="colContainer" style="justify-content:center">
  <h3>Memberships</h3>
  <table class="table table-striped" style="width: 84%;">
    <thead>
      <tr>
        <th scope="col">Title</th>
        <th scope="col">Cost</th>
        <th scope="col">Edit</th>
        <th scope="col">State</th>
      </tr>
    </thead>
    <tbody class="ProjectsOffersTable">
      @foreach($memberships as $membership)
      <tr id="{{$membership->id}}">
          <td><p>{{$membership->title}}</p></td>
          <td><p>{{$membership->cost}}</p></td>
          <td>
            <input type='button' class='btn btn-primary membershipInfo btn-sm' id="{{$membership->id}}" value='Info'/>
          </td>
          <td>
            <label class="switch">
                @if ($membership->state == 0)
                 <input type="checkbox"  name="state" value="{{$membership->state}}" class="editMembershipState">
                @else 
                 <input type="checkbox" checked="true"  name="state" value="{{$membership->state}}" class="editMembershipState">
                @endif
                <span class="slider round"></span>
            </label>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="InfoDiv"></div>