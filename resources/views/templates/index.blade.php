@extends('frontend_master')

@section('content')

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

		@if (count($errors) > 0)
			@if ( is_object($errors) )
				<div class="alert alert-info">{{$errors->first()}}</a></div>
			@else
				<div class="alert alert-info">{{$errors}}</a></div>
			@endif
		@endif
		
		{{Form::open(['url'=>'results/', 'class' => 'address-form', 'data-address-form' => true])}}
			<div id="addresserror" class="alert alert-error" style="display:none" data-error></div>
			<label for="address">Enter Your Address</label>
			<div class="form-input">
				<input type="text" id="address" name="address" data-address-input  value="{{ old('address') }}" />
				<button type="submit" class="btn btn-red" data-address-submit>Find</button>
				<input type="hidden" name="latitude" id="latitude" data-latitude-input>
				<input type="hidden" name="longitude" id="longitude" data-longitude-input>
				<input type="hidden" name="formatted_address" id="formatted_address" data-formatted-address-input>
			</div>
		{{Form::close()}}
	
</div><!-- address-form-container -->

@stop