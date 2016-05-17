<?php
$t = new FooWeChat\Helpers\Helper;

$unit = $t->getSelect('unit');
$type = $t->getSelect('resourceType');


?>
@extends('head')

@section('content')

<div class="container">
  <div class="col-md-4 col-md-offset-4">
  
<ol class="breadcrumb">
  <li><a href="/resource">资源管理</a></li>
  <li class="active" >{{ $act or '' }}</li>
</ol>

    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="glyphicon glyphicon-leaf"></i>&nbsp{{ $act or '' }}
      </div>
      <div class="panel-body">
      {!! Form::open(['url'=>'resource/store', 'role' => 'form']) !!}

      <div class="form-group">
          {!! Form::text('name',null,['placeholder'=>'资源名称', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
          {!! Form::select('unit', $unit,null,['class'=>'form-control']); !!}
      </div>

      <div class="form-group">
          {!! Form::select('type',$type,null,['class'=>'form-control']); !!}
      </div>

      <div class="form-group">
          {!! Form::text('notice',null,['placeholder'=>'提醒值', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
          {!! Form::text('alert',null,['placeholder'=>'报警值', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
          {!! Form::text('content',null,['placeholder'=>'备注', 'class'=>'form-control']) !!}
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