@extends('partials.master')
@section('content')

@if(Session::has('message'))
<div class="alert alert-error">{{Session::get('message')}}</div>
@endif


{{Form::open()}}
<ul>
	<li>
		{{Form::label('username', 'User Name')}}
		{{Form::text('username')}}
	</li>
	<li>
		{{Form::label('password', 'Password')}}
		{{Form::password('password')}}
	</li>
</ul>
{{Form::submit('Log In')}}
{{Form::close()}}

@stop

