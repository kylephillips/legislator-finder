<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Services\OpenStates;
use \App\Services\GoogleCivicInfo;
use \App\Entities\FederalLegislator\FederalLegislatorRepository;
use \App\Entities\StateLegislator\StateLegislatorRepository;
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

	/**
	* State Legislator Repository
	* @var object StateLegislatorRepository
	*/
	private $state_legislator_repo;

	public function __construct(OpenStates $openstates, GoogleCivicInfo $google, FederalLegislatorRepository $federal_legislator_repo, StateLegislatorRepository $state_legislator_repo)
	{
		$this->openstates = $openstates;
		$this->google = $google;
		$this->federal_legislator_repo = $federal_legislator_repo;
		$this->state_legislator_repo = $state_legislator_repo;
	}
	
	public function getIndex(Request $request)
	{
		$request->session()->forget('federal_legislators');
		$request->session()->forget('state_legislators');
		return view('templates.index');
	}
		
	/**
	* For "Back" functionality
	*/
	public function getResults($locale)
	{
		if ( !$locale ) return redirect()->route('index_page');
		$formatted_address = session('user_address');
		if ( !$formatted_address ) return redirect()->route('index_page');
		$locale = ( $locale == 'federal' ) ? 'federal' : 'state';
		$legislators = ( $locale == 'federal' ) ? session('federal_legislators') : session('state_legislators');
		return view('templates.results')
			->with('legislators', $legislators)
			->with('formatted_address', $formatted_address)
			->with('locale', $locale);
	}
	
	/*
	* Lookup Legislators
	*/
	public function postResults(Request $request)
	{
		$this->validate($request,[
			'latitude' => 'required|numeric',
			'longitude' => 'required|numeric'
		]);
			
		// Make the Google Civic Info API Call
		$longitude = $request->input('longitude');
		$latitude = $request->input('latitude');
		$this->google->fetchLegislativeInfo($latitude, $longitude);

		$legislators = ( $request->input('locale') == 'federal' ) ? session('federal_legislators') : session('state_legislators');
		$locale = ( $request->input('locale') == 'federal' ) ? 'federal' : 'state';
		
		// No legislators were found
		if ( empty($legislators->senate->senators) && empty($legislators->house->representatives) ) 
			return redirect()->route('index_page')->with('errors', ucfirst($locale) . ' legislators could not be found for the location you have entered.');

		$formatted_address = ( $request->input('formatted_address') ) 
			? $request->input('formatted_address') 
			: 'Your Current Location';
		
		$request->session()->put('user_address', $formatted_address);
				
		return view('templates.results')
			->with('legislators', $legislators)
			->with('formatted_address', $formatted_address)
			->with('locale', $locale);
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
		if ( !$slug || !$chamber ) return redirect()->route('index_page');
		if ( $chamber !== 'senator' && $chamber !== 'representative' ) return redirect()->route('index_page');
		$legislators = session('state_legislators');
		if ( !$legislators ) return redirect()->route('index_page');

		$legislator = ( $chamber == 'senator' ) 
			? $this->state_legislator_repo->getSenatorBySlug($slug) 
			: $this->state_legislator_repo->getRepresentativeBySlug($slug);

		if ( !$legislator ) return redirect()->route('index_page');
		$location = $legislators->location;	
		$district_number = ( $chamber == 'senator' ) ? $location->senate_district_number : $location->house_district_number;	
		
		return view('templates.state')
			->with('legislator',$legislator)
			->with('chamber',$chamber)
			->with('district_number',$district_number)
			->with('location',$location);
	}

	/*
	* Get the boundaries for a state district
	* @return json
	*/
	public function getStateDistrictBoundariesAjax(Request $request)
	{
		$this->validate($request, [
			'chamber' => 'required',
			'district_number' => 'required',
			'state' => 'required',
		]);

		// set the chamber letter for boundary id
		$chamber_short = ( $request->input('chamber') == "senator" ) ? "u" : "l";
		$district_number = $request->input('district_number');
		$state = strtolower($request->input('state'));

		// boundary id for fetching district boundary
		$boundary_id = 'sld' . $chamber_short . ':' . $district_number;

		// lookup the district boundary for the current legislator
		$boundary_data = $this->openstates->getDistrictBoundaries($state, $boundary_id);
		if ( !$boundary_data ) return response()->json(['status' => 'error', 'message' => 'The boundaries could not be located.']);

		return response()->json([
			'status' => 'success',
			'center_lat' => $boundary_data['region']['center_lat'],
			'center_lng' => $boundary_data['region']['center_lon'],
			'coordinates' => $boundary_data['shape'][0][0]
		]);
	}
}