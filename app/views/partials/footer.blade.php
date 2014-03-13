@if(isset($front_page))
<footer class="home">
@else
<footer>@endif
	<div class="container">
		<hr />
		<section class="icon">
			<a href="{{URL::route('home')}}">
				@if(isset($front_page))
				<img src="{{URL::asset('assets/images/loopsylist-icon-purple.png')}}" alt="Loopsy List Icon" />
				@else
				<img src="{{URL::asset('assets/images/loopsylist-icon-white.png')}}" alt="Loopsy List Icon" />
				@endif
			</a>
		</section>
		<section class="copyright">
			<p>
				&copy;<?php echo date('Y'); ?> Loopsy List. Loopsy List is in no way affiliated with the Lalaloopsy brand, MGA Entertainment, or any of it’s affiliates. For seriously.
			</p>
		</section>
	</div>
</footer>