@if(isset($front_page))
<section class="top-nav front-page">
@else
<section class="top-nav">
@endif
<div class="container">
	<div class="logo"><a href="{{URL::route('home')}}">Loopsy <em>List</em></a></div>
	<ul class="pull-right">
		<li><a href="{{URL::route('loopsy.index')}}">Loopsies</a></li>
		<li><a href="{{URL::route('find_list')}}">Find a List</a></li>
		@if(Auth::check())
		<?php $user = Auth::user()->username; ?>
		<li>
			<a href="#" class="login-btn">{{Auth::user()->slug}}</a>
			<ul>
				<li><a href="{{URL::route('user.show', array('user'=>$user))}}">My Account</a></li>
				<li><a href="#">My List</a></li>
				<li><a href="{{URL::route('logout')}}">Logout</a></li>
			</ul>
		</li>
		@else
		<li><a href="{{URL::route('user.create')}}">Sign up</a></li>
		<li><a href="{{URL::route('login_form')}}" class="login-btn login-trigger">Login</a></li>
		@endif
	</ul>
	<a href="#" class="login-btn nav-toggle">Menu</a>
</div>
</section>