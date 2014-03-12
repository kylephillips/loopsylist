<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<title>@yield('title','Loopsy List')</title>
	
	<link rel="shortcut icon" href="{{URL::asset('favicon.ico')}}">
	
	{{HTML::style('/assets/css/styles.css')}}
	
	<!-- Typekit -->
	<script type="text/javascript" src="//use.typekit.net/vmv4gsq.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js?ver=3.8'></script>

	@yield('head_content')
</head>

<body>
	
	<div id="modal-cont" class="modal">
		<div class="modal-body"></div>
		<div class="modal-close"><a href="#">Close</a></div>
	</div>

	<div id="mobile-nav">
	</div>

	<div id="page-wrap">
		<div class="content">
			@if(!isset($front_page))
			@include('partials.top')
			@endif

			@yield('content')

			@yield('footer_content')
		</div>
		@include('partials.footer')
	</div><!-- #page-wrap -->


	{{HTML::script('assets/js/scripts.js')}}

</body>
</html>