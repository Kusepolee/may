@extends('head')

@section('content')
<?php 
$t = new FooWeChat\Helpers\Helper; 
if(isset($type)){
$error = $t->errorCode($type, $code);
$err = $error[0].': '.$error[1];
}

?>
<div class="container">
  <div class="col-md-4 col-md-offset-4">
  
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="glyphicon glyphicon-barcode"></i>&nbsp身份验证
      </div>
      <div class="panel-body">
      {!! Form::open(['url'=>'login', 'role' => 'form']) !!}
        <div class="form-group">
          <h5>账号</h5>
          {!! Form::text('workid',null,['placeholder'=>'编号或手机号..', 'class'=>'form-control']) !!}
        </div>
        <div class="form-group">
          <h5>密码</h5>
          {!! Form::password('password',['placeholder'=>'密码..', 'class'=>'form-control']) !!}
        </div>

		@if (isset($redirect_path))
	        {!! Form::hidden('redirect_path', $redirect_path) !!}
	    @endif

		{!! Form::submit('确定', ['class'=>'btn btn-info btn-block']) !!}

        {!! Form::close() !!}
      </div>
    </div>
      <!-- 错误信息 -->

	    @if($errors->any())  
			<div class="panel-body">
      		<div class="alert alert-danger">
	        @foreach($errors->all() as $error)
	            <p>错误: {{ $error }}</p>
	        @endforeach
	        </div>
	        </div>
	                  
	    @endif

	    @if(isset($type))
	    	<div class="panel-body">
      		<div class="alert alert-danger">
	          <p>{{ $err }}</p>
	        </div>
	        </div>
	    @endif

      <!-- 错误信息:结束 -->
  </div>
</div>

@endsection