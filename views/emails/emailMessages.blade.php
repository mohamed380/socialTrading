<div class="notificationsDiv" style="display: none;flex-direction: column;justify-content: center;height: 16%;position: relative;top: 133px;">

</div>
<form method="post" class="emailForm omForm" enctype="multipart/form-data" style="width: 56%;" >
    {{ csrf_field() }}
    <input type="hidden" name="id">
        
      <div class=" align-items-center colContainer" style="justify-content: center;">
      <h4>New Message? </h4>
        <div class="col-auto">
          <input type="text" class="form-control mb-2" name="for" placeholder="For What..?">
        </div>
        <div class="col-auto">
          <input type="text" class="form-control mb-2" name="title"  placeholder="Title">
        </div>
        <div class="col-auto">
            <input type="text" class="form-control mb-2" name="subject" placeholder="subject">
          </div>
        <div class="col-auto">
            <textarea class="form-control mb-2" name="body"  placeholder="Body" rows=6 cols=23></textarea>
          </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary mb-2 btn-sm">Submit</button>
        </div>
      </div>
    </form>
    <div class="colContainer" style="justify-content:center">
        <h3>Emails</h3>
        <table class="table table-striped" style="width: 84%;">
          <thead>
            <tr>
              <th scope="col">For</th>
              <th scope="col">Title</th>
              <th scope="col">Edit</th>
            </tr>
          </thead>
          <tbody class="messagesTable">
            @foreach($emails as $email)
            <tr id="{{$email->id}}">
                <td><p>{{$email->for}}</p></td>
                <td><p>{{$email->title}}</p></td>
                <td>
                  <input type='button' class='btn btn-primary messageInfo btn-sm' id="{{$email->id}}" value='Edit'/>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="InfoDiv" style="justify-content:center"></div>