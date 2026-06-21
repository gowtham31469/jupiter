<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartOfTwoPieceSet extends Model
{
    protected $table = 'part_of_two_piece_set';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'polish', 'czech', 'slovak', 'hungarian'];
}
