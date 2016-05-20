<?php
$a = new FooWeChat\Authorize\Auth;
$h = new FooWeChat\Helpers\Helper;

$dp_list  = $h->getDepartmentsInUse();
$pos_list = $h->getPositionsInUse();
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
            <li class=""><a href="#seek" data-toggle="tab">查询</a>
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

                            @if(!$a->usingWechat())
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
                            @if(!$a->usingWechat())
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

                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel-heading"><em class="glyphicon glyphicon-th-list"></em>&nbsp&nbsp筛选条件:<a style=" float:right;" href="" class="glyphicon glyphicon-question-sign"></a></div>
                        <div class="panel-heading">

                           {!! Form::open(['url'=>'member/store', 'role' => 'form']) !!}

                        <label id="dp_label">部门</label>
                        <div class="input-group">
                           <div class="input-group-btn">
                              <button type="button" class="btn btn-default 
                                 dropdown-toggle" data-toggle="dropdown">
                                 <span id ="dp">等于</span>
                                 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu">
                                 <li><a href="javascript:set('=','dp');">等于: =</a></li>
                                 <li><a href="javascript:set('>=','dp');">大于等于: >=</a></li>
                                 <li><a href="javascript:set('<=','dp');">小于等于: <=</a></li>
                                 <li class="divider"></li>
                                 <li><a href="javascript:set('>','dp');">大于: ></a></li>
                                 <li><a href="javascript:set('<','dp');">小于: <</a></li>
                              </ul>
                           </div><!-- /btn-group -->

                           {!! Form::select('dp_val',$dp_list); !!}

                        </div><!-- /input-group -->
                    <p></p>
                    <label id="pos_label">职位</label>
                        <div class="input-group">
                           <div class="input-group-btn">
                              <button type="button" class="btn btn-default 
                                 dropdown-toggle" data-toggle="dropdown">
                            <span id ="pos">等于</span>
                                 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu">
                                 <li><a href="javascript:set('=','pos');">等于: =</a></li>
                                 <li><a href="javascript:set('>=','pos');">大于等于: >=</a></li>
                                 <li><a href="javascript:set('<=','pos');">小于等于: <=</a></li>
                                 <li class="divider"></li>
                                 <li><a href="javascript:set('>','pos');">大于: ></a></li>
                                 <li><a href="javascript:set('<','pos');">小于: <</a></li>
                              </ul>
                           </div><!-- /btn-group -->

                           {!! Form::select('dp_val',$pos_list); !!}

                        </div><!-- /input-group -->
                                <p></p>
                                <div class="form-group">
                                    <label>关键词</label>
                                    <input class="form-control" type="text">
                                </div>

                            {!! Form::submit("查询", ['class'=>'btn btn-info btn-block']) !!}

                            {!! Form::close() !!}
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
<script> 
function set(key, pos){
    //alert(key);
    var v = "#"+pos;
    var l = "#"+pos+"_label";

    var n = pos == "dp" ? "部门" : "职位";

    if(key=="="){
         $(v).html(key+"&nbsp&nbsp");
         $(l).html(n+"&nbsp&nbsp");
    }else{
        $(v).html("<span class=\"text-info\">"+key+"&nbsp&nbsp</span>");
        $(l).html("<span class=\"text-info\">"+n+"&nbsp&nbsp</span>");
    }
}
</script>

@endsection






