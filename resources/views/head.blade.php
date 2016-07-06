<?php 
        $a = new FooWeChat\Authorize\Auth;
        $h = new FooWeChat\Helpers\Helper;
        $copyRight = $h->copyRight();
        $title = $h->custom('short_name');
        // $domain = $h->custom('domain');
        // $domain_ex = $h->custom('domain_ex');
        if(Session::has('id')) $me = $h->getMemberItems(['img','name']);

?>
<!doctype html>
</html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>{{ $title or 'title' }}</title>

   <link rel="stylesheet" type="text/css" href="{{ URL::asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" >
   <link href="{{ URL::asset('custom/css/style.css') }}" rel="stylesheet" type="text/css" >
   <script src="{{ URL::asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
   <script src="{{ URL::asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>

</head>

<body>
<div class="container">
     <nav class="navbar">
       <div class="navbar-inner">
       <a href="/" class="navbar-brand logo"><img id="tu1" src="{{ URL::asset('custom/image/logo.svg') }}" alt=""></a>
       </div>

      {{-- 用户菜单 --}}
       @if(Session::has('name'))
       <ul class="pull-right" style="list-style-type:none">
         <li class="dropdown">
         
         @if($h->hasImage())
         <span class="text-primary"> {{ $me[1] }}</span><a href="#" class="dropdown-toggle" data-toggle="dropdown"><img id= "head_image" src="{{ URL::asset("upload/member/").'/'.$me[0]}}" class="img-circle image_circle">
         @else
         <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $me[1] }}
         @endif
         </a>
           <ul class="dropdown-menu  pull-right">
           <li><a href="/oa/vcard"><span class="pull-left glyphicon glyphicon-qrcode"></span>&nbsp&nbsp我的电子名片</a></li>
            
          @if($h->hasWechatCode())
           <li><a href="/oa/qrcode/{{ Session::get('id') }}"><span class="pull-left glyphicon glyphicon-qrcode menu_icon_success"></span>&nbsp加我微信</a></li>
           @endif


           <li class="divider"></li>

           <li><a href="/oa/qrcode"><span class="pull-left glyphicon glyphicon-qrcode menu_icon_info"></span>&nbsp关注{{ $h->custom('nic_name') }}</a></li>
           <li class="divider"></li>
           <li><a href="/member/show/{{ Session::get('id') }}"><span class="pull-left glyphicon glyphicon-cog"></span>&nbsp&nbsp个人中心</a></li>


           {{-- 使用微信 不显示'退出'项 --}}
           @if(!$a->usingWechat())
           <li class="divider"></li>
           <li><a href="/logout"><span class="pull-left glyphicon glyphicon-off"></span>&nbsp&nbsp退出</a></li>
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
