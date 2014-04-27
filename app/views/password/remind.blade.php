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

	<div class="alert alert-info">
	@if(Session::get('status'))
		{{Session::get('status')}}
	@else
		Enter the email you signed up with. You will receive a link to reset your password.
	@endif
	</div>
	
	@if(Session::get('error'))
	<div class="alert alert-danger">
		{{Session::get('error')}}
	</div>
	@endif

	@if(!Session::get('status'))
	{{Form::open()}}
	<div class="form-group">
		{{Form::label('email', 'Your Email')}}
		{{Form::text('email')}}
	</div>
	<div class="submit">
		{{Form::submit('Submit', array('class'=>'btn btn-primary'))}}
	</div>
	{{Form::close()}}
	@endif

	</div>
</div><!-- .container -->
@stop

@section('footer_content')
<script>
$('form').on('submit', function(){
	$(this).find('input[type=submit').attr('disabled', 'disabled');
});
</script>
@stop