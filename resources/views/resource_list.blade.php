@extends('head')

@section('form')

<!-- /widget --> 
          <div class="widget widget-nopad">
            <div class="widget-header"> <i class="icon-list-alt"></i>
              <h3> <a href="/resource">资源 </a>&nbsp&nbsp/ {{ $resource->name }}统计信息</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <ul class="news-items">

              @if($resource_records === 0 )
              <li>
              <div class="news-item-detail"> <a class="news-item-title" target="_blank">尚无记录</a>
                    <p class="news-item-preview"></p></div>
              </li>
              @else
              
                <li>
                  
                  <div class="news-item-date"> <span class="news-item-day">#</span> <span class="news-item-month">{{ $id }}:&nbsp&nbsp<strong>{{ $resource->name }}</strong>, (由{{ $resource->createByName }}于{{ $resource->created_at }}登记)</span> </div>
                  <div class="news-item-detail"> <a class="news-item-title" target="_blank">属{{ $resource->typeName }}, 当前库存:{{ $remain.$resource->unitName }}</a>
                    <p class="news-item-preview"></p>
           


                    <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                    <th> 日期 </th>
                    <th> 加减</th>
                    <th> 数量</th>
                    <th> 接收人</th>
                    <th> 用途</th>
                    </tr>
                    </thead>
                    <tbody>
                     @foreach ($resource_records as $out)
                    @if($out->out_or_in === 0)
                    <tr>
                    @else
                    <tr class="danger">
                    @endif
                    <td>{{ $out->created_at }} </td>

                    <td>                     
                    @if($out->out_or_in === 0)
                    -
                    @else
                    +
                    @endif</td>
                    <td> 
                    {{ floatval($out->amount) }}
                    </td>
                    <td> {{ $out->memberName }}</td>
                    <td> {{ $out->forName }}</td>
                    </tr>
                    
                    @endforeach 
                    </tbody>
                    </table>


                              
            <p><a href="/resource" class="btn">返回资源列表</a></p>
                  </div>
                  
                </li>
              
              @endif


              </ul>
            </div>
            <!-- /widget-content --> 
          </div>
          <!-- /widget -->
@endsection
