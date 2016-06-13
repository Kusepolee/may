<?php
$a = new FooWeChat\Authorize\Auth;
$h = new FooWeChat\Helpers\Helper;
?>
@extends('head')

@section('content')

	<div class="container">
		<div id="page-wrapper">
			<div id="page-inner">
				<div class="row">
					<div class="col-md-12">
						<h1 class="page-head-line"><a href="/resource"> {{ $rec->name }}</a> <span style="font-size:15px; color:#8C8C8C; font-weight:200;">库存: {{ floatval($rec->remain) }} {{ $rec->unitName }}</span></h1>
						<ul class="list_none">
						<li class="dropdown pull-right">
							@if($rec->img !='' && $rec->img != null)
			        		<a href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
			        		<img id="tu2" src="{{ URL::asset("upload/resource/").'/'.$rec->img }}" class="img-thumbnail"/>
			        		@else 
			        		<a href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
			        		<img id="tu2" src="{{ URL::asset("custom/image/").'/'.'qrcode_png.png' }}" class="img-thumbnail"/>
			        		@endif 
						<ul class="dropdown-menu" id = "show">	
							<li class="m_2"><a href="/resource/image/set/{{ $rec->id }}"><i class="glyphicon glyphicon-picture menu_icon_info"></i> 图片更新</a></li>
							@if($a->auth(['department'=>'=资源部']))
							<li class="m_2"><a href="/resource/in/{{ $rec->id }}"><i class="glyphicon glyphicon-log-in menu_icon_info"></i>入库</a></li>
							@endif
							<li class="m_2"><a href="/resource/out/{{ $rec->id }}"><i class="glyphicon glyphicon-log-out menu_icon_info"></i>领用</a></li>
							<!-- <li class="m_2"><a href="/resource/list/{{ $rec->id }}"><i class="glyphicon glyphicon-list-alt menu_icon_info"></i>记录</a></li> -->
							@if($a->auth(['department'=>'=资源部'])&&$a->auth(['position'=>'>=经理']))
							<li class="m_2"><a href="/resource/edit/{{ $rec->id }}"><i class="glyphicon glyphicon-edit menu_icon_info"></i>修改</a></li>
							<li class="m_2"><a href="/resource/delete/{{ $rec->id }}"><i class="glyphicon glyphicon-trash menu_icon_info"></i>删除</a></li>
							@endif
					    </ul>
					  	</ul>

						<h1 class="page-subhead-line">型号: {{ $rec->model }} / 类型: {{ $rec->typeName }} / 创建人: {{ $rec->createByName}} / @if(isset($rec) && $rec->notice !=0 && $rec->alert != 0)
								@if(isset($rec) && $rec->notice != '' && $rec->notice != null)
								提醒值: {{ floatval($rec->notice) }}
								@endif
								@if(isset($rec) && $rec->alert != '' && $rec->alert != null)
								报警值: {{ floatval($rec->alert) }}
								@endif
							@else
								无提示和报警值,<a href="/resource/edit/{{ $rec->id }}">立即设置</a>
							@endif @if(isset($rec) && $rec->content != '' && $rec->content != null)
								/ 备注: {{ $rec->content }}
							@endif</h1>
					</div>
				</div>

							<div class="col-md-16">
            
					            <div class="panel panel-info">
					            @if($resource_records === 0)
					            	<div class="panel-heading">尚无出入记录</div>
					            @else
					                <div class="panel-heading">出入库记录</div>
					                <table class="table table-hover">
					                    <thead>
					                        <tr>
					                            <th>数量</th>
					                            <th>出/入</th>
					                            <th>类型</th>
					                            <th>日期</th>
					                            @if(!$a->usingWechat())                        
					                            <th>接收人</th>
					                            <th>说明</th>
					                            @endif
					                        </tr>
					                    </thead>
					                    <tbody>
					                    @foreach($resource_records as $out)					                    	
					                        <tr>
					                            <td>{{ floatval($out->amount) }}&nbsp{{ $rec->unitName }}</td>
					                            <td>                     
					                            @if($out->out_or_in === 0)
					                            -
					                            @else
					                            +
					                            @endif</td>
					                            <td>{{ $out->typeName }}</td>
					                            <td>{{ $out->created_at }}</td>
					                            @if(!$a->usingWechat())
					                            <td>{{ $out->memberName }}</td>
					                            <td>{{ $out->content }}</td>
					                            @endif
					                        </tr>
					                    @endforeach
					                    </tbody>
					                </table>
								@endif
									<div class="container"> 
									@if($resource_records != 0)
		                            {!! $resource_records->render() !!}
		                            @endif
		                            </div>
					            </div>
					    	</div>
				
			</div>
		</div>
		
	</div>

@endsection