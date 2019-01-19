<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Puzzle extends Model
{
    use Uuids;
    public $incrementing = false;
    protected $fillable = ["matrix",'initial_matrix'];
    protected $casts = ["matrix" => "array","initial_matrix"=>"array"];
}
