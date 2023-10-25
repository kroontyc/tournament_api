<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tournament;

class Match extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['first_fighter', 'second_fighter', 'result', 'score', 'tournament_id', 'stage'];

    // Relacionamento com o usuário (owner)
    public function ownerUser()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }
}  