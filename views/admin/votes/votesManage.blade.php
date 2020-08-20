
<div class="colContainer">


  <div class="colContainer">
    <div class="col-auto" style="display: flex">
      <input type="text" class="form-control mb-2" class="questionInput" name="question" placeholder="Question">
      <div class="col-auto">
        <button  id="addVoteQuestion" type="button" class="btn btn-primary mb-2 ">Submit</button>
      </div>
    </div>
    <div class="submitedQuestion">
      <h5 class="question">{{$voting[0]}}</h5>
    </div>
    <div class="col-auto voteAnswer" style="display: flex">
      <input type="text" class="form-control mb-2" name="answer" id="inlineFormInput" placeholder="Answer">
      <div class="col-auto">
        <button  id="addVoteAnswer" type="button" class="btn btn-primary mb-2 ">Submit</button>
      </div>
    </div>
   
  </div>

</div>

<div class="colContainer" style="justify-content:center">

<table class="table table-striped table-answers" style="width: 84%;">
  <thead>
    <tr>
      <th scope="col">Answer</th>
      <th scope="col">Delete</th>
    </tr>
  </thead>
  <tbody>
  @foreach(array_slice($voting,1) as $vote)
    <tr>
      <td><input type="text" value="{{$vote}}" class="form-control" /></td>
    <td><input type='button' class='btn btn-danger delete-voteAnswer' id="{{$loop->index+1}}"  value='Delete'/></td>
    </tr>
  @endforeach
  </tbody>
</table>
  <div class="row justify-content-center">
    <button id="updateVoting" class="btn btn-success ">Submit Changes</button>
  </div>
</div>

