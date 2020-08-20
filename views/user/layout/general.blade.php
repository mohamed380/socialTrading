<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('fontawesome')}}/css/all.css">
    <link rel="stylesheet" href="{{asset('bootstrap')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('css')}}/wtp.css">
    <title>WTP</title>
</head>
<body>
  <div id='app' style="height:100%">
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" style="z-index:98">
        <a class="navbar-brand" href="#">Navbar</a>
    </nav>
 <div class="body" style="height:100%;margin-top:56px">
  <auth-buttons></auth-buttons>
  <search-box :key='$route.name'></search-box>
  <keep-alive include="projects" max="1">
    <router-view ></router-view> 
  </keep-alive>  
  <vue-progress-bar></vue-progress-bar>
 </div>
  </div>
  <script>
   
  </script>
  <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{asset('js')}}/jquery-3.4.1.min.js"></script>
    <script src="{{asset('js')}}/jquery-3.4.1.min.js"></script>
    <script src="{{asset('bootstrap')}}/js/bootstrap.min.js"></script>
    <script src="{{asset('js')}}/wtp.js"></script>  
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
 
</body>

</html>