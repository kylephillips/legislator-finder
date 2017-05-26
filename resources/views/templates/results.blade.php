@extends('frontend_master')

@section('content')
<nav>
	<ul>
		<li><a href="{{URL::to('/')}}">New Search</a></li>
	</ul>
</nav>

<div class="container results">

	@if($noaddress == true)
		<div class="alert alert-info">Legislators could not be found. <a href="{{URL::to('/')}}">Please try again.</a></div>
	@else	

	{{-- Federal Legislator Results --}}
	@if($locale == "federal")
		<h1>Federal Legislators</h1>
		<h3>for {{$formatted_address}}</h3>
		<ul class="federal">
			{{-- Federal Senators --}}
			@foreach( $legislators->senate->senators as $senator)
			<li>
				<a href="{{ url('federal') }}/{{ $senator['slug'] }}">
					<h3><em>{{ $legislators->location->state_name }}</em> Senator</h3>
					<span>
						<img src="{!! $senator['photoUrl'] !!}" alt="{{ $senator['name'] }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
						{!! \Str::party_snipe($senator['party']) !!}
					</span>
					<p>{!! $senator['name'] !!}</p>
					<div class="btn btn-block">View Details</div>
				</a>
			</li>
			@endforeach
			
			{{-- Federal Representatives --}}
			@foreach( $legislators->house->representatives as $representative)
			<li>
				<a href="{{ url('federal') }}/{{ $representative['slug'] }}">
					<h3><em>District {{ $legislators->location->house_district_number }}</em> Representative</h3>
					<span>
						<img src="{!! $representative['photoUrl'] !!}" alt="{{ $representative['name'] }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
						{!! \Str::party_snipe($representative['party']) !!}
					</span>
					<p>{{ $representative['name'] }}</p>
					<div class="btn btn-block">View Details</div>
				</a>
			</li>
			@endforeach	
		</ul>

	@else
		{{-- State Legislators --}}
		<h1>State Legislators</h1>
		<h3>for {{$formatted_address}}</h3>
		<ul class="two">
			{{-- State Senator --}}
			@foreach($legislators->senate->senators as $senator)
			<li>
				<a href="{{ url('state') }}/{{ $senator['slug'] }}">
					<h3><em>District {{ $legislators->location->senate_district_number }}</em> Senator</h3>
					<span>
						<img src="{{ $senator['photoUrl'] }}" alt="{{ $senator['name'] }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
						{!! \Str::party_snipe($senator['party']) !!}
					</span>
					<p>{{ $senator['name'] }}</p>
					<div class="btn btn-block">View Details</div>
				</a>
			</li>
			@endforeach
			
			{{-- State Represenatative --}}
			@foreach($legislators->house->representatives as $representative)
			<li>
				<a href="{{ url('state') }}/{{ $representative['slug'] }}">
					<h3><em>District {{ $legislators->location->house_district_number }}</em> Representative</h3>
					<span>
						<img src="{{ $representative['photoUrl'] }}" alt="{{ $representative['name'] }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
						{!! \Str::party_snipe($representative['party']) !!}
					</span>
					<p>{{ $representative['name'] }}</p>
					<div class="btn btn-block">View Details</div>
				</a>
			</li>
			@endforeach
		</ul>
	@endif{{-- Locale Type --}}

	@endif {{-- If form submitted --}}
		
</div>

@stop