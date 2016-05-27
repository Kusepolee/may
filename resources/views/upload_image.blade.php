@extends('head')

@section('content')

<script src="{{ URL::asset('bower_components/Croppie/croppie.min.js') }}"></script>
<link href="{{ URL::asset('bower_components/Croppie/croppie.css') }}" rel="stylesheet">


<div class="container">
   <div class="panel panel-default">
    <div class="panel-heading">
       File Uploads
    </div>
    <div class="form-group">
        <div>
			<span class="btn btn-file btn-info"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file"></span>      
        </div>
    </div>

<div class="fileupload-preview thumbnail" style="width: 345px; height: 250px;"></div>
	</div>
</div>



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
			$uploadCrop.croppie('result', 'canvas').then(function (resp) {
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