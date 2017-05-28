<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Legislator Finder: Find Your Senators &amp; Representatives</title>	
	<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
	<link rel="stylesheet" href="/assets/css/styles.css?v=1.0">
	<script type="text/javascript" src="//use.typekit.net/nxo4tar.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{env('GOOGLE_MAPS_KEY')}}&libraries=places"></script>
	@include('includes.scripts')
</head>
<body>
	@yield('content')
	<div class="data-sources">Data provided by <a href="https://developers.google.com/civic-information/">Google</a>, <a href="https://openstates.org/">Open States</a> and <a href="http://census.gov">census.gov</a>. &copy;{{ date('Y') }} <a href="https://github.com/kylephillips">Kyle Phillips</a></div>
</body>
</html>