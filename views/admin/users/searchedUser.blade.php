<div class="searchedUser">
    <div class="searchedUserImage">
            <img src="{{asset('js')}}/2.jpg"/>
            <div>
                <button type="button" class="btn btn-danger ">Ban</button>
                <button type="button" class="btn btn-success ">Send message</button>
            </div>
</div>
    <div class="searchedUserInfo">
        <li>
            <span>
                <i class="fas fa-at"></i>
                <p>Username</p>
            </span>
            <p>Mohamed</p>
        </li>
            <li>
            <span>
                <i class="fas fa-user-tag"></i>
                <p>Membership</p>
            </span>
            <p>Free</p>
        </li>
            <li>
            <span>
                <i class="fas fa-hashtag"></i>
                <p>Projects</p>
            </span>
            <p>10</p>
        </li>
            <li>
                <span>
                    <i class="fas fa-database"></i>
                    <p>Storage</p>
                </span>
                <p>2 GB</p>
        </li>
        <li>
            <span>
            <i class="fas fa-users"></i>
                <p>Followers</p>
            </span>
            <p>35k</p>
        </li>
        <div style="display:flex;">
            <li style="width:50%">
                <span>
                    <i class="fas fa-download"></i>
                    <p>User</p>
                </span>
                <p>5</p>
            </li>
            <li style="width:50%">
                <span>
                    <i class="fas fa-cart-arrow-down"></i>
                    <p>Followers</p>
                </span>
                <p>6</p>
            </li>
    </div>
    </div>
</div>
<hr/>
<h3 class="UserdetailsTitle">Projects</h3>
@include('admin.projects.ProjectsTemplate',['projects',$projects])
</div>
