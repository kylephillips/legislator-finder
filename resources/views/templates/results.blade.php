@extends('frontend_master')

@section('content')
<nav>
	<ul>
		<li><a href="{{URL::to('/')}}">New Search</a></li>
	</ul>
</nav>

<div class="container results">

	@if($noaddress == true)
		<div class="alert alert-info">
			Legislators could not be found. <a href="{{URL::to('/')}}">Please try again.</a>
		</div>
	@else	

	{{-- Federal Legislator Results --}}
	@if($locale == "federal")
		<h1>Federal Legislators</h1>
		<h3>for {{$formatted_address}}</h3>
		<ul class="federal">
		
		{{-- Federal Senators --}}
		@foreach($legislators as $legislator)

			@if( $legislator['chamber'] == "senate")
			<li>
				<a href="{{ url('federal') }}/{{ $legislator['bioguide_id'] }}">
					<h3><em>{{ $legislator['state_name'] }}</em> Senator</h3>
					<span>
						<img src="{{asset('assets/images/')}}/federal-legislators/{{$legislator['bioguide_id']}}.jpg" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
						{{ \Str::party_snipe($legislator) }}
					</span>
					<p>{{ \Str::legislator_name($legislator) }}</p>
					<div>View Details</div>
				</a>
			</li>
			@endif
		@endforeach
		
		{{-- Federal Representatives --}}
		@foreach($legislators as $legislator)
			@if($legislator['chamber'] == "house")
			<li>
				<a href="{{ url('federal') }}/{{ $legislator['bioguide_id'] }}">
					<h3><em>District {{$legislator['district']}}</em> Representative</h3>
					<span>
						<img src="{{asset('assets/images/')}}/federal-legislators/{{$legislator['bioguide_id']}}.jpg" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
						{{ \Str::party_snipe($legislator) }}
					</span>
					<p>{{ \Str::legislator_name($legislator) }}</p>
					<div>View Details</div>
				</a>
			</li>
			@endif
		@endforeach
	
		</ul>

	@else
		{{-- State Legislators --}}
		<h1>State Legislators</h1>
		<h3>for {{$formatted_address}}</h3>
		<ul class="two">
			
			{{-- State Senators --}}
			@foreach($legislators as $legislator)
				@if( $legislator['chamber'] == "upper")
				<li>
					<a href="{{ url('state') }}/{{$legislator['id']}}">
						<h3><em>District {{ $legislator['district'] }}</em> Senator</h3>
						<span>
							<img src="{{$legislator['photo_url']}}" alt="<?php echo $legislator['full_name']; ?>" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
							{{ \Str::party_snipe_state($legislator) }}
						</span>
						<p>{{ $legislator['full_name'] }}</p>
						<div>View Details</div>
					</a>
				</li>
				@endif
			@endforeach
			
			{{-- State Represenatative --}}
			@foreach($legislators as $legislator)
				@if( $legislator['chamber'] == "lower")
				<li>
					<a href="{{ url('state') }}/{{$legislator['id']}}">
						<h3><em>District {{$legislator['district']}}</em> Representative</h3>
						<span>
							<img src="{{$legislator['photo_url']}}" alt="<?php echo $legislator['full_name']; ?>" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
							{{ \Str::party_snipe_state($legislator) }}
						</span>
						<p>{{ $legislator['full_name'] }}</p>
						<div>View Details</div>
					</a>
				</li>
				@endif
			@endforeach
			
		</ul>
	@endif{{-- Locale Type --}}

	@endif {{-- If form submitted --}}
		
</div>

@stop