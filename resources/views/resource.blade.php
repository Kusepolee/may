@extends('head')

@section('form')

<!-- /widget --> 
          <div class="widget widget-nopad">
            <div class="widget-header"> <i class="icon-list-alt"></i>
              <h3> 资源列表 &nbsp&nbsp <a href="/resource/create">资源登记</a></h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <ul class="news-items">

              @if($outs === 0 )
              <li>
              <div class="news-item-detail"> <a class="news-item-title" target="_blank">尚无记录</a>
                    <p class="news-item-preview"></p></div>
              </li>
              @else
              @foreach ($outs as $out)
                <li>
                  
                  <div class="news-item-date"> <span class="news-item-day">#</span> <span class="news-item-month">{{ $out->id }}: {{ $out->name }}</span> </div>
                  <div class="news-item-detail"> <a class="news-item-title" target="_blank">库存:{{ floatval($out->remain).$out->unitName}}</a>
                    <p class="news-item-preview"></p>
                    {{ $out->typeName }}
                    @if($out->notice != '' && $out->notice != null)
                    | 提醒值: {{ floatval($out->notice) }}
                    @endif
                    @if($out->alert != '' && $out->alert != null)
                    | 报警值: {{ floatval($out->alert) }}
                    @endif
                    @if($out->content != '' && $out->content != null)
                    | {{ $out->content }}
                    @endif

                    <p><a href="/resource/out/{{ $out->id }}" class="btn">出货</a>&nbsp&nbsp<a href="/resource/in/{{ $out->id }}" class="btn">进货</a>&nbsp&nbsp<a href="/resource/list/{{ $out->id }}" class="btn">统计</a>&nbsp&nbsp<a href="#" class="btn">修改</a></p>
                  </div>
                  
                </li>
              @endforeach
              </ul>{!! $outs->render() !!}
              @endif


              
            </div>

            <!-- /widget-content --> 
          </div>
          <!-- /widget -->
@endsection
