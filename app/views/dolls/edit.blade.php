@extends('partials.master')

@section('head_content')
{{HTML::style('/assets/js/redactor/redactor.css')}}
{{HTML::script('/assets/js/redactor/redactor.min.js')}}
@stop

@section('content')
<section class="page-header">
	<div class="container">
		<h1>Edit <em>{{$doll->title}}</em></h1>
	</div>
</section>

{{Form::model($doll, array('route'=>array('loopsy.update', $doll->id),'method'=>'POST','class'=>'dropzone', 'files'=>true))}}
<div class="container">

	@if(Session::has('errors'))
	<div class="alert alert-danger">
		Please correct the following:
		<ul>
			<?php
			foreach ($errors->all('<li>:message</li>') as $error)
			{
			 echo $error;
			}
			?>
		</ul>
	</div>
	@endif

	@if(Session::has('success'))
	<div class="alert alert-info">
		{{Session::get('success')}}
	</div>
	@endif

	<div class="small-form">
	<div class="form-group">
		{{Form::label('type', 'Toy Type')}}
		{{Form::select('type', $types, '', array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('title', 'Name/Title*')}}
		{{Form::text('title', $doll->title, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('release_month', 'Month of Release')}}
		{{Form::selectMonth('release_month', $doll->release_month, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('release_year', 'Year of Release')}}
		{{Form::selectRange('release_year', '2010', date('Y'), $doll->release_year, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('sewn_on_month', 'Month Sewn')}}
		{{Form::selectMonth('sewn_on_month', $doll->sewn_on_month, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('sewn_on_day', 'Day Sewn')}}
		{{Form::selectRange('sewn_on_day', '1', '31', $doll->sewn_on_day, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('sewn_from', 'Sewn From')}}
		{{Form::text('sewn_from', $doll->sewn_from, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('pet', 'Pet')}}
		{{Form::text('pet', $doll->pet, array('class'=>'form-control', 'placeholder'=>'If applicable'))}}
	</div>
	<div class="form-group">
		{{Form::label('link', 'Purchase Link')}}
		{{Form::text('link', $doll->link, array('class'=>'form-control', 'placeholder'=>'Amazon, Ebay, etc...'))}}
	</div>
	<div class="form-group">
		{{Form::label('image', 'Image')}}
		<img src="{{URL::asset('uploads/toys/_thumbs/225x265_')}}{{$doll->image}}" alt="{{$doll->title}}" />
	</div>
	<div class="form-group textarea">
		{{Form::label('bio', 'Biography')}}
		{{Form::textarea('bio', $doll->bio, array('id'=>'bio'))}}
	</div>
	<div class="submit">
		{{Form::submit('Save Edits', array('class'=>'btn btn-primary'))}}
	</div>

</div><!-- .container -->
{{Form::close()}}

@stop

@section('footer_content')
<script>
$(document).ready(function(){
	$('#bio').redactor();
});
</script>
@stop