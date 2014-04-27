@extends('partials.master')
@section('title', 'Reset Password - Loopsy List')
@section('content')
<section class="page-header">
	<div class="container">
		<h1>Password Reset</h1>
	</div>
</section>

<div class="container login-page">
	<div id="login-form-cont">
	
	@if(Session::get('error'))
	<div class="alert alert-danger">
		{{Session::get('error')}}
	</div>
	@endif

	{{Form::open()}}
	{{Form::hidden('token', $token)}}
	<div class="form-group">
		{{Form::label('email', 'Your Email')}}
		{{Form::text('email')}}
	</div>
	<div class="form-group">
		{{Form::label('password', 'New Password')}}
		{{Form::password('password')}}
	</div>
	<div class="form-group">
		{{Form::label('password_confirmation', 'Confirm New Password')}}
		{{Form::password('password_confirmation')}}
	</div>
	<div class="submit">
		{{Form::submit('Submit', array('class'=>'btn btn-primary'))}}
	</div>
	{{Form::close()}}
	</div>
</div><!-- .container -->
@stop