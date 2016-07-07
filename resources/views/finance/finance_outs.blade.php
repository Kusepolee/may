<?php
$h = new FooWeChat\Helpers\Helper;

$bill = $h->getSelect('financeOut');

?>
@extends('head')

@section('content')
<div class="container">
	<div class="col-md-4 col-md-offset-4">
  
	<ol class="breadcrumb">
		<li><a href="/finance">财务</a></li>
		<li class="active" >支出</li>
	</ol>
		<div class="panel panel-info">
			<div class="panel-heading">
			<i class="glyphicon glyphicon-user"></i>&nbsp{{ $user }}
			<!-- <a style=" float:right;" href="#" class="glyphicon glyphicon-question-sign"></a> -->
			</div>
			<div class="panel-body">

			{!! Form::open(['url'=>'finance/outs/store', 'role' => 'form']) !!}
			{!! Form::hidden('out_user', $user) !!}

			<div class="form-group">
			  {!! Form::text('out_amount',null,['placeholder'=>'金额', 'class'=>'form-control']) !!}
			</div>
			
			<div class="form-group">
			  {!! Form::text('out_item',null,['placeholder'=>'支出项', 'class'=>'form-control']); !!}
			</div>
			
			<div class="form-group">
			    <input name = "out_date" type = "date" class = "form-control">
			</div>

			<div class="form-group">
			  {!! Form::select('out_bill',$bill,null,['class'=>'form-control']); !!}
			</div>

			<div class="form-group">
			  {!! Form::select('out_about',$dp,null,['class'=>'form-control']); !!}
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