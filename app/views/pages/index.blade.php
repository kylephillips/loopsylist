@extends('partials.master')
@section('title', 'Loopsy List - Keep Track of your Lalaloopsy Collection')
@section('content')

<section class="hero" data-stellar-background-ratio="0.5">
	@include('partials.top')
	<div class="container">
		<h1><em>Loopsy</em> List</h1>
		<img src="{{URL::asset('assets/images')}}/headline-shadow.png" alt="Headline shadow" />
		<p>A free and easy way to <strong>track and share</strong> your Lalaloopsy collection.</p>
		<div class="start-button has-border">
			<hr />
			<a href="{{URL::route('user.create')}}">Start Your List</a>
		</div>
		<p class="details">
			<a href="{{URL::route('login_form')}}">Login</a> or <a href="#">Find a list</a>
		</p>
	</div><!-- .container -->
</section><!-- .hero -->

<div class="pattern-border"></div>

<section class="find-home">
	<div class="container">
		<h3><em>Find</em> a List</h3>
		{{Form::open()}}
			<div class="switch">
				<ul>
					<li><a href="#name" class="active">By Name</a></li>
					<li><a href="#location">By Location</a></li>
				</ul>
				<span class="left"></span>
			</div>
			{{Form::text('name', '', array('id'=>'name', 'placeholder'=>'Who\'d you like to find?'))}}
			{{Form::text('location', '', array('id'=>'location', 'placeholder'=>'Where\'d you like to search?', 'style'=>'display:none;'))}}
			{{Form::hidden('type', 'name', array('id'=>'type'))}}
		{{Form::close()}}
	</div>
</section><!-- .find-home -->

<section class="testimonials">
	<div class="container">
		<ul>
			<li>
				<img src="{{URL::asset('/assets/images/dana.jpg')}}" alt="Dana" />
				<p>Loopsy List has streamlined birthdays and holidays. Now it’s easy to let friends and family know which Lalaloopsies my daughter has and wants.<em>&ndash; Dana, Mother of a Lalaloopsy Fanatic</em></p>
			</li>
			<li>
				<img src="{{URL::asset('/assets/images/kevin.jpg')}}" alt="Kevin" />
				<p>I finally know what my niece has and what she doesn’t. This is perfect for finding gifts without getting something she already has.<em>&ndash; Kevin, Formerly Disgruntled Gift Giver</em></p>
			</li>
			<li>
				<img src="{{URL::asset('/assets/images/adam.jpg')}}" alt="Adam" />
				<p>Their eyes. They stare at me whenever I walk into my daughter’s room. At least now we can track which ones are missing, so they can eventually come to life and conquer our house.<em>&ndash; Adam, Father of a Loopsy Overlord</em></p>
			</li>
		</ul>
	</div>
</section><!-- .testimonials -->

<section class="cta">
	<div class="container">
		<h3>Over <em>50</em> full-size dolls alone!</h3>
		<p>Do your friends & family know which Lalaloopsies your child has?</p>
		<div class="start-button">
			<a href="{{URL::route('user.create')}}">Start Your List</a>
		</div>
	</div>
</section><!-- .cta -->

@stop

@section('footer_content')

{{HTML::script('/assets/js/inflateText.js')}}
{{HTML::script('/assets/js/jquery.stellar.js')}}

<script>
$(window).stellar();
$('.switch a').on('click', function(e){
	e.preventDefault();
	
	var selected = $(this).attr('href');
	var span = $(this).parents('.switch').find('span');

	$('.switch a').removeClass('active');
	$(this).addClass('active');
	
	if ( selected == "#name" ){
		$(span).removeClass('right');
		$('#location').val('');
		$('#location').hide();
		$('#name').show();
		$('#type').val('name');
	} else {
		$(span).addClass('right');
		$('#name').val('');
		$('#name').hide();
		$('#location').show();
		$('#type').val('location');
	}
});
</script>
@stop