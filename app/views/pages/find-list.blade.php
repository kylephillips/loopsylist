@extends('partials.master')
@section('title', 'Loopsy List - Find a List')
@section('content')
<section class="page-header">
	<div class="container">
		<h1>Find a <em>Loosy List</em></h1>
	</div>
</section>

<section class="find-form">
	<div class="container">
		{{Form::open()}}
			<div class="switch">
				<ul>
					<li><a href="#name" class="active">By Name</a></li>
					<li><a href="#location">By Location</a></li>
				</ul>
				<span class="left"></span>
			</div>
			{{Form::text('name', '', array('id'=>'name', 'placeholder'=>'Who\'d you like to find?'))}}
			{{Form::text('location', '', array('id'=>'location', 'placeholder'=>'Search within 50 miles of... (address or zip)', 'style'=>'display:none;'))}}
			{{Form::hidden('type', 'name', array('id'=>'type'))}}
		{{Form::close()}}
	</div>
</section><!-- .find-form -->

<section class="find-results">
</section>

@stop