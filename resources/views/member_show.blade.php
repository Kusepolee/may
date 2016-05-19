<?php
$a = new FooWeChat\Authorize\Auth;
$h = new FooWeChat\Helpers\Helper;

$origin = $rec->name;
$xing = mb_substr($origin,0,1,'utf-8');
$ming = mb_substr($origin,1,mb_strlen($origin),'utf-8');

$vcard = 'BEGIN:VCARD
VERSION:2.1
N:'.$xing.';'.$ming.';
FN:'.$rec->name.'
ORG:'.$h->custom('name').'
TITLE:'.$rec->departmentName.'-'.$rec->positionName.'
TEL;CELL;VOICE:'.$rec->mobile.'
TEL;WORK;VOICE:'.$h->custom('tel').'
URL:'.$h->custom('url').'
EMAIL;PREF;INTERNET:'.$rec->email.'
REV:20060220T180305Z
END:VCARD';


?>



@extends('head')

@section('content')

	<div class="container">
		<div id="page-wrapper">
			<div id="page-inner">
				<div class="row">
					<div class="col-md-12">
						<h1 class="page-head-line"><a href="/member"> {{ $rec->name or '姓名' }}</a> </h1>
						<h1 class="page-subhead-line">{{ $rec->departmentName or '部门' }} - {{ $rec->positionName or '职位' }}
							@if(isset($rec) && $rec->admin === 0)
								(系统管理员)
							@endif
						</h1>
					</div>
				</div>

				<div class="row">
				  <div class="col-md-4" id ="left">
				  
				    <div class="panel panel-info"  >
				      <div class="panel-heading">
				        <i class="glyphicon glyphicon-qrcode"></i>&nbsp电子名片: 请使用微信扫描
				      </div>
				      <div class="panel-body" style="display:table;margin:10px auto;">
						{!! QrCode::encoding('UTF-8')->size(230)->generate($vcard);!!}
				      </div>
				    </div>

				  </div>

					<div class="col-md-8">

					@if(isset($rec) && $rec->state === 0 && $rec->admin === 0)
						<div class="alert alert-info" id= "right">
					@elseif(isset($rec) && $rec->state === 0 && $rec->admin != 0)
						<div class="alert alert-success" id = "right">
					@else
						<div class="alert alert-warning" id= "right">
					@endif
							<strong>基本信息:</strong>
							<p>--------------------</p>
							<p>编号: {{ $rec->work_id or '编号' }}</p>
							@if(isset($rec) && $rec->state === 0)
								<p>账号状态: 正常</p>
							@else
								<p>账号状态: 锁定</p>
							@endif

							@if(isset($rec) && $rec->mobile != '' && $rec->mobile != null)
								<p>电话: {{ $rec->mobile }}</p>
							@endif

							@if(isset($rec) && $rec->email != '' && $rec->email != null)
								<p>邮件: {{ $rec->email }}</p>
							@endif

							@if(isset($rec) && $rec->qq != '' && $rec->qq != null)
								<p>QQ: {{ $rec->qq }}</p>
							@endif

							@if(isset($rec) && $rec->weixinid != '' && $rec->weixinid != null)
								<p>微信: {{ $rec->weixinid }}</p>
							@endif

							@if(isset($rec) && $rec->created_by != '' && $rec->created_by != null)

								@if($rec->created_by === 1)
									<p>来源: 由系统创建</p>
								@else
									<p>来源: 由{{ $rec->created_byName }}在{{ $rec->created_at }}创建</p>
								@endif
								
							@endif
							

							@if(isset($rec) && $rec->content != '' && $rec->content != null)
								<p>备注: {{ $rec->content }}</p>
							@endif

						</div>
					</div>

					@if($a->hasRights($rec->id))
					<div class="col-md-12">
							<p><a href="/member/edit/{{ $rec->id }}" class="btn btn btn-success">修改</a>&nbsp
							@if(!$a->isSelf($rec->id))
								@if($rec->state === 0)
									<a href="/member/lock/{{ $rec->id }}" class="btn btn btn-warning">锁定</a>&nbsp
								@else
									<a href="/member/unlock/{{ $rec->id }}" class="btn btn btn-warning">解除锁定</a>&nbsp
								@endif

								@if($a->isRoot())
									@if($rec->admin === 0)
									<a href="/member/admin_lost/{{ $rec->id }}" class="btn btn btn-info">- 管理</a>&nbsp
									@else
									<a href="/member/admin_get/{{ $rec->id }}" class="btn btn btn-info">+ 管理</a>&nbsp
									@endif

								@endif
							@endif
							
					@endif

					@if($a->isSelf($rec->id) && !$a->hasRights($rec->id))
					<div class="col-md-12">
					<a href="/member/edit/{{ $rec->id }}" class="btn btn btn-success">修改</a>&nbsp
					</div>
					@endif

					@if($a->auth(['postion'=>'>=经理']) && $a->hasRights($rec->id) && !$a->isSelf($rec->id))

					<a href="/member/delete/{{ $rec->id }}" class="btn btn btn-danger">删除</a>&nbsp

					@endif
					</p>
					</div>
					



				</div>
			</div>
		</div>
		<hr>
	</div>

<script> 
$(document).ready(function() { 
      var r_height=$("#right").height(); 
      var l_height=$("#left").height();

      if(l_height > r_height){
      	  $("#right").height(l_height-50); 
      }else{
      	  $("#left").height(r_height);
      }
      
})
</script>

@endsection