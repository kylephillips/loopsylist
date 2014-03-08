@extends('partials.master')
@section('content')

<div class="container">
	<h1>Login</h1>

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
	</div>
	{{Form::submit('Log In', array('class'=>'btn btn-primary'))}}
	{{Form::close()}}
	@endif

</div><!-- .container -->

@stop

