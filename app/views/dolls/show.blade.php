@extends('partials.master')
@section('title', $pagetitle)
@section('content')
<section class="page-header">
	<div class="container">
		<h1>{{$loopsy->title}}</h1>
	</div>
</section>

<div class="container page">
	<ul class="breadcrumbs">
		<li><a href="{{URL::route('home')}}">Home</a></li>
		<li><a href="{{URL::route('loopsy.index')}}">All Lalaloopsies</a></li>
		<li>{{$loopsy->title}}</li>
	</ul>

	<section class="main">
		<h2>{{$loopsy->title}}</h2>
		{{$loopsy->bio}}
		@if( (Auth::check()) && (Auth::user()->group->id == 2) )
		<a href="{{URL::route('loopsy.edit', array('loopsy'=>$loopsy->id))}}" class="btn">Edit</a>
		@endif
	</section>

	<aside>

		@if(Auth::check())
		<section class="status-switch">
			<p>Do you have {{$loopsy->title}}?</p>
			<div>
				<ul>
					<li><a href="no" @if($status == 0)class="active"@endif data-id="{{$loopsy->id}}">Don't have</a></li>
					<li><a href="yes" @if($status == 1)class="active"@endif data-id="{{$loopsy->id}}">Have</a></li>
				</ul>
				<span @if($status == 1)class="right"@endif></span>
			</div>
		</section>
		@endif

		<img src="{{URL::asset('uploads/toys')}}/{{$loopsy->image}}" alt="{{$loopsy->title}}" />
		<?php
		$searchterm = urlencode($loopsy->title) . "+lalaloopsy";
		?>
		
		<a href="http://www.amazon.com/s/?field-keywords={{$searchterm}}" target="_blank" class="btn">Search On Amazon</a>

		<a href="http://www.ebay.com/sch/{{$searchterm}}" target="_blank" class="btn">Search on Ebay</a>

		<ul class="loopsy-details">
			@foreach($loopsy->dolltypes as $type)
			<li>
				<strong>Type</strong>
				<span>{{$type->title}}</span>
			@endforeach
			<li>
				<strong>Birthday</strong>
				<span>{{$birthday}}</span>
			</li>
			<li>
				<strong>Released</strong>
				<span><?php echo date('F', $loopsy->release_month); ?> {{$loopsy->release_year}}</span>
			</li>
			@if($loopsy->sewn_from)
			<li>
				<strong>Sewn From</strong>
				<span>{{$loopsy->sewn_from}}</span>
			</li>
			@endif
			@if($loopsy->pet)
			<li><strong>Pet</strong><span>{{$loopsy->pet}}</span></li>
			@endif
		</ul>
	</aside>
</div><!-- .container -->

@stop

@section('footer_content')
@if (Auth::check())
<script>
$('.status-switch a').on('click', function(e){
	if ( !$(this).hasClass('active') ){
		var status = $(this).attr('href');
		var id = $(this).data('id');

		$('.status-switch a').removeClass('active');
			$(this).addClass('active');
		if ( status == 'no' ){
			$('.status-switch span').removeClass('right');
			savePosition('no', id);
		} else {
			$('.status-switch span').addClass('right');
			savePosition('yes', id);
		}
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
</script>
@endif
@stop






