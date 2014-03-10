@extends('partials.master')
@section('content')

<div class="container">
	<h1>Welcome back!</h1>

	@if(Session::has('message'))
	<div class="alert alert-danger">{{Session::get('message')}}</div>
	@endif

	@if(Session::has('success'))
	<div class="alert alert-success">{{Session::get('success')}}</div>
	@endif

	@if(Auth::check())
	<p>
		You are logged in as {{Auth::user()->username}}<br />
		<a href="{{URL::route('logout')}}">Logout</a>
	</p>
	
	@else
	{{Form::open()}}
	<div class="form-group">
		{{Form::label('username', 'User Name')}}
		{{Form::text('username', '', array('class'=>'form-control', 'placeholder'=>'User Name'))}}
	</div>
	<div class="form-group">
		{{Form::label('password', 'Password')}}
		{{Form::password('password', array('class'=>'form-control', 'placeholder'=>'Password'))}}

		<!-- Toggle Password Visibility -->
		<input type="text" id="password_shown" class="form-control" placeholder="Password" style="display:none;">
		<div id="toggle-password"><label class="checkbox">
			<input type="checkbox" > <span>Show Password</span></label>
		</div>
	</div>
	{{Form::submit('Log In', array('class'=>'btn btn-primary'))}}
	<a href="{{URL::route('user.create')}}">Don't have a login?</a>
	{{Form::close()}}
	@endif

</div><!-- .container -->

@stop

