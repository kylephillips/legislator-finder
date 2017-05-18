$(document).ready(function(){
	
	// Only show geolocation btn if browser support geolocation
	if (navigator.geolocation){
		$('#getcoordinates').show();
	}
	
	// Get device coordinates for capable browsers
	$('#getcoordinates').click(function(e){
		e.preventDefault();
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(savePosition);
		} else {
		  alert("Geolocation is not supported by this browser");
		}
		savePosition();
	});
	
	// save the coordinates to the fields and submit the form
	function savePosition(position) {
		$('.page-loading').show();
		var long = position.coords.longitude;
		var lat = position.coords.latitude;
		$('#latitude').val(lat);
		$('#longitude').val(long);
		$('#addressform').submit();
	}
	
	
	// Geocode the address
	function codeAddress(address){
		geocoder = new google.maps.Geocoder();
		
		geocoder.geocode({
			'address' : address
		}, function(results, status){
			if ( status == google.maps.GeocoderStatus.OK ){
				
				var formattedaddress = results[0].formatted_address;
				var latitude = results[0].geometry.location.lat();
				var longitude = results[0].geometry.location.lng();
				
				$('#formatted_address').val(formattedaddress);
				$('#latitude').val(latitude);
				$('#longitude').val(longitude);
				
				$('#addressform').submit();

			} else {
				$('.page-loading').hide();
				$('#addresserror').show();
				$('#addressform button').removeAttr('disabled');
			}
		});
	}
		
	// Address form submission – save the form variables, validate, and pass to geocoding function
	$('#addressform button').click(function(e){
		e.preventDefault();
		$('.page-loading').show();
		$(this).attr('disabled','disabled');
		
		var streetaddress = $('#streetaddress').val();
		var city = $('#city').val();
		var state = $('#addstate').val();
		var zip = $('#zip').val();
		var address = streetaddress + " " + city + " " + state + " " + zip + "USA";
		var addressvalid = streetaddress + city + state + zip;
		
		var selected = $('#addressform input[type="radio"]:checked').val();
		

		if ( addressvalid == "" ){
			$('.page-loading').hide();
			$('#addresserror').show();
			$('#addressform button').removeAttr('disabled');
		} else {
			codeAddress(address);
		}
		
	});
	
});