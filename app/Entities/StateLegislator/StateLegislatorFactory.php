<?php
namespace App\Entities\StateLegislator;

/**
* Builds an object of state legislators data
*/
class StateLegislatorFactory
{
	public function build($data)
	{
		$state = new \stdClass;
		$state->senate = new \stdClass;
		$state->house = new \stdClass;
		$state->location = new \stdClass;

		foreach ( $data['offices'] as $office ){
			if ( !isset($office['levels']) || !isset($office['roles']) ) continue;
			if ( $office['levels'][0] == 'administrativeArea1' && $office['roles'][0] == 'legislatorUpperBody' ) $senate_office = $office;
			if ( $office['levels'][0] == 'administrativeArea1' && $office['roles'][0] == 'legislatorLowerBody' ) $house_office = $office;
		}

		// Push the senators into an array
		foreach ( $senate_office['officialIndices'] as $index ){
			$senators[] = $data['officials'][$index];
		}

		// Push the representatives into an array
		foreach ( $house_office['officialIndices'] as $index ){
			$representatives[] = $data['officials'][$index];
		}

		// Setup the Senate Object
		$state->senate->label = $senate_office['name'];
		$state->senate->division_id = $senate_office['divisionId'];
		$state->senate->senators = $senators;

		// Add slugs to senators
		foreach ( $state->senate->senators as $key => $senator ){
			$state->senate->senators[$key]['slug'] = str_slug($senator['name']);
		}		

		// Setup the House Object
		$state->house->label = $house_office['name'];
		$state->house->division_id = $house_office['divisionId'];
		$state->house->representatives = $representatives;

		// Add slugs to representatives
		foreach ( $state->house->representatives as $key => $representative ){
			$state->house->representatives[$key]['slug'] = str_slug($representative['name']);
		}
		
		// Normalize the District numbers for house and senate
		$s_division_array = explode('/', $state->senate->division_id);
		$s_district_number = str_replace('sldu:', '', end($s_division_array));
		$state->location->senate_district_number = $s_district_number;
		$h_division_array = explode('/', $state->house->division_id);
		$h_district_number = str_replace('sldl:', '', end($h_division_array));
		$state->location->house_district_number = $h_district_number;

		return $state;
	}
}