<?php
	$dp = ['6'=>'运营部', '10'=>'监察部', '3'=>'总经理'];
	$type = ['匿名','实名'];
?>
@extends('head')


@section('content')
<div class="container">
	<div class="col-md-4 col-md-offset-4">
  
	<ol class="breadcrumb">
		<li><a href="/panel">面板首页</a></li>
	</ol>
		<div class="panel panel-info">
			<div class="panel-heading">
			<i class="glyphicon glyphicon-tasks"></i>&nbsp投诉
			<!-- <a style=" float:right;" href="#" class="glyphicon glyphicon-question-sign"></a> -->
			</div>
			<div class="panel-body">

			{!! Form::open(['url'=>'panel/complaints/store', 'role' => 'form']) !!}
			{!! Form::hidden('user_id', $id) !!}

			<div class="form-group">
			  {!! Form::select('type',$type,null,['class'=>'form-control']); !!}
			</div>

			<div class="form-group">
			  {!! Form::select('target',$dp,null,['class'=>'form-control']); !!}
			</div>

			<div class="form-group">
			  {!! Form::textarea('content',null,['placeholder'=>'内容', 'class'=>'form-control', 'rows'=>'6']) !!}
			</div>

			{!! Form::submit('提交', ['class'=>'btn btn-info btn-block']) !!}
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
	</div>
</div>
@endsection