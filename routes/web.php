<?php
Route::get('/', array('as'=>'index_page','uses'=>'LegislatorController@getIndex'));
Route::post('results', array('as'=>'results', 'uses'=>'LegislatorController@postResults'));
Route::get('results', array('as'=>'results_back', 'uses'=>'LegislatorController@getResults'));

Route::get('federal/{chamber}/{slug?}', array('as'=>'federal_results','uses'=>'LegislatorController@getSingleFederal'));
Route::get('state/{chamber}/{slug?}', array('as'=>'state_results','uses'=>'LegislatorController@getSingleState'));

Route::post('state-district-boundaries', array('as'=>'state_district_boundaries', 'uses'=>'LegislatorController@getStateDistrictBoundariesAjax'));