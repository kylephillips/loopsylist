@extends('partials.master')
@section('title', 'Loopsy List - Find a List')
@section('content')
<section class="page-header">
	<div class="container">
		<h1>Find a <em>Loosy List</em></h1>
	</div>
</section>

<section class="search-form">
	<div class="container">
		{{Form::open(array('url'=>URL::route('post_search'), 'id'=>'searchform'))}}
			<div class="switch">
				<ul>
					<li><a href="#name" class="active">By Name</a></li>
					<li><a href="#location">By Location</a></li>
				</ul>
				<span class="left"></span>
			</div>
			{{Form::text('name', '', array('id'=>'name', 'placeholder'=>'Who\'d you like to find?'))}}
			{{Form::text('location', '', array('id'=>'location', 'placeholder'=>'Search within 50 miles of... ', 'style'=>'display:none;'))}}
			{{Form::hidden('type', 'name', array('id'=>'type'))}}
			{{Form::hidden('latitude', '', array('id'=>'latitude'))}}
			{{Form::hidden('longitude', '', array('id'=>'longitude'))}}
			<button type="submit"><i class="icon-search"></i></button>
		{{Form::close()}}
	</div>
</section><!-- .find-form -->

<div id="loading"></div>

<section class="search-results" style="display:none;">
	<div class="container">
		<h3>Lists Found</h3>
		<ul id="searchresults"></ul>
	</div>
</section>

@stop

@section('footer_content')
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
@stop