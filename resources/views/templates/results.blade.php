@extends('frontend_master')

@section('content')
<nav>
	<ul>
		<li><a href="{{URL::to('/')}}">New Search</a></li>
	</ul>
</nav>

<div class="container results">
	<h1>Your Legislators</h1>
	<h3>for {{$formatted_address}}</h3>
	<hr>
	<h2>Federal Legislators</h2>
	<ul class="federal">
		{{-- Federal Senators --}}
		@foreach( $federal_legislators->senate->senators as $key => $senator)
		<?php $photo = ( isset($senator->photoUrl) ) ? $senator->photoUrl : 'assets/images/leg-not-found.png'; ?>
		<li>
			<a href="{{ url('federal') }}/senator/{{ $senator->slug }}">
				<h3><em>{{ $federal_legislators->location->state_name }}</em> Senator</h3>
				<span>
					<img src="{!! $photo !!}" alt="{{ $senator->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
					{!! \Str::party_snipe($senator->party) !!}
				</span>
				<p>{!! $senator->name !!}</p>
				<div class="btn btn-block">View Details</div>
			</a>
		</li>
		@endforeach
		
		{{-- Federal Representatives --}}
		@foreach( $federal_legislators->house->representatives as $representative)
		<?php $photo = ( isset($representative->photoUrl) ) ? $representative->photoUrl : 'assets/images/leg-not-found.png'; ?>
		<li>
			<a href="{{ url('federal') }}/representative/{{ $representative->slug }}">
				<h3><em>House District {{ $federal_legislators->location->house_district_number }}</em> Representative</h3>
				<span>
					<img src="{!! $photo !!}" alt="{{ $representative->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
					{!! \Str::party_snipe($representative->party) !!}
				</span>
				<p>{{ $representative->name }}</p>
				<div class="btn btn-block">View Details</div>
			</a>
		</li>
		@endforeach	
	</ul>
	<hr>
	{{-- State Legislators --}}
	<h2>State Legislators</h>
	<ul class="two">
		{{-- State Senator --}}
		@foreach($state_legislators->senate->senators as $senator)
		<li>
			<a href="{{ url('state') }}/senator/{{ $senator->slug }}">
				<h3><em>District {{ $state_legislators->location->senate_district_number }}</em> Senator</h3>
				<span>
					<img src="{{ $senator->photoUrl }}" alt="{{ $senator->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
					{!! \Str::party_snipe($senator->party) !!}
				</span>
				<p>{{ $senator->name }}</p>
				<div class="btn btn-block">View Details</div>
			</a>
		</li>
		@endforeach
		
		{{-- State Represenatative --}}
		@foreach($state_legislators->house->representatives as $representative)
		<li>
			<a href="{{ url('state') }}/representative/{{ $representative->slug }}">
				<h3><em>District {{ $state_legislators->location->house_district_number }}</em> Representative</h3>
				<span>
					<img src="{{ $representative->photoUrl }}" alt="{{ $representative->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
					{!! \Str::party_snipe($representative->party) !!}
				</span>
				<p>{{ $representative->name }}</p>
				<div class="btn btn-block">View Details</div>
			</a>
		</li>
		@endforeach
	</ul>		
</div>

@stop