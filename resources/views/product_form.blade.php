<?php
$t = new FooWeChat\Helpers\Helper;
$type = $t->getSelect('productType');
?>
@extends('head')

@section('content')

<div class="container">
  <div class="col-md-4 col-md-offset-4">
  
<ol class="breadcrumb">
  <li><a href="/product">产品管理</a></li>
  <li class="active" >{{ $act or '' }}</li>
</ol>

    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="glyphicon glyphicon-th"></i>&nbsp{{ $act or '' }}
      </div>
      <div class="panel-body">
      {!! Form::open(['url'=>'product/store', 'role' => 'form']) !!}

      <div class="form-group">
          {!! Form::text('name',null,['placeholder'=>'产品名称', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
          {!! Form::text('for',null,['placeholder'=>'适配信息', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
          {!! Form::select('type', $type,null,['class'=>'form-control']); !!}
      </div>

      <div class="form-group">
          {!! Form::text('price',null,['placeholder'=>'价格', 'class'=>'form-control']) !!}
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