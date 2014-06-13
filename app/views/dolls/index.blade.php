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
	if ( $c % 3 == 0 ){
		echo '</ul><ul class="loopsy-gallery">';
	}
	?>
	@if ( in_array($loopsy->id, $dolls) )
		<li class="{{$loopsy->release_year}} @foreach($loopsy->dollTypes as $type){{$type->slug}}@endforeach has">
	@else
		<li class="{{$loopsy->release_year}} @foreach($loopsy->dollTypes as $type){{$type->slug}}@endforeach">
	@endif
		<div class="doll">
			<div class="title">
				<p>{{$loopsy->title}}</p>
				@if(in_array($loopsy->id, $wishlist))
				<button class="wishlist-btn active" data-doll="{{$loopsy->id}}"><i class="icon-star"></i></button>
				@else
				<button class="wishlist-btn" data-doll="{{$loopsy->id}}"><i class="icon-star"></i></button>
				@endif
			</div>
			<a href="{{URL::route('loopsy.show', array('loopsy'=>$loopsy->slug))}}">
				<div class="image">
					<img src="{{URL::asset('uploads/toys/_thumbs') . '/225x265_' . $loopsy->image}}" alt="{{$loopsy->title}}" />
				</div>
			</a>
			@if(Auth::check())
			<section class="status-switch">
				<div>
					<ul>
						<li><a href="no" @if(!in_array($loopsy->id, $dolls))class="active"@endif data-id="{{$loopsy->id}}"><i class="icon-lock"></i></a></li>
						<li><a href="yes" @if(in_array($loopsy->id, $dolls))class="active"@endif data-id="{{$loopsy->id}}"><i class="icon-check"></i></a></li>
					</ul>
					<span @if(in_array($loopsy->id, $dolls))class="right"@endif></span>
				</div>
			</section>
			@else
			<section class="status-switch loggedout"></section>
			@endif
		</div>
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

$('.status-switch a').on('click', function(e){
	
	var doll = $(this).parents('.status-switch').parents('li');
	var button = $(doll).find('span');
	var status = $(this).attr('href');
	var id = $(this).data('id');

	$(this).parents('.status-switch').find('a').removeClass('active');
	$(this).addClass('active');

	if ( status == 'no' ){
		$(button).removeClass('right');
		$(doll).removeClass('has');
		savePosition('no', id);
	} else {
		$(button).addClass('right');
		$(doll).addClass('has');
		savePosition('yes', id);
	}
	
	e.preventDefault();
});

function savePosition(position, id)
{
	$.ajax({
		url : "{{URL::route('save_switch')}}",
		type : 'GET',
		data : {
			status : position,
			doll : id
		}
	});
}

// Wishlist Button
$('.wishlist-btn').on('click', function(e){
	e.preventDefault();
	var doll = $(this).data('doll');
	
	if ( $(this).hasClass('active') ){
		$(this).removeClass('active');
		removeFromWishlist(doll);
	} else {
		$(this).addClass('active');
		addToWishlist(doll);
	}
});

function addToWishlist(id)
{
	$.ajax({
		url: "{{URL::route('wishlist.store')}}",
		type : 'POST',
		data : {
			doll : id
		}
	});
}

function removeFromWishlist(id)
{
	$.ajax({
		url: "{{URL::route('wishlist.destroy')}}",
		type : 'DELETE',
		data : {
			doll : id
		}
	});
}


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


