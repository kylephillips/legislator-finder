@extends('frontend_master')

@section('content')
<div class="results">
<div class="container">
	<nav>
		<ul>
			<li><a href="{{URL::to('/')}}">New Search</a></li>
		</ul>
	</nav>

	<div class="address">
		<h1>Your Legislators</h1>
		<h3>{{$formatted_address}}</h3>
	</div>
	<div class="legislators">
	<ul class="legislator-list federal">
		{{-- Federal Senators --}}
		@foreach( $federal_legislators->senate->senators as $key => $senator)
		<?php $photo = ( isset($senator->photoUrl) ) ? $senator->photoUrl : 'assets/images/leg-not-found.png'; ?>
		<li>
			<a href="{{ url('federal') }}/senator/{{ $senator->slug }}">	
				<div class="party {!! \Str::party_class($senator->party) !!}"></div>
				<span class="party-letter">{!! \Str::party_letter($senator->party) !!}</span>
				<div class="thumbnail">
					<i class="icon-spinner"></i>
					<img src="{!! $photo !!}" alt="{{ $senator->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
				</div>
				<h3><em>{{ $federal_legislators->location->state_name }}</em> Senator</h3>
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
				<div class="party {!! \Str::party_class($representative->party) !!}"></div>
				<span class="party-letter">{!! \Str::party_letter($representative->party) !!}</span>
				<div class="thumbnail">
					<i class="icon-spinner"></i>
					<img src="{!! $photo !!}" alt="{{ $representative->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
				</div>
				<h3><em>House District {{ $federal_legislators->location->house_district_number }}</em> Representative</h3>
				<p>{{ $representative->name }}</p>
				<div class="btn btn-block">View Details</div>
			</a>
		</li>
		@endforeach	
	</ul>

	@if ( !empty($state_legislators->senate->senators) || !empty($state_legislators->house->representatives) )
	{{-- State Legislators --}}
	<h2>{{ $state_name }} Legislators</h2>
	<ul class="legislator-list inline">
		{{-- State Senator --}}
		@foreach($state_legislators->senate->senators as $senator)
		<li>
			<a href="{{ url('state') }}/senator/{{ $senator->slug }}">
				<div class="party {!! \Str::party_class($senator->party) !!}"></div>
				<span class="party-letter">{!! \Str::party_letter($representative->party) !!}</span>
				<div class="thumbnail">
					<i class="icon-spinner"></i>
					<img src="{{ $senator->photoUrl }}" alt="{{ $senator->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
				</div>
				<h3><em>District {{ $state_legislators->location->senate_district_number }}</em> Senator</h3>
				<p>{{ $senator->name }}</p>
				<div class="btn btn-block">View Details</div>
			</a>
		</li>
		@endforeach
		
		{{-- State Represenatative --}}
		@foreach($state_legislators->house->representatives as $representative)
		<li>
			<a href="{{ url('state') }}/representative/{{ $representative->slug }}">
				<div class="party {!! \Str::party_class($senator->party) !!}"></div>
				<span class="party-letter">{!! \Str::party_letter($representative->party) !!}</span>
				<div class="thumbnail">
					<i class="icon-spinner"></i>
					<img src="{{ $representative->photoUrl }}" alt="{{ $representative->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
				</div>
				<h3><em>District {{ $state_legislators->location->house_district_number }}</em> Representative</h3>
				<p>{{ $representative->name }}</p>
				<div class="btn btn-block">View Details</div>
			</a>
		</li>
		@endforeach
	</ul>
	</div><!-- .legislators -->
	@endif
</div>
</div>

@stop