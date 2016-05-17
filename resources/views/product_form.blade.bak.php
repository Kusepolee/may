<?php
$t = new FooWeChat\Helpers\Helper;

$type = $t->getSelect('productType');

?>
@extends('head')

@section('form')
<div class="account-container register">
    
    <div class="content clearfix">
    {!! Form::open(['url'=>'product/store', 'class' => 'form']) !!}
    <h3><a href="/product">产品</a>
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

    <div class="field">
        {!! Form::text('name',null,['placeholder'=>'产品名称', 'class'=>'login']) !!}
    </div>

    <div class="field">
        {!! Form::text('for',null,['placeholder'=>'适配信息', 'class'=>'login']) !!}
    </div>

    <div class="field">
        {!! Form::select('type', $type,null,['class'=>'login']); !!}
    </div>

    <div class="field">
        {!! Form::text('price',null,['placeholder'=>'价格', 'class'=>'login']) !!}
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