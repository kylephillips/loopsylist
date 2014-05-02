@extends('partials.master')
@section('title', $title)
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
			<a href="{{URL::route('list.edit', array('user'=>$user->slug))}}" class="btn">Edit</a>
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

	<ul class="list-filters loggedin">
		<li id="yearselect">
			{{Form::label('year', 'Year')}}
			<select id="year" class="filter">
				@foreach($years as $key=>$year)
				<option value="{{$year['release_year']}}">{{$year['release_year']}}</option>
				@endforeach
			</select>
		</li>
		<li>
			{{Form::label('type', 'Type')}}
			{{Form::select('type', $types, '', array('class'=>'filter'))}}
		</li>
		<li>
			{{Form::label('status', 'Status')}}
			<select id="status" class="filter">
				<option value="all">All</option>
				<option value="yes">Has</option>
				<option value="no">Doesn't have</option>
			</select>
		</li>
	</ul>

	@if ($user->toylist->visibility == 'private')
	<div class="alert alert-info center">Your list is currently private.</div>
	@endif

	<div id="list"></div>

@else
<div class="alert alert-info center">This user's list is private.</div>
@endif
</div><!-- .container-->

@stop
@section('footer_content')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script>

$(document).ready(function(){
	loadList('full-size', "<?php $y1 = reset($years); echo $y1[key($y1)]; ?>", '');
});

/**
* Update list from selects
*/
$('select').on('change', function(){
	var type = $('#type').val();
	var status = $('#status').val();
	if ( type === 'full-size' ){
		var year = $('#year').val();
		$('.list-filters').addClass('loggedin');
		$('#yearselect').show();
		loadList(type, year, status);
	} else {
		$('.list-filters').removeClass('loggedin');
		$('#yearselect').hide();
		loadList(type, '', status);
	}
});


/**
* Display the list
*/
function displayList(data)
{	
	$('#list').empty();

	var i = 0;
	var out = '';
	var url = "{{URL::route('home')}}/loopsy/";
	var imgurl = "{{URL::asset('uploads/toys/_thumbs')}}" + '/225x265_';
	var check = '<img src="' + "{{URL::asset('assets/images/check-snipe.png')}}" + '" class="check" alt="You have this" />';

	$.each(data, function(index, doll){
		console.log(doll);
		var link = url + doll.slug;

		if ( i % 4 === 0 ){
			out += '</ul><ul class="loopsy-gallery">';
		}

		out += '<li><a href="' + link + '">';
		if ( doll.status === "1" ){
			out += '<div class="have">';
		} else{
			out += '<div>';
		}
		out += '<img src="' + imgurl + doll.image + '" alt="' + doll.title + '" />';
		if ( doll.status === "1" ){
			out += check;
		}
		out += '</div>';
		out += doll.title;
		out += '</a></li>' ;
		i++;
	});

	out += '</ul>';
	$('#list').removeClass('loading');
	$('#list').html(out);
}


/**
* Load the list
*/
function loadList(type, year, status)
{
	$('#list').addClass('loading');
	$.ajax({
		type : 'GET',
		url : "{{URL::route('user_dolls')}}",
		data: {
			type : type,
			year: year,
			status: status,
			user: "{{$user->id}}"
		},
		success: function(data){
			displayList(data);
		}
	});
}

/**
* Load the User Map
*/
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

</script>
@stop