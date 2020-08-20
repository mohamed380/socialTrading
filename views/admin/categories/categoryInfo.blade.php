<div class="fadeOut">
    <i class="fas fa-2x fa-times"></i>
  </div>
<div class="notificationsDiv" style="display: none; height:fit-content;" ></div>
<form method="post" id="submitCategory" class="omForm" style="width: 56%;" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$category->id}}">
        <div class="col-auto">
          <input type="text" class="form-control mb-2" name="title" value="{{$category->title}}" placeholder="Title">
        </div>
        <div class="col-auto">
          <input style="" type="file" name="img"  class="form-control-file mb-2">
        </div>
        <div class="col-auto">
          <input type="number" class="form-control mb-2" value="{{$category->storage}}" name="storage" placeholder="Maximum Storage"/>
        </div>
        <div class="col-auto">
          <input type="number" class="form-control mb-2" value="{{$category->cost}}" name="cost" placeholder="Maximum Price"/>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary mb-2 ">Submit</button>
        </div>
    </form>