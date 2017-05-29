<?php
namespace App\Entities\OtherOfficial;

/**
* Builds an object of other elected officials
*/
class OtherOfficialFactory
{
	public function build($data)
	{
		$federal = session('federal_legislators');
		$state = session('state_legislators');
		$other_officials = [];

		// Build up other offices array
		$other_offices = [];
		foreach ( $data->offices as $office ) :
			if ( $this->officeExists($office) ) continue;
			$other_offices[] = $office;
		endforeach;

		foreach ( $other_offices as $key => $office ) :
			$divison_index = $office->divisionId;
			foreach ( $office->officialIndices as $index ) :
				$other_officials[$key] = new \stdClass;
				$other_officials[$key]->office_name = $office->name;
				$other_officials[$key]->division = $data->divisions->$divison_index;
				$other_officials[$key]->division_name = $data->divisions->$divison_index->name;
				$other_officials[$key]->official = $data->officials[$index];
			endforeach;
		endforeach;

		// Group by divison name (ex: United States/Georgia)
		$other_officials_by_division = [];
		foreach ( $data->divisions as $div_id => $division ) :
			foreach ( $other_officials as $official ) :
				if ( $official->division_name == $division->name ) {
					$other_officials_by_division[$division->name][] = $official;
				}
			endforeach;
		endforeach;

		return $other_officials_by_division;
	}

	/**
	* Does the office already exists elsewhere in the app
	*/
	private function officeExists($office)
	{
		if ( !isset($office->levels[0]) && !isset($office->roles[0]) ) return false;
		if ( $office->levels[0] == 'country' && $office->roles[0] == 'legislatorUpperBody' ) return true;
		if ( $office->levels[0] == 'country' && $office->roles[0] == 'legislatorLowerBody' ) return true;
		if ( $office->levels[0] == 'administrativeArea1' && $office->roles[0] == 'legislatorUpperBody' ) return true;
		if ( $office->levels[0] == 'administrativeArea1' && $office->roles[0] == 'legislatorLowerBody' ) return true;
		return false;
	}
}