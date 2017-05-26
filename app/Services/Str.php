<?php
namespace App\Services;

/*
* Extended Str class adds required legislator methods
*/
class Str extends \Illuminate\Support\Str 
{
	
	/*
	* Return the properly formatted legislator name
	*/
	public static function legislator_name($legislator)
	{
		$out =  $legislator['first_name'] . ' ';
		if ( $legislator['nickname'] != "" ){
			$out .= '"' . $legislator['nickname'] . '" ';
		}
		$out .= $legislator['last_name'];
		if ( $legislator['name_suffix'] ){
			$out .= ' ' . $legislator['name_suffix'];
		}
		return $out;
	}
	
	/*
	* Return the legislator party snipe (federal)
	*/
	public static function party_snipe($legislator)
	{
		$party = ( is_array($legislator) ) ? $legislator['party'] : $legislator;
		$party = strtolower(substr($party, 0, 1));
		$out = '';
		switch($party){
			case "r" :
				$url = asset('assets/images/party-snipe-r.png');
				$out = '<img src="' . $url . '" class="snipe" alt="Republican" />';
			break;
		
			case "d" :
				$url = asset('assets/images/party-snipe-d.png');
				$out = '<img src="' . $url . '" class="snipe" alt="Democrat" />';
			break;
		
			case "i" :
				$url = asset('assets/images/party-snipe-i.png');
				$out = '<img src="' . $url . '" class="snipe" alt="Independent" />';
			break;
		
			case "l" :
				$url = asset('assets/images/party-snipe-l.png');
				$out = '<img src="' . $url . '" class="snipe" alt="Libertarian" />';
			break;
		}
		return $out;
	}
	
	/*
	* Return the legislator party snipe (state)
	*/
	public static function party_snipe_state($legislator)
	{
		$party = $legislator['party'];
		switch($party){
			case "Republican" :
				$url = asset('assets/images/party-snipe-r.png');
				$out = '<img src="' . $url . '" class="snipe" alt="Republican" />';
			break;
		
			case "Democrat" :
				$url = asset('assets/images/party-snipe-d.png');
				$out = '<img src="' . $url . '" class="snipe" alt="Democrat" />';
			break;
		
			case "Democratic" :
				$url = asset('assets/images/party-snipe-d.png');
				$out = '<img src="' . $url . '" class="snipe" alt="Democrat" />';
			break;
		
			case "Independent" :
				$url = asset('assets/images/party-snipe-i.png');
				$out = '<img src="' . $url . '" class="snipe" alt="Independent" />';
			break;
		
			case "Libertarian" :
				$url = asset('assets/images/party-snipe-l.png');
				$out = '<img src="' . $url . '" class="snipe" alt="Libertarian" />';
			break;
		}
		return $out;
	}
	
	/*
	* Return properly formatted party name
	*/
	public static function party_name($legislator)
	{
		$party = $legislator['party'];
		switch ($party){
			case "R" :
			$out = "Republican";
			break;
		
			case "D" :
			$out = "Democrat";
			break;
		
			case "I" :
			$out = "Independent";
			break;
		
			case "L" :
			$out = "Libertarian";
			break;
		}
		return $out;
	}
	
	/*
	* Google Map styles
	*/
	public static function map_styles()
	{
		$styles = '[
		  {
		    "stylers": [
		      { "visibility": "off" }
		    ]
		  },{
		    "featureType": "landscape",
		    "stylers": [
		      { "visibility": "on" },
		      { "color": "#151543" }
		    ]
		  },{
		    "featureType": "road.highway",
		    "elementType": "geometry",
		    "stylers": [
		      { "visibility": "simplified" },
		      { "color": "#1bad90" }
		    ]
		  },{
		    "featureType": "road.highway",
		    "elementType": "labels.text.fill",
		    "stylers": [
		      { "visibility": "on" },
		      { "color": "#ffffff" }
		    ]
		  },{
		    "featureType": "road.highway",
		    "elementType": "labels.text.stroke",
		    "stylers": [
		      { "visibility": "on" },
		      { "color": "#151540" }
		    ]
		  },{
		    "featureType": "administrative.province",
		    "elementType": "geometry",
		    "stylers": [
		      { "visibility": "on" },
		      { "color": "#232254" }
		    ]
		  },{
		    "featureType": "administrative.locality",
		    "elementType": "labels.text.fill",
		    "stylers": [
		      { "visibility": "on" },
		      { "color": "#3696da" }
		    ]
		  },{
		    "featureType": "administrative.locality",
		    "elementType": "labels.text.stroke",
		    "stylers": [
		      { "visibility": "simplified" },
		      { "color": "#151543" }
		    ]
		  },{
		    "featureType": "road.arterial",
		    "elementType": "geometry",
		    "stylers": [
		      { "visibility": "simplified" },
		      { "color": "#27275e" }
		    ]
		  },{
		    "featureType": "water",
		    "stylers": [
		      { "visibility": "on" },
		      { "color": "#0e0e33" }
		    ]
		  },{
		    "featureType": "administrative.neighborhood",
		    "elementType": "geometry",
		    "stylers": [
		      { "visibility": "on" }
		    ]
		  }
		]';
		return $styles;
	}

}