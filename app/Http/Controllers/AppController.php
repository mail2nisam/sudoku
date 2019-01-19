<?php

namespace App\Http\Controllers;

use App\Puzzle;
use Illuminate\Http\Request;
use App\Sudoku\Sudoku;


class AppController extends Controller
{
    /**
     * Check possibility of a number in a cell
     * @param Request $request
     * @param null $number
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPossibility(Request $request, $number = null)
    {

        $row = $request->get('row');
        $column = $request->get("column");
        $matrixId = $request->get("matrix_id");
        if (!$number) {
            Sudoku::updateMatrix($row, $column, "", $matrixId);
            return response()->json(["status" => true]);
        }
        if (Sudoku::isNumberIsAllowed($matrixId, $row, $column, $number)) {
            $matrix = Sudoku::updateMatrix($row, $column, $number, $matrixId);
            return response()->json(["status" => true]);

        } else {
            return response()->json(["status" => false]);
        }

    }

    /**
     * Auto Solve the Sudoku
     * @param $puzzleId
     * @return array
     */
    public function autoResolve($puzzleId)
    {
        $puzzle = Puzzle::find($puzzleId);
        if ($puzzle) {
            $matrix =  Sudoku::solution($puzzle->initial_matrix);
            $puzzle->matrix = $matrix;
            $puzzle->save();
            return $matrix;
        }
    }

    /**
     * Checking whether the filled Sudoku is really solved or not
     * @param $puzzleId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validatePuzzle($puzzleId, Request $request)
    {
        $puzzle = Puzzle::findOrFail($puzzleId);
        if ($puzzle) {
            $response = Sudoku::validate($request->get("matrix"));
            return response()->json(["status" => $response]);
        }
        return response()->json(["status" => false]);

    }
}
