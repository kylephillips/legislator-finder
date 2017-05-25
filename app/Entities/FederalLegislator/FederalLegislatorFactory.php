<?php
namespace App\Entities\FederalLegislator;

class FederalLegislatorFactory
{
	public function build($data)
	{
		$federal = new \stdClass;
		$federal->senate = new \stdClass;
		$federal->house = new \stdClass;
		$federal->location = new \stdClass;
		$federal->location->state = $data['normalizedInput']['state'];

		foreach ( $data['offices'] as $office ){
			if ( !isset($office['levels']) || !isset($office['roles']) ) continue;
			if ( $office['levels'][0] == 'country' && $office['roles'][0] == 'legislatorUpperBody' ) $senate_office = $office;
			if ( $office['levels'][0] == 'country' && $office['roles'][0] == 'legislatorLowerBody' ) $house_office = $office;
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
		$federal->senate->label = $senate_office['name'];
		$federal->senate->division_id = $senate_office['divisionId'];
		$federal->senate->senators = $senators;

		// Setup the House Object
		$federal->house->label = $house_office['name'];
		$federal->house->division_id = $house_office['divisionId'];
		$federal->house->representatives = $representatives;

		// Normalize the House District for fetching map boundaries
		$division_array = explode('/', $federal->house->division_id);
		$district_number = str_replace('cd:', '', end($division_array));
		$federal->location->district_number = $district_number;
		$federal->location->district_number_formatted = ( strlen($district_number) == 1 ) ? '0' . $district_number : $district_number;

		return $federal;
	}
}