@extends('head')

@section('content')
<div class="container"> 
{!! Form::open(['url'=>'/upload_test', 'role' => 'form', 'enctype'=>'multipart/form-data']) !!}

<input type="file" id="pic" name="pic"/> 

{!! Form::submit("提交", ['class'=>'btn btn-info btn-block']) !!}

{!! Form::close() !!}
</div>
@endsection