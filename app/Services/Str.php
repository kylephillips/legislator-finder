<?php
namespace App\Services;

/*
* Extended Str class adds required legislator methods
*/
class Str extends \Illuminate\Support\Str 
{	
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
	* Return the legislator party css class
	*/
	public static function party_class($party)
	{
		$party = strtolower(substr($party, 0, 1));
		$out = '';
		switch($party){
			case "r" :
				$out = 'red';
			break;
		
			case "d" :
				$out = 'blue';
			break;
		
			case "i" :
				$out = 'gray';
			break;
		
			case "l" :
				$out = 'gray';
			break;
		}
		return $out;
	}

	/*
	* Return the legislator party letter
	*/
	public static function party_letter($party)
	{
		return strtoupper(substr($party, 0, 1));
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

	/**
	* Return properly formatted legislator address
	* @param $address array returned by Google API
	*/
	public static function address($address)
	{
		$out = '';
		if ( isset($address->line1) ) $out .= $address->line1 . '<br>';
		if ( isset($address->line2) ) $out .= $address->line2 . '<br>';
		if ( isset($address->city) ) $out .= $address->city;
		if ( isset($address->state) ) $out .= ', ' . $address->state;
		if ( isset($address->zip) ) $out .= ' ' . $address->zip;
		return $out;
	}

	/**
	* Return a properly formatted list of legislator phone numbers
	* @param $phones array returned by Google API
	*/
	public static function phones($phones)
	{
		$out = '';
		foreach ( $phones as $key => $phone ){
			$out .= $phone;
			if ( $key + 1 < count($phones) ) $out .= ', ';
		}
		return $out;
	}

}