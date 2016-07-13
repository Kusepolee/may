<?php
	
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
			<i class="glyphicon glyphicon-tasks"></i>&nbsp图片上传
			<!-- <a style=" float:right;" href="#" class="glyphicon glyphicon-question-sign"></a> -->
			</div>
			<div class="panel-body">

			{!! Form::open(['url'=>'panel/complaints/image/store', 'role' => 'form', 'enctype'=>'multipart/form-data']) !!}
			{!! Form::hidden('id', $id) !!}

			<div class="form-group">
			  <input type="file" id="pic" name="pic" />
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