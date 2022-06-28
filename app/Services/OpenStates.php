<?php
namespace App\Services;

use \GuzzleHttp\Client;

/*
* Interacts with the Open States API
*/
class OpenStates
{
	/**
	* API Key
	*/
	private $apikey;

	public function __construct()
	{
		$this->apikey = env('SUNLIGHT_API_KEY');
	}

	/**
	* Get District Boundaries
	* @param string $state (2 letter abbr)
	* @param string $boundary_id (formatted boundary id ex: sldu:3)
	*/
	public function getDistrictBoundaries($state, $boundary_id)
	{
		try {
			$url = "http://data.openstates.org/boundaries/2018/ocd-division/country:us/state:" . strtolower($state) . "/" . $boundary_id . '.json';
			$boundary_client = new Client(['base_uri' => $url]);	
			$boundary_response = $boundary_client->get($url, [
				'query' => [
					'apikey' => $this->apikey
				],
				'verify' => false
			]);
			$boundary_data = json_decode($boundary_response->getBody()->getContents());
			return $boundary_data;
		} catch ( \Exception $e ){
			return null;
		}
	}
}