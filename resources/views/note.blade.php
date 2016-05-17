
<?php 
$h = new FooWeChat\Helpers\Helper;
$error = $h->errorCode($type, $code);
$type = $error[0];
$code = $error[1];
?>
@extends('head')

@section('content')

  <div class="container">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-6  col-md-offset-3">

            <div class="alert alert-{{ $color or 'info'}} text-center">

          <h4>{{ $type or 'type' }}</h4> 
          <hr />
          <i class="fa fa-warning fa-4x"></i>
          <p>
              {{ $code or 'code' }}
          </p>
          <hr />
               @if(isset($btn) && isset($link))
                  <a href="{{ $link or '#' }}" class="btn btn-{{$color or 'info'}}">{{ $btn or '空'}}</a> 
               @endif

               @if(isset($btn1) && isset($link1))
                  &nbsp&nbsp<a href="{{ $link1 or '#' }}" class="btn btn-{{$color or 'info'}}">{{ $btn1 or '空'}}</a> 
               @endif

               @if(isset($btn2) && isset($link2))
                  &nbsp&nbsp<a href="{{ $link2 or '#' }}" class="btn btn-{{$color or 'info'}}">{{ $btn2 or '空'}}</a> 
               @endif

          </div>
        </div>
      </div>
    </div>
  </div>

  @endsection
