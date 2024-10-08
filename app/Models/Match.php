<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tournament;

class Match extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['winner_team', 'name', 'max_keys', 'first_fighter', 'first_fighter_categorie', 'second_fighter_categorie','second_fighter', 'first_fighter_brand', 'second_fighter_brand', 'first_fighter_name', 'second_fighter_name', 'result', 'score', 'tournament_id', 'stage', 'arena_id', 'arena_name', 'p1_score', 'p2_score', 'accStage'];
    protected $table = 'match';
    // Relacionamento com o usuário (owner)
    public function ownerUser()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }
}  