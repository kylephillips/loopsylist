@extends('partials.master')
@section('content')

<div class="container">
	<div id="login-form-cont">
		<h1>Login to edit your list</h1>

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
		{{Form::open(array('url'=>URL::route('login'), 'id'=>'login-form'))}}
		<div class="alert alert-danger" id="login-error" style="display:none;"></div>
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

