<?php

$h = new FooWeChat\Helpers\Helper;
$qrcode = $h->custom('qrcode');
?>


@extends('head')
@section('content')
<div class="container">
  <div class="col-md-4 col-md-offset-4">
  
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="glyphicon glyphicon-qrcode"></i>&nbsp关注{{ $h->custom('nic_name') }}, 请使用微信扫描
      </div>
      <div class="panel-body" style="display:table;margin:10px auto;">
		{!! QrCode::encoding('UTF-8')->size(230)->generate($qrcode);!!}
      </div>
    </div>

  </div>
</div>
@endsection