@extends('partials.master')
@section('title', 'Loopsy List')
@section('content')

<section class="page-header">
	<div class="container">
		<h1>Edit Your <em>Loopsy List</em></h1>
	</div>
</section>

<div class="container small">
	
	<section class="type-switch">
		{{Form::label('type', 'Type')}}
		{{Form::select('type', $types, '', array('class'=>'filter'))}}
	</section>

	<ul class="user-status-switch">
		<li><a href="#all" class="active">All</a></li>
		<li><a href="#have">Have</a></li>
		<li><a href="#donthave">Don't Have</a></li>
	</ul>

	<section id="all" class="tab-content">
		<div class="user-list-head">
			<select id="year-select-all">
			<?php 
				for ($y = 2010; $y < date('Y'); $y++){
					$years[] = $y;
				}
				$years = array_reverse($years);
				foreach ($years as $year){
					echo '<option value="#' . $year . '">' . $year . ' Releases</option>';
				}
			?>
			</select>
			<strong>Have this Loopsy?</strong>
		</div>

		<?php $year = ""; $c = 1; ?>
		@foreach($dolls as $doll)
		
		<?php
			if ( $year !== $doll->release_year ) {
				echo '</ul><ul class="user-list-all" id="' . $doll->release_year . '"';
				if ( $c !== 1 ) echo ' style="display:none;"';
				echo '>';
			}
		?>
		<li>
			<div>
				<a href="#" class="showphoto" data-id="{{$doll->id}}"><i class="icon-search"></i></a>
				{{$doll->title}}
			</div>
			<section>
				<ul class="user-list-switch">
					<li><a href="no" data-id="{{$doll->id}}" @if(!in_array($doll->id, $dolls_have))class="active no"@endif>No</a></li>
					<li><a href="yes" data-id="{{$doll->id}}" @if(in_array($doll->id, $dolls_have))class="active"@endif>Yes</a></li>
				</ul>
				<span @if(in_array($doll->id, $dolls_have)) class="right"@endif></span>
			</section>
		</li>

		<?php 
			$year = $doll->release_year; 
			$c++;
		?>
		@endforeach
	</section><!-- #all -->

	<section id="have" style="display:none;" class="tab-content">
		Have
	</section><!-- #have -->

	<section id="donthave" style="display:none;" class="tab-content">
		Don't Have
	</section><!-- #donthave -->

</div><!-- .container -->


@stop
@section('footer_content')

<script>
$('.showphoto').on('click', function(e){
	$('#dolldetail .modal-body').empty();
	var doll = $(this).data('id');
	$.ajax({
		url: "{{URL::route('loopsy_image')}}",
		type: 'GET',
		data: {
			id: doll
		},
		success: function(data){
			console.log(data);
			var url = "{{URL::asset('uploads/toys')}}";
			var image = data.image;
			var fullimage = url + '/' + image;
			var img = '<img src="' + fullimage + '" />';
			$('#dolldetail .modal-body').append(img);
			$('#dolldetail').addClass('open');
		}
	});
	e.preventDefault();
});

$('#year-select-all').on('change', function(){
	$('.user-list-all').hide();
	var target = $(this).val();
	$(target).show();
});

$('.user-list-switch li a').on('click', function(e){
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
	}
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
@stop


