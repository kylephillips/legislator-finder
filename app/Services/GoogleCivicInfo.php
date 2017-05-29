<?php
namespace App\Services;

use \GuzzleHttp\Client;
use \App\Entities\FederalLegislator\FederalLegislatorFactory;
use \App\Entities\StateLegislator\StateLegislatorFactory;
use \App\Entities\OtherOfficial\OtherOfficialFactory;

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

	/**
	* Other Official Factory
	*/
	private $other_official_factory;

	public function __construct(FederalLegislatorFactory $federal_factory, StateLegislatorFactory $state_factory, OtherOfficialFactory $other_official_factory)
	{
		$this->apikey = env('GOOGLE_MAPS_KEY');
		$this->federal_factory = $federal_factory;
		$this->state_factory = $state_factory;
		$this->other_official_factory = $other_official_factory;
	}

	/**
	* Get Legislative Info, Format it, and Store them to the Session
	* @param string $latitude
	* @param string $longitude
	*/
	public function fetchLegislativeInfo($latitude, $longitude)
	{
		try {
			$client = new Client(['base_uri' => 'https://www.googleapis.com/civicinfo/v2/']);
			$response = $client->get('representatives', [
				'query' => [
					'key' => env('GOOGLE_MAPS_KEY'),
					'address' => $latitude . ',' . $longitude
				],
				'verify' => false
			]);	
			$civic_data = json_decode($response->getBody()->getContents());
			session(['federal_legislators' => $this->federal_factory->build($civic_data)]);
			session(['state_legislators' => $this->state_factory->build($civic_data)]);
			session(['other_officials' => $this->other_official_factory->build($civic_data)]);
		} catch ( \Exception $e ){
			throw new \Exception('Legislators could not be found for the provided location');
		}
	}
}