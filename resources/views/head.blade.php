<?php 
        $a = new FooWeChat\Authorize\Auth;
        $h = new FooWeChat\Helpers\Helper;
        $copyRight = $h->copyRight();
        $title = $h->custom('short_name');
        $domain = $h->custom('domain');
        $domain_ex = $h->custom('domain_ex');
?>
<!doctype html>
</html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>{{ $title or 'title' }}</title>
   <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
   <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
   <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
   <link href="{{ URL::asset('asset/css/style.css') }}" rel="stylesheet" type="text/css" />
   <link href="{{ URL::asset('asset/css/font-awesome.css')}}" rel="stylesheet" />

</head>

<body>
<div class="container">
     <nav class="navbar">
       <div class="navbar-inner">
       <a href="/" class="navbar-brand logo"><img id="tu1" src="{{ URL::asset('asset/img/logo.svg') }}" alt=""></a>
       </div>

      {{-- 用户菜单 --}}
       @if(Session::has('name'))
       <ul class="pull-right" style="list-style-type:none">
         <li class="dropdown">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown">
         {{ Session::get('name') }}
         </a>
           <ul class="dropdown-menu  pull-right">
           <li><a href="/member/show/{{ Session::get('id') }}">我的电子名片</a></li>
           <li><a href="/qrcode">关注{{ $h->custom('nic_name') }}</a></li>
           <li class="divider"></li>
           <li><a href="/member/show/{{ Session::get('id') }}">个人资料</a></li>


           {{-- 使用微信 不显示'退出'项 --}}
           @if(!$a->usingWechat())
           <li class="divider"></li>
           <li><a href="/logout">退出</a></li>
           @endif

           </ul>
         </li>
       </ul>
       @endif
       {{-- 用户菜单: 结束 --}}

     </nav>
</div>

<hr class="xian">

@yield('content')

<div class="footer">
		<p>{{ $copyRight or 'copyRight' }}</p>
</div>

</body>
</html>
