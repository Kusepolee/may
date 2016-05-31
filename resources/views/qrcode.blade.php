@extends('head')
@section('content')
<div class="container">
  <div class="col-md-4 col-md-offset-4">
  
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="glyphicon glyphicon-qrcode"></i>&nbsp关注{{ $name or 'error' }}, 请使用微信扫描
      </div>
      <div class="panel-body" style="display:table;margin:10px auto;">
      @if($png === 0)
    {!! QrCode::encoding('UTF-8')->size(230)->generate($qrcode);!!}
    @elseif($png ==='wifi')
		{!! QrCode::encoding('UTF-8')->size(230)->wiFi($qrcode);!!}
    @else
    {!! QrCode::encoding('UTF-8')->size(230)->generate($qrcode);!!}
    @endif
      </div>
    </div>

  </div>
</div>
@endsection