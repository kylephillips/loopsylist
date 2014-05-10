@extends('partials.master')
@section('title', 'Loopsy List – My Account')

@section('head_content')
{{HTML::style('/assets/js/redactor/redactor.css')}}
{{HTML::script('/assets/js/redactor/redactor.min.js')}}
@stop

@section('content')
<section class="page-header">
	<div class="container">
		<h1>My Profile</h1>
	</div>
</section>
<?php 
	$hashed_email = md5(strtolower(trim(Auth::user()->email)));
	$default_avatar = urlencode(URL::asset('assets/images/avatar-default.png'));
?>

<div class="container">
	<div class="small-form">
		
		<section class="user-info">
			<div class="avatar">
				<img src="http://www.gravatar.com/avatar/{{$hashed_email}}?s=200&d={{$default_avatar}}" />
				<a href="https://en.gravatar.com/" target="_blank">Edit Avatar</a>
			</div>
			<div id="usermap" class="loading"></div>
			<nav class="buttons">
				<ul>
					<li><a href="{{URL::route('list.show', array('user'=>$user->slug))}}">View List</a></li>
					<li><a href="{{URL::route('list.edit', array('user'=>$user->slug))}}">Edit List</a></li>
					<li><a href="{{URL::route('logout')}}">Logout</a></li>
				</ul>
			</nav>
		</section>

		@if(Session::get('emailerror'))
		has email error
		@endif

		@if(Session::has('errors'))
		<div class="alert alert-danger">
			Oopsie! Please correct the following:
			<ul>
				<?php
				foreach ($errors->all('<li>:message</li>') as $error)
				{
				 echo $error;
				}
				?>
			</ul>
		</div>
		@endif

		@if(Session::get('success'))
		<div class="alert alert-info">
			Your Profile has been succesfully updated.
		</div>
		@endif

		{{Form::open(array('route' => array('user.update', $user->id), 'method'=>'PUT','id'=>'addressUpdate'))}}
		<div class="form-group first has-feedback">
				{{Form::label('email', 'Email')}}
				{{Form::email('email', $user->email, array('class'=>'form-control validate', 'placeholder'=>'We Don\'t spam.'))}}
		</div>
		<div class="form-group first has-feedback">
				{{Form::label('name', 'Your Name')}}
				{{Form::text('name', $user->name, array('class'=>'form-control validate', 'placeholder'=>'Your first and last name.'))}}
		</div>
		<div class="form-group has-feedback">
			{{Form::label('zip', 'Zip Code')}}
			{{Form::text('zip', $user->zip_code, array('class'=>'form-control validate', 'placeholder'=>'So friends can find you by location.'))}}
		</div>
		<div class="form-group textarea">
			{{Form::label('bio', 'About You (optional)')}}
			{{Form::textarea('bio', $user->bio, array('id'=>'bio'))}}
		</div>
		<div class="password-change">
			<h4>Change Your Password</h4>
			<div class="form-group has-feedback">
				{{Form::label('password', 'New Password')}}
				{{Form::password('password', '', array('class'=>'form-control validate', 'placeholder'=>'At least 6 characters.'))}}
			</div>
			<div class="form-group last has-feedback">
				{{Form::label('password_confirmation', 'Confirm New Password')}}
				{{Form::password('password_confirmation', '', array('class'=>'form-control validate', 'placeholder'=>'At least 6 characters.'))}}
			</div>
		</div>
		<div class="form-group last">
			<label class="checkbox">
				@if( $public == true )
				<input type="checkbox" name="visibility" value="1" checked>
				@else
				<input type="checkbox" name="visibility" value="1" >
				@endif
				Make my list public (so friends can find me).
			</label>
		</div>
		<div class="submit">
			{{Form::hidden('latitude', '', array('id'=>'latitude'))}}
			{{Form::hidden('longitude', '', array('id'=>'longitude'))}}
			{{Form::hidden('city', '', array('id'=>'city'))}}
			{{Form::hidden('state', '', array('id'=>'state'))}}
			{{Form::submit('Save Changes', array('class'=>'btn btn-primary'))}}
		</div>
		{{Form::close()}}
	</div><!-- .small-form -->
</div><!-- .container -->

@if($user->id !== 2)
<!-- Delete Account -->
<div class="container">
	<div class="small-form delete-list">
		<p>
			<a href="#" class="toggle-delete">Want to delete your account?</a>
		</p>
		<div class="delete-section" style="display:none;">
			<div id="delete-error" class="alert alert-danger" style="display:none;"></div>
			<p><strong>Important:</strong> Once you delete your account, all of your profile and list information will be permanently removed. It is not possible to recover this information once this process is complete.</p>
			<div class="delete-confirmation">
				<label for="deleteconfirm">Enter the text "<strong>DELETE</strong>" and submit to completely remove your profile</label>
				<input type="text" id="deleteconfirm" placeholder="Enter Text" />
				<button type="submit" id="deleteaccount" class="btn">Delete Account</button>
			</div>
		</div><!-- .delete-section -->
	</div><!-- .small-form -->
</div><!-- .container -->
@endif

@stop
@section('footer_content')
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>
$(document).ready(function(){
	$('#bio').redactor({
		buttons: ['bold', 'italic', 'unorderedlist', 'orderedlist', 'link']
	});
});


/**
* Delete Account
*/
$('.toggle-delete').on('click', function(e){
	e.preventDefault();
	$('.delete-section').toggle();
});

$('#deleteaccount').on('click', function(e){
	e.preventDefault();
	validateDelete();
});

function validateDelete()
{
	$('#delete-error').hide();
	var text = $('#deleteconfirm').val();
	if ( text !== 'DELETE' ){
		$('#delete-error').text('You must enter the text "DELETE" to remove your account.');
		$('#delete-error').show();
	} else {
		deleteAccount();
	}
}

function deleteAccount()
{
	var url = "{{URL::route('user.destroy', array('id'=>$user->id))}}";
	$.ajax({
		url: url,
		method: 'DELETE',
		success: function(data){
			var home = "{{URL::route('home')}}";
			window.location = home;
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
	var map = new google.maps.Map(document.getElementById('usermap'), mapOptions);
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng({{$user->latitude}}, {{$user->longitude}}),
		map: map,
		icon: pin
	});
	$('#usermap').removeClass('loading');
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
@stop