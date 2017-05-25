<?php
namespace App\Entities\StateLegislator;

class StateLegislatorFactory
{
	public function build($data)
	{
		$state = new \stdClass;
		$state->senate = new \stdClass;
		$state->house = new \stdClass;

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

		// Setup the House Object
		$state->house->label = $house_office['name'];
		$state->house->division_id = $house_office['divisionId'];
		$state->house->representatives = $representatives;

		return $state;
	}
}