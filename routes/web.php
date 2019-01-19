<?php

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

Route::get('/', function () {
    $sample = \App\Sudoku\Sudoku::generate();
    if($sample){
        return redirect()->route("single_puzzle",["uuid"=>$sample->id]);
    }

})->name("home");
Route::get('/sudoku/{uuid}', function ($uuid) {
    $sample = \App\Puzzle::find($uuid)->matrix;
    return view('welcome', ['cells' => $sample,'matrix_id'=>$uuid]);
})->name("single_puzzle");

