<?php
$t = new FooWeChat\Helpers\Helper;

$unit = $t->getSelect('unit');
$inType = $t->getSelect('resourcein');
$outType = $t->getSelect('resourceout');


?>
@extends('head')

@section('content')

<div class="container">
  <div class="col-md-4 col-md-offset-4">
  
<ol class="breadcrumb">
  <li><a href="/resource/show/{{ $rec->id }}">资源信息</a></li>
  <li class="active" >{{ $act or '' }}</li>
</ol>

    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="glyphicon glyphicon-leaf"></i>&nbsp{{ $rec->name or '' }}&nbsp{{ $rec->model or '' }}
        <!-- <a style=" float:right;" href="#" class="glyphicon glyphicon-question-sign"></a> -->
      </div>
      <div class="panel-body">
      @if(isset($i))
      {!! Form::open(['url'=>'resource/in/store', 'role' => 'form']) !!}
      {!! Form::hidden('resource', $rec['id']) !!}

      <div class="form-group">
          {!! Form::select('type',$inType,null,['class'=>'form-control']); !!}
      </div>

      <div class="form-group">
          {!! Form::text('amount','',['placeholder'=>'数量', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
          {!! Form::text('content',null,['placeholder'=>'说明', 'class'=>'form-control']) !!}
      </div>
      
      {!! Form::submit('提交', ['class'=>'btn btn-info btn-block']) !!}

      {!! Form::close() !!}

      @else
      {!! Form::open(['url'=>'resource/out/store', 'role' => 'form']) !!}
      {!! Form::hidden('resource', $rec['id']) !!}

      <div class="form-group">
          {!! Form::select('type',$outType,null,['class'=>'form-control']); !!}
      </div>
      
      <div class="form-group">
          {!! Form::text('amount',null,['placeholder'=>'数量', 'class'=>'form-control']) !!}
      </div>

      <div class="form-group">
          {!! Form::text('content',null,['placeholder'=>'说明', 'class'=>'form-control']) !!}
      </div>
      
      {!! Form::submit('提交', ['class'=>'btn btn-info btn-block']) !!}

      {!! Form::close() !!}

      @endif
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