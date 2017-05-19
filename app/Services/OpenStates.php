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
	* Get Federal Legislators
	* @param string $latitude
	* @param string $longitude
	*/
	public function getFederalLegislators($latitude, $longitude)
	{
		$client = new Client();
		$response = $client->get('http://congress.api.sunlightfoundation.com/legislators/locate', [
			'query' => [
				'latitude' => $latitude,
				'longitude' => $longitude,
				'apikey' => $this->apikey
			]
		]);
		$legislators = $response->json();
		return $legislators['results'];
	}

	/**
	* Get State Legislators
	* @param string $latitude
	* @param string $longitude
	*/
	public function getStateLegislators($latitude, $longitude)
	{
		$client = new Client();
		$response = $client->get('http://openstates.org/api/v1/legislators/geo/', [
			'query' => [
				'lat' => $latitude,
				'long' => $longitude,
				'apikey' => $this->apikey
			]
		]);
		return $response->json();
	}

	/**
	* Get a Single Federal Legislator
	* @param string $id - bioguide id
	*/
	public function getSingleFederalLegislator($id)
	{
		$client = new Client();
		$response = $client->get('http://congress.api.sunlightfoundation.com/legislators', [
			'query' => [
				'bioguide_id' => $id,
				'apikey' => $this->apikey
			]
		]);
		$legislators = $response->json();
		return $legislators['results'][0];
	}

	/**
	* Get a Single State Legislator
	* @param string $id - bioguide id
	*/
	public function getSingleStateLegislator($id)
	{
		$client = new Client();
		$response = $client->get("http://openstates.org/api/v1//legislators/$id", [
			'query' => [
				'apikey' => $this->apikey
			]
		]);
		return $response->json();
	}
}