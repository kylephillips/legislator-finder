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
				@if( $legislator['chamber'] == "upper" )
				Senator
				@else
				Representative
				@endif
				{{$legislator['full_name']}}
			</h1>
			<h3>{{$legislator['party']}}, District {{$legislator['district']}}</h3>
			<p><strong>Current Term: </strong>{{$current_term['term']}}</p>
			
			@foreach($offices as $office){{-- Show all offices --}}
			<p>
				<strong>{{$office['name']}}</strong><br />
				@if($office['phone'] != "")
					<?php
					$number = $office['phone'];
					$formatted_number = preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
					echo 'Phone: ' . $formatted_number;
					?>
				@endif
				@if($office['email'] != "")
				<br /><a href="mailto:{{$office['email']}}">Email this office</a>
				@endif
			</p>
			@endforeach
			
			<ul>
				@if ( $legislator['url'] !== "" )
				<li class="website"><a href="{{$legislator['url']}}" target="_blank">Website</a></li>
				@endif
			</ul>
			
		</article>
		
		<span>
			<img src="{{$legislator['photo_url']}}" alt="<?php echo $legislator['full_name']; ?>" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
			{!! \Str::party_snipe_state($legislator) !!}
		</span>
		
	</section>
	
	
	<section class="committees">
		<h3>Committees <span>show <i class="icon-angle-down"></i></span></h3>
		<ul>
			@foreach($committees as $key=>$comm)
				@if ( $key > 0 ){{-- First item isn't committee --}}
				<li>
					<a href="http://openstates.org/{{$comm['state']}}/committees/{{$comm['committee_id']}}" target="_blank">{{$comm['committee']}} <em>({{$comm['position']}})</em></a></li>
				</li>
				@endif
			@endforeach
		</ul>
	</section>
	
	
	<section class="map">
		<h3>
			@if($legislator['chamber'] == "upper")
			Senate
			@else
			House
			@endif
			District {{$legislator['district']}}
		</h3>
		<div id="mapcont"></div>
	</section>
	
	
</div>{{-- Container --}}

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>
	
	$(document).ready(function(){
		
		var map = new google.maps.Map(document.getElementById('mapcont'), {
			center: new google.maps.LatLng({{$center_lat}}, {{$center_lon}}),
			mapTypeID: google.maps.MapTypeId.ROADMAP,
			zoom: 9,
			mapTypeControl: false,
			scaleControl: true,
			styles : {!! \Str::map_styles() !!}
		});
		
		var districtpoly;
		
		var coordinates = [	
			<?php
			$i = 0;
			$total_coordinates = count($coordinates) - 1;
			foreach ( $coordinates as $coordinate ){
				echo "new google.maps.LatLng(" . $coordinate[1] . ", " . $coordinate[0] . ")";
				if ( $i <  $total_coordinates) { echo ",\n"; }
				$i++;
			}
			?>
		];
		
		// create the polygon using the coordinates
		var districtpoly = new google.maps.Polygon({
			paths: coordinates,
			strokeColor: '#1bbc9b',
			strokeOpacity: '0.5',
			strokeWeight: 1,
			fillColor: '#1bbc9b',
			fillOpacity: '0.3'
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
		
	});

</script>

@stop