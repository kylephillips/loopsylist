@extends('partials.master')
@section('title', 'Loopsy List – My Account')
@section('content')
My Account page
<?php $hashed_email = md5(strtolower(trim(Auth::user()->email))); ?>
<img src="http://www.gravatar.com/avatar/{{$hashed_email}}" />
@stop
@section('footer_content')
@stop