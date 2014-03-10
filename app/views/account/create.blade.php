@extends('partials.master')
@section('content')
<div class="container">
	<h1>Create Your Loopsy List!</h1>
	<p>Already have a list? <a href="{{URL::route('login_form')}}">Login</a></p>

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

	{{Form::open(array('url'=>URL::route('user.store'),'method'=>'POST'))}}
	<div class="form-group has-feedback">
		{{Form::label('email', 'Your Email')}}
		{{Form::email('email', '', array('class'=>'form-control validate', 'placeholder'=>'We Don\'t spam.'))}}
	</div>
	<div class="form-group">
		{{Form::label('zip', 'Your Zip Code (optional)')}}
		{{Form::text('zip', '', array('class'=>'form-control', 'placeholder'=>'So friends and family can find you.'))}}
	</div>
	<div class="form-group has-feedback">
		{{Form::label('username', 'User Name')}}
		{{Form::text('username', '', array('class'=>'form-control validate', 'placeholder'=>'You\'ll use this to login'))}}
	</div>
	<div class="form-group has-feedback">
		{{Form::label('password', 'Password')}}
		{{Form::password('password', array('class'=>'form-control validate', 'placeholder'=>'At least 6 characters'))}}
		
		<!-- Toggle Password Visibility -->
		<input type="text" id="password_shown" class="form-control validate" placeholder="At least 6 characters" style="display:none;">
		<div id="toggle-password"><label class="checkbox">
			<input type="checkbox" > <span>Show Password</span></label>
		</div>

	</div>
	<div class="form-group">
		<label class="checkbox">{{Form::checkbox('age')}} I am at least 16 years of age or have the consent of a parent/gaurdian.</label>
	</div>
	<div class="hpr" tabindex="32000">
		{{Form::text('hp')}}
	</div>
	{{Form::submit('Sign Up', array('class'=>'btn btn-primary'))}}
	{{Form::close()}}
</div>
@stop


@section('footer_content')
<script>
$('.validate').on('keyup', function(){
	var value = $(this).val();
	var field = $(this).attr('id');
	var url = "{{URL::route('validate_signup')}}";
	validateField(field, value, url);
});
$('.validate').on('blur', function(){
	var value = $(this).val();
	var field = $(this).attr('id');
	var url = "{{URL::route('validate_signup')}}";
	validateField(field, value, url);
});
</script>
@stop

