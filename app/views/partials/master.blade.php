<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<title>@yield('title','Tenth Rep')</title>
	
	<link rel="shortcut icon" href="assets/favicon.ico">
	
	{{HTML::style('/assets/css/styles.css')}}
	
	<!-- Typekit -->
	<script type="text/javascript" src="//use.typekit.net/vmv4gsq.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js?ver=3.8'></script>
</head>

<body class="">
	
	<div id="page-wrap">
	@yield('content')

	@yield('footer_content')
	</div><!-- #page-wrap -->

	{{HTML::script('assets/js/scripts.js')}}

</body>
</html>