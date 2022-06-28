@extends('frontend_master')
@section('content')

<div class="search-page">

<div class="page-loading" data-loading>
	<div>
		<p>
			<i class="icon-spinner"></i><br />
			Finding Your Legislators
		</p>
	</div>
</div>

<div class="address-form-container">	
		
		<h1>Find Your Legislators</h1>
		@if ( is_array($errors) && count($errors) > 0)
			@if ( is_object($errors) )
				<div class="alert alert-info">{{$errors->first()}}</a></div>
			@else
				<div class="alert alert-info">{{$errors}}</a></div>
			@endif
		@endif

		@if ( is_string($errors) )
		<div class="alert alert-info">{{$errors}}</a></div>
		@endif
		
		<form method="POST" action="{{ route('results') }}" class="address-form" data-address-form="true">
			@method('POST')
			<div id="addresserror" class="alert alert-error" style="display:none" data-error></div>
			<div class="form-input">
				@csrf
				<input type="text" id="address" name="address" data-address-input  value="{{ old('address') }}" />
				<button type="submit" data-address-submit><i class="icon-arrow_forward"></i></button>
				<input type="hidden" name="latitude" id="latitude" data-latitude-input>
				<input type="hidden" name="longitude" id="longitude" data-longitude-input>
				<input type="hidden" name="formatted_address" id="formatted_address" data-formatted-address-input>
			</div>
		</form>
	
</div><!-- address-form-container -->

</div><!-- .search-page -->
<script>
	$(document).ready(function(){
		$('[data-address-input]').focus();
	});
</script>
@stop