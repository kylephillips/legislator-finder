<?php

Route::get('/', array('as'=>'index_page','uses'=>'LegislatorController@getIndex'));
Route::post('results', array('as'=>'results', 'uses'=>'LegislatorController@postResults'));
Route::get('results', array('as'=>'no_results', 'uses'=>'LegislatorController@getResults'));
Route::get('federal/{id?}', array('as'=>'federal_results','uses'=>'LegislatorController@getFederal'));
Route::get('state/{id?}', array('as'=>'state_results','uses'=>'LegislatorController@getState'));

Route::get('google-test/{address?}', array('as'=>'google_test','uses'=>'LegislatorController@googleTest'));


Route::get('404', array('as'=>'missing_page', function(){
	return view('templates.missing');
}));