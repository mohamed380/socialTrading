<form method="post" class="projectOfferForm omForm" enctype="multipart/form-data"  style="width:56%;">
{{ csrf_field() }}
<input type="hidden" name="id">
    
  <div class=" align-items-center colContainer" style="justify-content: center;">
  <h4>New Offer? </h4>
    <div class="col-auto">
      <input type="text" class="form-control mb-2" name="title" placeholder="Title">
    </div>
    <div class="col-auto">
      <textarea class="form-control mb-2" name="body"  placeholder="Description" rows=6 cols=23></textarea>
    </div>
    <div class="col-auto">
      <input type="number" class="form-control mb-2" name="discount"  placeholder="Discount ratio">
    </div>
    <div class="col-auto">
      <input type="date" class="form-control mb-2" name="enddate">
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary mb-2 add-subc">Add</button>
    </div>
  </div>
</form>
<div class="colContainer" style="justify-content:center">
    <h3>OFFERS</h3>
<table class="table table-striped " style="width: 84%;">
  <thead>
    <tr>
     
      <th scope="col">Title</th>
      <th scope="col">Discount</th>
      <th scope="col">Info.</th>
    </tr>
  </thead>
  <tbody class="ProjectsOffersTable">
  @foreach($projectOffers as $offer)
  <tr id="{{$offer->id}}">
      <td><p>{{$offer->title}}</p></td>
      <td><p>{{$offer->discount}}</p></td>
      <td>
        <input type='button' class='btn btn-primary projectOfferInfo' id="{{$offer->id}}" value='Info'/>
        <input type='button' class='btn btn-danger deleteProjectOffer' id="{{$offer->id}}"  value='Delete'/>
      </td>
    </tr>
  @endforeach
 

  </tbody>
</table>

</div>
<div class="InfoDiv"></div>

