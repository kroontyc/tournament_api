<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['arena_id', 'winner', 'p1', 'p2', 'match_id'];
    protected $table = 'results';
}
