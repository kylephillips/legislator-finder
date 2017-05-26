<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \GuzzleHttp\Client;
use \App\Services\OpenStates;
use \App\Services\GoogleCivicInfo;
use \App\Entities\FederalLegislator\FederalLegislatorRepository;
use Validator;

class LegislatorController extends Controller 
{
	/**
	* Open States Interaction Service Class
	* @var object OpenStates
	*/
	private $openstates;

	/**
	* Google Civic Information Service Class
	* @var object GoogleCivicInfo
	*/
	private $google;

	/**
	* Federal Legislator Repository
	* @var object FederalLegislatorRepository
	*/
	private $federal_legislator_repo;

	public function __construct(OpenStates $openstates, GoogleCivicInfo $google, FederalLegislatorRepository $federal_legislator_repo)
	{
		$this->openstates = $openstates;
		$this->google = $google;
		$this->federal_legislator_repo = $federal_legislator_repo;
	}
	
	public function getIndex(Request $request)
	{
		$request->session()->forget('federal_legislators');
		$request->session()->forget('state_legislators');
		return view('templates.index');
	}
		
	public function getResults()
	{
		return view('templates.results')->with('noaddress', true);
	}
	
	/*
	* Lookup Legislators
	*/
	public function postResults(Request $request)
	{
		$rules = array(
			'latitude' => 'required|numeric',
			'longitude' => 'required|numeric'
		);
		$validator = Validator::make($request->all(), $rules);
		if ( $validator->fails() ) return redirect()->route('index_page');
			
		// Make the Google Civic Info API Call
		$longitude = $request->input('longitude');
		$latitude = $request->input('latitude');
		$this->google->fetchLegislativeInfo($latitude, $longitude);

		$legislators = ( $request->input('locale') == 'federal' ) ? session('federal_legislators') : session('state_legislators');
		$locale = ( $request->input('locale') == 'federal' ) ? 'federal' : 'state';
		$not_found = ( empty($legislators->senate->senators) && empty($legislators->house->representatives) ) ? true : false;

		$formatted_address = ( $request->input('formatted_address') ) 
			? $request->input('formatted_address') 
			: 'Your Current Location';
				
		return view('templates.results')
			->with('legislators', $legislators)
			->with('formatted_address', $formatted_address)
			->with('locale', $locale)
			->with('noaddress', false)
			->with('not_found', $not_found);
	}
	
	/*
	* Single Federal Legislator
	*/
	public function getSingleFederal($chamber = null, $slug = null)
	{
		if ( !$slug || !$chamber ) return redirect()->route('index_page');
		if ( $chamber !== 'senator' && $chamber !== 'representative' ) return redirect()->route('index_page');
		$legislators = session('federal_legislators');
		if ( !$legislators ) return redirect()->route('index_page');

		$legislator = ( $chamber == 'senator' ) 
			? $this->federal_legislator_repo->getSenatorBySlug($slug) 
			: $this->federal_legislator_repo->getRepresentativeBySlug($slug);
		if ( !$legislator ) return redirect()->route('index_page');
		$location = $legislators->location;
		$division_id = ( $chamber == 'senator' ) ? $legislators->senate->division_id : $legislators->house->division_id;
	
		// Return view with necessary data
		return view('templates.federal')
			->with('chamber', $chamber)
			->with('legislator', $legislator)
			->with('location', $location)
			->with('division_id', $division_id);
	}
	
	
	/*
	* Single State Legislator
	*/
	public function getSingleState($chamber = null, $slug = null)
	{
		$sunlight_key = env('SUNLIGHT_API_KEY');
		if ( !$id ) return redirect()->route('index_page');
		$legislator = $this->openstates->getSingleStateLegislator($id);	
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
}