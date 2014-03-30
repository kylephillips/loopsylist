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
				<option value="has">Dolls I have</option>
				<option value="hasnot">Dolls I don't have</option>
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
	@if ( in_array($loopsy->id, $dolls) )
		<li class="{{$loopsy->release_year}} @foreach($loopsy->dollTypes as $type){{$type->slug}}@endforeach has">
	@else
		<li class="{{$loopsy->release_year}} @foreach($loopsy->dollTypes as $type){{$type->slug}}@endforeach">
	@endif
			<a href="{{URL::route('loopsy.show', array('loopsy'=>$loopsy->slug))}}">
				@if ( in_array($loopsy->id, $dolls) )
				<div class="have">
					<img src="{{URL::asset('uploads/toys/_thumbs') . '/225x265_' . $loopsy->image}}" alt="{{$loopsy->title}}" />
					<img src="{{URL::asset('assets/images/check-snipe.png')}}" class="check" alt="You have this" />
				</div>
				@else
				<div>
					<img src="{{URL::asset('uploads/toys/_thumbs') . '/225x265_' . $loopsy->image}}" alt="{{$loopsy->title}}" />
				</div>
				@endif
				{{$loopsy->title}}
			</a>
		</li>
	<?php $c++; ?>
	@endforeach
</ul>

@if( (Auth::check()) && (Auth::user()->group->id == 2) )
	<div class="center">
		<a href="{{URL::route('loopsy.create')}}" class="btn">Add a Loopsy</a>
	</div>
@endif

</div><!-- .container -->

@stop
