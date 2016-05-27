@extends('head')

@section('content')

<script src="{{ URL::asset('bower_components/iCheck/icheck.min.js') }}"></script>
<link href="{{ URL::asset('bower_components/iCheck/skins/square/blue.css') }}" rel="stylesheet">
<div class="container">
<input type="checkbox" checked>本人
<input type="radio" name="iCheck">所有上级
<input type="radio" name="iCheck" >上级
<input type="radio" name="iCheck1">下级
<input type="radio" name="iCheck1">所有下级
</div>
<script>
$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
  });
});
</script>
@endsection