<?php
$a = new FooWeChat\Authorize\Auth;
$h = new FooWeChat\Helpers\Helper;
$w = new FooWeChat\Core\WeChatAPI;

$origin = $rec->name;
$xing = mb_substr($origin,0,1,'utf-8');
$ming = mb_substr($origin,1,mb_strlen($origin),'utf-8');

$tel = str_replace(' ', '', $h->custom('tel'));

$vcard = 'BEGIN:VCARD
VERSION:2.1
N:'.$xing.';'.$ming.';
FN:'.$rec->name.'
ORG:'.$h->custom('name').'
TITLE:'.$rec->departmentName.'-'.$rec->positionName.'
TEL;CELL;VOICE:'.$rec->mobile.'
TEL;WORK;VOICE:'.$tel.'
URL:'.$h->custom('url').'
EMAIL;PREF;INTERNET:'.$rec->email.'
REV:20060220T180305Z
END:VCARD';
?>

@extends('head')

@section('content')

<div class="container">
  <div class="col-md-4 col-md-offset-4">
  
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="glyphicon glyphicon-qrcode"></i>&nbsp电子名片, 请使用微信扫描
      </div>
      <div class="panel-body" style="display:table;margin:10px auto;">
		{!! QrCode::encoding('UTF-8')->size(230)->generate($vcard);!!}
      </div>
    </div>

  </div>
</div>

@endsection