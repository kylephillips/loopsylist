@extends('partials.master')
@section('title', $pagetitle)
@section('content')

<?php
$userid = "";
if ( Auth::check() ) $userid = Auth::user()->id;
?>

<section class="page-header">
	<div class="container">
		<h1>{{$user->name}}'s <em>Loopsy List</em>
			@if($user->toylist->visibility == 'public')
			<br /><a href="#" class="details-toggle btn">Show Details</a>
			@endif
			@if($userid == $user->id)
			<a href="{{URL::route('list.show', array('user'=>$user->slug))}}" class="btn">Edit</a>
			@endif
		</h1>
		@if($user->toylist->visibility == 'public')
		<section class="user-details">
			<div class="left"
				<p><strong>Location:</strong><br /> {{$user->city}}, {{$user->state}}</p>
				{{$user->bio}}
			</div>
			<div id="map"></div>
		</section>
		@endif
	</div>
</section>

<div class="container">
@if( ($userid == $user->id) || ($user->toylist->visibility == 'public') )

	<ul class="list-filters">
		<li>
			{{Form::label('type', 'Type')}}
			{{Form::select('type', $types, $type, array('class'=>'filter'))}}
		</li>
		<li>
			{{Form::label('status', 'Status')}}
			<select id="status" class="filter">
				<option value="all" <?php if($status == 'all') echo 'selected'; ?>>All</option>
				<option value="has" <?php if($status == 'has') echo 'selected'; ?>>Has</option>
				<option value="hasnot" <?php if($status == 'hasnot') echo 'selected'; ?>>Doesn't have</option>
			</select>
		</li>
	</ul>

	@if ($user->toylist->visibility == 'private')
	<div class="alert alert-info center">Your list is currently private.</div>
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
@else
<div class="alert alert-info center">This user's list is private.</div>
@endif

</div><!-- .container -->

@stop

@section('footer_content')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>

<script>
function initialize(){
	var mapOptions = {
		center: new google.maps.LatLng({{$user->latitude}}, {{$user->longitude}}),
		zoom: 10,
		disableDefaultUI: true,
		styles: [
		  {
		    "stylers": [
		      { "visibility": "off" }
		    ]
		  },{
		    "featureType": "landscape",
		    "elementType": "geometry.fill",
		    "stylers": [
		      { "visibility": "on" },
		      { "color": "#fff4f9" }
		    ]
		  },{
		    "featureType": "road.highway",
		    "elementType": "geometry.stroke",
		    "stylers": [
		      { "visibility": "on" },
		      { "color": "#ed99c1" }
		    ]
		  },{
		    "featureType": "road.highway",
		    "elementType": "labels",
		    "stylers": [
		      { "visibility": "on" }
		    ]
		  },{
		    "featureType": "road.arterial",
		    "elementType": "geometry.stroke",
		    "stylers": [
		      { "visibility": "on" },
		      { "color": "#f6e3ec" }
		    ]
		  },{
		    "featureType": "administrative.locality",
		    "elementType": "labels.text.fill",
		    "stylers": [
		      { "visibility": "on" },
		      { "color": "#580143" }
		    ]
		  },{
		    "featureType": "administrative.locality",
		    "elementType": "labels.text.stroke",
		    "stylers": [
		      { "visibility": "on" },
		      { "color": "#fff4f9" }
		    ]
		  },{
		  }
		]
	};
	var pin = "{{URL::asset('assets/images/mappin.png')}}";
	var map = new google.maps.Map(document.getElementById('map'), mapOptions);
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng({{$user->latitude}}, {{$user->longitude}}),
		map: map,
		icon: pin
	});
}
google.maps.event.addDomListener(window, 'load', initialize);

$('.details-toggle').on('click', function(e){
	if ( $('.user-details').is(':visible') ){
		$('.user-details').hide();
		$(this).text('Show Details');
	} else {
		$('.user-details').show();
		$(this).text('Hide Details');
		initialize();	
	}	
	e.preventDefault();
});

$('.filter').on('change', function(){
	var type = $('#type').val();
	var status = $('#status').val();
	var url = "{{URL::route('user.show', array('id'=>$user->slug))}}";
	var newurl = url + '?status=' + status + '&type=' + type;
	window.location.replace(newurl);
});
</script>

@stop


