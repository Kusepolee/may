<?php 
$t = new FooWeChat\Helpers\Helper;
$error = $t->errorCode($type, $code);
$type = $error[0];
$code = $error[1];
?>
<!doctype html>
</html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>{{ $type or 'title' }}</title>
   <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
   <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
   <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
      <link href="{{ URL::asset('asset/css/style.css') }}" rel="stylesheet" type="text/css" />
   <link href="css/font-awesome.css" rel="stylesheet" />

</head>

<body>

  <div class="container">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-6  col-md-offset-3">

          @if($color =='info')
            <div class="alert alert-info text-center">
          @elseif($color == 'warning')
            <div class="alert alert-warning text-center">
          @elseif($color == 'success')
            <div class="alert alert-success text-center">
          @else
            <div class="alert alert-danger text-center">
          @endif

          <h4>{{ $type or 'type' }}</h4> 
          <hr />
          <i class="fa fa-warning fa-4x"></i>
          <p>
              {{ $code or 'code' }}
          </p>

          </div>
        </div>
      </div>
    </div>
  </div>


</body>
</html>