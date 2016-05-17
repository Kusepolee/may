@extends('head')

@section('form')

<!-- /widget --> 
          <div class="widget widget-nopad">
            <div class="widget-header"> <i class="icon-list-alt"></i>
              <h3> 产品列表 &nbsp&nbsp <a href="/product/create">录入产品</a>&nbsp&nbsp &nbsp&nbsp &nbsp&nbsp <a href="#">订单 </a><span class="badge">3</span></h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <ul class="news-items">
              @foreach ($outs as $out)
                <li>
                  
                  <div class="news-item-date"> <span class="news-item-day">#</span> <span class="news-item-month">{{ $out->id }}</span> </div>
                  <div class="news-item-detail"> <a class="news-item-title" target="_blank">{{ $out->name }}</a>
                    <p class="news-item-preview"></p>
                    {{ $out->typeName }}
                    @if($out->for != '' && $out->for != null)
                    | 适配: {{ $out->for }}
                    @endif
                    @if($out->content != '' && $out->content != null)
                    | {{ $out->content }}
                    @endif

                    <p><a href="#" class="btn">+订单</a>&nbsp&nbsp<a href="/product/quota/{{ $out->id }}" class="btn">定额</a></p>
                  </div>
                  
                </li>
              @endforeach
              </ul>
            </div>
            <!-- /widget-content --> 
          </div>
          <!-- /widget -->
@endsection
