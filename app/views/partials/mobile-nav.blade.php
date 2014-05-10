<a href="{{URL::route('home')}}" class="logo"><em>Loopsy</em> List</a>
<ul>
	<li><a href="{{URL::route('loopsy.index')}}">Loopsies</a></li>
	<li><a href="{{URL::route('find_list')}}">Find a List</a></li>
	@if(Auth::check())
	<?php $user = Auth::user()->username; ?>
	<li><a href="{{URL::route('user.edit', array('user'=>$user))}}">My Profile</a></li>
	<li><a href="{{URL::route('list.show', array('user'=>$user))}}">My List</a></li>
	<li><a href="{{URL::route('logout')}}" class="login-btn">Logout</a></li>
	@else
	<li><a href="{{URL::route('user.create')}}">Sign up</a></li>
	<li><a href="{{URL::route('login_form')}}" class="login-btn">Login</a></li>
	@endif
</ul>