@extends('partials.master')
@section('title', 'Loopsy List - All Lalaloopsies')
@section('content')
<section class="page-header">
	<div class="container">
		<h1>All <em>Lalaloopsies</em></h1>
	</div>
</section>

<div class="container">

	@if(Auth::check())
	<ul class="list-filters loggedin">
	@else
	<ul class="list-filters">
	@endif
		<li>
			{{Form::label('year', 'Year')}}
			{{Form::select('year', $years)}}
		</li>
		<li>
			{{Form::label('type', 'Type')}}
			{{Form::select('type', $types)}}
		</li>
		@if(Auth::check())
		<li>
			{{Form::label('status', 'Status')}}
			<select>
				<option>All</option>
			</select>
		</li>
		@endif
	</ul>


	<?php $c = 0; ?>	
	@foreach($loopsies as $loopsy)
	<?php
	if ( $c % 4 == 0 ){
		echo '</ul><ul class="loopsy-gallery">';
	}
	?>
		<li>
			<a href="{{URL::route('loopsy.show', array('loopsy'=>$loopsy->slug))}}">
				<div>
					<img src="{{URL::asset('uploads/toys/_thumbs') . '/225x265_' . $loopsy->image}}" alt="{{$loopsy->title}}" />
				</div>
				{{$loopsy->title}}
			</a>
		</li>
	<?php $c++; ?>
	@endforeach
</ul>
</div><!-- .container -->

@stop
