<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = ['owner_id', 'name', 'data', 'reward'];

    // Relacionamento com o usuário (owner)
    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner');
    }
}