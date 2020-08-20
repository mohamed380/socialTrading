<div class="fadeOut" style="color:black">
    <i class="fas fa-2x fa-times"></i>
</div>
<h3>Offers Details</h3>
<div class="offerDetialsDiv" id="{{$offer->id}}">
<form action="post" class="membershipOfferForm" enctype="multipart/form-data" style="width: 40%;">
{{ csrf_field() }}
<input type="hidden" name="id" value="{{$offer->id}}">
<h6>title</h6>
    <input type="text" class="form-control" name="title" value="{{$offer->title}}">
    <h6>Discription</h6>
    
       <textarea name="body" class="form-control" id="" rows=6 cols=40>{{$offer->body}}</textarea>
    <h6>Discount</h6>
    <input type="number" name="discount" class="discount form-control" value="{{$offer->discount}}">
    <h6>Interval</h6>
        <input type="date" class="form-control date" name="enddate" value="{{$offer->enddate}}">
    <h6>Storage+</h6>
        <input type="number" class="form-control date" name="storage" value="{{$offer->storage}}">
    <h6>Duration+</h6>
        <input type="number" class="form-control date" name="duration" value="{{$offer->duration}}">
        <input type="submit" class="btn btn-primary" value="Submit">
</form> 
<div class="searchDiv">
    <input type="text" class="form-control searchMembership" placeholder="Seacrh Memberships">
    <i class="fas fa-search searchMembershipIcon searchIcon"></i>
</div>
<div class="membershipSearchResult searchResult"></div>

    <h6>Memberships</h6>
    <div class="circlesDiv">
        @foreach($offer->Membership as $membership)
        <li class="" id="{{$membership->id}}">
            <i class="far fa-times-circle removeMembershipFromOffer" style="bottom: 17px;"></i>
            <p>{{$membership->title}}</p>
        </li> 
        @endforeach
</div>

</div>
