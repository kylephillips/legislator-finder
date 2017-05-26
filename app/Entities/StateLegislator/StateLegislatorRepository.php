<?php
namespace App\Entities\StateLegislator;

class StateLegislatorRepository
{
	public function getSenatorBySlug($slug)
	{
		$legislators = session('state_legislators');
		$return_senator = null;
		foreach ( $legislators->senate->senators as $key => $single_senator ){
			if ( $single_senator->slug == $slug ) $return_senator = $legislators->senate->senators[$key]  ;
		}
		return $return_senator;
	}

	public function getRepresentativeBySlug($slug)
	{
		$legislators = session('state_legislators');
		$return_rep = null;
		foreach ( $legislators->house->representatives as $key => $single_representative ){
			if ( $single_representative->slug == $slug ) $return_rep = $legislators->house->representatives[$key]  ;
		}
		return $return_rep;
	}
}