<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arena extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['id', 'owner_id', 'name', 'current_match', 'fighter_1', 'fighter_2', 'status', 'winner', 'second_fight_name', 'first_fight_name'];
    protected $table = 'arenas';
}
