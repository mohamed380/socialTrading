<div class="container" >
<table class="table table-striped table-reports" style="margin-top:20px">
    <thead>
      <tr>
       
        <th scope="col">Count</th>
        <th scope="col">Answer</th>
      </tr>
    </thead>
    <tbody class="VotesTable">
    @foreach($votes as $vote)
    <tr>
        <td>{{$vote->count()}}</td>
        <td>{{$answers[$vote[0]->answer_id]}}</td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>