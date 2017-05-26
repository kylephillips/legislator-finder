@extends('frontend_master')

@section('content')

<div class="page-loading" data-loading>
	<div>
		<p>
			<img src="{{asset('assets/images/loading.gif')}}" alt="loading"><br />
			Finding Your Legislators
		</p>
	</div>
</div>


<div class="container small home">

	{{Form::open(['url'=>'results/', 'id'=>'addressform', 'data-address-form' => true])}}
		
		<h1>Find Your Legislators</h1>

		@if (count($errors) > 0)
			@if ( is_object($errors) )
				<div class="alert alert-info">{{$errors->first()}}</a></div>
			@else
				<div class="alert alert-info">{{$errors}}</a></div>
			@endif
		@endif

		<div class="locale-select" data-locale-select>
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
		
		<a href="#" style="display:none;" class="btn btn-red" data-geolocation-button>
			<i class="icon-near_me"></i> Use My Location
		</a>
		
		<section class="by-address">
			<div id="addresserror" class="alert alert-error" style="display:none" data-error></div>
			<p>
				<label for="address">Address</label>
				<input type="text" id="address" name="address" data-address-input />
			</p>
			<input type="hidden" name="latitude" id="latitude" data-latitude-input>
			<input type="hidden" name="longitude" id="longitude" data-longitude-input>
			<input type="hidden" name="formatted_address" id="formatted_address" data-formatted-address-input>
			<button type="submit" class="btn btn-red" data-address-submit>Use my Address</button>
		</section><!-- By Address -->
	{{Form::close()}}
	
</div><!-- Container -->

@stop