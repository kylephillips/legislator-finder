/**
* Geocodes a user's address using either Google autocomplete places api or Google maps geocoder api
*/
var GeoCoder = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.lat = null;
	plugin.lng = null;
	plugin.formattedAddress = null;

	plugin.selectors = {
		geoButton : 'data-geolocation-button', // The geolocation button
		form : 'data-address-form', // The form element
		addressInput : 'data-address-input', // The address text input
		latInput : 'data-latitude-input', // The hidden latitude input
		lngInput : 'data-longitude-input', // The hidden longitude input
		formattedAddressInput : 'data-formatted-address-input', // The hidden formatted address input
		submitBtn : 'data-address-submit', // The form submit button
		loadingDiv : 'data-loading', // The loading div
		errorDiv : 'data-error' // The error notification/alert div
	}

	plugin.bindEvents = function()
	{
		$(document).ready(function(){
			plugin.toggleGeoLocation();
			plugin.enableAutoComplete();
		});
		$(document).on('location-changed', function(){
			plugin.populateAddressFields();
		});
		$(document).on('click', '[' + plugin.selectors.submitBtn + ']', function(e){
			e.preventDefault();
			plugin.submitForm();
		});
		$(document).on('click', '[' + plugin.selectors.geoButton + ']', function(e){
			e.preventDefault();
			plugin.geocodeLocation();
		});
	}

	/**
	* Toggle the "Use my location button"
	*/
	plugin.toggleGeoLocation = function()
	{
		if (navigator.geolocation) $('[' + plugin.selectors.geoButton + ']').show();
	}

	/**
	* Geocode using users location
	*/
	plugin.geocodeLocation = function()
	{
		if (!navigator.geolocation){
			plugin.displayError('Your location could not be determined at this time. Try entering an address.')
			return;
		}
		navigator.geolocation.getCurrentPosition(function(position){
			plugin.formattedAddress = 'Your Current Location';
			plugin.lat = position.coords.latitude;
			plugin.lng = position.coords.longitude;
		});
	}

	/**
	* Enable Google Places autocomplete on address field
	*/
	plugin.enableAutoComplete = function()
	{
		var field = $('[' + plugin.selectors.addressInput + ']');
		if ( field.length === 0 ) return;
		var autocomplete = new google.maps.places.Autocomplete(field[0]);
		google.maps.event.addListener(autocomplete, 'place_changed', function(){
			var place = autocomplete.getPlace();
			plugin.formattedAddress = place.formatted_address;
			plugin.lat = place.geometry.location.lat();
			plugin.lng = place.geometry.location.lng();
			$(document).trigger('location-changed');
		});
	}

	/**
	* Click Handler for Submit Button
	*/
	plugin.submitForm = function()
	{
		plugin.toggleLoading(true);
		if ( !plugin.lat && !plugin.lng ){
			plugin.geoCodeAddress();
			return;
		}
		$('[' + plugin.selectors.form + ']').submit();
	}

	/**
	* Populate the hidden address fields
	*/
	plugin.populateAddressFields = function()
	{
		$('[' + plugin.selectors.formattedAddressInput + ']').val(plugin.formattedAddress);
		$('[' + plugin.selectors.latInput + ']').val(plugin.lat);
		$('[' + plugin.selectors.lngInput + ']').val(plugin.lng);
	}

	/**
	* Geocode an address from the input manually
	*/
	plugin.geoCodeAddress = function()
	{
		var address = $('[' + plugin.selectors.addressInput + ']').val();
		
		if ( address === '' ){
			plugin.displayError('Please enter an address.');
			return;
		}

		geocoder = new google.maps.Geocoder();
		geocoder.geocode({
			'address' : address
		}, function(results, status){
			return;
			if ( status == google.maps.GeocoderStatus.OK ){
				plugin.formattedAddress = results[0].formatted_address;
				plugin.lat = results[0].geometry.location.lat();
				plugin.lng = results[0].geometry.location.lng();
				plugin.populateAddressFields();
				plugin.submitForm();
			} else {
				plugin.displayError('The address entered could not be found.');
			}
		});
	}

	/**
	* Toggle Loading State
	*/
	plugin.toggleLoading = function(loading)
	{
		if ( loading ){
			$('[' + plugin.selectors.loadingDiv + ']').show();
			$('[' + plugin.selectors.errorDiv + ']').hide();
			return;
		}
		$('[' + plugin.selectors.loadingDiv + ']').hide();
	}

	/**
	* Display an error
	*/
	plugin.displayError = function(error)
	{
		plugin.toggleLoading(false);
		$('[' + plugin.selectors.errorDiv + ']').text(error).show();
	}

	return plugin.bindEvents();
}