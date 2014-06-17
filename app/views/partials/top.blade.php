@if( (Auth::check()) && (Auth::user()->group->id == 2) )
<section class="admin-bar">
	<div class="container">
		<nav>
			<ul>
				<li><a href="#">Admin Menu</a>
					<ul>
						<li><a href="{{URL::route('loopsy.create')}}">Add a Loopsy</a>
					</ul>
				</li>
			</ul>
		</nav>
	</div>
</section>
@endif
@if(isset($front_page))
<section class="top-nav front-page">
@else
<section class="top-nav">
@endif
<div class="container">
	<div class="logo"><a href="{{URL::route('home')}}">Loopsy <em>List</em></a></div>
	<ul class="pull-right">
		<li><a href="{{URL::route('loopsy.index')}}"><i class="icon-happy"></i> Lalaloopsies</a></li>
		<li><a href="{{URL::route('find_list')}}"><i class="icon-search"></i> Find a List</a></li>
		@if(Auth::check())
		<?php $user = Auth::user()->slug; ?>
		<li>
			<a href="{{URL::route('user.edit', array('user'=>$user))}}" class="login-btn">{{Auth::user()->username}}</a>
			<ul>
				<li><a href="{{URL::route('user.edit', array('user'=>$user))}}"><i class="icon-user"></i> My Profile</a></li>
				<li><a href="{{URL::route('list.show', array('user'=>$user))}}"><i class="icon-checkmark"></i> My Loopsy List</a></li>
				<li><a href="{{URL::route('wishlist.show', array('user'=>$user))}}"><i class="icon-heart"></i> My Wishlist</a></li>
				<li><a href="{{URL::route('list.edit', array('id'=>$user))}}"><i class="icon-pencil"></i> Edit List</a></li>
				<li><a href="{{URL::route('logout')}}"><i class="icon-power"></i> Logout</a></li>
			</ul>
		</li>
		@else
		<li><a href="{{URL::route('user.create')}}"><i class="icon-checkmark"></i> Sign up</a></li>
		<li><a href="{{URL::route('login_form')}}" class="login-btn login-trigger">Login</a></li>
		@endif
	</ul>
	<a href="#" class="login-btn nav-toggle">Menu</a>
</div>
</section>