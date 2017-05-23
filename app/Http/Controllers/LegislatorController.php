<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \GuzzleHttp\Client;
use Validator;

class LegislatorController extends Controller 
{
	
	public function getIndex()
	{
		return view('templates.index');
	}
		
	public function getResults()
	{
		return view('templates.results')->with('noaddress', true);
	}
	
	/*
	* Lookup Results
	*/
	public function postResults(Request $request)
	{
		$rules = array(
			'latitude' => 'required|numeric',
			'longitude' => 'required|numeric'
		);
		$sunlight_key = env('SUNLIGHT_API_KEY');
		$validator = Validator::make($request->all(), $rules);
		
		if ( $validator->fails() ) return redirect()->route('index_page');
			
		// Setup required parameters for API call
		$longitude = $request->input('longitude');
		$latitude = $request->input('latitude');			
		$formatted_address = ( $request->input('formatted_address') ) 
			? $request->input('formatted_address') 
			: 'Your Current Location';
		
		// Federal Legislators
		if ( $request->input('locale') == "federal" ){
			
			$client = new Client();
			$response = $client->get('http://congress.api.sunlightfoundation.com/legislators/locate', [
				'query' => [
					'latitude' => $latitude,
					'longitude' => $longitude,
					'apikey' => $sunlight_key
					]
			]);
			$legislators = $response->json();
			$legislators = $legislators['results'];
			
			return view('templates.results')
				->with('legislators', $legislators)
				->with('formatted_address', $formatted_address)
				->with('locale', 'federal')
				->with('noaddress', false);
			
		} else { // State Legislators
			
			$client = new Client();
			$response = $client->get('http://openstates.org/api/v1/legislators/geo/', [
				'query' => [
					'lat' => $latitude,
					'long' => $longitude,
					'apikey' => $sunlight_key
				]
			]);
			$legislators = $response->json();
			$legislators = $legislators;
			
			return view('templates.results')
				->with('legislators', $legislators)
				->with('formatted_address', $formatted_address)
				->with('locale', 'state')
				->with('noaddress', false);
		}
		
	} //postFederal
	
	
	/*
	* Single Federal Legislator
	*/
	public function getFederal($id = null)
	{
		if ( !$id ) return redirect()->route('index_page');
		$sunlight_key = env('SUNLIGHT_API_KEY');

		$client = new Client();
		$response = $client->get('http://congress.api.sunlightfoundation.com/legislators', [
			'query' => [
				'bioguide_id' => $id,
				'apikey' => $sunlight_key
				]
		]);
		$legislators = $response->json();
		$legislator = $legislators['results'][0];

		if ( !$legislator ) return redirect()->route('index_page');
		
		// Convert state name for use in maps
		$stateLower = strtolower($legislator['state']);
		$stateUpper = strtoupper($stateLower);
		$term_start = strtotime($legislator['term_start']);
		$term_start = date('Y', $term_start);
		$term_end = strtotime($legislator['term_end']);
		$term_end = date('Y', $term_end);
		
		// get the district number and add leading zero if needed for map id
		$district = $legislator['district'];
		$district = sprintf("%02s", $district);

		// set the variable used to retrieve the map coordinates
		$district_map_id = $stateLower . '-' . $district;
		$boundary_id = $legislator['ocd_id'];

		// lookup the district boundary for the current legislator
		try {
			$boundary_client = new Client();
			$boundary_feed = "openstates.org/api/v1/districts/boundary/$boundary_id";
			$boundary_response = $boundary_client->get($boundary_feed, [
				'query' => [
					'apikey' => $sunlight_key
				]
			]);		
			$boundary_data = $boundary_response->json();
			$center_lat = $boundary_data['region']['center_lat'];
			$center_lon = $boundary_data['region']['center_lon'];
			$coordinates = $boundary_data['shape'][0][0];
		} catch (\Exception $e){
			$center_lat = false;
			$center_lon = false;
			$coordinates = false;
		}
	
		// Return view with necessary data
		return view('templates.federal')
			->with('legislator', $legislator)
			->with('stateLower', $stateLower)
			->with('stateUpper', $stateUpper)
			->with('term_start', $term_start)
			->with('term_end', $term_end)
			->with('district', $district)
			->with('coordinates', $coordinates)
			->with('district_map_id', $district_map_id);
	}
	
	
	/*
	* Single State Legislator
	*/
	public function getState($id = null)
	{
		$sunlight_key = env('SUNLIGHT_API_KEY');
		if ( !$id ) return redirect()->route('index_page');

		$client = new Client();
		$response = $client->get("http://openstates.org/api/v1//legislators/$id", [
			'query' => [
				'apikey' => $sunlight_key
				]
		]);
		$legislator = $response->json();
		
		if ( !$legislator ) return redirect()->route('index_page');
			
		// Get the current term object
		$current_term = $legislator['roles'][0];
		$offices = $legislator['offices'];
		
		// Get the roles for committees
		$committees = $legislator['roles'];

		// set the chamber letter for boundary id
		$chamber_short = ( $legislator['chamber'] == "upper" ) ? "u" : "l";

		// boundary id for fetching district boundary
		$boundary_id = 'sld' . $chamber_short . ':' . $legislator['district'];

		// lookup the district boundary for the current legislator
		$boundary_client = new Client();
		$boundary_feed = "openstates.org/api/v1/districts/boundary/ocd-division/country:us/state:" . $legislator['state'] . "/$boundary_id";
		$boundary_response = $boundary_client->get($boundary_feed, [
			'query' => [
				'apikey' => $sunlight_key
				]
		]);		
		$boundary_data = $boundary_response->json();
		$center_lat = $boundary_data['region']['center_lat'];
		$center_lon = $boundary_data['region']['center_lon'];
		$coordinates = $boundary_data['shape'][0][0];
		
		return view('templates.state')
			->with('legislator',$legislator)
			->with('current_term',$current_term)
			->with('offices',$offices)
			->with('committees',$committees)
			->with('center_lat',$center_lat)
			->with('center_lon',$center_lon)
			->with('coordinates',$coordinates);
	}

	public function googleTest($address)
	{
		$client = new Client();
		$civic_feed = "https://www.googleapis.com/civicinfo/v2/representatives";
		$civic_response = $client->get($civic_feed, [
			'query' => [
				'key' => env('GOOGLE_MAPS_KEY'),
				'address' => $address
			]
		]);		
		$civic_data = $civic_response->json();
		$divisions = $civic_data['divisions'];
		dd($civic_data);
	}
	
}