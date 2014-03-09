/* 
* Base Scripts
*/
$(document).ready(function(){
	// Home Headline
	$('.hero h1').inflateText();
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