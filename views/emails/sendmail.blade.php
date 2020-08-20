<!DOCTYPE html>
<html>
<head>

<title>{{ $details['title'] }}</title>
</head>
<style>
</style>
<body style="border:1px solid gainsboro; border-radius:4px; padding:10px 4px">
<span style="background:{{$details['color']}};color:white;padding:1px;border:1px solid green;border-radius:5px">{{$details['subject']}}</span>
   <div style="padding:5px">
   <pre>{{$details['body']}}</pre>
   </div>
    
</body>
</html>