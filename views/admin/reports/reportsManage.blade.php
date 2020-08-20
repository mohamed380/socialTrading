
<div class="colContainer">


  <div class="colContainer">
    <div class="col-auto">
      <input type="text" class="form-control mb-2" name="title" id="inlineFormInput" placeholder="Title">
    </div>
    <div class="col-auto">
      <button  id="addReportMessage" type="button" class="btn btn-primary mb-2 ">Submit</button>
    </div>
  </div>

</div>

<div class="colContainer" style="justify-content:center">

<table class="table table-striped table-reports" style="width: 84%;">
  <thead>
    <tr>
     
      <th scope="col">Title</th>
      <th scope="col">Delete</th>
    </tr>
  </thead>
  <tbody class="reprotsTable">
  @foreach($reports as $report)
  <tr >
      <td><input type="text" value="{{$report}}" class="form-control" /></td>
      <td><input type='button' class='btn btn-danger delete-report'   value='Delete'/></td>
    </tr>
  @endforeach
  </tbody>
</table>
  <div class="row justify-content-center">
    <button id="updateReportOptions" class="btn btn-success ">Submit Changes</button>
  </div>
</div>

