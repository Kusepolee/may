@extends('head')

@section('content')

<script src="{{ URL::asset('bower_components/Croppie/croppie.min.js') }}"></script>
<link href="{{ URL::asset('bower_components/Croppie/croppie.css') }}" rel="stylesheet">



<div class="container">
	<div class="demo">
		<div class="actions">
            <button class="file-btn">
                <span>上传</span>
                <input type="file" id="upload" value="选择图片文件" />
            </button>
            <div class="crop">
				<div id="upload-demo"></div>
				<button class="upload-result">裁剪</button>
			</div>
			<div id="result"></div>
        </div>
	</div>
</div>

<div class="container"> 
{!! Form::open(
    array(
        'url' => '/member/image/store', 
        'class' => 'form', 
        'novalidate' => 'novalidate', 
        'files' => true)) !!}
			<div class="form-group">
			    {!! Form::label('Product Image') !!}
			    {!! Form::file('image', null) !!}
			</div>
				<div class="form-group">
	{!! Form::hidden('base64',null, ['id'=>'base64']) !!}
    {!! Form::submit('store image!') !!}
{!! Form::close() !!}
</div>

<div id= 'fuck'></div>


<script>
$(function(){
	var $uploadCrop;

		function readFile(input) {
 			if (input.files && input.files[0]) {
	            var reader = new FileReader();
	            
	            reader.onload = function (e) {
	            	$uploadCrop.croppie('bind', {
	            		url: e.target.result
	            	});
	            	$('.upload-demo').addClass('ready');
	                // $('#blah').attr('src', e.target.result);
	            }
	            
	            reader.readAsDataURL(input.files[0]);
	        }
	        else {
		        alert("Sorry - you're browser doesn't support the FileReader API");
		    }
		}

		$uploadCrop = $('#upload-demo').croppie({
			viewport: {
				width: 200,
				height: 200,
			},
			boundary: {
				width: 300,
				height: 300
			}
		});

		$('#upload').on('change', function () { 
			$(".crop").show();
			readFile(this); 
		});
		$('.upload-result').on('click', function (ev) {
			$uploadCrop.croppie('result', {type: 'canvas', size: 'viewport', format: 'png', quality:0.8}).then(function (resp) {
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
</script>



@endsection






















