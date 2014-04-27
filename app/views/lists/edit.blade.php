@extends('partials.master')
@section('title', 'Edit Your List - Loopsy List')
@section('content')

<section class="page-header">
	<div class="container">
		<h1>Edit Your <em>Loopsy List</em></h1>
	</div>
</section>

<div class="container small">
	
	<section class="type-switch">
		{{Form::label('type', 'Type')}}
		{{Form::select('type', $types, 'full-size', array('class'=>'filter'))}}
	</section>

	<ul class="user-status-switch">
		<li><a href="all" class="active">All</a></li>
		<li><a href="1">Have</a></li>
		<li><a href="0">Don't Have</a></li>
		<input type="hidden" id="status" value="all">
	</ul>

	<section id="all" class="tab-content">
		<div class="user-list-head">
			<select id="year-select">
			<?php 
				for ($y = 2010; $y <= $latest_year; $y++){
					$years[] = $y;
				}
				$years = array_reverse($years);
				foreach ($years as $year){
					echo '<option value="' . $year . '">' . $year . ' Releases</option>';
				}
			?>
			</select>
			<strong>Have this Loopsy?</strong>
		</div>
		<ul id="list" class="user-list-all"></ul>
	</section>

</div><!-- .container -->


@stop
@section('footer_content')
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script>

$(document).on('click', '.showphoto', function(e){
	$('#dolldetail .modal-body').empty();
	var image = $(this).data('image');
	var url = "{{URL::asset('uploads/toys')}}" + '/' + image;
	var img = '<img src="' + url + '" />';
	$('#dolldetail .modal-body').append(img);
	$('#dolldetail').addClass('open');
	e.preventDefault();
});


/**
* Load full type and latest year by default
*/
$(document).ready(function(){
	var type = "full-size";
	var year = "{{$latest_year}}";
	var status = '';
	loadDolls(type, year, status);
});


/*
* Get a list of dolls based on parameters
*/
function loadDolls(type, year, status)
{
	$.ajax({
		type : 'GET',
		url : "{{URL::route('user_dolls')}}",
		data: {
			type : type,
			year: year,
			status: status
		},
		success: function(data){
			showDolls(data, type, status);
		}
	});
}


/*
* Show the Dolls Returned
*/
function showDolls(data, type, status)
{
	$('#list').empty();

	if ( status === "0" ){
		$('#list').addClass('donthavelist');
		makeSortable();
	} else {
		if ( $('#list').hasClass('donthavelist') ){
			removeSortable();
		}
		$('#list').removeClass('donthavelist');
	}

	var out = "";

	$.each(data, function(index, doll){
		if ( status === '0' ){
			out += donthaveRow(doll);
		} else {
			out += allRow(doll);
		}
	});

	$('#list').html(out);
}


/*
* Rows for ALL & HAVE Tab
*/
function allRow(doll)
{
	var id = doll.id;
	var title = doll.title;
	var image = doll.image;
	var order = doll.order;
	var year = doll.release_year;
	var status = doll.status;
	var out = '<li><div><a href="#" class="showphoto" data-image="' + image + '"><i class="icon-search"></i></a>' + title + '</div>';

	out += '<section><ul class="user-list-switch">';

	// The Choice Switch
	if ( status === '1' ){
		out += '<li><a href="no" data-id="' + id + '">No</a></li>';
		out += '<li><a href="yes" data-id="' + id + '" class="active">Yes</a></li>';
	} else {
		out += '<li><a href="no" data-id="' + id + '" class="active no">No</a></li>';
		out += '<li><a href="yes" data-id="' + id + '">Yes</a></li>';
	}

	// Switch Indicator
	if ( status === '1' ){
		out += '<span class="right"></span>';
	} else {
		out += '<span></span>';
	}

	out += '</ul></section></li>';

	return out;
}

/*
* Rows for DONT HAVE Tab
*/
function donthaveRow(doll)
{
	var id = doll.id;
	var title = doll.title;
	var image = doll.image;
	var order = doll.order;
	var year = doll.release_year;
	var status = doll.status;
	var out = "";

	if ( status !== '1' ){
		out += '<li class="donthave" id="' + id + '"><div><a href="#" class="showphoto" data-image="' + image + '"><i class="icon-search"></i></a>' + title + '</div></li>';
	}
	return out;
}


/**
* Change the type of doll shown
*/
$('#type').on('change', function(){
	
	var type = $(this).val();
	var status = $('#status').val();

	if ( type == 'full-size' ){
		$('#year-select').show();
		var year = $('#year-select').val();
		loadDolls(type, year, status);
	} else {
		$('#year-select').hide();
		loadDolls(type, '', status);
	}
});


/**
* Change displayed year (full-size only)
*/
$(document).on('change', '#year-select', function(){
	$('#list').hide();

	var year = $(this).val();
	var status = $('#status').val();
	var type = $('#type').val();

	loadDolls(type, year, status);
	$('#list').show();
});


/**
* Change list type
*/
$(document).on('click', '.user-status-switch li a', function(e){
	e.preventDefault();
	$('.user-status-switch li a').removeClass("active");
	$(this).addClass('active');

	var status = $(this).attr('href');
	var year = $('#year-select').val();
	var type = $('#type').val();

	$('#status').val(status);
	loadDolls(type, year, status)
});


/**
* Toggle the status switch  & save
*/
$(document).on('click', '.user-list-switch li a', function(e){
	e.preventDefault();
	if ( !$(this).hasClass('active') ){

		var target = $(this).attr('href');
		var span = $(this).parents('.user-list-switch').parent('section').find('span');
		var id = $(this).data('id');

		$(this).parents('.user-list-switch').find('a').removeClass('active');
		$(this).addClass('active');
	
		if ( target == 'yes' ){
			$(span).addClass('right');
			savePosition('yes', id);
		} else {
			$(span).removeClass('right');
			savePosition('no', id);
		}

		// Remove this item if on the "have" tab
		if ( $('#status').val() === '1' ){
			$(this).parents('.user-list-switch').parent('section').parent('li').fadeOut();
		}
	}
});

/**
* Save the position
*/
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

/**
* Enable Sortable Ordering
*/
function makeSortable()
{
	$('.donthavelist').sortable({
		stop : function(event, ui){
			var listorder = $(this).sortable('toArray');
			var url = "{{URL::route('list_order')}}?order=" + listorder;
			
			$.ajax({
				type:"GET",
				url: url,
				success:function(data){
					console.log(data);
				}
			});
		}
	});
}

/**
* Remove sortable
*/
function removeSortable()
{
	$('#list').sortable('destroy');
}
</script>
@stop


