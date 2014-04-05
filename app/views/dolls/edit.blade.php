@extends('partials.master')

@section('head_content')
{{HTML::style('assets/css/jquery.Jcrop.css')}}
{{HTML::style('/assets/js/redactor/redactor.css')}}
{{HTML::script('/assets/js/redactor/redactor.min.js')}}
@stop

@section('content')
<section class="page-header">
	<div class="container">
		<h1>Edit <em>{{$doll->title}}</em></h1>
	</div>
</section>

{{Form::model($doll, array('url'=>URL::route('loopsy.update', array('id'=>$doll->id)),'method'=>'PUT','files'=>true))}}

<div class="container">
	@if(Session::has('errors'))
	<div class="alert alert-danger">
		Please correct the following:
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

	@if(Session::has('success'))
	<div class="alert alert-info">
		{{Session::get('success')}}
	</div>
	@endif
	<div class="small-form">
	<div class="form-group">
		{{Form::label('type', 'Toy Type')}}
		{{Form::select('type', $types, $dolltype, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('title', 'Name/Title*')}}
		{{Form::text('title', $doll->title, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('release_month', 'Month of Release')}}
		{{Form::selectMonth('release_month', $doll->release_month, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('release_year', 'Year of Release')}}
		{{Form::selectRange('release_year', '2010', date('Y'), $doll->release_year, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('sewn_on_month', 'Month Sewn')}}
		{{Form::selectMonth('sewn_on_month', $doll->sewn_on_month, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('sewn_on_day', 'Day Sewn')}}
		{{Form::selectRange('sewn_on_day', '1', '31', $doll->sewn_on_day, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('sewn_from', 'Sewn From')}}
		{{Form::text('sewn_from', $doll->sewn_from, array('class'=>'form-control'))}}
	</div>
	<div class="form-group">
		{{Form::label('pet', 'Pet')}}
		{{Form::text('pet', $doll->pet, array('class'=>'form-control', 'placeholder'=>'If applicable'))}}
	</div>
	<div class="form-group">
		{{Form::label('link', 'Purchase Link')}}
		{{Form::text('link', $doll->link, array('class'=>'form-control', 'placeholder'=>'Amazon, Ebay, etc...'))}}
	</div>
	<div class="form-group">
		{{Form::label('thumbnail', 'Thumbnail')}}
		<img src="{{URL::asset('uploads/toys/_thumbs/225x265_')}}{{$doll->image}}" alt="{{$doll->title}}" class="thumbnail" />
		<br /><a href="#" class="btn edit-image">Recrop Thumbnail</a>
	</div>
	<div id="cropimage" style="display:none;">
		<img src="{{URL::asset('uploads/toys')}}/{{$doll->image}}" alt="{{$doll->title}}" id="crop" />
	</div>
	<div class="form-group textarea">
		{{Form::label('bio', 'Biography')}}
		{{Form::textarea('bio', $doll->bio, array('id'=>'bio'))}}
	</div>
	<div class="submit">
		{{Form::submit('Save Edits', array('class'=>'btn btn-primary'))}}
		<p><a href="{{URL::route('loopsy.show', array('id'=>$doll->slug))}}">View Loopsy</a></p>
	</div>

</div><!-- .container -->

{{Form::hidden('cropimage', '', array('id'=>'jcrop'))}}
{{Form::hidden('x', '', array('id'=>'x'))}}
{{Form::hidden('y', '', array('id'=>'y'))}}
{{Form::hidden('w', '', array('id'=>'w'))}}
{{Form::hidden('h', '', array('id'=>'h'))}}

{{Form::close()}}

@stop

@section('footer_content')

{{HTML::script('assets/js/jquery.Jcrop.js')}}

<script>
$(document).ready(function(){
	$('#bio').redactor();

	var jcrop_api;

	$('#crop').Jcrop({
			aspectRatio: 0.849056603774,
			onSelect: updateCoords,
			keySupport: false,
			boxWidth: 500,
			trueSize: [<?php echo $image_size[0]; ?>,<?php echo $image_size[1]; ?>]
		}, function(){
			jcrop_api = this;	
		});
	function updateCoords(c){
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	};
});
// Image Cropping
$('.edit-image').on('click', function(e){
	e.preventDefault();
	if ( $('#cropimage').is(':visible') ){
		$(this).text('Recrop');
		$('#jcrop').val('');
		$('#cropimage').hide();
	} else {
		$(this).text('Cancel Crop')
		$('#jcrop').val('1');
		$('#cropimage').show();
	}
});
</script>
@stop