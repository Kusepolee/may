<?php
$a = new FooWeChat\Authorize\Auth;
$h = new FooWeChat\Helpers\Helper;
$w = new FooWeChat\Core\WeChatAPI;

$dp_list  = $h->getDepartmentsInUse();
$pos_list = $h->getPositionsInUse();

//设置EXCEL,微信通知查询条件
count($dp) ? $dp_string = implode("|", $dp) : $dp_string = '_not';
count($pos) ? $pos_string = implode("|", $pos) :$pos_string = '_not';
$key != '' && $key != null ? $key_string = $key : $key_string = '_not';
$full_seek_string = $dp_string."-".$pos_string."-".$key_string;

?>
@extends('head')

@section('content')
<!-- icheck -->
<script src="{{ URL::asset('bower_components/iCheck/icheck.min.js') }}"></script>
<link href="{{ URL::asset('bower_components/iCheck/skins/square/blue.css') }}" rel="stylesheet">
<!-- end of ickeck -->

<div class="container">
		<!-- nav -->
	    <ol class="breadcrumb">
        <li class="active" >用户管理</li>
        <li><a href="/member/create">添加用户</a></li>
        @if(count($dp) || count($pos) || ($key != '' && $key != null))
          <li><a href="/member">重置查询条件</a></li>
        @endif
        </ol>
        <!-- end nav -->

	<!-- labels head-->
	<ul id="myTab" class="nav nav-tabs">
	   @if(count($dp) || count($pos) || ($key != '' && $key != null))
            <li class="active"><a href="#members" data-toggle="tab">查询结果-{{ count($outs) }}</a>
        @else
            <li class="active"><a href="#members" data-toggle="tab">所有用户-{{ count($outs) }}</a>
        @endif
	   <li><a href="#seek" data-toggle="tab">查询</a></li>
	   <li><a href="#manage" data-toggle="tab">功能</a></li>
	</ul>
	<!-- end of labels head -->
	<!-- labels body -->
	<div id="myTabContent" class="tab-content">
	               <!-- members list -->
            <div class="tab-pane fade active in" id="members">
                <div class="table-responsive">
            @if(count($outs))
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
                              @if($a->isSelf($out->id))
                              <td><span class="text-primary">{{ $out->work_id }}</span></td>
                              @else
                              <td>{{ $out->work_id }}</td>
                              @endif
                              

                              @if($out->state == 0 && $out->admin != 0)
                                @if(!$w->hasFollow($out->id))
                                  <td> <a href="/member/show/{{ $out->id }}" class="btn btn-sm btn-default">{{ $out->name }}</a>
                                @else 
                                  <td> <a href="/member/show/{{ $out->id }}" class="btn btn-sm btn-success">{{ $out->name }}</a>
                                @endif
                              @elseif($out->state == 0 && $out->admin == 0)
                                @if(!$w->hasFollow($out->id))
                                  <td> <a href="/member/show/{{ $out->id }}" class="btn btn-sm btn-default">{{ $out->name }}</a>
                                @else 
                                  <td> <a href="/member/show/{{ $out->id }}" class="btn btn-sm btn-info">{{ $out->name }}</a>
                                @endif
                              @else
                                <td> <a href="/member/show/{{ $out->id }}" class="btn btn-sm btn-warning">{{ $out->name }}</a>
                              @endif
                              </td>
                              @if($a->sameDepartment($out->id))
                                  <td><span class="text-primary">{{ $out->departmentName }}</span></td>
                              @else 
                                  <td>{{ $out->departmentName }}</td>
                              @endif

                              @if($a->isSelf($out->id))
                              <td><span class="text-primary">{{ $out->positionName }}</span></td>
                              @else
                              <td>{{ $out->positionName }}</td>
                              @endif

                              @if(!$a->usingWechat())

                              @if($a->sameDepartment($out->id) || $a->auth(['position'=>'>=总监']) || $a->auth(['position'=>'>员工', 'department'=>'>=运营部']))
                                  <td>{{ $out->mobile }}</td>
                              @else
                                  <td>(已保护)</td>
                              @endif

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

                    <div class="container"> 
                        {!! $outs->render() !!}
                    </div>
                </div>
            </div>
            <!-- end of members -->
			<!-- seek -->
            <div class="tab-pane fade" id="seek">

                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel-heading"><em class="glyphicon glyphicon-th-list"></em>&nbsp&nbsp筛选条件:<a style=" float:right;" href="" class="glyphicon glyphicon-question-sign"></a></div>

                           {!! Form::open(['url'=>'member/seek', 'role' => 'form']) !!}
                           {!! Form::hidden('dp_operator','=',['id'=>'dp_operator']) !!}
                           {!! Form::hidden('pos_operator','=',['id'=>'pos_operator']) !!}
                    
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

                           {!! Form::select('dp_val',$dp_list, null,['class'=>'form-control']); !!}

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

                           {!! Form::select('pos_val',$pos_list, null,['class'=>'form-control']); !!}

                        </div><!-- /input-group -->
                                <p></p>
                                <div class="form-group">
                                    <label>关键词</label>
                                {!! Form::text('key',null,['placeholder'=>'关键词', 'class'=>'form-control']) !!}
                                </div>

                            {!! Form::submit("查询", ['class'=>'btn btn-info btn-block']) !!}

                            {!! Form::close() !!}
                        </div>

                    </div>
              <!-- end of seek -->
        <!-- manage -->
        <p></p>
	    <div class="tab-pane fade" id="manage">
		    <div class="col-md-4 col-sm-4" id="excel_div">
	        	<div class="panel panel-success">
	            <div class="panel-heading">
	                <i class="glyphicon glyphicon-th"></i>&nbsp&nbspExcel
	            </div>
	            <div class="panel-body">
                  <span id="info_txt"></span>
	                <blockquote>
	                <small>将本次查询结果保存为Excel文件, 若有多页, 则所有结果都保存,但Excel文件中不再分页。</small>
                  </blockquote>
                          <!-- form excel -->
                          {!! Form::open(['url'=>'/excel/member', 'role' => 'form', 'id'=>'excel_get']) !!}
                          {!! Form::hidden('seek_string', $full_seek_string) !!}
                          {!! Form::close() !!}
                          <!-- end of form excel -->

                  <input type="hidden" id="seek_string" value="{{$full_seek_string}}">
                  <input type="hidden" id="_token" value="{{$full_seek_string}}">
	            </div>
	            <div class="panel-footer">
                  @if($a->auth(['admin'=>'no', 'position'=>'>=总监', 'department' => '>=运营部']))
	                <a class="btn btn-sm btn-success btn-block" href="javascript:getExcel();">保存</a>
                  @else 
                  没有权限
                  @endif 
	            </div>
	        	</div>
	    	</div>

        <p></p>
	    	<div class="col-md-4 col-sm-4" id="notice_div">
	        	<div class="panel panel-info">
	            <div class="panel-heading">
	                <i class="glyphicon glyphicon-bullhorn"></i>&nbsp&nbsp通知查询结果中的用户
	            </div>
	            <div class="panel-body">
                  <!-- form msg -->
                  {!! Form::open(['url'=>'/notice/member', 'role' => 'form', 'id'=>'notice_send']) !!}
                  {!! Form::hidden('seek_string_notice', $full_seek_string) !!}
                  {!! Form::textarea('notice', null, ['placeholder'=>'消息内容', 'class'=>'form-control']) !!}
                  <p></p>
                  <input type="radio" checked="checked">&nbsp&nbsp忽略无权发送和未关注的
                  {!! Form::close() !!}
                  <!-- end of form msg -->

	            </div>
	            <div class="panel-footer">
                  @if($a->auth(['position'=>'>=经理']))
                  <a class="btn btn-sm btn-info btn-block" href="javascript:sendNotice();">发送微信通知</a>
                  @else 
                  没有权限
                  @endif 
	                
	            </div>
	        	</div>
	    	</div>
	   </div>
	   <!-- end of manage -->
	</div>
	<!-- end of labels body -->
</div>
<script> 
// init ichcek
$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
  });
});

//查询
function set(key, pos){
    var v = "#"+pos;
    var l = "#"+pos+"_label";
    var op = "#"+pos+"_operator";

    $(op).val(key);

    var n = pos == "dp" ? "部门" : "职位";

    if(key=="="){
         $(v).html(key+"&nbsp&nbsp");
         $(l).html(n+"&nbsp&nbsp");
    }else{
        $(v).html("<span class=\"text-info\">"+key+"&nbsp&nbsp</span>");
        $(l).html("<span class=\"text-info\">"+n+"&nbsp&nbsp</span>");
    }
}
// excel
function getExcel(){
  $("#excel_get").submit();
}

//notice
function sendNotice(){
  $("#notice_send").submit();
}
</script>
@endsection











