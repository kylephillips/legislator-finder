@extends('frontend_master')

@section('content')

<div class="page-loading">
	<div>
		<p>
			<img src="{{asset('assets/images/loading.gif')}}" alt="loading"><br />
			Finding Your Legislators
		</p>
	</div>
</div>


<div class="container small home">

	<div style="width:100%;height:500px;" id="test-map"></div>
	<script>

	$(document).ready(function(){
		var cd = '04'; // The Congressional District to Load
		loadGeoJson(cd);	
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

	{{Form::open(array('url'=>'results/','id'=>'addressform'))}}
		
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
		
		<a href="#" id="getcoordinates" style="display:none;" class="btn btn-red">
			<i class="icon-near_me"></i> Use My Location
		</a>
		
		<section class="by-address">
			<div id="addresserror" class="alert alert-error" style="display:none">
				The address entered could not be found.
			</div>
		
			<p>
				{{Form::label('streetaddress', 'Street Address')}}
				{{Form::text('streetaddress')}}
			</p>
			<p>
				{{Form::label('city', 'City')}}
				{{Form::text('city')}}
			</p>
			<p class="half">
				{{Form::label('state')}}
				<span class="select">
					{{Form::select('state', 
						array(
							''=>"Select a State",
							'AL'=>"Alabama",  
							'AK'=>"Alaska",  
							'AZ'=>"Arizona",  
							'AR'=>"Arkansas",  
							'CA'=>"California",  
							'CO'=>"Colorado",  
							'CT'=>"Connecticut",  
							'DE'=>"Delaware",  
							'DC'=>"District Of Columbia",  
							'FL'=>"Florida",  
							'GA'=>"Georgia",  
							'HI'=>"Hawaii",  
							'ID'=>"Idaho",  
							'IL'=>"Illinois",  
							'IN'=>"Indiana",  
							'IA'=>"Iowa",  
							'KS'=>"Kansas",  
							'KY'=>"Kentucky",  
							'LA'=>"Louisiana",  
							'ME'=>"Maine",  
							'MD'=>"Maryland",  
							'MA'=>"Massachusetts",  
							'MI'=>"Michigan",  
							'MN'=>"Minnesota",  
							'MS'=>"Mississippi",  
							'MO'=>"Missouri",  
							'MT'=>"Montana",
							'NE'=>"Nebraska",
							'NV'=>"Nevada",
							'NH'=>"New Hampshire",
							'NJ'=>"New Jersey",
							'NM'=>"New Mexico",
							'NY'=>"New York",
							'NC'=>"North Carolina",
							'ND'=>"North Dakota",
							'OH'=>"Ohio",  
							'OK'=>"Oklahoma",  
							'OR'=>"Oregon",  
							'PA'=>"Pennsylvania",  
							'RI'=>"Rhode Island",  
							'SC'=>"South Carolina",  
							'SD'=>"South Dakota",
							'TN'=>"Tennessee",  
							'TX'=>"Texas",  
							'UT'=>"Utah",  
							'VT'=>"Vermont",  
							'VA'=>"Virginia",  
							'WA'=>"Washington",  
							'WV'=>"West Virginia",  
							'WI'=>"Wisconsin",  
							'WY'=>"Wyoming"
						),
						null,
						array('id'=>'addstate'))}}
				<i class="icon-caret-down"></i>
				</span>
			</p>
			<p class="half right">
				{{Form::label('zip')}}
				{{Form::text('zip')}}
			</p>
			{{Form::hidden('latitude', null, array('id'=>'latitude'))}}
			{{Form::hidden('longitude', null, array('id'=>'longitude'))}}
			{{Form::hidden('formatted_address', null, array('id'=>'formatted_address'))}}
			<button type="submit" class="btn btn-red">Use my Address</button>
		</section><!-- By Address -->
	{{Form::close()}}
	
</div><!-- Container -->


@stop