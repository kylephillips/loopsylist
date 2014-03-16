@extends('partials.master')
@section('content')
<section class="page-header">
	<div class="container">
		<h1>Login to <em>Loosy List</em></h1>
	</div>
</section>

<div class="container login-page">
	<div id="login-form-cont">
		@if(Session::has('message'))
		<div class="alert alert-danger">{{Session::get('message')}}</div>
		@endif

		@if(Auth::check())
		<div class="alert alert-info">
			You are logged in as {{Auth::user()->username}}<br />
			<a href="{{URL::route('logout')}}">Logout</a>
		</div>
		
		@else
		{{Form::open(array('url'=>URL::route('login'), 'id'=>'login-form'))}}
		<div class="alert alert-danger" id="login-error" style="display:none;"></div>
		<div class="form-group">
			{{Form::label('username', 'User Name')}}
			{{Form::text('username', '', array('class'=>'form-control', 'placeholder'=>'User Name'))}}
		</div>
		<div class="form-group">
			{{Form::label('password', 'Password')}}
			{{Form::password('password', array('class'=>'form-control', 'placeholder'=>'Password'))}}
		</div>
		{{Form::submit('Log In', array('class'=>'btn btn-primary', 'id'=>'login-submit'))}}
		<ul class="login-options">
			<li><a href="{{URL::route('user.create')}}">Sign Up</a></li>
			<li><a href="#">Recover Password</a></li>
		</ul>
		{{Form::close()}}
		@endif

	</div><!-- #login-form -->

</div><!-- .container -->

@stop

