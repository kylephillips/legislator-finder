<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LegislatorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LegislatorController::class, 'getIndex'])->name('getIndex');
Route::post('results', [LegislatorController::class, 'postResults'])->name('results');
Route::get('results', [LegislatorController::class, 'getResults'])->name('results_back');

Route::get('federal/{chamber}/{slug?}', [LegislatorController::class, 'getSingleFederal'])->name('federal_results');
Route::get('state/{chamber}/{slug?}', [LegislatorController::class, 'getSingleState'])->name('state_results');

Route::post('state-district-boundaries', [LegislatorController::class, 'getStateDistrictBoundariesAjax'])->name('state_district_boundaries');