/* 
* Base Scripts
*/


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
$('#toggle-password').on('click', function(){
	var check = $(this).find('input');
	var text = $(this).find('span');

	if ( $(check).is(':checked') ){
		$(text).text('Hide Password');
		$('#password').hide();
		$('#password_shown').show();
	} else {
		$(text).text('Show Password');
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
$('#signup').on('submit', function(e){
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
				
			var latitude = results[0].geometry.location.lat();
			var longitude = results[0].geometry.location.lng();
				
			$('#latitude').val(latitude);
			$('#longitude').val(longitude);
			
			$('#signup').unbind('submit');
			$('#signup').submit();

		} else {
			$('.page-loading').hide();
			$('#addresserror').show();
			$('#addressform button').removeAttr('disabled');
		}
	});
}