<?php
namespace App\Services;

use \GuzzleHttp\Client;
use \App\Entities\FederalLegislator\FederalLegislatorFactory;
use \App\Entities\StateLegislator\StateLegislatorFactory;

/*
* Interacts with the Google Civic Information API
* @link https://developers.google.com/civic-information/docs/using_api
*/
class GoogleCivicInfo
{
	/**
	* API Key
	*/
	private $apikey;

	/**
	* Federal Legislator Factory
	*/
	private $federal_factory;

	/**
	* State Legislator Factory
	*/
	private $state_factory;

	public function __construct(FederalLegislatorFactory $federal_factory, StateLegislatorFactory $state_factory)
	{
		$this->apikey = env('GOOGLE_MAPS_KEY');
		$this->federal_factory = $federal_factory;
		$this->state_factory = $state_factory;
	}

	/**
	* Get Legislative Info, Format it, and Store them to the Session
	* @param string $latitude
	* @param string $longitude
	*/
	public function fetchLegislativeInfo($latitude, $longitude)
	{
		try {
			$client = new Client();
			$civic_feed = "https://www.googleapis.com/civicinfo/v2/representatives";
			$civic_response = $client->get($civic_feed, [
				'query' => [
					'key' => env('GOOGLE_MAPS_KEY'),
					'address' => $latitude . ',' . $longitude
				]
			]);		
			$civic_data = $civic_response->json();
			$federal_legislators = $this->federal_factory->build($civic_data);
			$state_legislators = $this->state_factory->build($civic_data);
			session(['federal_legislators' => $federal_legislators]);
			session(['state_legislators' => $state_legislators]);
		} catch ( \Exception $e ){
		
		}
	}
}