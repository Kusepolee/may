<?php
$t = new FooWeChat\Authorize\Auth;
?>
@extends('head')

@section('content')


<div class="col-md-16">
    <ol class="breadcrumb">
        <li class="active" >用户管理</li>
        <li><a href="/member/create">添加用户</a></li>
    </ol>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#members" data-toggle="tab">员工列表</a>
            </li>
            <li class=""><a href="#seek" data-toggle="tab">查询条件</a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- members list -->
            <div class="tab-pane fade active in" id="members">
                <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>姓名</th>
                            <th>部门</th>
                            <th>职位</th>

                            @if(!$t->usingWechat())
                            <th>手机</th>
                            <th>信箱</th>
                            <th>QQ</th>
                            <th>微信</th>
                            <th>备注</th>
                            @endif

                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($outs as $out)
                        <tr>
                            <td>{{ $out->work_id }}</td>
                            @if($out->state == 0 && $out->admin != 0)
                            <td> <a href="/member/show/{{ $out->id }}" class="btn btn-sm btn-success">{{ $out->name }}</a href="#">
                            @elseif($out->state == 0 && $out->admin == 0)
                            <td> <a href="/member/show/{{ $out->id }}" class="btn btn-sm btn-info">{{ $out->name }}</a href="#">
                            @else
                            <td> <a href="/member/show/{{ $out->id }}" class="btn btn-sm btn-warning">{{ $out->name }}</a href="#">
                            @endif
                        </td>
                            <td>{{ $out->departmentName }}</td>
                            <td>{{ $out->positionName }}</td>
                            @if(!$t->usingWechat())
                            <td>{{ $out->mobile }}</td>

                                @if($out->email != '' && $out->email != null)
                                <td>{{ $out->email }}</td>
                                @else
                                <td>(无)</td>
                                @endif

                                @if($out->qq != '' && $out->qq != null)
                                <td>{{ $out->qq }}</td>
                                @else
                                <td>(无)</td>
                                @endif

                                @if($out->weixinid != '' && $out->weixinid != null)
                                <td>{{ $out->weixinid }}</td>
                                @else
                                <td>(无)</td>
                                @endif

                                @if($out->content != '' && $out->content != null)
                                <td>{{ $out->content }}</td>
                                @else
                                <td>(无)</td>
                                @endif

                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>
            <!-- end of members list -->

            <!-- seek -->
            <div class="tab-pane fade" id="seek">
                <div class="panel">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel-heading"><em class="glyphicon glyphicon-search"></em>&nbsp&nbsp请输入查询条件<a style=" float:right;" href="" class="glyphicon glyphicon-question-sign"></a></div>
                        <div class="panel-heading">
                            <form role="">
                                <div class="form-group">
                                    <label>部门</label>
                                    <input class="form-control" type="text">
                                </div>

                                <div class="form-group">
                                    <label>职位</label>
                                    <input class="form-control" type="text">
                                </div>

                                <div class="form-group">
                                    <label>关键词</label>
                                    <input class="form-control" type="text">
                                </div>

                                </form>
                            <a href="#" class="btn btn-info btn-block">View All Alerts</a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- seek -->
        </div>
    </div>

    <div class="container"> 
        {!! $outs->render() !!}
    </div>

@endsection