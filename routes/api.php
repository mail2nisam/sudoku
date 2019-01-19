<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/sudoku/check-possibility/{number?}', 'AppController@checkPossibility')->name('is_allowed');
Route::get('/sudoku/auto-resolve/{puzzleId}', 'AppController@autoResolve')->name('auto_resolve');
Route::post('/sudoku/validate/{puzzleId}', 'AppController@validatePuzzle')->name('auto_resolve');
