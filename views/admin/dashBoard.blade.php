
<html>
<head>
    <title>Hartala</title>
    <link rel="stylesheet" href="{{asset('css')}}/test.css">
    <link rel="stylesheet" href="{{asset('fontawesome')}}/css/all.css">
    <link rel="stylesheet" href="{{asset('bootstrap')}}/css/bootstrap.min.css">
    
</head>
<body>
    <div class="container-fluid">
        <div class="row main">
            <div class="leftSideAdmin">
                <h1>WEBSITE</h1>
                <div class="userInfoBox">
                    <div>
                    

                        @if ($admin->img)
                    <img src="{{asset('storage')}}{{$admin->img}}" alt="" id="pp" name="pp">
                        @else
                             <i class="fas fa-user"></i>
                        @endif            
                        <div class="changeImage">
                        <form action="post" class="ppform" enctype="multipart/form-data" >
                                {{ csrf_field() }}
                                <input name="pp" id="profile-image-upload" type="file" style="display:none">
                            </form>
                            <i class="fas fa-camera changeImageIcon" style="color:black;cursor:pointer"></i>
                        </div>
                    </div>
                    <div class="userInfo">
                        <p>Mohamed</p>
                        <div >
                        <i class="fas fa-cog"></i>
                        <a href="{{ route('logout') }}" style="color:white">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                            <div class="notificationIcon">
                            <i class="fas fa-bell " style="margin-right:-5px"></i>
                            @if($projects->count() != 0)
                                <span></span>
                            @endif
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="LeftBox">
                    <p>Main Categories</p>
                    <div class="tabsDiv">
                      <li class="home tabActive"><i class="fas fa-warehouse"></i><p>Home</p></li>
                      <li class="users"><i class="fas fa-users-cog"></i><p>Users</p></li>
                      <li class="projects"><i class="fas fa-project-diagram"></i><p>Projects</p></li>
                      <li class="categoryType"><i class="fas fa-boxes"></i><p>Category Type</p></li>
                        <li class="categories"><i class="fas fa-boxes"></i><p>Categories</p></li>
                        <li class="tags"><i class="fas fa-tags"></i><p>Tags</p></li>
                        <li class="programs"><i class="fab fa-buffer"></i><p>Programs</p></li>
                        <li class="offers"><i class="fas fa-gift"></i><p>Offers</p></li>
                        <li class="membership"><i class="fas fa-users"></i><p>Membership</p></li>
                        <li class="reports"><i class="fas fa-users"></i><p>Reports</p></li>
                        <li class="votes"><i class="fas fa-users"></i><p>Voting</p></li>
                    </div>
                </div>
                <div class="LeftBox">
                    <p>Extra Categories</p>
                    <div class="tabsDiv">
                      <li class=""><p>Privacy</p></li>
                      <li class=""><p>About</p></li>
                    </div>
                </div>
            </div>
            <div class="mainWindow" >
                <div class="loading">
                    <div class="loadingIcon"></div>
                </div>
                @include('admin.home.adminHome',['projects' => $projects])
            </div>
        </div>
    
    
    </div>

        <script src="{{asset('js')}}/jquery-3.4.1.min.js"></script>
    <script src="{{asset('bootstrap')}}/js/bootstrap.min.js"></script>
    <script src="{{asset('js')}}/hartala.js"></script>
    
    
</body>


</html>
