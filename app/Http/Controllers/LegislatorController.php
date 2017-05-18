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
		return View::make('templates.results')->with('noaddress', true);
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
		$validator = Validator::make($request->all(), $rules);
		
		if ( !$validator->fails() ){
			
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
						'apikey' => config('keys.sunlight')
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
						'apikey' => config('keys.sunlight')
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
		}
	} //postFederal
	
	
	/*
	* Single Federal Legislator
	*/
	public function getFederal($id = null)
	{
		if ($id){

			$client = new Client();
			$response = $client->get('http://congress.api.sunlightfoundation.com/legislators', [
				'query' => [
					'bioguide_id' => $id,
					'apikey' => \Config::get('keys.sunlight')
					]
			]);
			$legislators = $response->json();
			$legislator = $legislators['results'][0];

			if ( $legislator ){
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
			
				// Return view with necessary data
				return view('templates.federal')
					->with('legislator', $legislator)
					->with('stateLower', $stateLower)
					->with('stateUpper', $stateUpper)
					->with('term_start', $term_start)
					->with('term_end', $term_end)
					->with('district', $district)
					->with('district_map_id', $district_map_id);
			} else {
				// Invalid legislator ID
				return Redirect::route('index_page');
			}
			
		}
		return Redirect::route('index_page');
	}
	
	
	/*
	* Single State Legislator
	*/
	public function getState($id = null)
	{
		if ($id){

			$client = new Client();
			$response = $client->get("http://openstates.org/api/v1//legislators/$id", [
				'query' => [
					'apikey' => \Config::get('keys.sunlight')
					]
			]);
			$legislator = $response->json();
			
			if ($legislator){
				
				// Get the current term object
				$current_term = $legislator['roles'][0];
				$offices = $legislator['offices'];
				
				// Get the roles for committees
				$committees = $legislator['roles'];
		
				// set the chamber letter for boundary id
				if ( $legislator['chamber'] == "upper" ){
					$chamber_short = "u";
				} else {
					$chamber_short = "l";
				}
		
				// boundary id for fetching district boundary
				$boundary_id = 'sld' . $chamber_short . '/' . $legislator['state'] . '-' . $legislator['district'];
		
				// lookup the district boundary for the current legislator
				$sunlightkey = \Config::get('keys.sunlight');
				$boundary_feed = "openstates.org/api/v1//districts/boundary/$boundary_id/?apikey=$sunlightkey";
				$boundary_data = Str::load_curl($boundary_feed,"state");
				

				$center_lat = $boundary_data->region->center_lat;
				$center_lon = $boundary_data->region->center_lon;
				$coordinates = $boundary_data->shape[0][0];
				
				return View::make('templates.state')
					->with('legislator',$legislator)
					->with('current_term',$current_term)
					->with('offices',$offices)
					->with('committees',$committees)
					->with('center_lat',$center_lat)
					->with('center_lon',$center_lon)
					->with('coordinates',$coordinates);
				
			} else {
				// Invalid legislator ID
				return Redirect::route('index_page');
			}
				
		} else {
			return Redirect::route('index_page');
		}
	}
	
}