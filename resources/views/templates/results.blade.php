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
		<?php $photo = ( isset($senator->photoUrl) ) ? $senator->photoUrl : null; ?>
		<li>
			<a href="{{ url('federal') }}/senator/{{ $senator->slug }}">	
				<div class="party {!! \Str::party_class($senator->party) !!}"></div>
				<span class="party-letter">{!! \Str::party_letter($senator->party) !!}</span>
				@if($photo)
				<div class="thumbnail">
					<i class="icon-spinner"></i>
					<img src="{!! $photo !!}" alt="{{ $senator->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
				</div>
				@else
				<div class="thumbnail no-photo">
					<span>{!! \Str::initials($senator->name) !!}</span>
				</div>
				@endif
				<h3><em>{{ $federal_legislators->location->state_name }}</em> Senator</h3>
				<p>{!! $senator->name !!}</p>
				<div class="btn btn-block">View Details</div>
			</a>
		</li>
		@endforeach
		
		{{-- Federal Representatives --}}
		@foreach( $federal_legislators->house->representatives as $representative)
		<?php $photo = ( isset($representative->photoUrl) ) ? $representative->photoUrl : null; ?>
		<li>
			<a href="{{ url('federal') }}/representative/{{ $representative->slug }}">
				<div class="party {!! \Str::party_class($representative->party) !!}"></div>
				<span class="party-letter">{!! \Str::party_letter($representative->party) !!}</span>
				@if($photo)
				<div class="thumbnail">
					<i class="icon-spinner"></i>
					<img src="{!! $photo !!}" alt="{{ $representative->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
				</div>
				@else
				<div class="thumbnail no-photo">
					<span>{!! \Str::initials($representative->name) !!}</span>
				</div>
				@endif
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
		<?php $photo = ( isset($senator->photoUrl) ) ? $senator->photoUrl : null; ?>
		<li>
			<a href="{{ url('state') }}/senator/{{ $senator->slug }}">
				<div class="party {!! \Str::party_class($senator->party) !!}"></div>
				<span class="party-letter">{!! \Str::party_letter($senator->party) !!}</span>
				@if($photo)
				<div class="thumbnail">
					<i class="icon-spinner"></i>
					<img src="{!! $photo !!}" alt="{{ $senator->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
				</div>
				@else
				<div class="thumbnail no-photo">
					<span>{!! \Str::initials($senator->name) !!}</span>
				</div>
				@endif
				<h3><em>District {{ $state_legislators->location->senate_district_number }}</em> Senator</h3>
				<p>{{ $senator->name }}</p>
				<div class="btn btn-block">View Details</div>
			</a>
		</li>
		@endforeach
		
		{{-- State Represenatative --}}
		@foreach($state_legislators->house->representatives as $representative)
		<?php $photo = ( isset($representative->photoUrl) ) ? $representative->photoUrl : null; ?>
		<li>
			<a href="{{ url('state') }}/representative/{{ $representative->slug }}">
				<div class="party {!! \Str::party_class($representative->party) !!}"></div>
				<span class="party-letter">{!! \Str::party_letter($representative->party) !!}</span>
				@if($photo)
				<div class="thumbnail">
					<i class="icon-spinner"></i>
					<img src="{!! $photo !!}" alt="{{ $representative->name }}" onerror="this.src='{{ asset('assets/images/') }}/leg-not-found.png'" />
				</div>
				@else
				<div class="thumbnail no-photo">
					<span>{!! \Str::initials($representative->name) !!}</span>
				</div>
				@endif
				<h3><em>District {{ $state_legislators->location->house_district_number }}</em> Representative</h3>
				<p>{{ $representative->name }}</p>
				<div class="btn btn-block">View Details</div>
			</a>
		</li>
		@endforeach
	</ul>
	</div><!-- .legislators -->
	@endif

	@if( !empty($other_officials) )
	<div class="other-officials">
		<h2>Other Officials</h2>
		<div class="inner">
		@foreach($other_officials as $division => $offices)
		<h4>{{ $division }}</h4>
		<ul class="official-list">
			@foreach($offices as $office)
			@if( isset($office->official->photoUrl) )
			<li class="has-thumbnail">
				<div class="thumbnail">
					<img src="{{ $office->official->photoUrl }}" alt="{{ $office->official->name }}" />
				</div>
			@else
			<li>
			@endif
				<h5>{{ $office->office_name }}</h5>
				<h6>{{ $office->official->name }} ({{ $office->official->party }})</h6>
				@foreach($office->official->address as $address)
				<p>
					{{ $address->line1 }}
					@if( isset($address->line2) ) <br>{{$address->line2}} @endif @if( isset($address->city) ) @endif @if( isset($address->state) ) {{ $address->state }} @endif @if( isset($address->zip) ) {{ $address->zip }} @endif
				</p>
				@endforeach
				@if(isset($office->official->channels))
				<ul class="channels">
					@foreach( $office->official->channels as $channel )

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
				</ul>
				@endif
			</li>
			@endforeach
		</ul>
		@endforeach
		</div><!-- .inner -->
	</div>
	@endif

</div><!-- .container -->
</div><!-- .results -->

@stop