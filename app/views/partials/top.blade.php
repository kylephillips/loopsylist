<div class="container top-nav">
	<ul>
		@if(Auth::check())
		<li>
			<a href="#" class="login-btn">{{Auth::user()->username}}</a>
			<ul>
				<li><a href="#">My List</a></li>
				<li><a href="{{URL::route('logout')}}">Logout</a></li>
			</ul>
		</li>
		@else
		<li><a href="{{URL::route('login_form')}}" class="login-btn">Login</a></li>
		@endif
		<li><a href="#">Find a List</a></li>
	</ul>
</div>