<?php
$t = new FooWeChat\Helpers\Helper;

$unit = $t->getSelect('unit');
$type = $t->getSelect('resourceIn');


?>
@extends('head')

@section('form')
<div class="account-container register">
    
    <div class="content clearfix">
    {!! Form::open(['url'=>'resource/in/store', 'class' => 'form']) !!}
    <h3><a href="/resource">资源: </a>{{ $outs['name'] }}{{ $remain.$outs['unit'] }}
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
        {!! Form::select('type',$type,null,['class'=>'login']); !!}
    </div>

    <div class="field">
        {!! Form::text('amount',null,['placeholder'=>'数量', 'class'=>'login']) !!}
    </div>

    <div class="field">
        {!! Form::text('content',null,['placeholder'=>'说明', 'class'=>'login']) !!}
    </div>

    {!! Form::hidden('resource', $outs['id']) !!}


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