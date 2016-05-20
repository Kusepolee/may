<?php
$a = new FooWeChat\Authorize\Auth;
$h = new FooWeChat\Helpers\Helper;

$gender = $h->getSelect('gender');

if($a->isRoot()){
  //$department = $h->getAllDepartments();
  $department = $h->getInsideDepartments();
  $position = $h->getAllPositions();
}elseif(!$a->isRoot() && $a->isAdmin()){
  $department = $h->getInsideDepartments();
  $position = $h->getAllPositions();
}else{
  $department = $a->getDepartments();
  $position = $a->getPositions();
}

$allPositions = $h->getAllPositions();

?>
@extends('head')

@section('content')

<div class="container">
  <div class="col-md-4 col-md-offset-4">
  
<ol class="breadcrumb">
  <li><a href="/member">用户管理</a></li>
  <li class="active" >{{ $act or '' }}</li>
</ol>

    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="glyphicon glyphicon-user"></i>&nbsp{{ $act or '' }}
      <a style=" float:right;" href="#" class="glyphicon glyphicon-question-sign"></a></div>
      <div class="panel-body">
      @if(isset($rec))
      {!! Form::open(['url'=>'member/update/'.$rec->id, 'role' => 'form']) !!}
      @else
      {!! Form::open(['url'=>'member/store', 'role' => 'form']) !!}
      @endif
      <div class="form-group">
        {!! Form::text('name', isset($rec) ? $rec->name : null,['placeholder'=>'姓名', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
          {!! Form::select('gender', $gender,isset($rec) ? $rec->gender : null,['class'=>'form-control']); !!}
      </div>
      
      @if(isset($rec) && !$a->isRoot() && !$a->isAdmin())
        <div class="form-group">
            {!! Form::select('department_show',$department,isset($rec) ? $rec->department : null,['class'=>'form-control', 'disabled'=>'ture']); !!}
        </div>

        <div class="form-group">
            {!! Form::select('position_show',$allPositions,isset($rec) ? $rec->position : null,['class'=>'form-control', 'disabled'=>'ture']); !!}
        </div>
        {!! Form::hidden('department',isset($rec) ? $rec->department : null) !!}
        {!! Form::hidden('position',isset($rec) ? $rec->position : null) !!}
      @else
        <div class="form-group">
            {!! Form::select('department', $department,isset($rec) ? $rec->department : null,['class'=>'form-control']); !!}
        </div>

        <div class="form-group">
            {!! Form::select('position', $position,isset($rec) ? $rec->position : null,['class'=>'form-control']); !!}
        </div>
      @endif

      <div class="form-group">
          {!! Form::text('mobile',isset($rec) ? $rec->mobile : null,['placeholder'=>'手机号', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
          {!! Form::text('email',isset($rec) ? $rec->email : null,['placeholder'=>'邮件', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
          {!! Form::text('weixinid',isset($rec) ? $rec->weixinid : null,['placeholder'=>'微信号', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
          {!! Form::text('qq',isset($rec) ? $rec->qq : null,['placeholder'=>'QQ号', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
          {!! Form::text('content',isset($rec) ? $rec->content : null,['placeholder'=>'备注', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
           {!! Form::password('password',['placeholder'=>'密码', 'class'=>'form-control']) !!}
      </div>

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