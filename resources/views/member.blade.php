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

    <div class="container"> 
        {!! $outs->render() !!}
    </div>

@endsection