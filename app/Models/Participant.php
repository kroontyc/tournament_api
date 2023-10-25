<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tournament;
class Participant extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'age', 'weight', 'rank', 'height', 'gub', 'sex', 'categorie', 'team_name', 'tournament_id'];

    // Relacionamento com o usuário (owner)
    public function ownerUser()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }
}