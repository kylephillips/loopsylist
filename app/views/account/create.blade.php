@extends('partials.master')
@section('content')

<section class="signup-hero">
	<div class="container">
		<h1><em>Start</em> Your Loopsie List</h1>
		<p>The best free way to keep track of your Lalaloopsy collection.</p>
	</div>
</section>

@if ( Auth::check() )
<?php $user = Auth::user()->username; ?>
<div class="container small">
	<div class="alert alert-info center">
		<strong>Awesome!</strong> You have an account setup! <a href="{{URL::route('list.show', array('user'=>$user))}}">View your details and list here.</a>
	</div>
</div>
@else

<div class="container">
	<div class="small-form">
		
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
		<div class="fields">
			<div class="form-group first has-feedback">
				{{Form::label('email', 'Your Email')}}
				{{Form::email('email', '', array('class'=>'form-control validate', 'placeholder'=>'We Don\'t spam.'))}}
			</div>
			<div class="form-group has-feedback">
				{{Form::label('username', 'User Name')}}
				{{Form::text('username', '', array('class'=>'form-control validate', 'placeholder'=>'You\'ll use this to login'))}}
			</div>
			<div class="form-group has-feedback has-password">
				{{Form::label('password', 'Password')}}
				{{Form::password('password', array('class'=>'form-control validate', 'placeholder'=>'At least 6 characters'))}}
				
				<!-- Toggle Password Visibility -->
				<input type="text" id="password_shown" class="form-control validate" placeholder="At least 6 characters" autocomplete="off" style="display:none;">

				<a href="#" id="toggle-password">Show</a>
			</div>
			<div class="form-group last">
				<label class="checkbox">{{Form::checkbox('age')}} I am at least 16 years of age or have the consent of a parent/gaurdian.</label>
			</div>
		</div><!-- .fields -->

		<div class="hpr" tabindex="32000">
			{{Form::text('hp')}}
		</div>
		
		<div class="submit">
			{{Form::submit('Sign Up', array('class'=>'btn btn-primary'))}}
		</div>
		{{Form::close()}}
	</div><!-- .small-form -->
</div><!-- .container -->
@endif
@stop


@section('footer_content')
<script>
$(document).ready(function(){
	$('#email').focus();
});
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

