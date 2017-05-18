@extends('frontend_master')

@section('content')
<nav>
	<ul>
		<li><a href="{{URL::to('/')}}">New Search</a></li>
		<li><a href="#" class="back">Back to Results</a></li>
	</ul>
</nav>

<div class="container">
	<section class="legislator-details">
		<article>
			<h1>
				@if($legislator['chamber'] == "senate")
				Senator
				@else
				Representative
				@endif
				{{ \Str::legislator_name($legislator) }}
			</h1>
			<h3>
				{{ \Str::party_name($legislator) }},
				@if($legislator['chamber'] == "senate")
				{{$legislator['state_name']}}
				@else
				District {{$legislator['district']}}
				@endif
			</h3>
			<p>
				<strong>Entered:</strong> {{$term_start}}<br />
				<strong>Current Term Ends: </strong>{{$term_end}}
			</p>
			<ul>
				@if( $legislator['twitter_id'] != "" )
				<li><a href="http://twitter.com/{{$legislator['twitter_id']}}" target="_blank"><i class="icon-twitter"></i></a></li>
				@endif
				
				@if( $legislator['facebook_id'] != "" )
				<li><a href="http://facebook.com/{{$legislator['facebook_id']}}" target="_blank"><i class="icon-facebook"></i></a></li>
				@endif
				
				@if( $legislator['youtube_id'] != "" )
				<li><a href="http://youtube.com/{{$legislator['youtube_id']}}" target="_blank"><i class="icon-youtube"></i></a></li>
				@endif
				
				@if( $legislator['website'] != "" )
				<li class="website"><a href="{{$legislator['website']}}" target="_blank">Website</a></li>
				@endif
			</ul>
		</article>
		
		<span>
			<img src="{{asset('assets/images/')}}/federal-legislators/{{$legislator['bioguide_id']}}.jpg" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
			{!! \Str::party_snipe($legislator) !!}
		</span>
	</section>
	
	
	{{-- Map Area --}}
	@if($legislator['chamber'] == "senate")
	<h3>{{$legislator['state_name']}} Senator</h3>
	@else
	<h3>House District {{$legislator['district']}}</h3>
	@endif
		
	<section class="map">
		<div id="mapcont"></div>
	</section>
	
</div>{{-- Container --}}

@if($legislator['chamber'] == "house")
<script>
	$(document).ready(function(){
		
		get_district_boundaries();
		
		// Get the district boundaries
		function get_district_boundaries() {

			$.ajax({
				url : "http://gis.govtrack.us/boundaries/cd-2012/{{$district_map_id}}/shape",
				dataType: 'jsonp',
				success: function(data){
					
					var points = data.coordinates[0][0];
					
					var coordinates = []; // empty array to push coordinates into
					
					// Create gmaps polygon objects for each of the boundary coordinates, push into coordinates array
					for ( var i = 0; i < points.length; i++ ){
						coordinates.push(new google.maps.LatLng(points[i][1], points[i][0]));
					}
					
					// create the map
				    var map = new google.maps.Map(document.getElementById('mapcont'), {
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						mapTypeControl: false,
						streetViewControl: false,
						scaleControl : true,
						styles : {!! \Str::map_styles() !!}
				    });
					
					// create the polygon using the coordinates
					var districtpoly = new google.maps.Polygon({
						paths: coordinates,
						strokeColor: '#e84c3d',
						strokeOpacity: '0.5',
						strokeWeight: 1,
						fillColor: '#e84c3d',
						fillOpacity: '0.4'
					});
					
					// set the map for the polygon
					districtpoly.setMap(map);
					
					
					// Set the zoom to fit the polygon in the map area
					google.maps.Polygon.prototype.getBounds = function() {
					    var bounds = new google.maps.LatLngBounds();
					    var paths = this.getPaths();
					    var path;        
					    for (var i = 0; i < paths.getLength(); i++) {
					        path = paths.getAt(i);
					        for (var ii = 0; ii < path.getLength(); ii++) {
					            bounds.extend(path.getAt(ii));
					        }
					    }
					    return bounds;
					}
					map.fitBounds(districtpoly.getBounds());
					
				
				},
				error: function(data){
					// to do - add error state for map
				}
			});
		} // get_district_boundaries
		
	
	});	
</script>

@else {{-- It's a senate district --}}

<script>
	
	$(document).ready(function(){
		load_state_map();
		
		
		function load_state_map(){
			// get state center lat & long
			$.ajax({
				url : "{{asset('/assets/state-centers.json')}}",
				dataType : "json",
				success: function(data){
					for ( var i = 0; i < data.length; i++ ){
						if ( data[i].state == "{{$stateUpper}}"){
							var statelat = data[i].latitude;
							var statelong = data[i].longitude;
							
							drawstatemap(statelat, statelong);
							
						}
					}
				}
			});
		}
		
		
		function drawstatemap(statelat, statelong){
			var state = new google.maps.LatLng(statelat, statelong);

			map = new google.maps.Map(document.getElementById('mapcont'), {
			  center: state,
			  zoom: 6,
			  mapTypeId: 'roadmap',
			  styles : {!! \Str::map_styles() !!}
			});

			layer = new google.maps.FusionTablesLayer({
				styles: [{
				polygonOptions : {
					fillColor: "#1bbc9b",
					strokeColor: "#1bbc9b"
				}
				}],
				suppressInfoWindows: true
				});
			
			var StateName = "{{$stateUpper}}";
			
			layer.setQuery({
				select:'geometry',
				from:"17aT9Ud-YnGiXdXEJUyycH2ocUqreOeKGbzCkUw",
				where:"'id' = '"+StateName+"'"
			});
			
			layer.setMap(map);
		}
	});
	
</script>

@endif {{-- Chamber --}}

	
@stop