<div class="fadeOut" style="color:black">
    <i class="fas fa-2x fa-times"></i>
</div>
<div style="display:flex;justify-content:center;width:100%;flex-direction:row">
<div class="notificationsDiv" style="display: none;flex-direction: column;justify-content: center;height: 16%;position: relative;top: 133px;"></div>
<form method="post" class="emailForm omForm" enctype="multipart/form-data" style="width: 56%;" >
    {{ csrf_field() }}
<input type="hidden" name="id" value="{{$message->id}}">
        
      <div class=" align-items-center colContainer" style="justify-content: center;">
      <h4>New Message? </h4>
        <div class="col-auto">
        <input type="text" class="form-control mb-2" name="for" placeholder="For What..?" value="{{$message->for}}">
        </div>
        <div class="col-auto">
        <input type="text" class="form-control mb-2" name="title"  placeholder="Title" value="{{$message->title}}">
        </div>
        <div class="col-auto">
        <input type="text" class="form-control mb-2" name="subject" placeholder="subject" value="{{$message->subject}}">
          </div>
        <div class="col-auto">
        <textarea class="form-control mb-2" name="body"  placeholder="Body" rows=6 cols=23>{{$message->body}}</textarea>
          </div>
        <div class="col-auto">
          <button type="submit" name="submit" class="btn btn-primary mb-2 btn-sm">Submit</button>
        </div>
      </div>
    </form>
</div>