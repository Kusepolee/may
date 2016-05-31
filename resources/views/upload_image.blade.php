<?php
if(!isset($path)) die('upload_image_blade.php : 缺少参数');
?>
@extends('head')

@section('content')

<script src="{{ URL::asset('bower_components/Croppie/croppie.min.js') }}"></script>
<script src="{{ URL::asset('bower_components/not_bower/jFormslider.js') }}"></script>
<link href="{{ URL::asset('bower_components/Croppie/croppie.css') }}" rel="stylesheet">



<div class="container">
   <div class="panel panel-default">
    <div class="panel-heading">
       <a href="/member">用户管理</a>&nbsp/&nbsp图片上传
    </div>
    <div id="upload-preview"></div>

    <div id="result" class="container"></div>
	<p></p><p></p>
    <div class="form-group">
        <div>
			<span onclick="javascript:form_show_cut();" class="btn btn-file btn-info"><span><i class="glyphicon glyphicon-folder-open"></i>&nbsp选择图片</span><input id="upload" type="file"></span> 

            <span id="cut_div">&nbsp&nbsp<btn onclick="javascript:show_upload();" id = "cut" href="" class="btn btn-file btn-info"><i class="glyphicon glyphicon-scissors"></i>&nbsp裁剪</btn></span>   
			<span id="upload_div">&nbsp&nbsp<a href="javascript:form_submit();" class="btn btn-file btn-success"><i class="	glyphicon glyphicon-cloud-upload"></i>&nbsp上传</a></span>      
        </div>
    </div>
	</div>
</div>
{!! Form::open(
    array(
    	'id' =>'upload_form',
        'url' => $path, 
        'class' => 'form', 
        'novalidate' => 'novalidate', 
        'files' => true)) !!}
	{!! Form::hidden('base64',null, ['id'=>'base64']) !!}
{!! Form::close() !!}
<script>
$(function(){
	// init
	$("#cut_div").hide();
	$("#upload_div").hide();

	//croppie
	var $uploadCrop;

		function readFile(input) {
 			if (input.files && input.files[0]) {
	            var reader = new FileReader();
	            
	            reader.onload = function (e) {
	            	$uploadCrop.croppie('bind', {
	            		url: e.target.result
	            	});
	            	$('#upload-preview').addClass('ready');
	                // $('#blah').attr('src', e.target.result);
	            }
	            
	            reader.readAsDataURL(input.files[0]);
	        }
	        else {
		        //alert("Sorry - you're browser doesn't support the FileReader API");
		    }
		}

		$uploadCrop = $('#upload-preview').croppie({
			viewport: {
				width: 150,
				height: 150,
			},
			boundary: {
				width: 250,
				height: 250
			}
		});

		$('#upload').on('change', function () { 
			$("#cut_div").show();
			readFile(this); 
		});
		$('#cut').on('click', function (ev) {
			$uploadCrop.croppie('result', {type: 'canvas', size: 'viewport', format: 'png', quality:1}).then(function (resp) {
				$("#base64").val(resp);
				popupResult({
					src: resp
				});
			});
		});
		
	function popupResult(result) {
		var html;
		if (result.html) {
			html = result.html;
		}
		if (result.src) {
			html = '<img src="' + result.src + '" />';
		}
		$("#result").html(html);
	}
});

//提交表单
function form_submit()
{
	$("#upload_form").submit();
}

//显示裁剪和上传
function show_cut()
{
	$("#cut_div").show();
	//$("#upload_div").hide();
}

//显示上传
function show_upload()
{
	$("#upload_div").show();
}
</script>



@endsection






















