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
					<li><a href="no" class="active">Don't have</a></li>
					<li><a href="yes">Have</a></li>
				</ul>
				<span></span>
			</div>
		</section>
		@endif

		<img src="{{URL::asset('uploads/toys')}}/{{$loopsy->image}}" alt="{{$loopsy->title}}" />

		<ul class="loopsy-details">
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
<script>
$('.status-switch a').on('click', function(e){
	var status = $(this).attr('href');
	$('.status-switch a').removeClass('active');
		$(this).addClass('active');
	if ( status == 'no' ){
		$('.status-switch span').removeClass('right');
	} else {
		$('.status-switch span').addClass('right');
	}
	e.preventDefault();
});
</script>
@stop






