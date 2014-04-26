@extends('partials.master')
@section('title', 'Loopsy List - Keep Track of your Lalaloopsy Collection')
@section('content')

<section class="hero" data-stellar-background-ratio="0.5">
	@include('partials.top')
	<div class="container">
		@if(Auth::check())
		<?php $user = Auth::user()->username; ?>
		<h1>Woohoo!</h1>
		<h3>You're all in!</h3>
		<div class="start-button has-border">
			<hr />
			<a href="{{URL::route('list.show', array('user'=>$user))}}">View/Edit Your List</a>
		</div>
		@else
		<h1>Track <i>&amp;</i> Share</h1>
		<h3>Your Lalaloopsy Collection!</h3>
		<div class="start-button has-border">
			<hr />
			<a href="{{URL::route('user.create')}}">Start Your List</a>
		</div>
		<p class="details">
			<a href="{{URL::route('login_form')}}" class="login-trigger">Login</a> or <a href="{{URL::route('find_list')}}">Find a list</a>
		</p>
		@endif
	</div><!-- .container -->
</section><!-- .hero -->

<div class="pattern-border"></div>

<section class="search-form home">
	<div class="container">
		<h3><em>Find</em> a List</h3>
		{{Form::open(array('url'=>URL::route('post_search'), 'id'=>'searchform'))}}
			<div class="switch">
				<ul>
					<li><a href="#name" class="active">By Name</a></li>
					<li><a href="#location">By Location</a></li>
				</ul>
				<span class="left"></span>
			</div>
			{{Form::text('name', '', array('id'=>'name', 'placeholder'=>'Who\'d you like to find?', 'autocomplete'=>'off'))}}
			{{Form::text('location', '', array('id'=>'location', 'placeholder'=>'Search within 50 miles of...', 'style'=>'display:none;', 'autocomplete'=>'off'))}}
			{{Form::hidden('type', 'name', array('id'=>'type'))}}
			{{Form::hidden('latitude', '', array('id'=>'latitude'))}}
			{{Form::hidden('longitude', '', array('id'=>'longitude'))}}
			<button type="submit"><i class="icon-search"></i></button>
		{{Form::close()}}
	</div>
</section><!-- .find-form -->

<div id="loading"></div>

<section class="search-results home" style="display:none;">
	<div class="container">
		<h3>Lists Found</h3>
		<ul id="searchresults"></ul>
	</div>
</section>

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
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

{{HTML::script('/assets/js/inflateText.js')}}
{{HTML::script('/assets/js/jquery.stellar.js')}}

<script>
$(window).stellar();
$(document).ready(function(){
	// Home Headline
	$('.hero h1').inflateText();
});
</script>
@stop