<?php
$t = new FooWeChat\Helpers\Helper;

$unit = $t->getSelect('unit');
$type = $t->getSelect('resourceType');


?>
@extends('head')

@section('form')
<div class="account-container register">
    
    <div class="content clearfix">
    {!! Form::open(['url'=>'resource/store', 'class' => 'form']) !!}
    <h3><a href="/resource">资源</a>
    @if(isset($act))
    {{ $act }}
    @endif
    </h3>
    <p></p>
    <div class="login-fields">

    
    @if($errors->any())  

        @foreach($errors->all() as $error)
            <p>错误: {{ $error }}</p>
        @endforeach
                  
    @endif


    @if(isset($db_error))
          {!! $t->errorCode($db_error) !!}
    @endif

    <div class="field">
        {!! Form::text('name',null,['placeholder'=>'资源名称', 'class'=>'login']) !!}
    </div>

    <div class="field">
        {!! Form::select('unit', $unit,null,['class'=>'login']); !!}
    </div>

    <div class="field">
        {!! Form::select('type',$type,null,['class'=>'login']); !!}
    </div>

    <div class="field">
        {!! Form::text('notice',null,['placeholder'=>'提醒值', 'class'=>'login']) !!}
    </div>

    <div class="field">
        {!! Form::text('alert',null,['placeholder'=>'报警值', 'class'=>'login']) !!}
    </div>

    <div class="field">
        {!! Form::text('content',null,['placeholder'=>'备注', 'class'=>'login']) !!}
    </div>


    <div class="login-actions">
        <span class="login-checkbox">
                </span>
         {!! Form::submit('提交', ['class'=>'btn btn-primary']) !!}

    </div>
    </div>
    {!! Form::close() !!}
</div>
</div>
@endsection