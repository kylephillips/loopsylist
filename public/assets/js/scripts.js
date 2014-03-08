// Show/Hide Password
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