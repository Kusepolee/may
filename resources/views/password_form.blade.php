<?php 
$path = Request::path();
?>
@extends('head')

@section('content')

<div class="container">
  <div class="col-md-4 col-md-offset-4">
  
<ol class="breadcrumb">
  <li><a href="/member">用户管理</a></li>
  <li><a href="/member/show/{{ $id }}">我的资料</a></li>
  <li class="active" >{{ $act or '' }}</li>
</ol>

    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="glyphicon glyphicon-user"></i>&nbsp{{ $act or '' }}
      <a style=" float:right;" href="#" class="glyphicon glyphicon-question-sign"></a></div>
      <div class="panel-body">
      {!! Form::open(['url'=>'member/password/reset/'.$id, 'role' => 'form']) !!}

      <div class="form-group">
           {!! Form::password('password',['placeholder'=>'密码', 'class'=>'form-control']) !!}
      </div>
      {!! Form::hidden('path',isset($path) ? $path : null) !!}
      {!! Form::hidden('jump',isset($jump) ? $jump : 0) !!}

      <div class="form-group">
           {!! Form::password('password_confirmation',['placeholder'=>'确认密码', 'class'=>'form-control']) !!}
      </div>
      
      {!! Form::submit("提交", ['class'=>'btn btn-info btn-block']) !!}

      {!! Form::close() !!}

      </div>
    </div>

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

  </div>
</div>

@endsection