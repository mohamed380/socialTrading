<div class="topNavArea">
      <div class="topNav">
        <li class="stati topNavActive">Statistics</li>
        <li class="noti">Notification
          @if($projects->count() != 0)
          <span style="width: 7px;height: 7px;border-radius: 13px;margin-left: 5px;background-color: red;"></span> 
    @endif
           </li>
        <li class="emailMessages">Email Messages</li>
      </div>
 </div>
<div class="homeContainer rowContainer" style="justify-content:center">
    @include('admin.adminStatistics',['values'=>$values],['labels'=>$labels])
</div>

