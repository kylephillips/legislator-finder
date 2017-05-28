@extends('frontend_master')
@section('content')

<nav>
	<ul>
		<li><a href="{{URL::to('/')}}">New Search</a></li>
		<li><a href="{{URL::to('/results')}}" class="back">Back to Results</a></li>
	</ul>
</nav>

<div class="container">
	<div class="legislator-overview">
	<h1>
		@if($chamber == "senator")
		Senator
		@else
		Representative
		@endif
		{{ $legislator->name }}
	</h1>
	</div>
	<section class="legislator-details">
		
		<div class="legislator-card">
			<div class="party-background {{ \Str::party_class($legislator->party) }}"></div>
			<div class="thumbnail">
				<?php $photo = ( isset($legislator->photoUrl) ) ? $legislator->photoUrl : 'assets/images/leg-not-found.png'; ?>
				<img src="{{ $photo }}" alt="{{ $legislator->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
			</div>
			<h3>
				{{ $legislator->party }},
				@if($chamber == "senator")
				{{ $location->state_name }}
				@else
				House District {{ $location->house_district_number }}
				@endif
			</h3>	
			<ul>
				@foreach( $legislator->channels as $channel )

					@if( $channel->type == "Twitter" )
					<li><a href="http://twitter.com/{{ $channel->id }}" target="_blank"><i class="icon-twitter"></i></a></li>
					@endif

					@if( $channel->type == "Facebook" )
					<li><a href="http://facebook.com/{{ $channel->id }}" target="_blank"><i class="icon-facebook"></i></a></li>
					@endif

					@if( $channel->type == "YouTube" )
					<li><a href="http://youtube.com/{{ $channel->id }}" target="_blank"><i class="icon-youtube"></i></a></li>
					@endif

				@endforeach
				
				@if( $legislator->urls )
				@foreach( $legislator->urls as $url )
				<li class="website"><a href="{{ $url }}" target="_blank">Website</a></li>
				@endforeach
				@endif
			</ul>
			@foreach( $legislator->address as $add )
			<p>{!! \Str::address($add) !!}</p>
			@endforeach
			<p>{!! \Str::phones($legislator->phones) !!}</p>
		</div><!-- .legislator-card -->
		<section class="map">
			<div id="mapcont" class="loading"><i class="icon-spinner"></i></div>
		</section>
	</section>
	
</div>{{-- Container --}}

@if( $level == 'federal' )
<script>
$(document).ready(function(){
	@if($chamber == "senator")
		new StateMap("{{ $location->state }}", $('#mapcont'));
	@else
		new FederalHouseDistrictMap("{{ $location->house_district_number_formatted }}", "{{ $location->state }}", $('#mapcont'));
	@endif
});
</script>
@else
<script>
	$(document).ready(function(){
		new StateDistrictMap("{{ csrf_token() }}", "{{ $chamber }}", "{{ $location->state }}", "{{ $district_number }}", $('#mapcont'));
	});
</script>
@endif
@stop