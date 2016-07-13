<?php
	$a = new FooWeChat\Authorize\Auth;
	$h = new FooWeChat\Helpers\Helper;
?>
@extends('head')

@section('content')

<div class="container">
	<div class="col-md-16">
    <ol class="breadcrumb">
        <li class="active" >投诉记录</li>
        <li><a href="/panel">面板首页</a></li>
    </ol>
        <div class="table-responsive">
            @if(count($rec))
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>投诉人</th>
                            <th>投送部门</th>
                            <th>内容</th>
                            <th>时间</th>
                            @if(!$a->usingWechat())
                            <th>状态</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rec as $out)
                        <tr>
                        	<td>{{ $out->id }}</td>
                        	<td>{{ $out->userName }}</td>
                        	<td>{{ $out->dpName }}</td>
                            <td><a href="/panel/complaints/show/{{ $out->id }}">查看</a></td>
                            <td>{{ $out->created_at }}</td>
                            @if(!$a->usingWechat())
                            <td>{{ $out->state }}</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p></p>
                <div class="col-md-4 col-sm-4 col-md-offset-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <em class="glyphicon glyphicon-info-sign"></em>&nbsp&nbsp提示
                        </div>
                        <div class="panel-body">
                            <p>无记录: 可能因没有符合查询条件记录, 或尚未有数据录入</p>
                        </div>
                    </div>
                </div>
            @endif
                <div> 
                    {!! $rec->render() !!}
                </div>
        </div>
    </div>
</div>

@endsection