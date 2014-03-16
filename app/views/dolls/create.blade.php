@extends('partials.master')

@section('head_content')
{{HTML::style('/assets/js/redactor/redactor.css')}}
{{HTML::script('/assets/js/redactor/redactor.min.js')}}
@stop

@section('content')
<section class="page-header">
	<div class="container">
		<h1>Add a <em>Loopsy</em></h1>
	</div>
</section>

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

	{{Form::open(array('url'=>URL::route('loopsie.store'),'method'=>'POST','class'=>'dropzone', 'files'=>true))}}
	<div class="form-group">
		{{Form::label('type', 'Toy Type')}}
		{{Form::select('type', $types, '', array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('title', 'Name/Title*')}}
		{{Form::text('title', '', array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('release_month', 'Month of Release')}}
		{{Form::selectMonth('release_month', '', array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('release_year', 'Year of Release')}}
		{{Form::selectRange('release_year', '2010', date('Y'), '', array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('sewn_on_month', 'Month Sewn')}}
		{{Form::selectMonth('sewn_on_month', '', array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('sewn_on_day', 'Day Sewn')}}
		{{Form::selectRange('sewn_on_day', '1', '31', '', array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('sewn_from', 'Sewn From')}}
		{{Form::text('sewn_from', '', array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('pet', 'Pet')}}
		{{Form::text('pet', '', array('class'=>'form-control', 'placeholder'=>'If applicable'))}}
	</div>
	<div class="form-group">
		{{Form::label('link', 'Purchase Link')}}
		{{Form::text('link', '', array('class'=>'form-control', 'placeholder'=>'Amazon, Ebay, etc...'))}}
	</div>
	<div class="form-group">
		{{Form::label('image', 'Image')}}
		{{Form::file('image')}}
	</div>
	<div class="form-group textarea">
		{{Form::label('bio', 'Biography')}}
		{{Form::textarea('bio', '', array('id'=>'bio'))}}
	</div>
	<div class="submit">
		{{Form::submit('Add Loopsy', array('class'=>'btn btn-primary'))}}
	</div>
	{{Form::close()}}

</div>
@stop

@section('footer_content')
<script>
$(document).ready(function(){
	$('#bio').redactor();
});
</script>
@stop