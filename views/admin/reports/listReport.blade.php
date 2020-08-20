<div class="container" >
<table class="table table-striped table-reports" style="margin-top:20px">
    <thead>
      <tr>
       
        <th scope="col">id</th>
        <th scope="col">Complain</th>
        <th scope="col">User</th>
        <th scope="col">Project</th>
      </tr>
    </thead>
    <tbody class="reprotsTable">
    @foreach($reports as $report)
    <tr >
        <td>{{$report->id}}</td>
        <td>{{$report->complain}}</td>

        <td>{{$report->user->email}}</td>

        <td>{{$report->project->title}}</td>

    
      </tr>
    @endforeach
    </tbody>
  </table>
</div>