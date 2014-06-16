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
@if(Auth::check())
<script type='text/javascript'>
/* <![CDATA[ */
var loopsy_data = {
	"home" : "{{URL::route('home')}}",
	"status_switch" : "{{URL::route('save_switch')}}",
	"wishlist_store":"{{URL::route('wishlist.store')}}",
	"wishlist_destroy":"{{URL::route('wishlist.destroy')}}"
};
/* ]]> */
</script>
@else
<script type='text/javascript'>
/* <![CDATA[ */
var loopsy_data = {
	"home" : "{{URL::route('home')}}"
};
/* ]]> */
</script>
@endif