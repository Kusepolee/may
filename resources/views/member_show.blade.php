<?php
$a = new FooWeChat\Authorize\Auth;
$h = new FooWeChat\Helpers\Helper;
$w = new FooWeChat\Core\WeChatAPI;

$origin = $rec->name;
$xing = mb_substr($origin,0,1,'utf-8');
$ming = mb_substr($origin,1,mb_strlen($origin),'utf-8');

$tel = str_replace(' ', '', $h->custom('tel'));

$vcard = 'BEGIN:VCARD
VERSION:2.1
N:'.$xing.';'.$ming.';
FN:'.$rec->name.'
ORG:'.$h->custom('name').'
TITLE:'.$rec->departmentName.'-'.$rec->positionName.'
TEL;CELL;VOICE:'.$rec->mobile.'
TEL;WORK;VOICE:'.$tel.'
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
		
		@if(!$a->isSelf($rec->id) && !$a->hasRights($rec->id))
		<ul class="list_none">
		<li class="dropdown pull-right">
			@if($rec->img != '' && $rec->img != null)
			<a href="#" class="dropdown-toggle avatar" data-toggle="dropdown"><img id="tu2" src="{{ URL::asset("upload/member/").'/'.$rec->img}}" class="img-thumbnail"/>
			@else
				<a href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
				@if($rec->gender === 1)
					<img id="tu2" src="{{ URL::asset("custom/image/").'/'.'member_base_man.png'}}" class="img-thumbnail"/>
				@else 
					<img id="tu2" src="{{ URL::asset("custom/image/").'/'.'member_base_lady.png'}}" class="img-thumbnail"/>
				@endif 
			@endif
		<!-- finance tran  -->
		<ul class="dropdown-menu" id = "show">
			@if($a->auth(['admin'=>'no', 'position'=>'>=经理']))
			<li class="m_2"><a href="/finance/trans/{{$rec->id}}"><i class="glyphicon glyphicon-yen menu_icon_warning"></i> 资金给予</a></li>
			@endif
		</ul>	
		<!-- finance tran end -->
		</li></ul>
		@else
		<ul class="list_none">
		<li class="dropdown pull-right">
					@if($rec->img !='' && $rec->img != null)
	        		<a href="#" class="dropdown-toggle avatar" data-toggle="dropdown"><img id="tu2" src="{{ URL::asset("upload/member/").'/'.$rec->img}}" class="img-thumbnail"/><span class="badge">{{$rec->work_id}}</span></a>
	        		@else 
	        			<a href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
							@if($rec->gender === 1)
	        			<img id="tu2" src="{{ URL::asset("custom/image/").'/'.'member_base_man.png'}}" class="img-thumbnail"/>
							@else
						<img id="tu2" src="{{ URL::asset("custom/image/").'/'.'member_base_lady.png'}}" class="img-thumbnail"/> 
							@endif 
	        			<span class="badge">{{$rec->work_id}}</span></a>

	        		@endif 

		<ul class="dropdown-menu" id = "show">
						@if($a->isSelf($rec->id) || $a->hasRights($rec->id))
						<li class="m_2"><a href="/member/edit/{{ $rec->id }}"><i class="glyphicon glyphicon-edit menu_icon_info"></i> 修改资料 </a></li>
						@endif

						@if($a->isSelf($rec->id))
						<li class="m_2"><a href="/member/image/set"><i class="glyphicon glyphicon-user menu_icon_info"></i> 头像更新</a></li>
						<li class="m_2"><a href="/member/password/form"><i class="glyphicon glyphicon-barcode menu_icon_success"></i> 密码修改</a></li>
						@endif


						@if($a->hasRights($rec->id) && !$a->isSelf($rec->id))
						<li class="divider"></li>
						
						
							@if($rec->state === 0)
						<li class="m_2"><a href="/member/lock/{{ $rec->id }}"><i class="glyphicon glyphicon-lock menu_icon_warning"></i> 锁定该用户</a></li>
							@else
						<li class="m_2"><a href="/member/unlock/{{ $rec->id }}"><i class="glyphicon glyphicon-ok menu_icon_success"></i> 解锁该用户</a></li>
							@endif
							<!-- finance tran -->
							<li class="divider"></li>
							<li class="m_2"><a href="/finance/trans/{{$rec->id}}"><i class="glyphicon glyphicon-yen menu_icon_warning"></i> 资金给予</a></li>
							<!-- finance tran end -->
							<li class="divider"></li>
							<li class="m_2"><a href="/member/delete/{{$rec->id}}"><i class="glyphicon glyphicon-remove menu_icon_danger"></i> 删除用户</a></li>

						@endif

						@if(!$a->isSelf($rec->id) && $a->isRoot())
							@if($rec->admin === 0)
						<li class="m_2"><a href="/member/admin_lost/{{ $rec->id }}"><i class="glyphicon glyphicon-king menu_icon_success"></i> 取消管理权限</a></li>
							@else
						<li class="divider"></li>
						<li class="m_2"><a href="/member/admin_get/{{ $rec->id }}"><i class="glyphicon glyphicon-king menu_icon_danger"></i> 授予管理权限</a></li>
							@endif
						@endif 

	    </ul>
	  
	    </ul>
	    @endif


						<h1 class="page-subhead-line">{{ $rec->departmentName or '部门' }} - {{ $rec->positionName or '职位' }}
						</h1>
					</div>
				</div>

				<div class="row">


					<div class="col-md-8">

					@if(isset($rec) && $rec->state === 0 && $rec->admin === 0)
						<div class="alert alert-info" id= "right">
					@elseif(isset($rec) && $rec->state === 0 && $rec->admin != 0)
						<div class="alert alert-success" id = "right">
					@else
						<div class="alert alert-warning" id= "right">
					@endif
							<strong>基本信息:@if(isset($rec) && $rec->admin === 0)
								(系统管理员)
							@endif</strong>
							<p>--------------------</p>
							<p>编号: {{ $rec->work_id or '编号' }}</p>
							@if(isset($rec) && $rec->state === 0)
								<p>账号状态: 正常</p>
							@else
								<p>账号状态: 锁定</p>
							@endif

							@if(isset($rec) && $w->hasFollow($rec->id))
								<p>微信已关注: 是</p>
							@else
								<p>微信已关注: 否</p>
							@endif


							@if(isset($rec) && $rec->mobile != '' && $rec->mobile != null)
								@if($a->auth(['position'=>'>=经理']) || $a->samePosition($rec->id) || $a->sameDepartment($rec->id))
								<p>电话: {{ $rec->mobile }}</p>
								@else 
								<p>电话: 已保护</p>
								@endif
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
				</div>
			</div>
		</div>
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