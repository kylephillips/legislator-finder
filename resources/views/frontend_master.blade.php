<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Legislator Finder: Find Your Senators &amp; Representatives</title>	
	<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
	{{Html::style('assets/css/styles.css')}}
	<script type="text/javascript" src="//use.typekit.net/nxo4tar.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{env('GOOGLE_MAPS_KEY')}}"></script>
	@include('includes.scripts')
</head>
<body>
	@yield('content')
	<div class="sunlight">Data provided by <a href="https://openstates.org/">Open States</a></div>
</body>
</html>