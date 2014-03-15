@extends('partials.master')
@section('title', 'Loopsy List - Create Your Account')
@section('head_content')
{{HTML::style('/assets/js/redactor/redactor.css')}}
{{HTML::script('/assets/js/redactor/redactor.min.js')}}
@stop
@section('content')

<section class="page-header">
	<div class="container">
		<h1>Sign Up: <em>Additional Details</em></h1>
	</div>
</section>

<div class="container">
	<div class="small-form">

		<h3 class="center">Welcome to Loopsy List!</h3>
		<p class="center">Include the details below to help friends find your list. If you'd like to complete this later, <a href="#">skip right to your list</a>.</p>
		
		@if(Session::has('errors'))
		<div class="alert alert-danger">
			Oopsie! Please correct the following:
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
		<div class="alert alert-success">
			{{Session::get('success')}}
		</div>
		@endif

		{{Form::open(array('url'=>URL::route('create_step_two_post'),'method'=>'POST', 'id'=>'addressUpdate'))}}
		<div class="fields">
			<div class="form-group first has-feedback">
				{{Form::label('name', 'Your Name')}}
				{{Form::text('name', '', array('class'=>'form-control validate', 'placeholder'=>'Your first and last name.'))}}
			</div>
			<div class="form-group has-feedback">
				{{Form::label('zip', 'Zip Code')}}
				{{Form::text('zip', '', array('class'=>'form-control validate', 'placeholder'=>'So friends can find you by location.'))}}
			</div>
			<div class="form-group textarea">
				{{Form::label('bio', 'About You (optional)')}}
				{{Form::textarea('bio', '', array('id'=>'bio'))}}
			</div>
			<div class="form-group last">
				<label class="checkbox">{{Form::checkbox('visibility', '1', true)}} Make my list public (so friends can find me).</label>
			</div>
		</div><!-- .fields -->

		<div class="hpr" tabindex="32000">
			{{Form::text('hp')}}
		</div>
		
		<div class="submit">
			{{Form::hidden('latitude', '', array('id'=>'latitude'))}}
			{{Form::hidden('longitude', '', array('id'=>'longitude'))}}
			{{Form::submit('Save', array('class'=>'btn btn-primary'))}}
			<p><a href="#">or Skip and complete later</a></p>
		</div>
		{{Form::close()}}
	</div><!-- .small-form -->
</div><!-- .container -->
@stop

@section('footer_content')
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>
$(document).ready(function(){
	$('#bio').redactor({
		buttons: ['bold', 'italic', 'unorderedlist', 'orderedlist', 'link']
	});
});
</script>
@stop