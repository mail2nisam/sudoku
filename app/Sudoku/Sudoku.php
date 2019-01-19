<?php
/**
 * Created by PhpStorm.
 * User: nisam
 * Date: 19/1/19
 * Time: 2:33 PM
 */

namespace App\Sudoku;

use App\Puzzle;

class Sudoku
{
    /**
     * @var array
     */
    public static $matrix = [];

    /**
     * Generate partially filled sudoku matrix and saving to database
     * @return mixed
     */
    public static function generate()
    {
        $self = new self();
        self::$matrix = $self->resolve(self::emptyMatrix());
        $cells = array_rand(range(0, 80), 30);
        $i = 0;
        foreach (self::$matrix as &$row) {
            foreach ($row as &$cell) {
                if (!in_array($i++, $cells)) {
                    $cell = null;
                }
            }
        }
        return Puzzle::create(["matrix" => self::$matrix, 'initial_matrix' => self::$matrix]);

    }

    /**
     * Getting solution matrix from a partially filled matrix
     * @param $matrix
     * @return array
     */
    public static function solution($matrix)
    {
        self::$matrix = with(new self())->resolve($matrix);
        return self::$matrix;
    }

    /**
     * Generate empty matrix with a size 9X9
     * @return array
     */
    public static function emptyMatrix()
    {
        return array_fill(0, 9, array_fill(0, 9, 0));
    }

    /**
     * Fetching possible numbers in a cell based on the given matrix
     * @param $matrix
     * @param $row
     * @param $column
     * @return array
     */
    public static function getAllowedNumbers($matrix, $row, $column)
    {
        $validNumbers = range(1, 9);
        $invalidNumbers = $matrix[$row];
        for ($index = 0; $index < 9; $index++) {
            $invalidNumbers[] = $matrix[$index][$column];
        }
        $boxRow = $row % 3 == 0 ? $row : $row - $row % 3;
        $boxColumn = $column % 3 == 0 ? $column : $column - $column % 3;
        $invalidNumbers = array_unique(array_merge(
            $invalidNumbers,
            array_slice($matrix[$boxRow], $boxColumn, 3),
            array_slice($matrix[$boxRow + 1], $boxColumn, 3),
            array_slice($matrix[$boxRow + 2], $boxColumn, 3)
        ));
        $validNumbers = array_diff($validNumbers, $invalidNumbers);
        shuffle($validNumbers);
        return $validNumbers;
    }

    /**
     * Checking whether a number can be occupied in a cell based on the given matrix
     * @param $matrixId
     * @param $row
     * @param $column
     * @param $number
     * @return bool
     */
    public static function isNumberIsAllowed($matrixId, $row, $column, $number)
    {
        $puzzle = Puzzle::findOrFail($matrixId);
        $matrix = $puzzle->matrix;
        $matrix[$row][$column] = 0;
        $allowedNumbers = self::getAllowedNumbers($matrix, $row, $column);
        return in_array($number, $allowedNumbers) || empty($allowedNumbers);
    }

    /**
     * Update a cell
     * @param $row
     * @param $column
     * @param $number
     * @param $matrixId
     * @return array
     */
    public static function updateMatrix($row, $column, $number, $matrixId)
    {
        $puzzle = Puzzle::findOrFail($matrixId);
        $matrix = $puzzle->matrix;
        $matrix[$row][$column] = (int)$number;
        $puzzle->matrix = $matrix;
        $puzzle->save();
        return self::$matrix;
    }

    /**
     * Validating a sudoku
     * @param $matrix
     * @return bool
     */
    public static function validate($matrix)
    {
        //check for duplicate values
        for ($raw = 0; $raw < 9; $raw++) {
            $bitMap = 0;
            for ($col = 0; $col < 9; $col++) {
                if ('0' != $matrix[$raw][$col]) {
                    // calculate mask to get or set any particular bit from bitMap
                    $mask = pow(2, $matrix[$raw][$col]);
                    // check if a bit againt the value is already set to one,
                    // if it is already set,it means program already encounter the value, return false,
                    // else set it to one
                    if (($bitMap & $mask) == 0) {
                        $bitMap = $bitMap | $mask;
                    } else {
                        return false;
                    }
                }
            }
        }
        // check all columns for duplicate values
        for ($col = 0; $col < 9; $col++) {
            $bitMap = 0;
            for ($raw = 0; $raw < 9; $raw++) {
                if ('0' != $matrix[$raw][$col]) {

                    // calculate mask to get or set any particular bit from bitMap
                    $mask = pow(2, $matrix[$raw][$col]);
                    // check if a bit againt the value is already set to one
                    // if it is already set,it means program already encounter the value, return false,
                    // else set it to one
                    if (($bitMap & $mask) == 0)
                        $bitMap = $bitMap | $mask;
                    else
                        return false;
                }
            }
        }
        $xStart = 0;
        $yStart = 0;

        // check for 9 blocks
        // IN Sudoku, we have 9 blocks, each blocks contain 3 rows and 3 columns
        // any duplicate value in any block will fail validation
        for ($b = 0; $b < 9; $b++) {

            // set start x index and y index for every block according to block number
            $xStart = floor($b / 3) * 3;
            $yStart = ($b % 3) * 3;
            $bitMap = 0;

            // traverse through all rows in the block
            for ($x = $xStart; $x < $xStart + 3; $x++) {
                // traverse through all columns in the block
                for ($y = $yStart; $y < $yStart + 3; $y++) {

                    if ('0' != $matrix[$x][$y]) {
                        // calculate mask to get or set any particular bit from bitMap
                        $mask = pow(2, $matrix[$x][$y]);

                        // check if a bit againt the value is already set to one
                        // if it is already set,it means program already encounter the value, return false,
                        // else set it to one
                        if (($bitMap & $mask) == 0)
                            $bitMap = $bitMap | $mask;
                        else
                            return false;
                    }
                }
            }
        }
        // if validate for all rows, columns and blocks are passed then Sudoku is valid
        return true;
    }

    /**
     * Solving a sudoku from an input matrix
     * @param $matrix
     * @return bool
     */
    private function resolve($matrix)
    {
        while (true) {
            $options = [];
            foreach ($matrix as $rowIndex => $row) {
                foreach ($row as $columnIndex => $cell) {
                    if (!empty($cell)) {
                        continue;
                    }
                    $allowedNumbers = self::getAllowedNumbers($matrix, $rowIndex, $columnIndex);
                    if (count($allowedNumbers) == 0) {
                        return false;
                    }
                    $options[] = [
                        'rowIndex' => $rowIndex,
                        'columnIndex' => $columnIndex,
                        'allowedNumbers' => $allowedNumbers
                    ];
                }
            }
            if (count($options) == 0) {
                return $matrix;
            }

            usort($options, array($this, 'sortLogic'));

            if (count($options[0]['allowedNumbers']) == 1) {
                $matrix[$options[0]['rowIndex']][$options[0]['columnIndex']] = current($options[0]['allowedNumbers']);
                continue;
            }

            foreach ($options[0]['allowedNumbers'] as $value) {
                $tmp = $matrix;
                $tmp[$options[0]['rowIndex']][$options[0]['columnIndex']] = $value;
                if ($result = $this->resolve($tmp)) {
                    return $result;
                }
            }

            return false;
        }
    }

    /**
     * Sort Callback
     * @param $a
     * @param $b
     * @return int
     */
    private function sortLogic($a, $b)
    {
        $a = count($a['allowedNumbers']);
        $b = count($b['allowedNumbers']);
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}