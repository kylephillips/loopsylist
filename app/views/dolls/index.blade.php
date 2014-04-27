@extends('partials.master')
@section('title', 'Loopsy List - All Lalaloopsies')
@section('content')
<section class="page-header">
	<div class="container">
		<h1>All <em>Lalaloopsies</em></h1>
	</div>
</section>

<div class="container">
	
	<ul class="list-filters">
		<li>
			{{Form::label('year', 'Year')}}
			{{Form::select('year', $years, $year, array('class'=>'filter'))}}
		</li>
		<li>
			{{Form::label('type', 'Type')}}
			{{Form::select('type', $types, $type, array('class'=>'filter'))}}
		</li>
	</ul>

	@if(count($loopsies) == 0)
	<div class="alert alert-info">No Results for the current selection.</div>
	@endif

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
			@if ( (Auth::check()) && (Auth::user()->group->id == 2) )
			<p><a href="{{URL::route('loopsy.edit', array('id'=>$loopsy->id))}}">(Edit)</a></p>
			@endif
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

@section('footer_content')
@if (Auth::check())
<script>
$('.filter').on('change', function(){
	var year = $('#year').val();
	var type = $('#type').val();
	var status = $('#status').val();
	var url = "{{URL::route('loopsy.index')}}";
	var newurl = url + '?year=' + year + '&type=' + type + '&status=' + status;
	window.location.replace(newurl);
});
</script>
@else
<script>
$('.filter').on('change', function(){
	var year = $('#year').val();
	var type = $('#type').val();
	var url = "{{URL::route('loopsy.index')}}";
	var newurl = url + '?year=' + year + '&type=' + type;
	window.location.replace(newurl);
});
</script>
@endif
@stop


