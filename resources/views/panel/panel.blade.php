<?php
	
?>
@extends('head')

@section('content')
<div class="container">
	<div class="col-md-12">
		<ol class="breadcrumb">
            <li class="active" >面板</li>
            <li><a href="/panel/complaints/record">投诉记录</a></li>
        </ol>
	    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="0ms">
	        <div class="media service-box"><a href="panel/complaints">
	            <div class="hexagon">
		            <div class="inner">              
		                <span class="glyphicon glyphicon-edit"></span>
		            </div>
	            </div> 
	            <div class="service-box">
	                <h2>投诉</h2></a>
	            </div>
	        </div>
	    </div>

	    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="100ms">
	        <div class="media service-box"><a href="">
	            <div class="hexagon">
		            <div class="inner">              
		               	<span class="glyphicon glyphicon-list-alt"></span>
		            </div>
	            </div>  
	            <div class="service-box">
	                <h2>出门证</h2></a>
	            </div>
	        </div>
	    </div>
    </div>
</div>

@endsection