<?php
$a = new FooWeChat\Authorize\Auth;
// $h = new FooWeChat\Helpers\Helper;

// $image = 'http://'.$h->custom('url').'/upload/complaints/'.$img;

?>
@extends('head')

@section('content')

    <div class="container">
        <div id="page-wrapper" class="col-md-8">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line"><a href="/panel/complaints/record">{{ $user_name }}</a></h1>
                        <h1 class="page-subhead-line"><span style="font-size:15px; color:#8C8C8C; font-weight:200;">投送部门: {{ $dp }}</span></h1>
                    </div>
                </div>
                    <p>内容:</p>
                    <div class="col-md-16">
                        <div class="alert alert-info">
                            <p>{{ $rec->content }}</p>
                        </div>
                    </div>
                    @if($img != 0)
                    <p>图片:</p>
                        @if(!$a->usingWechat())
                        <div class="col-md-16">
                        <a href="{{ URL::asset("upload/complaints/").'/'.$img['0'] }}"><img src="{{ URL::asset("upload/complaints/").'/'.$img['0'] }}" class="img-thumbnail"></a>
                        @if(isset($img['1']))<a href="{{ URL::asset("upload/complaints/").'/'.$img['1'] }}"><img src="{{ URL::asset("upload/complaints/").'/'.$img['1'] }}" class="img-thumbnail"></a>@endif
                        @if(isset($img['2']))<a href="{{ URL::asset("upload/complaints/").'/'.$img['2'] }}"><img src="{{ URL::asset("upload/complaints/").'/'.$img['2'] }}" class="img-thumbnail"></a>@endif
                        @if(isset($img['3']))<a href="{{ URL::asset("upload/complaints/").'/'.$img['3'] }}"><img src="{{ URL::asset("upload/complaints/").'/'.$img['2'] }}" class="img-thumbnail"></a>@endif
                        @if(isset($img['4']))<a href="{{ URL::asset("upload/complaints/").'/'.$img['4'] }}"><img src="{{ URL::asset("upload/complaints/").'/'.$img['2'] }}" class="img-thumbnail"></a>@endif
                        </div>
                        @else
                        <div class="col-md-16">
                        <a href="{{ URL::asset("upload/complaints/").'/'.$img['0'] }}"><img src="{{ URL::asset("upload/complaints/").'/'.$img['0'] }}" class="img-thumbnail"></a>
                        @if(isset($img['1']))<a href="{{ URL::asset("upload/complaints/").'/'.$img['1'] }}"><img src="{{ URL::asset("upload/complaints/").'/'.$img['1'] }}" class="img-thumbnail"></a>@endif
                        @if(isset($img['2']))<a href="{{ URL::asset("upload/complaints/").'/'.$img['2'] }}"><img src="{{ URL::asset("upload/complaints/").'/'.$img['2'] }}" class="img-thumbnail"></a>@endif
                        @if(isset($img['3']))<a href="{{ URL::asset("upload/complaints/").'/'.$img['3'] }}"><img src="{{ URL::asset("upload/complaints/").'/'.$img['2'] }}" class="img-thumbnail"></a>@endif
                        @if(isset($img['4']))<a href="{{ URL::asset("upload/complaints/").'/'.$img['4'] }}"><img src="{{ URL::asset("upload/complaints/").'/'.$img['2'] }}" class="img-thumbnail"></a>@endif
                        </div>
                        @endif
                    @endif
            </div>
        </div>
        
    </div>

@endsection