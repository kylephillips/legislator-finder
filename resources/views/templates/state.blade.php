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
				@if( $chamber == 'senator' )
				Senator
				@else
				Representative
				@endif
				{{ $legislator['name'] }}
			</h1>

			@if( $chamber == 'senator' )
				<h3>{{$legislator['party']}}, Senate District {{$district_number}}</h3>
			@else
				<h3>{{$legislator['party']}}, House District {{$district_number}}</h3>
			@endif
			
			<ul>
				@foreach( $legislator['channels'] as $channel )

					@if( $channel['type'] == "Twitter" )
					<li><a href="http://twitter.com/{{ $channel['id'] }}" target="_blank"><i class="icon-twitter"></i></a></li>
					@endif

					@if( $channel['type'] == "Facebook" )
					<li><a href="http://facebook.com/{{ $channel['id'] }}" target="_blank"><i class="icon-facebook"></i></a></li>
					@endif

					@if( $channel['type'] == "YouTube" )
					<li><a href="http://youtube.com/{{ $channel['id'] }}" target="_blank"><i class="icon-youtube"></i></a></li>
					@endif

				@endforeach
				
				@if( $legislator['urls'] )
				@foreach( $legislator['urls'] as $url )
				<li class="website"><a href="{{ $url }}" target="_blank">Website</a></li>
				@endforeach
				@endif
			</ul>
			@foreach( $legislator['address'] as $add )
			<p>{!! \Str::address($add) !!}</p>
			@endforeach
			
		</article>
		
		<span>
			<?php $photo = ( isset($legislator['photoUrl']) ) ? $legislator['photoUrl'] : 'assets/images/leg-not-found.png'; ?>
			<img src="{{ $photo }}" alt="{{ $legislator['name'] }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
			{!! \Str::party_snipe($legislator['party']) !!}
		</span>
		
	</section>
	
	
	<section class="map">
		<h3>
			@if($chamber == "senator")
			Senate District {{ $district_number }}
			@else
			House District {{ $district_number }}
			@endif
		</h3>
		<div id="mapcont"></div>
	</section>
	
</div>{{-- Container --}}

<script>
	$(document).ready(function(){
		new StateDistrictMap("{{ csrf_token() }}", "{{ $chamber }}", "{{ $location->state }}", "{{ $district_number }}", $('#mapcont'));
	});
</script>

@stop