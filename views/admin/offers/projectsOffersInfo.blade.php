<div class="fadeOut" style="color:black">
    <i class="fas fa-2x fa-times"></i>
</div>
<h3>Offers Details</h3>
<div class="offerDetialsDiv" id="{{$offer->id}}">
<form action="post" class="projectOfferForm" enctype="multipart/form-data" style="width: 40%;">
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
        <input type="submit" class="btn btn-primary" value="Submit">
</form> 
<div class="searchProjectDiv searchDiv">
    <input type="text" class="form-control searchProject" placeholder="Seacrh Projects">
    <i class="fas fa-search searchProjectIcon searchIcon"></i>
</div>
<div class="projectSearchResult searchResult"></div>

    <h6>Projects</h6>
    <div class="circlesDiv">
            @foreach($offer->Project as $project)
            <li class="" id="{{$project->id}}">
                <i class="far fa-times-circle removeProjectFromOffer"></i>
                <img src="{{$project->path}}/{{$project->img}}"/>
                <p>{{$project->title}}</p>
            </li> 
            @endforeach
    </div>

</div>
