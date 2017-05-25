@extends('frontend_master')

@section('content')

<div class="page-loading" data-loading>
	<div>
		<p>
			<img src="{{asset('assets/images/loading.gif')}}" alt="loading"><br />
			Finding Your Legislators
		</p>
	</div>
</div>


<div class="container small home">

	{{-- <div style="width:100%;height:500px;" id="test-map"></div> --}}
	<script>

	$(document).ready(function(){
		var cd = '04'; // The Congressional District to Load
		// loadGeoJson(cd);	
	});
	
	function loadGeoJson(cd)
	{
		var geoJson = '{{asset('assets/js/congressional_districts_115.geojson')}}';
		$.getJSON(geoJson, function(data){
			var layers = data.features;
			$.each(layers, function(key, val){
				// See : https://en.wikipedia.org/wiki/Federal_Information_Processing_Standard_state_code
				if ( val.properties.STATEFP === '13' && val.properties.CD115FP === '07' ) initMap(val);
			});
		});
	}

	function initMap(json)
	{
		var map;
		map = new google.maps.Map(document.getElementById('test-map'),{
			zoom: 4,
			center: {lat: 35.1825687, lng: -100.0268953}
		});
		var geoJson = {
			type: 'FeatureCollection',
			features : [
				json
			]
		}

		map.data.addGeoJson(geoJson);
		map.data.setStyle({
			fillColor: '#151442',
			strokeWeight: 0
		});

		// Zoom and fit the map to the congressional boundary
		var bounds = new google.maps.LatLngBounds(); 
		map.data.forEach(function(feature){
			feature.getGeometry().forEachLatLng(function(latlng){
				bounds.extend(latlng);
			});
		});
		map.fitBounds(bounds);
	}
	</script>

	{{Form::open(['url'=>'results/', 'id'=>'addressform', 'data-address-form' => true])}}
		
		<h1>Find Your Legislators</h1>

		<div class="locale-select">
			<span class="switch"></span>
			<ul>
				<li><a href="#federal" class="active">Federal</a></li>
				<li><a href="#state">State</a></li>
			</ul>
		</div>
		
		<p class="locale-radios">
			<label><input type="radio" id="federal" name="locale" value="federal" checked> Federal Level</label><br />
			<label><input type="radio" id="state" name="locale" value="state"> State Level</label>
		</p>
		
		<a href="#" style="display:none;" class="btn btn-red" data-geolocation-button>
			<i class="icon-near_me"></i> Use My Location
		</a>
		
		<section class="by-address">
			<div id="addresserror" class="alert alert-error" style="display:none" data-error></div>
			<p>
				<label for="address">Address</label>
				<input type="text" id="address" name="address" data-address-input />
			</p>
			<input type="hidden" name="latitude" id="latitude" data-latitude-input>
			<input type="hidden" name="longitude" id="longitude" data-longitude-input>
			<input type="hidden" name="formatted_address" id="formatted_address" data-formatted-address-input>
			<button type="submit" class="btn btn-red" data-address-submit>Use my Address</button>
		</section><!-- By Address -->
	{{Form::close()}}
	
</div><!-- Container -->


@stop