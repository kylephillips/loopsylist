/* 
* Base Scripts
*/

/*
* Toggle Mobile Nav
*/
$('.nav-toggle').on('click', function(e){
	e.preventDefault();
	$('body').toggleClass('open');
});

/*
* Login Modal
*/
$('.login-trigger').on('click', function(e){
	e.preventDefault();
	var url = $(this).attr('href') + ' ' + '#login-form-cont';
	$('#modal-cont').addClass('open');
	$('#modal-cont .modal-body').load(url, function(){
		$('#username').focus();
	});
});

// Hide Modal when clicked outside
$('.modal-body, .login-trigger').click(function(e){e.stopPropagation();});
$(document).click(function(e){
	if ( e.target.className !== '.modal-body'){
		$('.modal').removeClass('open');
	}
});

// Form Switch
$('.switch a').on('click', function(e){
	e.preventDefault();
	
	var selected = $(this).attr('href');
	var span = $(this).parents('.switch').find('span');

	$('.switch a').removeClass('active');
	$(this).addClass('active');
	
	if ( selected == "#name" ){
		$(span).removeClass('right');
		$('#location').val('');
		$('#location').hide();
		$('#name').show();
		$('#type').val('name');
	} else {
		$(span).addClass('right');
		$('#name').val('');
		$('#name').hide();
		$('#location').show();
		$('#type').val('location');
	}
});

/*
* Search Form / Results
*/
$('#searchform').on('submit', function(e){
	e.preventDefault();
	var type = $('#type').val();
	$('.search-results').hide();
	$('#loading').show();
	if ( type == 'name' ){
		searchLists();
	} else {
		geocodeSearch();
	}
});

function searchLists()
{
	var url = $('#searchform').attr('action');
	var data = $('#searchform').serialize();
	$.ajax({
		type: 'POST',
		url: url,
		data: data,
		success: function(data){
			displayResults(data);
		}
	});
}

function displayResults(data)
{
	$('#searchresults').empty();

	if ( jQuery.isEmptyObject(data) ){
		$('#searchresults').append('<li class="no-results">No Lists Found</li>');
	}

	var html = "";
	$.each(data, function(i, item){
		var li = '<li><a href="/user/' + item.slug + '"><strong>' + item.name + '</strong>, <span>' + item.city + ' ' + item.state + '</a></li>';
		html = html + li;
	});
	$('#searchresults').append(html);
	$('#loading').hide();
	$('.search-results').show();
}

function geocodeSearch()
{
	var location = $('#location').val();
	geocoder = new google.maps.Geocoder();
	geocoder.geocode({
		'address' : location
	}, function(results, status){
		if ( status == google.maps.GeocoderStatus.OK ){

			// Get the lat and lng from the returned results	
			var latitude = results[0].geometry.location.lat();
			var longitude = results[0].geometry.location.lng();
				
			// Populate the form fields
			$('#latitude').val(latitude);
			$('#longitude').val(longitude);
			
			// Submit the form
			searchLists();
		}
	});

}


/*
* Ajax login
*/
$(document).on('submit', '#login-form', function(e){
	e.preventDefault();
	var url = $(this).attr('action');
	var data = $(this).serialize();
	$.ajax({
		type : 'POST',
		url : url,
		data : data,
		success : function(data){
			if ( data.status == 'error' ){
				$('#login-error').text(data.message);
				$('#login-error').show();
			} else {
				location.reload();
			}
		}
	});
});

/* 
* Show/Hide Password Functionality
*/
$(document).on('keyup', '#password', function(){
	var pass = $('#password').val();
	$('#password_shown').val(pass);
});
$(document).on('keyup', '#password_shown', function(){
	var pass = $('#password_shown').val();
	$('#password').val(pass);
});
$('#toggle-password').on('click', function(e){
	e.preventDefault();
	if ( $('#password').is(':visible') ){
		$(this).text('Hide');
		$('#password').hide();
		$('#password_shown').show();
	} else {
		$(this).text('Show');
		$('#password').show();
		$('#password_shown').hide();
	}
});


/* 
* Validate fields before submission
*/
function validateField(field, value, url)
{
	if ( field == 'password_shown' ){
		var field = 'password';
	}
	if ( value.length > 0 ){
		$.ajax({
			type : 'GET',
			url : url,
			data : {
				field : field,
				value : value
			},
			success : function(data){
				if ( data.status === 'error' ){
					displayError(field);
				} else {
					displaySuccess(field);
				}
			}
		});
	} else {
		removeFeedback(field);
	}
}

/*
* Remove form feedback
*/
function removeFeedback(field){
	var element = '#' + field;
	var parent = $(element).parent('div');
	$(parent).removeClass('has-error');
	$(parent).removeClass('has-success');
	$(parent).find('.form-control-feedback').remove();
}

/*
* Provide error feedback with form
*/
function displayError(field)
{
	var element = '#' + field;
	var parent = $(element).parent('div');
	removeFeedback(field);
	$(parent).addClass('has-error');
	$(parent).append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
}

/*
* Provide success feedback with form
*/
function displaySuccess(field)
{
	var element = '#' + field;
	var parent = $(element).parent('div');
	removeFeedback(field);
	$(parent).addClass('has-success');
	$(parent).append('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');
}

/*
* Geocode zip codes on user signup
*/
$('#addressUpdate').on('submit', function(e){
	var zip = $('#zip').val();
	if ( zip.length > 0 ){
		e.preventDefault();
		geocodeZip(zip);
	}
});

function geocodeZip(zip){
	geocoder = new google.maps.Geocoder();
	geocoder.geocode({
		'address' : zip
	}, function(results, status){
		if ( status == google.maps.GeocoderStatus.OK ){

			// Get the city and state from the returned results
			for (var component in results[0]['address_components']) {
	            for (var i in results[0]['address_components'][component]['types']) {
	                if (results[0]['address_components'][component]['types'][i] == "locality") {
	                    city = results[0]['address_components'][component]['short_name'];
	                }
	                if (results[0]['address_components'][component]['types'][i] == "administrative_area_level_1") {
	                    state = results[0]['address_components'][component]['long_name'];
	                }
	            }
        	}
			
			// Get the lat and lng from the returned results	
			var latitude = results[0].geometry.location.lat();
			var longitude = results[0].geometry.location.lng();
				
			// Populate the form fields
			$('#latitude').val(latitude);
			$('#longitude').val(longitude);
			$('#city').val(city);
			$('#state').val(state);
			
			// Submit the form
			$('#addressUpdate').unbind('submit');
			$('#addressUpdate').submit();

		} else {
			$('.page-loading').hide();
			$('#addresserror').show();
			$('#addressform button').removeAttr('disabled');
		}
	});
}